<?php
/**
 * REST endpoint to render the DonationBarometer component as an embeddable HTML page (for iframes/OBS).
 *
 * @endpoint /wp-json/sos/v1/barometer/embed
 * @method GET
 *
 * @param string  $search_id                  REQUIRED - FundraisingBox Search ID (e.g., "327242")
 * @param number  $goal                       REQUIRED - Goal amount or count (e.g., 50000 or 100)
 * @param string  $type                       REQUIRED - Display type: "sum" (amount) or "count" (donors)
 * @param string  $barometer_text             OPTIONAL - Main headline text above the barometer
 *                                                       Supports placeholders: {current_amount}, {donor_count}, {goal_amount}
 * @param string  $barometer_text_current     OPTIONAL - Text showing current progress (e.g., "Wir haben bereits {current_amount} Dauerspender!")
 *                                                       Supports placeholders: {current_amount}, {donor_count}
 * @param string  $barometer_text_goal_amount OPTIONAL - Text showing goal (e.g., "Ziel {goal_amount} Dauerspenden")
 *                                                       Supports placeholders: {current_amount}, {donor_count}, {goal_amount}
 * @param string  $title                      OPTIONAL - Legacy parameter, mapped to barometer_text if barometer_text is empty
 * @param string  $theme                      OPTIONAL - Color theme: "light" (default) or "dark"
 *                                                       light: white background, dark text
 *                                                       dark: dark blue background, white text
 * @param boolean $transparent                OPTIONAL - Enable transparent background (default: false)
 *                                                       Use 1 or true to enable
 * @param number  $refresh_interval           OPTIONAL - Auto-refresh interval in seconds (0 = disabled, default: 0)
 *                                                       Minimum: 60 seconds to prevent server overload
 *
 * @example Basic usage (amount-based):
 *   /wp-json/sos/v1/barometer/embed?search_id=327242&goal=50000&type=sum
 *
 * @example Full featured (dark theme, transparent, auto-refresh):
 *   /wp-json/sos/v1/barometer/embed?search_id=327242&goal=40000&type=sum&barometer_text=SOS%20Humanity%20erweitert%20die%20Flotte&barometer_text_current=Wir%20haben%20bereits%20{current_amount}%20Dauerspender!&barometer_text_goal_amount=Ziel%20{goal_amount}%20Dauerspenden&theme=dark&transparent=1&refresh_interval=60
 *
 * @example Count-based with custom text:
 *   /wp-json/sos/v1/barometer/embed?search_id=327242&goal=100&type=count&barometer_text=Unser%20Spendenziel&barometer_text_current={donor_count}%20Spender&theme=light
 *
 * @example For OBS/Streaming (with auto-refresh every 60 seconds):
 *   /wp-json/sos/v1/barometer/embed?search_id=327242&goal=50000&type=sum&theme=dark&transparent=1&refresh_interval=60
 */

// Prevent direct access if not in WP context
if (!defined('ABSPATH')) {
  exit;
}

add_action('rest_api_init', function () {
  register_rest_route('sos/v1', '/barometer/embed', [
    'methods'  => 'GET',
    'args'     => [
      'search_id' => ['type' => 'string', 'required' => false, 'default' => ''],
      'goal'      => ['type' => 'number', 'required' => false],
      'type'      => ['type' => 'string', 'required' => false], // 'sum' | 'count'
      // Per-action recurring source (alternative to search_id): campaign code from the page URL
      'cfd'       => ['type' => 'string', 'required' => false, 'default' => ''],
      'metric'    => ['type' => 'string', 'default' => 'count'], // 'count' (Dauerspender) | 'sum' (Intervallsumme)
      // Text parameters used by the component
      'barometer_text' => ['type' => 'string', 'default' => ''],
      'barometer_text_current' => ['type' => 'string', 'default' => ''],
      'barometer_text_goal_amount' => ['type' => 'string', 'default' => ''],
      // Back-compat: optional title mapped to barometer_text if provided
      'title'     => ['type' => 'string', 'default' => ''],
      'theme'     => ['type' => 'string', 'default' => 'light'],
      'transparent' => ['type' => 'boolean', 'default' => false],
      // Auto-refresh interval in seconds (0 or not set = no refresh)
      'refresh_interval' => ['type' => 'number', 'default' => 0],
    ],
    'permission_callback' => '__return_true',
    'callback' => function ($request) {
      // Two data sources: a saved Smart Search (search_id) or a per-action cfd.
      $searchId = sanitize_text_field($request['search_id'] ?? '');
      $cfd = sanitize_text_field((string) ($request['cfd'] ?? ''));
      $goalParam = $request->get_param('goal');
      $type = $request->get_param('type');

      if ($cfd === '' && $searchId === '') {
        return new WP_REST_Response('Missing required parameters: provide either cfd or search_id (+ goal, type)', 400, ['Content-Type' => 'text/plain; charset=UTF-8']);
      }

      // Resolve per-action recurring data when a cfd is given.
      $cfdData = null;
      if ($cfd !== '') {
        $metric = sanitize_text_field((string) ($request['metric'] ?? 'count'));
        $type = ($metric === 'sum') ? 'sum' : 'count';
        if (function_exists('Flynt\\FRBox\\get_action_recurring_by_cfd')) {
          $cfdData = \Flynt\FRBox\get_action_recurring_by_cfd($cfd);
        }
        if ($cfdData === null) {
          return new WP_REST_Response('cfd could not be resolved to a fundraising page', 404, ['Content-Type' => 'text/plain; charset=UTF-8']);
        }
      } elseif ($type === null || $type === '') {
        return new WP_REST_Response('Missing required parameter: type', 400, ['Content-Type' => 'text/plain; charset=UTF-8']);
      }

      // Goal: explicit param wins. For the cfd preview without a goal we derive a
      // dynamic, "nice" round goal from the current value so the ship sits at a sensible
      // fill per action (the real per-action recurring goal is a pending SOS decision).
      if ($goalParam !== null && $goalParam !== '') {
        $goal = (float) $goalParam;
      } elseif ($cfdData !== null) {
        $value = ($type === 'sum')
          ? (float) ($cfdData['stats']['recurring_sum'] ?? 0)
          : (int) ($cfdData['stats']['recurring_count'] ?? 0);
        // Aim for ~70% fill, then round up to a nice number (1/2/2.5/5/10 × 10^n).
        $target = $value > 0 ? $value / 0.7 : ($type === 'sum' ? 100 : 10);
        $mag = pow(10, floor(log10(max($target, 1))));
        $goal = 10 * $mag;
        foreach ([1, 2, 2.5, 5] as $m) {
          if ($target <= $m * $mag) { $goal = $m * $mag; break; }
        }
      } else {
        return new WP_REST_Response('Missing required parameter: goal', 400, ['Content-Type' => 'text/plain; charset=UTF-8']);
      }

      // Map incoming params into component context
      $barometerText = (string) ($request['barometer_text'] ?? '');
      $title = (string) ($request['title'] ?? '');
      if ($barometerText === '' && $title !== '') {
        // Back-compat: use title as headline text
        $barometerText = $title;
      }

      // Set colors based on theme
      $theme = sanitize_text_field($request['theme'] ?? 'light');
      $themeColors = [
        'dark' => [
          'background' => '#1c2445',
          'text' => '#ffffff',
          'brand' => '#2EB7EC'
        ],
        'light' => [
          'background' => '#ffffff',
          'text' => '#1c2445',
          'brand' => '#2EB7EC'
        ]
      ];
      $colors = $themeColors[$theme] ?? $themeColors['light'];

      $params = [
        'goal_amount'  => (float) $goal,
        'search_id'    => $searchId,
        'display_type' => sanitize_text_field($type),
        'theme'        => $theme,
        'transparent'  => (bool) $request['transparent'],
        // Texts used in Twig template
        'barometer_text' => sanitize_text_field($barometerText),
        'barometer_text_current' => isset($request['barometer_text_current']) ? wp_kses_post($request['barometer_text_current']) : '',
        'barometer_text_goal_amount' => sanitize_text_field($request['barometer_text_goal_amount'] ?? ''),
        // Theme colors (override options if set)
        'options' => [
          'colorBrandBackground' => $colors['background'],
          'colorBrandText' => $colors['text'],
          'colorBrandFill' => $colors['brand']
        ]
      ];

      // Ensure component functions (including fetch_donations) are available
      $componentFunctions = get_theme_file_path('/Components/DonationBarometer/functions.php');
      if (!file_exists($componentFunctions)) {
        // Also check parent theme (boilerplate-flynt-next)
        $componentFunctions = get_template_directory() . '/Components/DonationBarometer/functions.php';
      }
      if (file_exists($componentFunctions)) {
        require_once $componentFunctions;
      }

      // Populate live data from FundraisingBox for REST embed (Timber::compile won't run Flynt data filters)
      if ($cfdData !== null) {
        // Per-action recurring data: count = number of Dauerspende setups, sum = raw interval amounts.
        $stats = $cfdData['stats'];
        $params['donor_count'] = (int) ($stats['recurring_count'] ?? 0);
        $params['current_amount'] = (float) ($stats['recurring_sum'] ?? 0);
      } elseif (function_exists('Flynt\\Components\\DonationBarometer\\fetch_donations')) {
        $apiData = \Flynt\Components\DonationBarometer\fetch_donations($searchId, $type);
        $params['current_amount'] = (float) ($apiData['current_amount'] ?? 0);
        $params['donor_count'] = (int) ($apiData['donor_count'] ?? 0);
      } else {
        // Fallback to zeros to avoid undefined variables in Twig
        $params['current_amount'] = 0.0;
        $params['donor_count'] = 0;
      }

      // Pre-fill the placeholders in the progress text with the real values server-side,
      // so the number is correct even if the component's count-up animation does not run
      // in the embed/iframe context (the {…} tokens would otherwise render as an animated 0-span).
      if (!empty($params['barometer_text_current'])) {
        $params['barometer_text_current'] = strtr($params['barometer_text_current'], [
          '{donor_count}'    => number_format((int) $params['donor_count'], 0, ',', '.'),
          '{current_amount}' => number_format((float) $params['current_amount'], 0, ',', '.'),
        ]);
      }

      // Render the component using Timber or Flynt helper
      $html = '';
      if (class_exists('Timber\\Timber')) {
        // Render the Twig of the component directly
        $twigPathChild = get_theme_file_path('/Components/DonationBarometer/index.twig');
        $twigPathParent = get_template_directory() . '/Components/DonationBarometer/index.twig';
        $twigToUse = file_exists($twigPathChild) ? 'Components/DonationBarometer/index.twig' : (file_exists($twigPathParent) ? 'Components/DonationBarometer/index.twig' : '');
        if ($twigToUse) {
          $html = \Timber\Timber::compile($twigToUse, $params);
        }
      }

      if (!$html && function_exists('Flynt\\renderComponent')) {
        $html = \Flynt\renderComponent('DonationBarometer', $params);
      }

      if (!$html) {
        $html = '<div>DonationBarometer-Komponente nicht gefunden.</div>';
      }

      // Compose minimal HTML document with optional transparent background
      $bg = $params['transparent'] ? 'transparent' : '#fff';

      // Get refresh interval (minimum 10 seconds to avoid server overload, 0 = disabled)
      $refreshInterval = (int) ($request['refresh_interval'] ?? 0);
      $refreshMs = $refreshInterval > 0 ? max(10, $refreshInterval) * 1000 : 0;

      // Build a full HTML document and print enqueued assets so the component JS/CSS loads
      ob_start();
      echo '<!doctype html><html lang="de">';
      echo '<head><meta charset="utf-8"/>';
      echo '<meta name="viewport" content="width=device-width, initial-scale=1"/>';
      echo '<title>Donation Barometer</title>';
      echo '<style>
                html,body{margin:0;padding:0;background:' . esc_attr($bg) . ' !important;}
                body { opacity: 1; transition: opacity 0.3s ease-in-out; }
                body.reloading { opacity: 0; }
            </style>';

      // Ensure scripts/styles are enqueued for this REST context
      if (function_exists('do_action')) {
        do_action('wp_enqueue_scripts');
      }
      // Output head assets (styles/scripts) enqueued by the theme/components
      if (function_exists('wp_head')) {
        wp_head();
      }

      // Compact layout overrides for the per-action (cfd) embed: less whitespace,
      // bigger ship/bar. Emitted after wp_head so they win over the component CSS.
      // Scoped to .sos-embed-compact so the OBS/search_id embeds stay unchanged.
      echo '<style>
        .sos-embed-compact .donation-barometer__panel{padding:0 !important;max-width:none !important;}
      </style>';

      $bodyClass = ($cfdData !== null) ? 'sos-embed-compact' : '';
      echo '</head><body class="' . esc_attr($bodyClass) . '">';

      // Rendered component markup
      echo $html;

      // Output footer assets (usually component JS initializes here)
      if (function_exists('wp_footer')) {
        wp_footer();
      }

      // Auto-reload script with fade transition (only if refresh_interval is set and > 0)
      if ($refreshMs > 0) {
        ?>
        <script>
          // Auto-refresh every <?php echo esc_js($refreshInterval); ?> seconds with smooth fade
          (function() {
            setTimeout(function() {
              document.body.classList.add("reloading");
              setTimeout(function() {
                location.reload();
              }, 300);
            }, <?php echo esc_js($refreshMs); ?>);
          })();
        </script>
        <?php
      }

      // Robust progress setter for the embed: the component's IntersectionObserver-driven
      // count-up does not reliably fire inside a nested/short iframe, so set fill, ship
      // position and the numbers directly from the data attributes (no animation needed).
      $fallbackJs = <<<'JS'
<script>
(function () {
  function apply() {
    var c = document.querySelector('[name="DonationBarometer"]') || document.querySelector('.donation-barometer');
    if (!c) return;
    var goal = parseFloat(c.dataset.goal) || 0;
    var cur = parseFloat(c.dataset.current) || 0;
    var don = parseInt(c.dataset.donors, 10) || 0;
    var val = (c.dataset.displayType === 'count') ? don : cur;
    var p = goal > 0 ? Math.min(val / goal, 1) : 0;
    var fill = c.querySelector('.donation-barometer__fill');
    var bar = c.querySelector('.donation-barometer__bar-container');
    var ship = c.querySelector('.donation-barometer__ship');
    if (fill) fill.style.transform = 'scaleX(' + p + ')';
    if (bar && ship) {
      var sx = p * (bar.getBoundingClientRect().width - ship.getBoundingClientRect().width);
      ship.style.setProperty('--ship-x', sx + 'px');
    }
    var dc = c.querySelector('.donation-barometer__donor-count');
    if (dc) dc.textContent = don.toLocaleString('de-DE');
    var ca = c.querySelector('.donation-barometer__current-amount');
    if (ca) ca.textContent = cur.toLocaleString('de-DE');
  }
  if (document.readyState !== 'loading') { setTimeout(apply, 300); } else { document.addEventListener('DOMContentLoaded', function(){ setTimeout(apply, 300); }); }
  setTimeout(apply, 1000);
  window.addEventListener('resize', apply);
})();
</script>
JS;
      echo $fallbackJs;

      echo '</body></html>';
      $out = ob_get_clean();

      return new WP_REST_Response($out, 200, [ 'Content-Type' => 'text/html; charset=UTF-8' ]);
    }
  ]);
});

// Loosen frame embedding headers only for the above route
// and serve raw HTML instead of JSON-encoded string
add_action('rest_pre_serve_request', function ($served, $result, $request, $server) {
  $route = is_object($request) && method_exists($request, 'get_route') ? $request->get_route() : '';
  if (str_contains($route, '/sos/v1/barometer/embed')) {
    if (function_exists('header_remove')) {
      @header_remove('X-Frame-Options');
    }
    // Allow embedding on the SOS sites (incl. Kinsta staging), the FRBox action iframe,
    // and common streaming platforms.
    header("Content-Security-Policy: frame-ancestors 'self' https://sos-humanity.org https://*.sos-humanity.org https://*.kinsta.cloud https://secure.fundraisingbox.com https://*.twitch.tv https://*.youtube.com https://studio.youtube.com https://streamlabs.com");

    // If our callback returned a string (full HTML), output it directly and stop default JSON serving
    $data = is_object($result) && method_exists($result, 'get_data') ? $result->get_data() : null;
    if (is_string($data)) {
      // Ensure proper content type
      header('Content-Type: text/html; charset=UTF-8');
      echo $data;
      return true; // mark as served
    }
  }
  return $served;
}, 10, 4);