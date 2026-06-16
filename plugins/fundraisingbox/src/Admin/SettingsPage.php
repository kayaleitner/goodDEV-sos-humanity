<?php
namespace FundraisingBoxPlugin\Admin;

use FundraisingBoxPlugin\Support\Options;

class SettingsPage
{
    public function register(): void
    {
        add_action('admin_menu', [$this, 'menu']);
        add_action('admin_init', [$this, 'settings']);
    }

    public function menu(): void
    {
        add_options_page(
            __('FundraisingBox API', 'fundraisingbox'),
            __('FundraisingBox API', 'fundraisingbox'),
            'manage_options',
            Options::PAGE,
            [$this, 'render']
        );
    }

    public function settings(): void
    {
        register_setting(Options::GROUP, Options::API_BASE_URL, [
            'type' => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'default' => '',
        ]);
        register_setting(Options::GROUP, Options::ACCESS_TOKEN, [
            'type' => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'default' => '',
        ]);
        register_setting(Options::GROUP, Options::BAROMETER_DATA_CACHE_EXPIRATION, [
          'type' => 'integer',
          'sanitize_callback' => 'intval',
          'default' => '',
        ]);

        add_settings_section('fundraisingbox_main', __('FundraisingBox API Settings', 'fundraisingbox'), '__return_false', Options::PAGE);

        add_settings_field('api_base_url', __('API Base URL', 'fundraisingbox'), function () {
            printf('<input type="text" class="regular-text" name="%s" value="%s" />',
                esc_attr(Options::API_BASE_URL), esc_attr((string) get_option(Options::API_BASE_URL, '')));
        }, Options::PAGE, 'fundraisingbox_main');

        add_settings_field('access_token', __('Access Token', 'fundraisingbox'), function () {
            printf('<input type="password" class="regular-text" name="%s" value="%s" />',
                esc_attr(Options::ACCESS_TOKEN), esc_attr((string) get_option(Options::ACCESS_TOKEN, '')));
        }, Options::PAGE, 'fundraisingbox_main');

        add_settings_field('barometer_data_cache_expiration', __('Cache Lebenzeit für Barometer Daten (in min)', 'fundraisingbox'), function () {
          printf('<input type="number" min="1" step="1" class="regular-text" name="%s" value="%s" />',
            esc_attr(Options::BAROMETER_DATA_CACHE_EXPIRATION), esc_attr((int) get_option(Options::BAROMETER_DATA_CACHE_EXPIRATION, '')));
        }, Options::PAGE, 'fundraisingbox_main');
    }

    public function render(): void
    {
        if (!current_user_can('manage_options')) {
            wp_die(esc_html__('You do not have permission to view this page.', 'fundraisingbox'));
        }
        echo '<div class="wrap">';
        echo '<h1>' . esc_html__('FundraisingBox API Settings', 'fundraisingbox') . '</h1>';
        echo '<form method="post" action="options.php">';
        settings_fields(Options::GROUP);
        do_settings_sections(Options::PAGE);
        submit_button();
        echo '</form>';
        echo '</div>';
    }
}
