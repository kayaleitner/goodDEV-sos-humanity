<?php
namespace FundraisingBoxPlugin;

use FundraisingBoxPlugin\Admin\SettingsPage;

class Plugin
{
    private static $instance;

    public static function instance(): self
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function boot(): void
    {
        // Register settings page
        add_action('init', function(){
            if (is_admin()) {
                (new SettingsPage())->register();
            }
        });

        // Register generic REST webhook for cache invalidation and refresh
        add_action('rest_api_init', function(){
            register_rest_route('fundraising/v1', '/update', [
                'methods' => 'POST',
                'callback' => function ($request) {
                    $params = $request->get_json_params();
                    $searchId = isset($params['search_id']) ? sanitize_text_field((string) $params['search_id']) : '';
                    $donationType = isset($params['donation_type']) ? sanitize_text_field((string) $params['donation_type']) : 'both';

                    if ($searchId === '') {
                        return rest_ensure_response(['error' => 'search_id required']);
                    }

                    $cacheKey = 'donation_barometer_' . md5($searchId . '|' . $donationType);
                    delete_transient($cacheKey);

                    // Try to warm cache by calling component fetch if available
                    if (function_exists('Flynt\\Components\\DonationBarometer\\fetch_donations')) {
                        $data = \Flynt\Components\DonationBarometer\fetch_donations($searchId, $donationType);
                    } else {
                        $data = ['status' => 'cache_cleared'];
                    }

                    return rest_ensure_response($data);
                },
                'permission_callback' => '__return_true',
            ]);
        });

        // Schedule hourly cron to refresh donations
        add_action('init', function(){
            if (!wp_next_scheduled('fundraisingbox_refresh_donations')) {
                wp_schedule_event(time() + 5 * MINUTE_IN_SECONDS, 'hourly', 'fundraisingbox_refresh_donations');
            }
        });

        add_action('fundraisingbox_refresh_donations', function(){
            $args = [
                'post_type' => ['page', 'post'],
                'post_status' => 'publish',
                'meta_query' => [
                    'relation' => 'AND',
                    [
                        'key' => 'search_id',
                        'compare' => 'EXISTS',
                    ],
                    [
                        'key' => 'search_id',
                        'value' => '',
                        'compare' => '!=',
                    ],
                ],
                'fields' => 'ids',
                'posts_per_page' => 500,
            ];
            $q = new \WP_Query($args);
            if ($q->have_posts()) {
                foreach ($q->posts as $postId) {
                    $searchId = (string) get_post_meta($postId, 'search_id', true);
                    $donationType = (string) get_post_meta($postId, 'donation_type', true);
                    if ($searchId === '') { continue; }
                    if ($donationType === '') { $donationType = 'both'; }

                    $cacheKey = 'donation_barometer_' . md5($searchId . '|' . $donationType);
                    delete_transient($cacheKey);

                    if (function_exists('Flynt\\Components\\DonationBarometer\\fetch_donations')) {
                        \Flynt\Components\DonationBarometer\fetch_donations($searchId, $donationType);
                    }
                }
            }
            wp_reset_postdata();
        });
    }
}
