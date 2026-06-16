<?php
/**
 * Registers per-page header/footer variant selectors.
 * Safe defaults ensure existing pages keep current header/footer.
 */

add_action('acf/init', function () {
  if (!function_exists('acf_add_local_field_group')) {
    return;
  }

  acf_add_local_field_group([
    'key' => 'group_page_shell_variants',
    'title' => __('Seitengerüst', 'flynt'),
    'fields' => [
      [
        'key' => 'field_header_variant',
        'label' => __('Header-Variante', 'flynt'),
        'name' => 'header_variant',
        'type' => 'select',
        'choices' => [
          'default' => __('Standard', 'flynt'),
          'landing_page' => __('Landingpage', 'flynt'),
        ],
        'default_value' => 'default',
        'ui' => 1,
        'allow_null' => 1,
        'wrapper' => ['width' => '', 'class' => '', 'id' => ''],
      ],
      [
        'key' => 'field_footer_variant',
        'label' => __('Footer-Variante', 'flynt'),
        'name' => 'footer_variant',
        'type' => 'select',
        'choices' => [
          'default' => __('Standard', 'flynt'),
          'landing_page' => __('Landingpage', 'flynt'),
        ],
        'default_value' => 'default',
        'ui' => 1,
        'allow_null' => 1,
        'wrapper' => ['width' => '', 'class' => '', 'id' => ''],
      ],
    ],
    'location' => [[[
      'param' => 'post_type',
      'operator' => '==',
      'value' => 'page',
    ]]],
    'position' => 'side',
    'style' => 'default',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'active' => true,
    'show_in_rest' => 0,
  ]);

  // Optional global defaults in Options Page if available.
  if (function_exists('acf_add_options_page')) {
    // ensure an option page exists; many projects already have one.
    acf_add_options_sub_page([
      'page_title' => __('Seitengerüst-Defaults', 'flynt'),
      'menu_title' => __('Seitengerüst', 'flynt'),
      'parent_slug' => 'themes.php', // Appearance
      'menu_slug' => 'page-shell-defaults',
      'autoload' => true,
    ]);

    acf_add_local_field_group([
      'key' => 'group_page_shell_defaults',
      'title' => __('Seitengerüst-Defaults', 'flynt'),
      'fields' => [
        [
          'key' => 'field_header_variant_default',
          'label' => __('Header-Variante (Default)', 'flynt'),
          'name' => 'header_variant_default',
          'type' => 'select',
          'choices' => [
            'default' => __('Standard', 'flynt'),
            'landing_page' => __('Landingpage', 'flynt'),
          ],
          'default_value' => 'default',
          'ui' => 1,
          'allow_null' => 1,
        ],
        [
          'key' => 'field_footer_variant_default',
          'label' => __('Footer-Variante (Default)', 'flynt'),
          'name' => 'footer_variant_default',
          'type' => 'select',
          'choices' => [
            'default' => __('Standard', 'flynt'),
            'landing_page' => __('Landingpage', 'flynt'),
          ],
          'default_value' => 'default',
          'ui' => 1,
          'allow_null' => 1,
        ],
      ],
      'location' => [[[
        'param' => 'options_page',
        'operator' => '==',
        'value' => 'page-shell-defaults',
      ]]],
      'position' => 'normal',
      'style' => 'default',
      'active' => true,
      'show_in_rest' => 0,
    ]);
  }
});
