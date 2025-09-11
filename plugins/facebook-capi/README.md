# Facebook CAPI (Custom) WordPress Plugin

Note: This plugin follows WordPress best practices. The main entry point is `facebook-capi.php` and logic lives in `src/` with PSR-4 autoloading.

A lightweight WordPress plugin that sends server-side events to Facebook’s Conversions API (CAPI). It exposes a REST endpoint you can call from your front end, and provides an admin settings page for your Access Token, Pixel ID, and optional Test Mode.

## Features
- REST endpoint: `POST /wp-json/fb-capi/v1/event`
- Sends events to Facebook CAPI using the official Facebook PHP Business SDK
- Admin settings under Settings → Facebook CAPI:
  - Access Token
  - Pixel ID
  - Activate Test Mode
  - Test Event Code (Test ID)
- Shortcode `[fb_capi_thanks_example]` outputs a simple front-end JS snippet that posts a sample event to the REST endpoint

## Requirements
- WordPress 5.8+
- PHP 7.4+
- Composer (for installing the Facebook SDK)
- A Facebook Pixel and a System User Access Token with sufficient permissions

## Installation
1. Place this plugin in your WordPress installation at:
   `wp-content/plugins/facebook-capi/`
2. Install dependencies via Composer inside the plugin directory:
   ```bash
   cd wp-content/plugins/facebook-capi
   # if vendor/ is missing
   composer install
   composer dump-autoload -o
   # or, if composer.json didn’t exist before, initialize and require the SDK:
   # composer init
   # composer require facebook/php-business-sdk
   ```
   This installs the SDK and generates `vendor/autoload.php` which the plugin will load automatically.
3. Activate the plugin in WordPress Admin → Plugins.

## Installation workaround without access to `wp-content/plugins`

We set a symlink via composer post install script:

```json
{
  "scripts": {
    "post-install-cmd": [
      "composer install -d plugins/facebook-capi --no-dev --no-interaction --prefer-dist",
      "./scripts/postinstall.sh"
    ],
    "post-update-cmd": [
      "@post-install-cmd"
    ]
  }
}
```

For correct symlink installation you have to execute composer inside docker container, you can do it with:
```shell
docker run --rm -it -v "${HOME}/DevKinsta/public/soshumanity:/www/kinsta/public/soshumanity" -w /www/kinsta/public/soshumanity/wp-content/themes/boilerplate-flynt-next  composer install
```

## Configuration
Go to Settings → Facebook CAPI and fill in:
- Access Token: Your Facebook System User Access Token
- Pixel ID: Your Facebook Pixel ID
- Activate Test Mode: Toggle if you want to send test events
- Test Event Code (Test ID): Your test event code from Events Manager (only used if Test Mode is enabled)

All values are stored in `wp_options` as:
- `facebook_capi_access_token`
- `facebook_capi_pixel_id`
- `facebook_capi_activate_test_modus`
- `facebook_capi_test_id`

## Non-REST Pretty URL Endpoint (Option 2)
Endpoint: `POST /capi/event`

- This endpoint is DISABLED by default. Enable it via filter:
  ```php
  add_filter('facebook_capi_enable_pretty_endpoint', '__return_true');
  ```
- Bypasses WordPress REST API entirely (works even if REST is disabled).
- Optional HMAC header for server-to-server: `X-FB-CAPI-Signature` where value = `hash_hmac('sha256', <raw_request_body>, <shared_secret>)`.
- Configure the shared secret in Settings → Facebook CAPI → "Shared Secret (HMAC für /capi/event)".

cURL example:
```bash
curl -X POST \
  -H "Content-Type: application/json" \
  -H "X-FB-CAPI-Signature: $(php -r 'echo hash_hmac("sha256", "{\"event_name\":\"Donate\"}", "YOUR_SECRET");')" \
  -d '{
    "event_name": "Donate",
    "event_time": '$(date +%s)',
    "action_source": "website",
    "event_id": "abc123",
    "event_source_url": "https://example.com/thank-you",
    "user_data": {"em": "buyer@example.com"},
    "custom_data": {"content_name": "Donation", "value": 10, "currency": "EUR", "num_items": 1, "content_type": "donation"}
  }' \
  https://your-site.tld/capi/event
```

## REST API Usage
Endpoint: `POST /wp-json/fb-capi/v1/event`

Send JSON body with the following fields (example):
```json
{
  "event_name": "Purchase",
  "event_time": 1725537600,
  "action_source": "website",
  "event_id": "unique-token-abc123",
  "event_source_url": "https://example.com/thank-you",
  "user_data": {
    "em": "buyer@example.com"
  },
  "custom_data": {
    "content_name": "Donation",
    "value": 25,
    "currency": "EUR",
    "num_items": 1,
    "content_type": "donation"
  }
}
```
Notes:
- `user_data.em` may be sent unhashed; the SDK will hash it as required. Ensure it’s a valid email.
- If Test Mode and Test ID are configured, requests will be flagged as test events in Facebook Events Manager.
- The route verifies a REST nonce from the `X-WP-Nonce` header (created with `wp_create_nonce('wp_rest')`) and enforces same-origin. Requests without a valid nonce are rejected.

### cURL Example
```bash
curl -X POST \
  -H "Content-Type: application/json" \
  -d '{
    "event_name": "Purchase",
    "event_time": '$(date +%s)',
    "action_source": "website",
    "event_id": "abc123",
    "event_source_url": "https://example.com/thank-you",
    "user_data": {"em": "buyer@example.com"},
    "custom_data": {"content_name": "Donation", "value": 10, "currency": "EUR", "num_items": 1, "content_type": "donation"}
  }' \
  https://your-site.tld/wp-json/fb-capi/v1/event
```

## Front-end Integration (Theme Script)
If you are using the Flynt-based theme integration added in this project, a small script (`assets/scripts/tra.js`) is enqueued by the theme and will:
- Persist UTM parameters in `sessionStorage` on landing pages.
- On defined thank-you pages, validate URL parameters (`token`, `amount`, `interval`).
- Check Borlabs Cookie consent for the Meta Pixel (`meta-pixel` consent group).
- POST a payload to this plugin’s REST endpoint.
- Optionally push a `purchase` event into `dataLayer` for GTM.

Configuration from the theme:
- The theme localizes a JS object `TRACKING_DATA` with:
  - `endpoint`: `rest_url('fb-capi/v1/event')`
  - `thanksPages`: array of thank-you page paths (e.g. `['/spende/danke', '/en/donate/thank-you']`)
  - `nonce`: `wp_create_nonce('wp_rest')` (If the visitor is logged-in, the REST nonce will be valid. For anonymous visitors the route currently does not enforce nonce.)
- Filter available to configure thank-you pages in PHP:
  ```php
  add_filter('facebook_capi_thanks_pages', function($pages){
    return ['/spende/danke', '/en/donate/thank-you'];
  });
  ```

Borlabs consent:
- The script checks `window.BorlabsCookie.Consents.hasConsent('meta-pixel')`. Make sure your Borlabs setup uses a consent key `meta-pixel` for the Meta/Facebook Pixel.

## Front-end Example (Shortcode)
Insert the shortcode on your thank-you page:
```
[fb_capi_thanks_example]
```
This prints a script that sends a minimal example payload to the REST endpoint with an `X-WP-Nonce` header and logs the response to the console. Replace the sample values in your own integration or create your own fetch call.

## How It Works
- On activation, default options are created if they don’t exist.
- On each event POST, the plugin initializes the Facebook SDK with your Access Token and constructs a Server-Side Event with optional `user_data` and `custom_data`.
- If Test Mode + Test ID are set, the request is sent with the Test Event Code.

## Troubleshooting
- Admin notice: “Composer Autoloader not found”
  - Make sure you ran `composer require facebook/php-business-sdk` inside the plugin directory.
- HTTP 500 with `fb_capi_config_missing` or `fb_capi_sdk_missing`
  - Ensure Access Token and Pixel ID are configured, and the SDK is installed.
- Events not visible in Events Manager
  - Check Test Mode/Test ID; verify your server time and the payload data; monitor `wp-content/debug.log` for errors if WP_DEBUG is enabled.

## Security Considerations
- The API route now requires a valid REST nonce via the `X-WP-Nonce` header and will reject cross-origin requests. Generate the nonce with `wp_create_nonce('wp_rest')` and send it from same-origin pages.
- Validate and sanitize inputs if you extend the payload structure.
- If your theme or another plugin disables the REST API globally, ensure the path `/wp-json/fb-capi/v1/` is allowlisted. In this project, `inc/disableRestApi.php` already includes it.

## Dependencies: Where to keep vendor?
- Recommended: Keep Composer dependencies that are specific to CAPI inside this plugin (wp-content/plugins/facebook-capi/vendor). This keeps the theme lightweight and the plugin self-contained. The plugin already prefers its local `vendor/autoload.php` and falls back to a global autoloader if present.
- Not recommended: Putting these dependencies into the theme’s composer.json can bloat the theme and couples unrelated concerns. Only use a global (site-level) vendor directory if you operate with a monorepo setup and are aware of autoload ordering.

## License
- Plugin code: GPL-2.0-or-later (as declared in the plugin header)
- Facebook PHP Business SDK: subject to its own license

## Credits
- Developed by goodDEV
- Uses Facebook PHP Business SDK
