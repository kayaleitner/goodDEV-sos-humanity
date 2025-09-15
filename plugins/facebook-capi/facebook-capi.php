<?php
/**
 * Plugin Name: Facebook CAPI (Custom)
 * Description: Clean, structured integration of Facebook Conversions API. Loads modular classes.
 * Version: 1.2.0
 * Author: goodDEV
 * Text Domain: facebook-capi
 */

use FacebookCapiPlugin\Plugin;

if (!defined('ABSPATH')) { exit; }

// Load Composer autoloader from the theme vendor directory
$themeVendorAutoload = dirname(__DIR__, 2) . '/vendor/autoload.php';
if (file_exists($themeVendorAutoload)) {
    require_once $themeVendorAutoload;
}

if (!class_exists('FacebookCapiPlugin\\Plugin')) {
    // Fallback: try to load all classes from src/ manually (in case composer dump-autoload wasn't run yet)
    $srcDir = __DIR__ . '/src';
    if (is_dir($srcDir)) {
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($srcDir));
        foreach ($iterator as $file) {
            if ($file instanceof SplFileInfo && $file->isFile() && substr($file->getFilename(), -4) === '.php') {
                require_once $file->getPathname();
            }
        }
    }
}

if (!class_exists('FacebookCapiPlugin\\Plugin')) {
    // Show an admin notice if classes are not available (composer missing or not deployed yet)
    add_action('admin_notices', function(){
        $themePath = esc_html(str_replace(ABSPATH, '', dirname(__DIR__, 2)));
        echo '<div class="notice notice-warning"><p>'
            . esc_html__('Facebook CAPI: Composer autoloader not found. Please install dependencies in the THEME directory.', 'facebook-capi')
            . '</p><p><code>cd ' . $themePath . ' && composer install && composer dump-autoload -o</code></p></div>';
    });
    return;
}

add_action('plugins_loaded', function(){
    Plugin::instance()->boot();
});
