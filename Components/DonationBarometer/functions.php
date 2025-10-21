<?php

namespace Flynt\Components\DonationBarometer;

// Load ACF fields (local group for minimal setup)
require_once __DIR__ . '/acf-fields.php';

/**
 * Helper: Build a cache key per search/type to avoid mixing results across instances.
 */
function get_cache_key(string $searchId, string $displayType): string
{
    return 'donation_barometer_' . md5($searchId . '|' . $displayType);
}

/**
 * Fetch donations via FundraisingBox Smart Search API.
 *
 * Retrieves donation data based on a given Smart Search ID from FundraisingBox.
 * Uses GET parameters instead of POST body as per API documentation.
 * Results are cached for 10 minutes.
 *
 * @param string $searchId
 *   The Smart Search ID configured in the component.
 * @param string $displayType
 *   The display type filter: 'count' or 'sum'.
 *
 * @return array
 *   An associative array containing:
 *   - current_amount (float): Total number of all matching donations.
 *   - donor_count (int): Number of matching donations.
 */
function fetch_donations(string $searchId, string $displayType = 'sum'): array
{
  $searchId = trim($searchId);
  $cacheExpiration = (int) get_option('barometer_data_cache_expiration', 3);

  if ($searchId === '') {
    return [
      'current_amount' => 0.0,
      'donor_count' => 0,
    ];
  }

  $cacheKey = get_cache_key($searchId, $displayType);
  $cached = get_transient($cacheKey);
  if ($cached !== false && is_array($cached)) {
    return $cached;
  }

  $baseUrl = rtrim(get_option('fundraisingbox_api_base_url', 'https://api.fundraisingbox.com/v1/'), '/') . '/';
  $endpoint = 'donations.json';
  $accessToken = (string) get_option('fundraisingbox_api_access_token', '');
  $apiUrl = $baseUrl . $endpoint;

  if (empty($accessToken)) {
    error_log('[fetch_donations] Missing API access token.');
    return [
      'current_amount' => 0.0,
      'donor_count' => 0,
    ];
  }

  $totalAmount = 0.0;
  $totalDonors = 0;
  $page = 1;
  $perPage = 100;

  try {
    do {
      $params = [
        'search_id' => $searchId,
        'page'      => $page,
        'perPage'   => $perPage,
      ];

      $url = $apiUrl . '?' . http_build_query($params);

      $args = [
        'headers' => [
          'Authorization' => 'Basic ' . base64_encode($accessToken . ':X'),
          'Accept' => 'application/json',
        ],
        'timeout' => 20,
      ];

      $response = wp_remote_get($url, $args);

      if (is_wp_error($response)) {
        throw new \Exception('WP Remote Error: ' . $response->get_error_message());
      }

      $body = wp_remote_retrieve_body($response);
      $data = json_decode($body, true);

      if (!is_array($data) || !isset($data['data'])) {
        throw new \Exception('Invalid API response: ' . $body);
      }

      foreach ($data['data'] as $donation) {
        $totalAmount += isset($donation['amount']) ? (float) $donation['amount'] : 0.0;
      }

      $totalDonors += count($data['data']);
      $hasMore = $data['hasMore'] ?? false;
      $page++;

    } while ($hasMore);

  } catch (\Exception $e) {
    error_log('[fetch_donations] Error fetching donations: ' . $e->getMessage());
    // Optional: vorherige Ergebnisse aus Cache zurückgeben oder 0
    return [
      'current_amount' => 0.0,
      'donor_count' => 0,
    ];
  }

  $result = [
    'current_amount' => round($totalAmount, 2),
    'donor_count' => $totalDonors,
  ];

  set_transient($cacheKey, $result, $cacheExpiration * MINUTE_IN_SECONDS);

  return $result;
}




/**
 * Store the latest data per component instance on the parent post as post-meta.
 */
function store_instance_data(int $postId, string $instanceId, array $data): void
{
    $metaKey = '_donation_barometer_' . sanitize_key($instanceId);
    $payload = [
        'current_amount' => (float) ($data['current_amount'] ?? 0),
        'donor_count' => (int) ($data['donor_count'] ?? 0),
        'updated_at' => time(),
    ];
    update_post_meta($postId, $metaKey, $payload);
}

/**
 * Provide data to Twig via Flynt filter.
 */
add_filter('Flynt/addComponentData?name=DonationBarometer', function (array $data): array {
  $postId = isset($data['post']) && is_object($data['post']) ? (int) $data['post']->ID : (int) get_the_ID();

  $title = $data['title'] ?? '';
  $goalAmount = (float) ($data['goal_amount'] ?? 0);
  $searchId = (string) ($data['search_id'] ?? '');
  $displayType = (string) ($data['display_type'] ?? 'sum');

  // synthetic instance ID
  $instanceId = md5($postId . '|' . $searchId . '|' . $displayType);

  $apiData = fetch_donations($searchId, $displayType);

  if (($apiData['current_amount'] ?? 0) === 0 && ($apiData['donor_count'] ?? 0) === 0) {
    $storedData = get_post_meta($postId, '_donation_barometer_' . $instanceId, true);
    if (is_array($storedData)) {
      $apiData = $storedData;
    }
  }

  $data['title'] = $title;
  $data['goal_amount'] = $goalAmount;
  $data['current_amount'] = (float) ($apiData['current_amount'] ?? 0);
  $data['donor_count'] = (int) ($apiData['donor_count'] ?? 0);

  $data['donationGoal'] = $goalAmount;
  $data['donationLevel'] = ($displayType === 'count') ? (float) $data['donor_count'] : (float) $data['current_amount'];

  // Store data permanently
  store_instance_data($postId, $instanceId, $apiData);

  return $data;
});

