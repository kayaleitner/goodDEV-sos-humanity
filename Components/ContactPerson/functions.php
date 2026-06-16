<?php

namespace Flynt\Components\ContactPerson;

use Flynt\FieldVariables;

function getACFLayout(): array {
  return [
    'name' => 'ContactPerson',
    'label' => 'Contact Person',
    'sub_fields' => [
      [
        'label' => __('Content', 'flynt'),
        'name' => 'contentTab',
        'type' => 'tab',
        'placement' => 'top',
        'endpoint' => 0,
      ],
      [
        'label' => __('Title', 'flynt'),
        'name' => 'title',
        'type' => 'text',
      ],
      [
        'label' => __('Contact Person', 'flynt'),
        'type' => 'group',
        'name' => 'person',
        'layout' => 'block',
        'sub_fields' => [
          [
            'label' => __('Image/Photo', 'flynt'),
            'name' => 'image',
            'type' => 'image',
            'return_format' => 'array',
            'preview_size' => 'medium',
            'library' => 'all',
            'mime_types' => 'jpg,jpeg,png,webp',
          ],
          [
            'label' => __('Name', 'flynt'),
            'name' => 'name',
            'type' => 'text',
          ],
          [
            'label' => __('Position', 'flynt'),
            'name' => 'position',
            'type' => 'text',
          ],
          [
            'label' => __('E-Mail', 'flynt'),
            'name' => 'email',
            'type' => 'email',
          ],
          [
            'label' => __('Phone Number', 'flynt'),
            'name' => 'phone',
            'type' => 'text',
          ],
        ],
      ],
      [
        'label' => __('Options', 'flynt'),
        'name' => 'optionsTab',
        'type' => 'tab',
        'placement' => 'top',
        'endpoint' => 0,
      ],
      [
        'label' => '',
        'name' => 'options',
        'type' => 'group',
        'layout' => 'block',
        'sub_fields' => [
          FieldVariables\getTheme(),
        ],
      ],
    ],
  ];
}

// Helper: normalize ACF image field to id and provide url/alt for twig
add_filter('Flynt/addComponentData?name=ContactPerson', function ($data) {
  $image = $data['person']['image'] ?? NULL;

  $normalizeToId = function ($val) {
    // Accepts array (ACF image), int (attachment id), Timber\Image, WP_Post
    if (is_array($val)) {
      // ACF image array can have 'ID' or 'id'
      return $val['ID'] ?? $val['id'] ?? NULL;
    }
    if (is_int($val)) {
      return $val;
    }
    if (is_object($val)) {
      // Timber\Image exposes id
      if (isset($val->id) && is_int($val->id)) {
        return $val->id;
      }
      // WP_Post for attachment
      if ($val instanceof \WP_Post) {
        return $val->ID;
      }
    }
    return NULL;
  };

  $personImage = $normalizeToId($image ?? NULL);

  $data['person'] = [
    'image' => $personImage ? [
      'src' => wp_get_attachment_image_url($personImage, 'full'),
      'srcset' => wp_get_attachment_image_srcset($personImage, 'full'),
      'alt' => get_post_meta($personImage, '_wp_attachment_image_alt', TRUE) ?: '',
    ] : NULL,
  ] + $data['person'] ?? [] ;

  return $data;
});
