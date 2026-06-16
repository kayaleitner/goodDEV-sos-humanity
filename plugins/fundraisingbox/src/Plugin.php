<?php

namespace FundraisingBoxPlugin;

use FundraisingBoxPlugin\Admin\SettingsPage;

/**
 * Main plugin class.
 *
 * Handles initialization and setup for the FundraisingBox plugin.
 *
 * @package FundraisingBoxPlugin
 */
class Plugin
{
  /**
   * The single instance of the class.
   *
   * @var self|null
   */
  private static $instance = null;

  /**
   * Get the singleton instance.
   *
   * @return self
   */
  public static function instance(): self
  {
    if (!self::$instance) {
      self::$instance = new self();
    }
    return self::$instance;
  }

  /**
   * Bootstraps the plugin.
   *
   * Registers admin settings and hooks.
   *
   * @return void
   */
  public function boot(): void
  {
    // Register admin settings page when in backend
    add_action('init', function () {
      if (is_admin()) {
        (new SettingsPage())->register();
      }
    });
  }
}
