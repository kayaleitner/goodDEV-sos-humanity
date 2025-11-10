<?php
/**
 * REST endpoint to render the DonationBarometer component as an embeddable HTML page (for iframes/OBS).
 *
 * URL example:
 *  /wp-json/sos/v1/barometer/embed?search_id=ABC123&goal=10000&type=sum&title=Spenden&theme=dark&transparent=1
 */

// Prevent direct access if not in WP context
if (!defined('ABSPATH')) {
    exit;
}

add_action('rest_api_init', function () {
    register_rest_route('sos/v1', '/barometer/embed', [
        'methods'  => 'GET',
        'args'     => [
            'search_id' => ['type' => 'string', 'required' => true],
            'goal'      => ['type' => 'number', 'required' => true],
            'type'      => ['type' => 'string', 'required' => true], // 'sum' | 'count'
            // Text parameters used by the component
            'barometer_text' => ['type' => 'string', 'default' => ''],
            'barometer_text_current' => ['type' => 'string', 'default' => ''],
            'barometer_text_goal_amount' => ['type' => 'string', 'default' => ''],
            // Back-compat: optional title mapped to barometer_text if provided
            'title'     => ['type' => 'string', 'default' => ''],
            'theme'     => ['type' => 'string', 'default' => 'light'],
            'transparent' => ['type' => 'boolean', 'default' => false],
        ],
        'permission_callback' => '__return_true',
        'callback' => function ($request) {
            // Validate required params
            $searchId = sanitize_text_field($request['search_id'] ?? '');
            $goal = $request->get_param('goal');
            $type = $request->get_param('type');
            if ($searchId === '' || $goal === null || $type === null || $type === '') {
                return new WP_REST_Response('Missing required parameters: search_id, goal, type', 400, ['Content-Type' => 'text/plain; charset=UTF-8']);
            }

            // Map incoming params into component context
            $barometerText = (string) ($request['barometer_text'] ?? '');
            $title = (string) ($request['title'] ?? '');
            if ($barometerText === '' && $title !== '') {
                // Back-compat: use title as headline text
                $barometerText = $title;
            }

            $params = [
                'goal_amount'  => (float) $goal,
                'search_id'    => $searchId,
                'display_type' => sanitize_text_field($type),
                'theme'        => sanitize_text_field($request['theme'] ?? 'light'),
                'transparent'  => (bool) $request['transparent'],
                // Texts used in Twig template
                'barometer_text' => sanitize_text_field($barometerText),
                'barometer_text_current' => isset($request['barometer_text_current']) ? wp_kses_post($request['barometer_text_current']) : '',
                'barometer_text_goal_amount' => sanitize_text_field($request['barometer_text_goal_amount'] ?? ''),
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
            if (function_exists('Flynt\\Components\\DonationBarometer\\fetch_donations')) {
                $apiData = \Flynt\Components\DonationBarometer\fetch_donations($searchId, $type);
                $params['current_amount'] = (float) ($apiData['current_amount'] ?? 0);
                $params['donor_count'] = (int) ($apiData['donor_count'] ?? 0);
            } else {
                // Fallback to zeros to avoid undefined variables in Twig
                $params['current_amount'] = 0.0;
                $params['donor_count'] = 0;
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

            // Build a full HTML document and print enqueued assets so the component JS/CSS loads
            ob_start();
            echo '<!doctype html><html lang="de">';
            echo '<head><meta charset="utf-8"/>';
            echo '<meta name="viewport" content="width=device-width, initial-scale=1"/>';
            echo '<title>Donation Barometer</title>';
            echo '<style>html,body{margin:0;padding:0;background:' . esc_attr($bg) . ';}</style>';
            // Ensure scripts/styles are enqueued for this REST context
            if (function_exists('do_action')) {
                do_action('wp_enqueue_scripts');
            }
            // Output head assets (styles/scripts) enqueued by the theme/components
            if (function_exists('wp_head')) {
                wp_head();
            }
            echo '</head><body>';

            // Rendered component markup
            echo $html;

            // Output footer assets (usually component JS initializes here)
            if (function_exists('wp_footer')) {
                wp_footer();
            }
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
        // Allow embedding from common streaming platforms, adjust as needed
        header("Content-Security-Policy: frame-ancestors 'self' https://*.twitch.tv https://*.youtube.com https://studio.youtube.com https://streamlabs.com");

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
