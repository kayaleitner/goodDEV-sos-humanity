<?php
namespace FacebookCapiPlugin\Support;

class Options
{
    const ACCESS_TOKEN = 'facebook_capi_access_token';
    const PIXEL_ID     = 'facebook_capi_pixel_id';
    const TEST_MODE    = 'facebook_capi_activate_test_modus';
    const TEST_ID      = 'facebook_capi_test_id';

    public static function key(string $k): string { return $k; }

    public static function get(string $key, $default = '') {
        // Prefer ACF Theme Options if available
        if (function_exists('get_field')) {
            $acfVal = get_field($key, 'option');
            if ($acfVal !== null && $acfVal !== false && $acfVal !== '') {
                return $acfVal;
            }
        }
        // Fallback to wp_options
        return get_option($key, $default);
    }

    public static function ensureDefaults(): void
    {
        foreach ([self::ACCESS_TOKEN => '', self::PIXEL_ID => '', self::TEST_MODE => 0, self::TEST_ID => ''] as $k => $v) {
            if (get_option($k, null) === null) {
                add_option($k, $v);
            }
        }
    }

    public static function all(): array
    {
        return [
            'access_token' => (string) self::get(self::ACCESS_TOKEN, ''),
            'pixel_id'     => (string) self::get(self::PIXEL_ID, ''),
            'test_mode'    => (int) self::get(self::TEST_MODE, 0),
            'test_id'      => (string) self::get(self::TEST_ID, ''),
        ];
    }
}
