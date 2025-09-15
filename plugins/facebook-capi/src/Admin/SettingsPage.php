<?php
namespace FacebookCapiPlugin\Admin;

use FacebookCapiPlugin\Support\Options;

class SettingsPage
{
    const PAGE = 'facebook-capi-settings';
    const GROUP = 'facebook_capi_settings_group';

    public function register(): void
    {
        add_action('admin_menu', [$this, 'menu']);
        add_action('admin_init', [$this, 'settings']);
    }

    public function menu(): void
    {
        add_options_page(
            __('Facebook CAPI', 'facebook-capi'),
            __('Facebook CAPI', 'facebook-capi'),
            'manage_options',
            self::PAGE,
            [$this, 'render']
        );
    }

    public function settings(): void
    {
        register_setting(self::GROUP, Options::ACCESS_TOKEN, [
            'type' => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'default' => '',
        ]);
        register_setting(self::GROUP, Options::PIXEL_ID, [
            'type' => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'default' => '',
        ]);
        register_setting(self::GROUP, Options::TEST_MODE, [
            'type' => 'boolean',
            'sanitize_callback' => function ($v) { return (int) (bool) $v; },
            'default' => 0,
        ]);
        register_setting(self::GROUP, Options::TEST_ID, [
            'type' => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'default' => '',
        ]);

        add_settings_section('fb_capi_main', __('General', 'facebook-capi'), '__return_false', self::PAGE);

        add_settings_field('access_token', __('Access Token', 'facebook-capi'), function () {
            printf('<input type="text" class="regular-text" name="%s" value="%s" />',
                esc_attr(Options::ACCESS_TOKEN), esc_attr((string) get_option(Options::ACCESS_TOKEN, '')));
        }, self::PAGE, 'fb_capi_main');

        add_settings_field('pixel_id', __('Pixel ID', 'facebook-capi'), function () {
            printf('<input type="text" class="regular-text" name="%s" value="%s" />',
                esc_attr(Options::PIXEL_ID), esc_attr((string) get_option(Options::PIXEL_ID, '')));
        }, self::PAGE, 'fb_capi_main');

        add_settings_field('test_mode', __('Activate Test Mode', 'facebook-capi'), function () {
            $checked = (int) get_option(Options::TEST_MODE, 0) === 1 ? 'checked' : '';
            printf('<label><input type="checkbox" name="%s" value="1" %s /> %s</label>',
                esc_attr(Options::TEST_MODE), $checked, esc_html__('Send events as test', 'facebook-capi'));
        }, self::PAGE, 'fb_capi_main');

        add_settings_field('test_id', __('Test Event Code', 'facebook-capi'), function () {
            printf('<input type="text" class="regular-text" name="%s" value="%s" />',
                esc_attr(Options::TEST_ID), esc_attr((string) get_option(Options::TEST_ID, '')));
        }, self::PAGE, 'fb_capi_main');

    }

    public function render(): void
    {
        if (!current_user_can('manage_options')) {
            wp_die(esc_html__('You do not have permission to view this page.', 'facebook-capi'));
        }
        echo '<div class="wrap">';
        echo '<h1>' . esc_html__('Facebook CAPI', 'facebook-capi') . '</h1>';
        echo '<form method="post" action="options.php">';
        settings_fields(self::GROUP);
        do_settings_sections(self::PAGE);
        submit_button();
        echo '</form>';
        echo '</div>';
    }
}
