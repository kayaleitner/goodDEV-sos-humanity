<?php
namespace FacebookCapiPlugin\Rest;

use WP_REST_Request;
use WP_REST_Response;
use WP_Error;
use FacebookCapiPlugin\Support\Options;
use FacebookCapiPlugin\Service\FacebookClient;

class EventsController
{
    const REST_NAMESPACE = 'fb-capi/v1';

    public function register_routes(): void
    {
        register_rest_route(self::REST_NAMESPACE, '/event', [
            'methods'  => 'POST',
            'callback' => [$this, 'handle'],
            'permission_callback' => function(\WP_REST_Request $request){
                // Verify REST nonce from header X-WP-Nonce (matches wp_create_nonce('wp_rest'))
                $nonce = $request->get_header('X-WP-Nonce');
                if (!$nonce || !wp_verify_nonce($nonce, 'wp_rest')) {
                    return new \WP_Error('rest_forbidden', __('Invalid or missing REST nonce.', 'facebook-capi'), ['status' => 401]);
                }
                // Enforce same-origin: require referrer from same host if available
                $referer = isset($_SERVER['HTTP_REFERER']) ? (string) $_SERVER['HTTP_REFERER'] : '';
                if ($referer) {
                    $site = parse_url(home_url(), PHP_URL_HOST);
                    $ref  = parse_url($referer, PHP_URL_HOST);
                    if ($site && $ref && $site !== $ref) {
                        return new \WP_Error('rest_forbidden', __('Cross-origin request not allowed.', 'facebook-capi'), ['status' => 403]);
                    }
                }
                // Optional referrer allowlist validation via filter
                $allowedFragments = apply_filters('facebook_capi_allowed_referrers', []);
                if (is_array($allowedFragments) && !empty($allowedFragments)) {
                    $allowed = false;
                    foreach ($allowedFragments as $fragment) {
                        $frag = (string) $fragment;
                        if ($frag !== '' && stripos($referer, $frag) !== false) {
                            $allowed = true;
                            break;
                        }
                    }
                    if (!$allowed) {
                        return new \WP_Error('rest_forbidden', __('Forbidden: invalid referrer.', 'facebook-capi'), ['status' => 403]);
                    }
                }
                return true;
            },
        ]);
    }

    public function handle(WP_REST_Request $req)
    {
        // Read payload early so we can honor a client-side consent hint when the cookie is not readable by the REST domain
        $payload = (array) $req->get_json_params();
        // Server-side Borlabs consent enforcement (production by default)
        $enforceConsent = apply_filters('facebook_capi_enforce_consent', function_exists('wp_get_environment_type') ? (wp_get_environment_type() === 'production') : true);

        if ($enforceConsent) {
            $aliases = apply_filters('facebook_capi_borlabs_service_aliases', ['meta-pixel', 'facebook-pixel', 'facebook']);
            $hasConsent = false;

            // Helper to extract borlabs-cookie value from $_COOKIE or raw header if needed
            $extractBorlabs = static function(): ?string {
                // Standard PHP cookie bag
                if (!empty($_COOKIE['borlabs-cookie'])) {
                    return (string) $_COOKIE['borlabs-cookie'];
                }
                // parse from raw Cookie header if available
                $httpCookie = isset($_SERVER['HTTP_COOKIE']) ? (string) $_SERVER['HTTP_COOKIE'] : '';
                if ($httpCookie !== '') {
                    // look for borlabs-cookie=...; boundary is semicolon or string end
                    if (preg_match('/(?:^|;\s*)borlabs-cookie=([^;]*)/i', $httpCookie, $m)) {
                        return $m[1];
                    }
                }
                return null;
            };

            // 1) Try cookie-based consent (Borlabs v3 JSON in borlabs-cookie)
            $rawCookie = $extractBorlabs();
            if (!empty($rawCookie)) {
                $raw = $rawCookie;

                // Some setups double-encode
                $val = urldecode($raw);
                $val2 = urldecode($val);
                $json = json_decode(is_string($val2) && strlen($val2) > 0 && $val2[0] === '{' ? $val2 : $val, true);
                if (is_array($json) && !empty($json['consents']) && is_array($json['consents'])) {
                    foreach ($json['consents'] as $group) {
                        if (is_array($group)) {
                            foreach ($aliases as $sid) {
                                if (in_array($sid, $group, true)) { $hasConsent = true; break 2; }
                            }
                        }
                    }
                }
            }

            // Optional debug: show the presence of cookie/header when WP_DEBUG
            if (defined('WP_DEBUG') && WP_DEBUG) {
                $hasRawCookie = !empty($rawCookie);
                $hdrLen = isset($_SERVER['HTTP_COOKIE']) ? strlen((string) $_SERVER['HTTP_COOKIE']) : 0;
                error_log('[Facebook CAPI][Consent] borlabs-cookie present: ' . ($hasRawCookie ? 'yes' : 'no') . '; HTTP_COOKIE length: ' . $hdrLen);
            }

            // 2) Fallback: honor an explicit client hint if provided (helps when REST domain cannot read cookie)
            if (!$hasConsent && !empty($payload['borlabs_consent'])) {
                $hasConsent = (bool) $payload['borlabs_consent'];
            }

            if (!$hasConsent) {
                if (defined('WP_DEBUG') && WP_DEBUG) {
                    error_log('[Facebook CAPI] Skipping send: no consent (cookie nor client hint)');
                }
                return new WP_REST_Response(['ok' => true, 'skipped' => 'no_consent'], 200);
            }
        }

        $opts = Options::all();
        if (empty($opts['access_token']) || empty($opts['pixel_id'])) {
            return new WP_Error('fb_capi_config_missing', __('Access Token oder Pixel ID fehlt.', 'facebook-capi'), ['status' => 500]);
        }
        if (!FacebookClient::isAvailable()) {
            return new WP_Error('fb_capi_sdk_missing', __('Facebook PHP Business SDK nicht installiert.', 'facebook-capi'), ['status' => 500]);
        }

        $payload = (array) $req->get_json_params();
        try {
            $client = new FacebookClient($opts['access_token'], $opts['pixel_id'], !empty($opts['test_mode']) ? $opts['test_id'] : null);

            // In test mode, log a concise summary for debugging
            if (!empty($opts['test_mode'])) {
                $summary = [
                    'event_name' => $payload['event_name'] ?? null,
                    'event_id' => $payload['event_id'] ?? null,
                    'event_time' => $payload['event_time'] ?? null,
                    'action_source' => $payload['action_source'] ?? null,
                    'has_user_data' => !empty($payload['user_data']),
                    'em' => !empty($payload['user_data']['em'] ?? null),
                    'has_fbp' => !empty($payload['user_data']['fbp'] ?? null),
                    'has_fbc' => !empty($payload['user_data']['fbc'] ?? null),
                ];
                error_log('[Facebook CAPI][TEST] Payload summary: ' . wp_json_encode($summary));
            }

            $res = $client->send($payload);
            $safe = is_scalar($res) ? $res : json_decode((string) $res, true);
            return new WP_REST_Response(['ok' => true, 'result' => $safe], 200);
        } catch (\Throwable $e) {
            error_log('[Facebook CAPI] Error: ' . $e->getMessage());
            return new WP_Error('fb_capi_exception', $e->getMessage(), ['status' => 500]);
        }
    }
}
