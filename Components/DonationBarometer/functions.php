<?php

namespace Flynt\Components\DonationBarometer;

// Load ACF fields (local group for minimal setup)
require_once __DIR__ . '/acf-fields.php';

/**
 * Helper: Build a cache key per search/type to avoid mixing results across instances.
 */
function get_cache_key(string $searchId, string $donationType): string
{
    return 'donation_barometer_' . md5($searchId . '|' . $donationType);
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
 * @param string $donationType
 *   The donation type filter: 'one_time', 'recurring', or 'both'.
 *
 * @return array
 *   An associative array containing:
 *   - current_amount (float): Total amount of all matching donations.
 *   - donor_count (int): Number of matching donations.
 */
function fetch_donations(string $searchId, string $donationType = 'both'): array
{
  $searchId = trim($searchId);
  $donationType = $donationType ?: 'both';

  if ($searchId === '') {
    return [
      'current_amount' => 0.0,
      'donor_count' => 0,
    ];
  }

  $cacheKey = get_cache_key($searchId, $donationType);
  $cached = get_transient($cacheKey);
  if ($cached !== false && is_array($cached)) {
    return $cached;
  }

  $baseUrl = rtrim(get_option('fundraisingbox_api_base_url', 'https://api.fundraisingbox.com/v1/'), '/') . '/';
  $endpoint = 'donations.json';
  $accessToken = (string) get_option('fundraisingbox_api_access_token', '');
  $apiUrl = $baseUrl . $endpoint;

  if (empty($accessToken)) {
    return [
      'current_amount' => 0.0,
      'donor_count' => 0,
    ];
  }

  $totalAmount = 0.0;
  $totalDonors = 0;
  $page = 1;
  $perPage = 100; // max items per request, je nach API

  do {
    $params = [
      'search_id' => $searchId,
      'page'      => $page,
      'perPage'  => $perPage,
    ];

    if ($donationType !== 'both') {
      $params['donation_type'] = $donationType;
    }

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
      break;
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    if (!is_array($data) || !isset($data['data'])) {
      break;
    }

    foreach ($data['data'] as $donation) {
      $totalAmount += isset($donation['amount']) ? (float) $donation['amount'] : 0.0;
    }

    $totalDonors += count($data['data']);

    $hasMore = $data['hasMore'] ?? false;
    $page++;
  } while ($hasMore);

  $result = [
    'current_amount' => round($totalAmount, 2),
    'donor_count' => $totalDonors,
  ];

  set_transient($cacheKey, $result, 10 * MINUTE_IN_SECONDS);

  return $result;
}




/**
 * Store latest data per component instance on the parent post as post meta.
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
    $donationType = (string) ($data['donation_type'] ?? 'both');
    $displayType = (string) ($data['display_type'] ?? 'sum');

    $apiData = fetch_donations($searchId, $donationType);
    // map required template vars
    $data['title'] = $title;
    $data['goal_amount'] = $goalAmount;
    $data['current_amount'] = (float) ($apiData['current_amount'] ?? 0);
    $data['donor_count'] = (int) ($apiData['donor_count'] ?? 0);

    // keep compatibility with existing index.twig which expects donationGoal/Level
    $data['donationGoal'] = $goalAmount;
    $data['donationLevel'] = ($displayType === 'count') ? (float) $data['donor_count'] : (float) $data['current_amount'];

    // persist per-instance meta (use a synthetic instance id from field values)
    $instanceId = md5($postId . '|' . $searchId . '|' . $donationType . '|' . $displayType);
    store_instance_data($postId, $instanceId, $apiData);

    return $data;
});

/**
 * AJAX: Manual refresh for editors (nonce recommended; minimal implementation here).
 */
add_action('wp_ajax_update_donation_barometer', function () {
    if (!current_user_can('edit_posts')) {
        wp_send_json_error('Unauthorized', 403);
    }

    $searchId = isset($_POST['search_id']) ? sanitize_text_field((string) $_POST['search_id']) : '';
    $donationType = isset($_POST['donation_type']) ? sanitize_text_field((string) $_POST['donation_type']) : 'both';

    // clear cache and refetch
    delete_transient(get_cache_key($searchId, $donationType));
    $data = fetch_donations($searchId, $donationType);

    // optionally store on the current post if provided
    $postId = isset($_POST['post_id']) ? (int) $_POST['post_id'] : 0;
    if ($postId > 0) {
        $instanceId = md5($postId . '|' . $searchId . '|' . $donationType);
        store_instance_data($postId, $instanceId, $data);
    }

    wp_send_json_success($data);
});

