<?php
namespace FacebookCapiPlugin\Rest;

use FacebookCapiPlugin\Support\Options;
use FacebookCapiPlugin\Service\FacebookClient;

class PrettyEndpoint
{
    public static function registerQueryVar(array $vars): array
    {
        $vars[] = 'fb_capi_event';
        return $vars;
    }

    public static function addRewriteRule(): void
    {
        add_rewrite_rule('^capi/event/?$', 'index.php?fb_capi_event=1', 'top');
    }

    public static function handle(): void
    {
        if (!get_query_var('fb_capi_event')) {
            return;
        }

        $method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');

        header('Vary: Origin');
        if (!headers_sent()) {
            $origin = $_SERVER['HTTP_ORIGIN'] ?? '';
            if ($origin) { header('Access-Control-Allow-Origin: ' . $origin); }
            header('Access-Control-Allow-Methods: POST, OPTIONS');
            header('Access-Control-Allow-Headers: Content-Type, X-FB-CAPI-Signature');
            header('Access-Control-Max-Age: 86400');
        }

        if ($method === 'OPTIONS') { status_header(204); exit; }
        if ($method !== 'POST') {
            header('Allow: POST, OPTIONS');
            self::sendJson(['error' => 'Method not allowed'], 405);
        }

        $contentType = $_SERVER['CONTENT_TYPE'] ?? $_SERVER['HTTP_CONTENT_TYPE'] ?? '';
        if (stripos($contentType, 'application/json') !== false) {
            $raw = file_get_contents('php://input');
            $data = json_decode($raw, true);
            if (!is_array($data)) { $data = []; }
        } else {
            $data = $_POST;
            $raw = wp_json_encode($data);
        }

        $opts = Options::all();
        $secret = (string) $opts['shared_secret'];
        if ($secret !== '') {
            $sig  = $_SERVER['HTTP_X_FB_CAPI_SIGNATURE'] ?? '';
            $calc = hash_hmac('sha256', (string)($raw ?? ''), $secret);
            if (!hash_equals($calc, $sig)) {
                self::sendJson(['error' => 'Invalid signature'], 401);
            }
        }

        if (empty($data['event_name'])) {
            self::sendJson(['error' => 'event_name is required'], 400);
        }

        if (empty($opts['access_token']) || empty($opts['pixel_id'])) {
            self::sendJson(['error' => 'fb_capi_config_missing'], 500);
        }
        if (!FacebookClient::isAvailable()) {
            self::sendJson(['error' => 'fb_capi_sdk_missing'], 500);
        }

        try {
            $client = new FacebookClient($opts['access_token'], $opts['pixel_id'], !empty($opts['test_mode']) ? $opts['test_id'] : null);
            $res = $client->send($data);
            self::sendJson(['ok' => true, 'result' => is_scalar($res) ? $res : json_decode((string)$res, true)], 200);
        } catch (\Throwable $e) {
            self::sendJson(['error' => 'fb_capi_exception', 'message' => $e->getMessage()], 500);
        }
    }

    private static function sendJson($data, int $status = 200): void
    {
        status_header($status);
        header('Content-Type: application/json; charset=utf-8');
        echo wp_json_encode($data);
        exit;
    }
}
