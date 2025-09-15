<?php

namespace Flynt;

use Flynt\Utils\FileLoader;

require_once __DIR__ . '/vendor/autoload.php';

// Ensure bundled Facebook CAPI plugin is loaded when theme is active
$fbCapiBootstrap = __DIR__ . '/plugins/facebook-capi/facebook-capi.php';
if (file_exists($fbCapiBootstrap)) {
    require_once $fbCapiBootstrap;
    // If plugins_loaded has already fired (themes load after), boot the plugin directly
    if (class_exists('FacebookCapiPlugin\\Plugin')) {
        \FacebookCapiPlugin\Plugin::instance()->boot();
    }
}

if (!defined('WP_ENV')) {
    define('WP_ENV', function_exists('wp_get_environment_type') ? wp_get_environment_type() : 'production');
} elseif (!defined('WP_ENVIRONMENT_TYPE')) {
    define('WP_ENVIRONMENT_TYPE', WP_ENV);
}

// Check if the required plugins are installed and activated.
// If they aren't, this function redirects the template rendering to use
// plugin-inactive.php instead and shows a warning in the admin backend.
if (Init::checkRequiredPlugins()) {
    FileLoader::loadPhpFiles('inc');
    add_action('after_setup_theme', [\Flynt\Init::class, 'initTheme']);
    add_action('after_setup_theme', [\Flynt\Init::class, 'loadComponents'], 101);
}

// Remove the admin-bar inline-CSS as it isn't compatible with the sticky footer CSS.
// This prevents unintended scrolling on pages with few content, when logged in.
add_theme_support('admin-bar', ['callback' => '__return_false']);

add_action('after_setup_theme', function (): void {
    // Make theme available for translation.
    // Translations can be filed in the /languages/ directory.
    load_theme_textdomain('flynt', get_template_directory() . '/languages');
});


add_action('wp_enqueue_scripts', function() {
//  if (is_admin()) return;

  $script_url = get_stylesheet_directory_uri() . '/assets/scripts/tra.js';
  wp_enqueue_script('tra', $script_url, [], '1.0.0', true);

  $data = [
    'endpoint' => esc_url_raw(rest_url('fb-capi/v1/event')),
    'nonce' => wp_create_nonce('wp_rest'),
  ];
  wp_localize_script('tra', 'TRACKING_DATA', $data);
});
