<?php
namespace FacebookCapiPlugin\Support;

class Options
{
    const ACCESS_TOKEN = 'facebook_capi_access_token';
    const PIXEL_ID     = 'facebook_capi_pixel_id';
    const TEST_MODE    = 'facebook_capi_activate_test_modus';
    const TEST_ID      = 'facebook_capi_test_id';
    const SHARED_SECRET= 'facebook_capi_shared_secret';

    public static function key(string $k): string { return $k; }

    public static function get(string $key, $default = '') {
        return get_option($key, $default);
    }

    public static function ensureDefaults(): void
    {
        foreach ([self::ACCESS_TOKEN => '', self::PIXEL_ID => '', self::TEST_MODE => 0, self::TEST_ID => '', self::SHARED_SECRET => ''] as $k => $v) {
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
            'shared_secret'=> (string) self::get(self::SHARED_SECRET, ''),
        ];
    }
}
