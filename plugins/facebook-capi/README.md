# Facebook CAPI (Custom) — Project Integration Guide

This project integrates a custom Facebook Conversions API (CAPI) plugin with configuration in the theme’s Global Options (ACF) and Composer dependencies installed at the THEME level.

Highlights:
- REST endpoint only: `POST /wp-json/fb-capi/v1/event`
- Settings are configured via Theme → Global Options → “Facebook CAPI”
- Composer dependencies are installed under the theme: `wp-content/themes/boilerplate-flynt-next/vendor`
- The plugin autoloads the theme’s `vendor/autoload.php`

## Requirements
- WordPress 5.8+
- PHP 7.4+
- ACF Pro active (for Theme Options)
- Composer available on your machine or inside your Docker container

## Install / Update Dependencies
Run Composer in the THEME directory (not in this plugin):

```bash
cd wp-content/themes/boilerplate-flynt-next
composer install
composer dump-autoload -o
```

The plugin automatically requires the theme’s autoloader from `vendor/autoload.php`.

## Configure Settings (ACF Theme Options)
Go to: WordPress Admin → Theme → Global Options → “Facebook CAPI” and set:
- Access Token
- Pixel ID
- Activate Test Mode (optional)
- Test Event Code (optional, used when Test Mode is enabled)

Note: The plugin keeps a small Settings page under Settings → Facebook CAPI as a fallback, but ACF Theme Options take precedence.

## REST API Usage
Endpoint: `POST /wp-json/fb-capi/v1/event`

Example payload:
```json
{
  "event_name": "Purchase",
  "event_time": 1725537600,
  "action_source": "website",
  "event_id": "unique-token-abc123",
  "event_source_url": "https://example.com/thank-you",
  "user_data": { "em": "buyer@example.com" },
  "custom_data": { "content_name": "Donation", "value": 25, "currency": "EUR", "num_items": 1, "content_type": "donation" }
}
```

Notes:
- If Test Mode + Test ID are set in Theme Options, the request is sent as a test event.
- The route is allowlisted in `inc/disableRestApi.php` as `/wp-json/fb-capi/v1/`.

## What We Removed
- Shared Secret/HMAC setting and validation.
- Non-REST “pretty” endpoint `/capi/event`.
- Composer workflow inside the plugin and any symlink setup.

## Troubleshooting
- Admin notice “Composer autoloader not found”: Run Composer in the THEME directory as shown above.
- `fb_capi_config_missing`: Ensure Access Token and Pixel ID are set in Theme → Global Options → Facebook CAPI.
- `fb_capi_sdk_missing`: Ensure `facebook/php-business-sdk` is installed in the theme and autoloader is present.

## License / Credits
- Plugin code: GPL-2.0-or-later
- Uses Facebook PHP Business SDK
- Developed by goodDEV
