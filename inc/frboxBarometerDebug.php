<?php

/**
 * TEMPORARY debug endpoint for the per-action recurring data layer.
 *
 *   GET /wp-json/sos/v1/barometer/debug?cfd=su995
 *
 * Returns the resolved fundraising page + recurring/one-time aggregates so the
 * data path (cfd → page id → split) can be checked in a browser locally.
 *
 * Access is restricted to local DevKinsta hosts (*.local) or admins, so it is
 * not reachable on the production domain. Remove this file once the real
 * barometer rendering is in place.
 *
 * @see inc/frboxFundraisingPageData.php
 */

namespace Flynt\FRBox\Debug;

if (!defined('ABSPATH')) {
    exit;
}

add_action('rest_api_init', function () {
    register_rest_route('sos/v1', '/barometer/debug', [
        'methods'  => 'GET',
        'args'     => [
            'cfd' => ['type' => 'string', 'required' => false, 'default' => 'su995'],
        ],
        'permission_callback' => function () {
            $host = isset($_SERVER['HTTP_HOST']) ? (string) $_SERVER['HTTP_HOST'] : '';
            if (strpos($host, '.local') !== false) {
                return true; // local DevKinsta only
            }
            return current_user_can('manage_options');
        },
        'callback' => function ($request) {
            $cfd = sanitize_text_field((string) $request->get_param('cfd'));

            if (!function_exists('Flynt\\FRBox\\get_action_recurring_by_cfd')) {
                return new \WP_REST_Response(['error' => 'data layer not loaded'], 500);
            }

            $data = \Flynt\FRBox\get_action_recurring_by_cfd($cfd);
            if ($data === null) {
                return new \WP_REST_Response([
                    'cfd'   => $cfd,
                    'error' => 'cfd could not be resolved to a fundraising page (check API access right "Spendenaktionen" / cfd value)',
                ], 404);
            }

            $stats = $data['stats'];

            // Convenience preview of the two candidate metrics (final choice TBD with SOS).
            $data['metric_preview'] = [
                'recurring_donor_count'   => $stats['recurring_count'],
                'recurring_amount_raw_sum' => $stats['recurring_sum'], // per-interval amounts, not monthly-normalised
            ];

            return new \WP_REST_Response($data, 200);
        },
    ]);
});
