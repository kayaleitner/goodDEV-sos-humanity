<?php

namespace Flynt\Components\ShareOptions;

use Flynt\Utils\Options;
use Timber\Timber;

add_filter('Flynt/addComponentData?name=ShareOptions', function($data) {
  // Defaults: current page
  $context = Timber::context();
  $post = $context['post'] ?? NULL;
  $currentUrl = (function() use ($post, $context) {
    if (function_exists('is_singular') && is_singular()) {
      return get_permalink();
    }
    if ($post && function_exists('get_permalink')) {
      return get_permalink($post->ID ?? NULL);
    }
    return home_url(add_query_arg([]));
  })();

  $pageTitle = $post && method_exists($post, 'title') ? $post->title() : get_bloginfo('name');

  // Translatable labels
  $labels = Options::getTranslatable('ShareOptions', 'labels') ?: [];
  $data['labels'] = array_merge([
    'heading' => __('Teilen', 'flynt'),
    'copyLink' => __('Link kopieren', 'flynt'),
    'facebook' => 'Facebook',
    'whatsapp' => 'WhatsApp',
    'linkedin' => 'LinkedIn',
    'email' => __('E‑Mail', 'flynt'),
    'copied' => __('Kopiert!', 'flynt'),
  ], $labels);

  // Options: overrides for share targets
  $opts = Options::getGlobal('ShareOptions') ?: [];

  // Prefer per-instance fields over global options
  $shareUrl = $data['overrideShareUrl'] ?? ($opts['overrideShareUrl'] ?? '');
  if (empty($shareUrl)) {
    $shareUrl = $currentUrl;
  }

  $emailSubject = $data['emailSubject'] ?? ($opts['emailSubject'] ?? sprintf(__('Schau dir das an: %s', 'flynt'), $pageTitle));
  $emailBody = $data['emailBody'] ?? ($opts['emailBody'] ?? sprintf(__('Ich wollte das mit dir teilen: %s', 'flynt'), $shareUrl));

  $encodedUrl = rawurlencode($shareUrl);
  $encodedTitle = rawurlencode($pageTitle);

  // Per-network optional overrides (prefer per-instance over global)
  $copyUrlOverride = $data['copyUrl'] ?? ($opts['copyUrl'] ?? '');
  $facebookUrlOverride = $data['facebookUrl'] ?? ($opts['facebookUrl'] ?? '');
  $whatsappUrlOverride = $data['whatsappUrl'] ?? ($opts['whatsappUrl'] ?? '');
  $linkedinUrlOverride = $data['linkedinUrl'] ?? ($opts['linkedinUrl'] ?? '');
  $emailUrlOverride = $data['emailUrl'] ?? ($opts['emailUrl'] ?? '');

  $links = [];
  $links['copy'] = !empty($copyUrlOverride) ? $copyUrlOverride : $shareUrl;
  $links['facebook'] = !empty($facebookUrlOverride)
    ? $facebookUrlOverride
    : "https://www.facebook.com/sharer/sharer.php?u={$encodedUrl}";
  $links['whatsapp'] = !empty($whatsappUrlOverride)
    ? $whatsappUrlOverride
    : "https://api.whatsapp.com/send?text={$encodedTitle}%20{$encodedUrl}";
  $links['linkedin'] = !empty($linkedinUrlOverride)
    ? $linkedinUrlOverride
    : "https://www.linkedin.com/sharing/share-offsite/?url={$encodedUrl}";
  $links['email'] = !empty($emailUrlOverride)
    ? $emailUrlOverride
    : ('mailto:?subject=' . rawurlencode($emailSubject) . '&body=' . rawurlencode($emailBody));

  $data['links'] = $links;

  // Allow developer override via filter
  $data['links'] = apply_filters('Flynt/ShareOptions/links', $data['links'], $data);

  // Enable/disable networks (prefer per-instance over global; fallback to default)
  $enabled = $data['localShareSettings']['enabledNetworks'] ?? ($opts['localShareSettings']['enabledNetworks'] ?? [
    'copy',
    'facebook',
    'whatsapp',
    'linkedin',
    'email',
  ]);
  $data['enabled'] = $enabled;

  return $data;
});

function getACFLayout() {
  return [
    'name' => 'shareOptions',
    'label' => __('Share: Options', 'flynt'),
    'sub_fields' => [
      [
        'label' => __('Einleitung / Überschrift', 'flynt'),
        'name' => 'preheading',
        'type' => 'text',
        'instructions' => __('Optionaler Text oberhalb der Buttons', 'flynt'),
      ],
      [
        'label' => __('Einstellungen (nur für diese Komponente)', 'flynt'),
        'name' => 'localShareSettings',
        'type' => 'group',
        'layout' => 'block',
        'sub_fields' => [
          [
            'label' => __('Share URL überschreiben', 'flynt'),
            'name' => 'overrideShareUrl',
            'type' => 'url',
            'instructions' => __('Optional. Wenn leer, wird die globale Option oder die aktuelle Seite verwendet.', 'flynt'),
          ],
          [
            'label' => __('Aktive Netzwerke', 'flynt'),
            'name' => 'enabledNetworks',
            'type' => 'checkbox',
            'choices' => [
              'copy' => 'Link kopieren',
              'facebook' => 'Facebook',
              'whatsapp' => 'WhatsApp',
              'linkedin' => 'LinkedIn',
              'email' => 'E‑Mail',
            ],
            'return_format' => 'value',
            'default_value' => ['copy', 'facebook', 'whatsapp', 'linkedin', 'email'],
            'layout' => 'horizontal',
          ],
          [
            'label' => __('E‑Mail Betreff', 'flynt'),
            'name' => 'emailSubject',
            'type' => 'text',
          ],
          [
            'label' => __('E‑Mail Nachricht', 'flynt'),
            'name' => 'emailBody',
            'type' => 'textarea',
            'rows' => 3,
          ],
        ],
      ],
    ],
  ];
}

Options::addTranslatable('ShareOptions', [
  [
    'label' => __('Labels', 'flynt'),
    'name' => 'labels',
    'type' => 'group',
    'layout' => 'block',
    'sub_fields' => [
      [
        'label' => __('Heading', 'flynt'),
        'name' => 'heading',
        'type' => 'text',
        'default_value' => 'Teilen',
      ],
      [
        'label' => __('Copy Link', 'flynt'),
        'name' => 'copyLink',
        'type' => 'text',
        'default_value' => 'Link kopieren',
      ],
      [
        'label' => __('Copied Feedback', 'flynt'),
        'name' => 'copied',
        'type' => 'text',
        'default_value' => 'Kopiert!',
      ],
      [
        'label' => __('E‑Mail', 'flynt'),
        'name' => 'email',
        'type' => 'text',
        'default_value' => 'E‑Mail',
      ],
    ],
  ],
]);

