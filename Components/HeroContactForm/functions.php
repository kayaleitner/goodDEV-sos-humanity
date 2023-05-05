<?php

namespace Flynt\Components\HeroContactForm;

use Flynt\FieldVariables;

add_filter('wpcf7_load_js', '__return_false');
add_filter('wpcf7_load_css', '__return_false');

add_filter('Flynt/addComponentData?name=HeroContactForm', function ($data) {
    function_exists('wpcf7_enqueue_scripts') && enqueueWpcf7Scripts();
    function_exists('wpcf7_enqueue_styles') && wpcf7_enqueue_styles();
    wp_script_add_data('contact-form-7', 'defer', true);

    return $data;
});

function enqueueWpcf7Scripts()
{
    $inFooter = true;

    if ('header' === wpcf7_load_js()) {
        $inFooter = false;
    }

    wp_enqueue_script(
        'contact-form-7',
        wpcf7_plugin_url('includes/js/scripts.js'),
        ['Flynt/assets'],
        WPCF7_VERSION,
        $inFooter
    );

    $wpcf7 = [
        'apiSettings' => [
            'root' => esc_url_raw(rest_url('contact-form-7/v1')),
            'namespace' => 'contact-form-7/v1',
        ],
    ];

    if (defined('WP_CACHE') and WP_CACHE) {
        $wpcf7['cached'] = 1;
    }

    if (wpcf7_support_html5_fallback()) {
        $wpcf7['jqueryUi'] = 1;
    }

    wp_localize_script('contact-form-7', 'wpcf7', $wpcf7);

    do_action('wpcf7_enqueue_scripts');
}

remove_action('wpcf7_init', 'wpcf7_add_form_tag_submit', 10, 0);

add_action('wpcf7_init', function () {
    wpcf7_add_form_tag('submit', function ($tag) {
        $class = wpcf7_form_controls_class($tag->type, 'button');

        $atts = [];

        $atts['class'] = $tag->get_class_option($class);
        $atts['id'] = $tag->get_id_option();
        $atts['tabindex'] = $tag->get_option('tabindex', 'signed_int', true);

        $value = isset($tag->values[0]) ? $tag->values[0] : '';

        if (empty($value)) {
            $value = __('Send', 'contact-form-7');
        }

        $atts['type'] = 'submit';
        $atts['value'] = $value;

        $atts = wpcf7_format_atts($atts);

        $html = sprintf('<button %1$s>%2$s</button>', $atts, $value);
        return $html;
    });
}, 10, 0);

function getACFFields()
{
    return [
        [
            'label' => __('General', 'flynt'),
            'name' => 'Tab',
            'type' => 'tab',
            'placement' => 'top',
            'endpoint' => 0
        ],
        [
            'label' => __('Title', 'flynt'),
            'name' => 'blockTitle',
            'type' => 'text'
        ],
        [
            'label' => __('Contact Form 7 Form', 'flynt'),
            'name' => 'formId',
            'type' => 'post_object',
            'post_type' => [
                'wpcf7_contact_form'
            ],
            'allow_null' => 0,
            'multiple' => 0,
            'return_format' => 'id',
            'ui' => 1,
            'required' => 0,
            'instructions' => __('If there is no form available, please first create a suitable one in the <a href="' . admin_url('admin.php?page=wpcf7') . '" target="_blank">Contact Form 7 admin page</a>.', 'flynt'),
        ],
        [
            'label' => __('Contact Info', 'flynt'),
            'name' => 'contactInfoTab',
            'type' => 'tab',
            'placement' => 'top',
            'endpoint' => 0,
        ],
        [
            'label' => __('Contact Info', 'flynt'),
            'name' => 'contactInfo',
            'type' => 'repeater',
            'collapsed' => '',
            'layout' => 'block',
            'button_label' => __('Add Column', 'flynt'),
            'max' => 3,
            'sub_fields' => [
                [
                    'label' => __('Text Editor', 'flynt'),
                    'name' => 'textEditorHtml',
                    'type' => 'wysiwyg',
                    'tabs' => 'visual',
                    'media_upload' => 0,
                    'delay' => 1,
                    'wrapper' => [
                        'width' => 100
                    ],
                ]
            ]
        ],
        [
            'label' => __('Options', 'flynt'),
            'name' => 'optionsTab',
            'type' => 'tab',
            'placement' => 'top',
            'endpoint' => 0
        ],
        [
            'label' => '',
            'name' => 'options',
            'type' => 'group',
            'layout' => 'row',
            'sub_fields' => [
                FieldVariables\getColorBackground(),
                FieldVariables\getColorText(),
                FieldVariables\getColorSecondary(),
                FieldVariables\getNavStyle('dark-blur'),
            ]
        ],
    ];
}

function getACFLayout()
{
    return [
        'name' => 'HeroContactForm',
        'label' => 'Contact Form',
        'sub_fields' => getACFFields()
    ];
}
