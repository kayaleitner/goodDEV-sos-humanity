<?php

use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class WpKsesCustomExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('wp_kses_custom', [$this, 'wpKsesCustom'], ['is_safe' => ['html']]),
        ];
    }

    public function wpKsesCustom($string)
    {
        /** get allowed html array */
        $allowed_tags = wp_kses_allowed_html('post');

        /** add custom html tags to array */
        $allowed_tags['input'] = [
            'type' => true,
            'name' => true,
            'value' => true,
            'placeholder' => true,
            'id' => true,
            'class' => true,
            'required' => true,
        ];
        $allowed_tags['form'] = [
            'id' => true,
            'class' => true,
        ];
        $allowed_tags['select'] = [
            'name' => true,
            'value' => true,
            'id' => true,
            'class' => true,
            'required' => true,
        ];
        $allowed_tags['option'] = [
            'value' => true,
        ];

        return wp_kses($string, $allowed_tags);
    }
}

add_filter('timber/twig', function (Environment $twig) {
    $twig->addExtension(new WpKsesCustomExtension());
    return $twig;
});
