### FundraisingBox Settings (Theme-Bundled) — README

#### Overview
This plugin is bundled inside your theme and provides a central settings page for FundraisingBox (FRBox) API credentials and options that are used by theme components (e.g., Donation Barometer). It keeps secrets in one place and exposes them via standard WordPress options.

It is intentionally designed to be extensible so you can later add new features such as FRBox webhook integrations and cron-based tasks.

#### Where it lives
- Path: `wp-content/themes/boilerplate-flynt-next/plugins/fundraisingbox/`
- Main file: `fundraisingbox.php`
- Namespace: `FundraisingBoxPlugin` (classes live under `src/`)

The theme boots this plugin automatically from `functions.php`:
```php
$frbBootstrap = __DIR__ . '/plugins/fundraisingbox/fundraisingbox.php';
if (file_exists($frbBootstrap)) {
  require_once $frbBootstrap;
  if (class_exists('FundraisingBoxPlugin\Plugin')) {
    \FundraisingBoxPlugin\Plugin::instance()->boot();
  }
}
```

#### What it does
- Registers an admin settings page under WP Admin → Settings → FundraisingBox API.
- Stores the FRBox API Base URL, Access Token, and a cache expiration used by the Donation Barometer component.
- Makes these options available via `get_option()` so components can consume them.

#### Admin Settings
Menu: Settings → FundraisingBox API (slug `fundraisingbox-settings`)

Stored options (see `src/Support/Options.php`):
- `fundraisingbox_api_base_url` — FRBox API Base URL (e.g., `https://api.fundraisingbox.com/v1/`). If not set, components fall back to this default.
- `fundraisingbox_api_access_token` — FRBox Access Token used for API authentication.
- `barometer_data_cache_expiration` — Cache lifetime in minutes for Donation Barometer data. If empty, components default to 3 minutes.

Permissions: Only administrators with `manage_options` can view/update settings.

#### Autoloader and dependencies
- The plugin tries to use the theme’s Composer autoloader at `themes/boilerplate-flynt-next/vendor/autoload.php`.
- If classes can’t be found, it falls back to a simple loader that `require_once`s all PHP files in `src/`.
- If neither works, an admin notice will suggest running Composer in the theme directory:
  ```bash
  cd wp-content/themes/boilerplate-flynt-next && composer install && composer dump-autoload -o
  ```

#### How the theme uses these settings (example: Donation Barometer)
Component: `Components/DonationBarometer`
- Reads `fundraisingbox_api_base_url` (defaults to `https://api.fundraisingbox.com/v1/` if unset).
- Reads `fundraisingbox_api_access_token` for authentication.
- Reads `barometer_data_cache_expiration` to set the transient lifetime (minutes) for fetched totals.

Behavior (see `Components/DonationBarometer/functions.php`):
- Fetches data from `GET {API_BASE_URL}/donations.json` using a Smart Search ID (`search_id`).
- Auth: Sends `Authorization: Basic {base64(ACCESS_TOKEN + ':X')}`.
- Pagination: Requests pages of 100 records until `hasMore` is false; aggregates amount and donor count.
- Caching: Stores the result in a per-query transient for the configured minutes.
- Fallback: If an API call fails or returns zeros, it attempts to reuse the last stored per-instance data from post meta.
- Output: Provides `current_amount` and `donor_count` to Twig via the Flynt `addComponentData` filter. The component can show either total amount (`sum`) or number of donors (`count`).

What you must configure for Donation Barometer to work:
- In WP Admin → Settings → FundraisingBox API:
    - Set `Access Token` (required).
    - Optionally set `API Base URL` (if using a non-default endpoint).
    - Optionally set `Cache lifetime` in minutes (leave empty to use default 3 minutes).
- In the component’s ACF fields on the page/post:
    - Provide the FRBox Smart Search `Search ID`.
    - Choose display type (`sum` or `count`) and set a goal.

#### Installation & activation
Because this plugin is theme-bundled, there’s nothing to install separately:
- Activate the theme. The theme will load and boot this plugin automatically.
- If you see an admin notice about the autoloader, run Composer in the theme directory as shown above.

#### Security notes
- Keep your FRBox Access Token private. Only admins can edit it.
- Use HTTPS for all front-end pages and API calls.
- Server-side code logs errors via `error_log()`; avoid exposing sensitive info in logs.

#### Internationalization (i18n)
- Text domain: `fundraisingbox`
- Admin UI labels are translation-ready. One field label is currently German (`Cache Lebenzeit …`), which you can translate via standard WordPress translation tools if needed.

#### Performance
- API results are cached per query (search ID + display type) for the configured number of minutes.
- Consider increasing the cache interval on high-traffic sites to reduce API load.

#### Troubleshooting
- "Autoloader not found" admin notice: Run Composer in the theme directory so classes can be autoloaded.
- Empty numbers in the barometer: Ensure `Access Token` is set and valid. Check that the `Search ID` exists in FRBox and that `is_test=no` matches your data.
- Slow pages: Increase the cache lifetime; ensure your server can reach `api.fundraisingbox.com`.

#### Extensibility roadmap (planned)
This plugin is built to be easy to extend later:

1) Webhook integration from FundraisingBox
- Add a REST endpoint like `wp-json/fbx/v1/webhook`.
- Verify signature using a shared secret (e.g., `FRBOX_WEBHOOK_SECRET`) and dispatch actions such as `do_action('frbox_webhook_event', $event)`.
- Use this to update post meta, trigger notifications, or sync statuses when donations/subscriptions change.

2) Cron tasks for maintenance/sync
- Schedule jobs with `wp_schedule_event` (e.g., hourly) to refresh totals, expire campaigns, or clean old transients.
- Optionally add WP-CLI commands for manual runs (e.g., `wp fbx sync`).

These features are not in the current code yet but are supported by the plugin’s structure and option storage.

#### Developer reference
Key files:
- `plugins/fundraisingbox/fundraisingbox.php` — Plugin bootstrap and autoloader wiring.
- `plugins/fundraisingbox/src/Plugin.php` — Registers admin hooks in `boot()`.
- `plugins/fundraisingbox/src/Admin/SettingsPage.php` — Settings page and fields.
- `plugins/fundraisingbox/src/Support/Options.php` — Option names and constants.
- `Components/DonationBarometer/functions.php` — Consumer example that uses the stored options.

Option names for programmatic access:
```php
get_option('fundraisingbox_api_base_url');
get_option('fundraisingbox_api_access_token');
get_option('barometer_data_cache_expiration');
```

#### Code examples (Webhook & Cron)

Below are ready-to-copy examples that fit this theme-bundled plugin. You can drop them into a new file under the plugin (e.g., `plugins/fundraisingbox/src/Extensions.php`) or into your theme’s `functions.php` (adjust namespaces accordingly).

1) Webhook endpoint example (receive events from FundraisingBox)

- Endpoint URL after adding this code: `/wp-json/fbx/v1/webhook`
- Authentication: HMAC signature header `x-frbox-signature` using a shared secret.
- Where to store the secret: Recommended via `wp-config.php` constant `FRBOX_WEBHOOK_SECRET`.

```php
<?php
// If placed inside the plugin under src/, keep the namespace. If in theme, remove/change namespace.
namespace FundraisingBoxPlugin; 

if (!defined('ABSPATH')) { exit; }

// Register REST endpoint
add_action('rest_api_init', function () {
  register_rest_route('fbx/v1', '/webhook', [
    'methods'  => 'POST',
    'callback' => __NAMESPACE__ . '\\fbx_handle_webhook',
    'permission_callback' => '__return_true', // Signature check happens inside
  ]);
});

/**
 * Verify HMAC signature sent by FundraisingBox.
 */
function fbx_verify_signature(string $payload, ?string $signature): bool
{
  $secret = defined('FRBOX_WEBHOOK_SECRET') ? FRBOX_WEBHOOK_SECRET : '';
  if ($secret === '' || !$signature) {
    return false;
  }
  // Example: use sha256 HMAC. Adjust if FRBox uses a different scheme.
  $expected = hash_hmac('sha256', $payload, $secret);
  // Timing-safe compare
  return hash_equals($expected, $signature);
}

/**
 * Handle webhook requests.
 */
function fbx_handle_webhook(\WP_REST_Request $request): \WP_REST_Response
{
  $payload   = $request->get_body();
  $signature = $request->get_header('x-frbox-signature');

  if (!fbx_verify_signature($payload, $signature)) {
    return new \WP_REST_Response(['ok' => false, 'error' => 'invalid_signature'], 401);
  }

  $event = json_decode($payload, true);
  if (!is_array($event)) {
    return new \WP_REST_Response(['ok' => false, 'error' => 'invalid_payload'], 400);
  }

  // Dispatch a WP action so your theme/components can react.
  do_action('frbox_webhook_event', $event);

  // Optional: simple logging for debugging (avoid logging secrets in production)
  if (defined('WP_DEBUG') && WP_DEBUG) {
    error_log('[FRBox Webhook] ' . wp_json_encode([
      'type' => $event['type'] ?? 'unknown',
      'id'   => $event['id']   ?? null,
    ]));
  }

  return new \WP_REST_Response(['ok' => true], 200);
}
```

Usage notes:
- Add your secret to `wp-config.php`:
  ```php
  define('FRBOX_WEBHOOK_SECRET', 'your-generated-secret-here');
  ```
- In FRBox, configure the webhook to POST JSON to `https://your-site.com/wp-json/fbx/v1/webhook` and send the HMAC signature in `x-frbox-signature`.
- In your theme or a plugin, listen for the action and react:
  ```php
  add_action('frbox_webhook_event', function(array $event) {
    // Example: mark a donor as verified, update post meta, etc.
  });
  ```

2) Cron task example (hourly sync/cleanup)

This schedules a WordPress Cron event hourly. It includes a handler where you can refresh cached numbers, expire campaigns, or run any routine.

```php
<?php
namespace FundraisingBoxPlugin; 

if (!defined('ABSPATH')) { exit; }

// Schedule on init if not present
add_action('init', function () {
  if (!wp_next_scheduled('fbx_hourly_maintenance')) {
    wp_schedule_event(time(), 'hourly', 'fbx_hourly_maintenance');
  }
});

// Clear on theme switch (useful for theme-bundled plugin)
add_action('switch_theme', function () {
  wp_clear_scheduled_hook('fbx_hourly_maintenance');
});

// Main task handler
add_action('fbx_hourly_maintenance', __NAMESPACE__ . '\\fbx_run_hourly_maintenance');

function fbx_run_hourly_maintenance(): void
{
  // Example: clean up old transients created by the Donation Barometer component
  global $wpdb;
  $like = $wpdb->esc_like('donation_barometer_') . '%';
  $sql  = $wpdb->prepare("SELECT option_name FROM {$wpdb->options} WHERE option_name LIKE %s", '_transient_' . $like);
  $rows = $wpdb->get_col($sql);
  foreach ($rows as $optionName) {
    $transientKey = substr($optionName, strlen('_transient_'));
    delete_transient($transientKey);
  }

  // You could also pre-warm caches by calling your data fetcher with known search IDs.
  // Example (pseudo):
  // $searchIds = ['123', 'abc'];
  // foreach ($searchIds as $sid) {
  //   \Flynt\Components\DonationBarometer\fetch_donations($sid, 'sum');
  // }
}
```

Optional: WP-CLI command to run the job manually
```php
<?php
namespace FundraisingBoxPlugin; 

if (defined('WP_CLI') && WP_CLI) {
  \WP_CLI::add_command('fbx sync', function () {
    fbx_run_hourly_maintenance();
    \WP_CLI::success('FBX maintenance completed.');
  });
}
```

Security tip for cron/webhook code
- Validate and sanitize all inputs.
- Use nonces/capability checks for any admin-only tasks.
- Keep secrets in `wp-config.php` (constants) or in a secure secret manager, not in the DB unless necessary.
