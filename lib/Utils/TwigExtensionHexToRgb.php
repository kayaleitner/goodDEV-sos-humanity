<?php

namespace Flynt\Utils;

use Twig\TwigFilter;
use Twig\Extension\AbstractExtension;

class TwigExtensionHexToRgb extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('hex_to_rgb', [$this, 'hexToRgb']),
        ];
    }

    public function hexToRgb($hexColor)
    {
        // Remove '#' if it exists
        $hexColor = ltrim($hexColor, '#');

        // If the color is in shorthand, expand it first
        if (strlen($hexColor) == 3) {
            $hexColor = $hexColor[0] . $hexColor[0] . $hexColor[1] . $hexColor[1] . $hexColor[2] . $hexColor[2];
        }

        $int = hexdec($hexColor);

        return [
            'red'   => ($int >> 16) & 0xFF,
            'green' => ($int >> 8) & 0xFF,
            'blue'  => $int & 0xFF
        ];
    }
}
