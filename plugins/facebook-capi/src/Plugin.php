<?php
namespace FacebookCapiPlugin;

use FacebookCapiPlugin\Admin\SettingsPage;
use FacebookCapiPlugin\Rest\EventsController;
use FacebookCapiPlugin\Rest\PrettyEndpoint;
use FacebookCapiPlugin\Frontend\Shortcodes;
use FacebookCapiPlugin\Support\Options;

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
        // i18n (optional)
        load_plugin_textdomain('facebook-capi', false, dirname(plugin_basename(__FILE__), 2) . '/languages');

        // Activation hooks (set defaults + flush rewrites for pretty endpoint)
        register_activation_hook($this->plugin_file(), function () {
            Options::ensureDefaults();
            $enable_pretty = apply_filters('facebook_capi_enable_pretty_endpoint', false);
            if ($enable_pretty) {
                PrettyEndpoint::addRewriteRule();
            }
            flush_rewrite_rules();
        });
        register_deactivation_hook($this->plugin_file(), function () {
            flush_rewrite_rules();
        });

        // Admin settings
        (new SettingsPage())->register();

        // REST Routes
        add_action('rest_api_init', function () {
            (new EventsController())->register_routes();
        });

        // Pretty URL endpoint (non-REST) — optional
        $enable_pretty = apply_filters('facebook_capi_enable_pretty_endpoint', false);
        if ($enable_pretty) {
            add_action('init', [PrettyEndpoint::class, 'addRewriteRule']);
            add_filter('query_vars', [PrettyEndpoint::class, 'registerQueryVar']);
            add_action('template_redirect', [PrettyEndpoint::class, 'handle']);
        }

        // Shortcodes / Frontend helpers
        (new Shortcodes())->register();
    }

    private function plugin_file(): string
    {
        static $file;
        if (!$file) {
            $file = dirname(__DIR__) . '/facebook-capi.php';
        }
        return $file;
    }
}
