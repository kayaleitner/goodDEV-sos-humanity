<?php

namespace Flynt\Utils;

use Twig\TwigFilter;
use Twig\Extension\AbstractExtension;

/**
 * Class TwigExtensionHexToLuminance
 *
 * Provides a Twig filter to calculate the relative luminance of a HEX color.
 *
 * Usage in Twig:
 *   {% if '#1c2445'|hex_to_luminance < 0.5 %}
 *       {# Color is dark, use light variant #}
 *   {% else %}
 *       {# Color is light, use dark variant #}
 *   {% endif %}
 *
 * This is useful for dynamically deciding foreground colors (like text or SVG)
 * based on the background color, for example in the DonationBarometer component.
 *
 * @package Flynt\Utils
 */
class TwigExtensionHexToLuminance extends AbstractExtension
{
  /**
   * Registers the Twig filter.
   *
   * @return array
   */
  public function getFilters(): array {
    return [
      new TwigFilter('hex_to_luminance', [$this, 'hexToLuminance']),
    ];
  }

  /**
   * Converts a HEX color code to relative luminance (0 = dark, 1 = light).
   *
   * @param string $hexColor HEX color string, e.g. '#ffffff' or 'fff'.
   *
   * @return float Relative luminance (0 = darkest, 1 = brightest)
   */
  public function hexToLuminance(string $hexColor): float
  {
    if (empty($hexColor)) {
      return 1; // default to light if invalid
    }

    $hex = str_replace('#', '', $hexColor);
    if (strlen($hex) === 3) {
      $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
    }

    $r = hexdec(substr($hex,0,2)) / 255;
    $g = hexdec(substr($hex,2,2)) / 255;
    $b = hexdec(substr($hex,4,2)) / 255;

    // relative luminance formula
    return 0.2126 * $r + 0.7152 * $g + 0.0722 * $b;
  }
}
