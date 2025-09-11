<?php
/**
 * Plugin Name: Facebook CAPI (Custom)
 * Description: Clean, structured integration of Facebook Conversions API. Loads modular classes.
 * Version: 1.1.0
 * Author: goodDEV
 * Text Domain: facebook-capi
 */

if (!defined('ABSPATH')) { exit; }

// Prefer local plugin vendor autoload; fall back to global if available
$autoloads = [
  __DIR__ . '/vendor/autoload.php',
  ABSPATH . 'vendor/autoload.php',
];
foreach ($autoloads as $autoload) {
  if (file_exists($autoload)) { require_once $autoload; break; }
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
  // Show admin notice if classes are not available (composer missing or not deployed yet)
  add_action('admin_notices', function(){
    $path = esc_html(str_replace(ABSPATH, '', __DIR__));
    echo '<div class="notice notice-warning"><p>'
      . esc_html__('Facebook CAPI: Autoloader or classes not available. Ensure you ran composer install in the plugin directory.', 'facebook-capi')
      . '</p><p><code>cd ' . $path . ' && composer install && composer dump-autoload -o</code></p></div>';
  });
  return;
}

add_action('plugins_loaded', function(){
  \FacebookCapiPlugin\Plugin::instance()->boot();
});
