<?php
/**
 * REST endpoint: per-action display config for a Spendenaktion, derived from the
 * FRBox custom fields set per action.
 *
 * @endpoint /wp-json/sos/v1/action/config?cfd=su995
 * @method GET
 * @returns { "cfd": "su995", "barometer": true, "list": true, "only_monthly": false }
 *
 * Consumed by the FRBox-injected JS (runs inside the cross-origin FRBox iframe), so
 * the response carries permissive CORS headers. Only booleans, no sensitive data.
 *
 * @see inc/frboxFundraisingPageData.php (custom field IDs 16328/16329/16330)
 */

if (!defined('ABSPATH')) {
    exit;
}

add_action('rest_api_init', function () {
    register_rest_route('sos/v1', '/action/config', [
        'methods'  => 'GET',
        'args'     => [
            'cfd' => ['type' => 'string', 'required' => true],
        ],
        'permission_callback' => '__return_true',
        'callback' => function ($request) {
            header('Access-Control-Allow-Origin: *');
            header('Cache-Control: public, max-age=300');

            $cfd = sanitize_text_field((string) $request->get_param('cfd'));
            $flags = ['barometer' => false, 'list' => false, 'only_monthly' => false];

            if ($cfd !== '' && function_exists('Flynt\\FRBox\\get_action_flags')) {
                $flags = \Flynt\FRBox\get_action_flags($cfd);
            }

            return new WP_REST_Response(array_merge(['cfd' => $cfd], $flags), 200);
        },
    ]);
});
