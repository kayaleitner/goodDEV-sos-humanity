<?php

/**
 * FundraisingBox – per-action (Spendenaktion / fundraising page) data layer.
 *
 * Provides the automatic data path for the per-action recurring donation
 * barometer (W1) and the recurring/one-time donor marker (W2):
 *
 *   cfd (from page URL)  →  get_fundraising_page_by_cfd()  →  fb_fundraising_page_id
 *                        →  get_action_recurring_stats()   →  recurring vs one-time split
 *
 * Key facts (verified against the live SOS-Humanity FRBox account):
 *  - The FRBox admin UI "Spendenaktions-ID" is NOT the API id. The API id is the
 *    fundraising page `id` (= fb_fundraising_page_id), e.g. cfd "su995" → 72512.
 *  - donations.json filters reliably only by `fb_fundraising_page_id`
 *    (fb_project_id / *_promotion_code do NOT work for this).
 *  - A donation belongs to a *recurring* donation (Dauerspende set up via the
 *    action) when `fb_recurring_payment_id` is set. NOTE: `by_recurring` only
 *    flags automatic follow-up payments and is always 0 for action donations.
 *  - Pages have no `domain`/cfd filter param; the cfd appears in `link`/`json_link`.
 *    Requires the API user to have read access to "Spendenaktionen" (pages.json).
 *
 * Privacy: only aggregates (counts/sums) are cached here – never donor rows.
 */

namespace Flynt\FRBox;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Build the configured FundraisingBox API base URL (trailing slash guaranteed).
 */
function api_base_url(): string
{
    return rtrim(get_option('fundraisingbox_api_base_url', 'https://api.fundraisingbox.com/v1/'), '/') . '/';
}

/**
 * Perform an authenticated GET against the FundraisingBox REST API.
 *
 * @return array|null Decoded `data` collection on success, null on failure.
 *                    Pass $meta by reference to receive ['hasMore' => bool].
 */
function api_get_collection(string $path, array $params, ?array &$meta = null): ?array
{
    $token = (string) get_option('fundraisingbox_api_access_token', '');
    if ($token === '') {
        error_log('[FRBox] Missing API access token.');
        return null;
    }

    $url = api_base_url() . ltrim($path, '/') . '?' . http_build_query($params);

    $response = wp_remote_get($url, [
        'headers' => [
            'Authorization' => 'Basic ' . base64_encode($token . ':X'),
            'Accept'        => 'application/json',
        ],
        'timeout' => 20,
    ]);

    if (is_wp_error($response)) {
        error_log('[FRBox] API request failed: ' . $response->get_error_message());
        return null;
    }

    $code = (int) wp_remote_retrieve_response_code($response);
    $body = json_decode(wp_remote_retrieve_body($response), true);

    if ($code !== 200 || !is_array($body)) {
        $hint = is_array($body) && isset($body['errors']) ? wp_json_encode($body['errors']) : ('HTTP ' . $code);
        error_log('[FRBox] API error for ' . $path . ': ' . $hint);
        return null;
    }

    $meta = ['hasMore' => (bool) ($body['hasMore'] ?? false)];

    return isset($body['data']) && is_array($body['data']) ? $body['data'] : [];
}

/**
 * Fetch every page of a collection (capped to avoid runaway loops).
 *
 * @return array List of all records across pages.
 */
function api_get_all(string $path, array $params, int $maxPages = 30): array
{
    $rows = [];
    $page = 1;
    do {
        $meta = null;
        $batch = api_get_collection($path, array_merge($params, [
            'page'    => $page,
            'perPage' => 100,
            'is_test' => 'no',
        ]), $meta);

        if ($batch === null) {
            break;
        }
        $rows = array_merge($rows, $batch);
        $page++;
    } while (!empty($meta['hasMore']) && $page <= $maxPages);

    return $rows;
}

/**
 * Resolve a campaign code (cfd, e.g. "su995") to its fundraising page record.
 *
 * The pages collection is small (~50 entries) and rarely changes, so it is
 * cached for an hour. Matching is done on the cfd occurring in `link`/`json_link`
 * because the page object exposes neither a `domain` field nor a cfd filter.
 *
 * @return array|null ['id', 'goal', 'received', 'donation_count', 'title', 'status'] or null.
 */
function get_fundraising_page_by_cfd(string $cfd): ?array
{
    $cfd = trim($cfd);
    if ($cfd === '' || !preg_match('/^[A-Za-z0-9_-]+$/', $cfd)) {
        return null;
    }

    $cacheKey = 'frbox_page_by_cfd_' . md5($cfd);
    $cached = get_transient($cacheKey);
    if (is_array($cached)) {
        return $cached;
    }

    $pages = api_get_all('pages.json', [], 10);
    $needle = '=' . $cfd; // cfd appears as "?cfd=su995" in link and "/su995.json" in json_link

    $match = null;
    foreach ($pages as $p) {
        $haystack = ($p['link'] ?? '') . ' ' . ($p['json_link'] ?? '') . ' ' . ($p['payment_link'] ?? '');
        if (strpos($haystack, $needle) !== false || strpos($haystack, '/' . $cfd . '.json') !== false) {
            $match = $p;
            break;
        }
    }

    if (!$match || !isset($match['id'])) {
        // Cache the miss briefly so a bad cfd doesn't hammer the API.
        set_transient($cacheKey, [], 5 * MINUTE_IN_SECONDS);
        return null;
    }

    $result = [
        'id'             => (int) $match['id'],
        'goal'           => isset($match['goal']) ? (float) $match['goal'] : 0.0,
        'received'       => isset($match['received']) ? (float) $match['received'] : 0.0,
        'donation_count' => isset($match['donation_count']) ? (int) $match['donation_count'] : 0,
        'title'          => (string) ($match['title'] ?? ''),
        'status'         => (string) ($match['status'] ?? ''),
    ];

    set_transient($cacheKey, $result, HOUR_IN_SECONDS);

    return $result;
}

/**
 * Aggregate recurring vs. one-time donations for a fundraising page.
 *
 * A donation counts as a recurring "setup" when `fb_recurring_payment_id` is set
 * (someone started a Dauerspende through this action). Its amount is the raw
 * per-interval charge; monthly normalisation would require the linked recurring's
 * interval (not implemented here – see note below).
 *
 * Only aggregates are returned/cached – no personal donor data.
 *
 * @return array{
 *   recurring_count:int, recurring_sum:float,
 *   onetime_count:int, onetime_sum:float,
 *   total_count:int, total_sum:float
 * }
 */
function get_action_recurring_stats(int $pageId): array
{
    $empty = [
        'recurring_count' => 0, 'recurring_sum' => 0.0,
        'onetime_count'   => 0, 'onetime_sum'   => 0.0,
        'total_count'     => 0, 'total_sum'     => 0.0,
    ];
    if ($pageId <= 0) {
        return $empty;
    }

    $cacheExpiration = (int) get_option('barometer_data_cache_expiration', 3);
    $cacheKey = 'frbox_action_recurring_' . $pageId;
    $cached = get_transient($cacheKey);
    if (is_array($cached)) {
        return $cached;
    }

    $rows = api_get_all('donations.json', ['fb_fundraising_page_id' => $pageId]);
    if (!$rows) {
        return $empty;
    }

    $stats = $empty;
    foreach ($rows as $d) {
        $amount = isset($d['amount']) ? (float) $d['amount'] : 0.0;
        $isRecurring = !empty($d['fb_recurring_payment_id']);
        $stats['total_count']++;
        $stats['total_sum'] += $amount;
        if ($isRecurring) {
            $stats['recurring_count']++;
            $stats['recurring_sum'] += $amount;
        } else {
            $stats['onetime_count']++;
            $stats['onetime_sum'] += $amount;
        }
    }
    $stats['recurring_sum'] = round($stats['recurring_sum'], 2);
    $stats['onetime_sum']   = round($stats['onetime_sum'], 2);
    $stats['total_sum']     = round($stats['total_sum'], 2);

    set_transient($cacheKey, $stats, max(1, $cacheExpiration) * MINUTE_IN_SECONDS);

    return $stats;
}

/**
 * Convenience: resolve a cfd and return its recurring stats plus page meta.
 *
 * @return array{page:array,stats:array}|null Null if the cfd cannot be resolved.
 */
function get_action_recurring_by_cfd(string $cfd): ?array
{
    $page = get_fundraising_page_by_cfd($cfd);
    if (!$page) {
        return null;
    }
    return [
        'page'  => $page,
        'stats' => get_action_recurring_stats((int) $page['id']),
    ];
}

/**
 * Donor list for a fundraising page: name, amount, date and whether the donation
 * set up a recurring donation (Dauerspende = fb_recurring_payment_id present).
 *
 * Only the publicly displayed name (public_name, as already shown in the FRBox
 * donor list) is returned. Sorted newest first. Cached briefly.
 *
 * @return list<array{name:string,amount:float,date:string,recurring:bool}>
 */
function get_action_donor_list(int $pageId): array
{
    if ($pageId <= 0) {
        return [];
    }

    $cacheExpiration = (int) get_option('barometer_data_cache_expiration', 3);
    $cacheKey = 'frbox_action_donorlist_' . $pageId;
    $cached = get_transient($cacheKey);
    if (is_array($cached)) {
        return $cached;
    }

    $rows = api_get_all('donations.json', ['fb_fundraising_page_id' => $pageId]);

    $list = [];
    foreach ($rows as $d) {
        $list[] = [
            'name'      => trim((string) ($d['public_name'] ?? '')),
            'amount'    => isset($d['amount']) ? (float) $d['amount'] : 0.0,
            'date'      => (string) ($d['received_at'] ?? $d['created_at'] ?? ''),
            'message'   => trim((string) ($d['public_message'] ?? '')),
            'recurring' => !empty($d['fb_recurring_payment_id']),
        ];
    }

    // Newest first.
    usort($list, static function ($a, $b) {
        return strcmp($b['date'], $a['date']);
    });

    set_transient($cacheKey, $list, max(1, $cacheExpiration) * MINUTE_IN_SECONDS);

    return $list;
}

/**
 * Convenience: resolve a cfd and return page meta plus the donor list.
 *
 * @return array{page:array,list:array}|null Null if the cfd cannot be resolved.
 */
function get_action_donor_list_by_cfd(string $cfd): ?array
{
    $page = get_fundraising_page_by_cfd($cfd);
    if (!$page) {
        return null;
    }
    return [
        'page' => $page,
        'list' => get_action_donor_list((int) $page['id']),
    ];
}
