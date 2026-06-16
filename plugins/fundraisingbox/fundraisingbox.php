<?php
/**
 * Plugin Name: FundraisingBox Settings (Theme Bundled)
 * Description: Provides a central settings page and option storage for FundraisingBox API credentials used across the theme/components.
 * Version: 1.0.0
 * Author: goodDEV
 * Text Domain: fundraisingbox
 */

namespace FundraisingBoxPlugin;

if (!defined('ABSPATH')) { exit; }

// Try to use the theme's Composer autoloader
$themeVendorAutoload = dirname(__DIR__, 2) . '/vendor/autoload.php';
if (file_exists($themeVendorAutoload)) {
    require_once $themeVendorAutoload;
}

// Fallback loader for our src tree if Composer classmap isn't available
if (!class_exists('FundraisingBoxPlugin\\Plugin')) {
    $srcDir = __DIR__ . '/src';
    if (is_dir($srcDir)) {
        $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($srcDir));
        foreach ($iterator as $file) {
            if ($file instanceof \SplFileInfo && $file->isFile() && substr($file->getFilename(), -4) === '.php') {
                require_once $file->getPathname();
            }
        }
    }
}

if (!class_exists('FundraisingBoxPlugin\\Plugin')) {
    add_action('admin_notices', function(){
        $themePath = esc_html(str_replace(ABSPATH, '', dirname(__DIR__, 2)));
        echo '<div class="notice notice-warning"><p>'
            . esc_html__('FundraisingBox Settings: Autoloader not found. Please run composer in the THEME directory if classes fail to load.', 'fundraisingbox')
            . '</p><p><code>cd ' . $themePath . ' && composer install && composer dump-autoload -o</code></p></div>';
    });
    return;
}

add_action('plugins_loaded', function(){
    Plugin::instance()->boot();
});
