<?php
namespace FacebookCapiPlugin\Rest;

use WP_REST_Request;
use WP_REST_Response;
use WP_Error;
use FacebookCapiPlugin\Support\Options;
use FacebookCapiPlugin\Service\FacebookClient;

class EventsController
{
    const NAMESPACE = 'fb-capi/v1';

    public function register_routes(): void
    {
        register_rest_route(self::NAMESPACE, '/event', [
            'methods'  => 'POST',
            'callback' => [$this, 'handle'],
            'permission_callback' => function(\WP_REST_Request $request){
                // Verify REST nonce from header X-WP-Nonce
                $nonce = $request->get_header('X-WP-Nonce');
                if (!$nonce || !wp_verify_nonce($nonce, 'wp_rest')) {
                    return new \WP_Error('rest_forbidden', __('Invalid or missing REST nonce.', 'facebook-capi'), ['status' => 401]);
                }
                // Enforce same-origin: require referrer from same host if available
                $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
                if ($referer) {
                    $site = parse_url(home_url(), PHP_URL_HOST);
                    $ref  = parse_url($referer, PHP_URL_HOST);
                    if ($site && $ref && $site !== $ref) {
                        return new \WP_Error('rest_forbidden', __('Cross-origin request not allowed.', 'facebook-capi'), ['status' => 403]);
                    }
                }
                return true;
            },
        ]);
    }

    public function handle(WP_REST_Request $req)
    {
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
            $res = $client->send($payload);
            $safe = is_scalar($res) ? $res : json_decode((string) $res, true);
            return new WP_REST_Response(['ok' => true, 'result' => $safe], 200);
        } catch (\Throwable $e) {
            return new WP_Error('fb_capi_exception', $e->getMessage(), ['status' => 500]);
        }
    }
}
