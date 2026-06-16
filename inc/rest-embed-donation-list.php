<?php
/**
 * REST endpoint that renders the donor list of a fundraising page (Spendenaktion)
 * as an embeddable HTML page, with a "Dauerspende / einmalig" marker per entry.
 *
 * @endpoint /wp-json/sos/v1/donorlist/embed
 * @method GET
 *
 * @param string  $cfd             REQUIRED - campaign code from the action URL (e.g. "su995")
 * @param string  $theme           OPTIONAL - "light" (default) | "dark"
 * @param boolean $transparent     OPTIONAL - transparent background (default false)
 * @param number  $limit           OPTIONAL - max entries (0 = all, default 0)
 * @param string  $label_recurring OPTIONAL - badge text for recurring donations (default "Dauerspende")
 * @param string  $label_onetime   OPTIONAL - badge text for one-time donations (default "einmalig")
 * @param string  $title           OPTIONAL - heading; "{count}" is replaced with the number of entries
 *
 * Data source: Flynt\FRBox data layer (inc/frboxFundraisingPageData.php), which reads the
 * authenticated FundraisingBox API. Only the publicly displayed donor name is shown.
 */

if (!defined('ABSPATH')) {
    exit;
}

add_action('rest_api_init', function () {
    register_rest_route('sos/v1', '/donorlist/embed', [
        'methods'  => 'GET',
        'args'     => [
            'cfd'             => ['type' => 'string', 'required' => true],
            'theme'           => ['type' => 'string', 'default' => 'light'],
            'transparent'     => ['type' => 'boolean', 'default' => false],
            'limit'           => ['type' => 'number', 'default' => 0],
            'label_recurring' => ['type' => 'string', 'default' => 'Dauerspende'],
            'label_onetime'   => ['type' => 'string', 'default' => 'Einzelspende'],
            'title'           => ['type' => 'string', 'default' => ''],
        ],
        'permission_callback' => '__return_true',
        'callback' => function ($request) {
            $cfd = sanitize_text_field((string) $request->get_param('cfd'));
            if ($cfd === '') {
                return new WP_REST_Response('Missing required parameter: cfd', 400, ['Content-Type' => 'text/plain; charset=UTF-8']);
            }

            if (!function_exists('Flynt\\FRBox\\get_action_donor_list_by_cfd')) {
                return new WP_REST_Response('data layer not loaded', 500, ['Content-Type' => 'text/plain; charset=UTF-8']);
            }

            $data = \Flynt\FRBox\get_action_donor_list_by_cfd($cfd);
            if ($data === null) {
                return new WP_REST_Response('cfd could not be resolved to a fundraising page', 404, ['Content-Type' => 'text/plain; charset=UTF-8']);
            }

            $list = $data['list'];
            $limit = (int) $request->get_param('limit');
            if ($limit > 0) {
                $list = array_slice($list, 0, $limit);
            }

            $labelRecurring = sanitize_text_field((string) $request->get_param('label_recurring'));
            $labelOnetime   = sanitize_text_field((string) $request->get_param('label_onetime'));
            $theme          = sanitize_text_field((string) $request->get_param('theme')) === 'dark' ? 'dark' : 'light';
            $transparent    = (bool) $request->get_param('transparent');
            $titleTpl       = (string) $request->get_param('title');

            $colors = $theme === 'dark'
                ? ['bg' => '#1c2445', 'text' => '#ffffff', 'muted' => 'rgba(255,255,255,.65)', 'line' => 'rgba(255,255,255,.15)', 'rowAlt' => 'rgba(255,255,255,.04)']
                : ['bg' => '#ffffff', 'text' => '#1c2445', 'muted' => '#6b7280', 'line' => '#e5e7eb', 'rowAlt' => '#f7f7f5'];
            $brand = '#2EB7EC';
            $pageBg = $transparent ? 'transparent' : $colors['bg'];

            // Build rows
            $rowsHtml = '';
            foreach ($list as $d) {
                $name = $d['name'] !== '' ? $d['name'] : 'Anonym';
                $amount = number_format((float) $d['amount'], 2, ',', '.') . ' &euro;';
                $date = '';
                if (!empty($d['date'])) {
                    $ts = strtotime($d['date']);
                    if ($ts) {
                        $date = date_i18n('d.m.Y - H:i', $ts);
                    }
                }
                $isRec = !empty($d['recurring']);
                $badgeText = $isRec ? $labelRecurring : $labelOnetime;
                $badgeClass = $isRec ? 'badge badge--recurring' : 'badge badge--onetime';

                $message = trim((string) ($d['message'] ?? ''));

                $rowsHtml .= '<li class="row">'
                    . '<div class="main">'
                    . '<div class="left"><span class="amount">' . esc_html(html_entity_decode($amount)) . '</span>'
                    . ($date ? '<span class="date">' . esc_html($date) . '</span>' : '')
                    . '</div>'
                    . '<div class="right"><span class="name">' . esc_html($name) . '</span>'
                    . '<span class="' . esc_attr($badgeClass) . '">' . esc_html($badgeText) . '</span>'
                    . '</div>'
                    . '</div>'
                    . ($message !== '' ? '<div class="comment">' . esc_html($message) . '</div>' : '')
                    . '</li>';
            }

            $heading = '';
            if ($titleTpl !== '') {
                $heading = '<h2 class="heading">' . esc_html(str_replace('{count}', (string) count($data['list']), $titleTpl)) . '</h2>';
            }

            ob_start();
            ?>
<!doctype html><html lang="de"><head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1"/>
<title>Spenderliste</title>
<style>
  html,body{margin:0;padding:0;background:<?php echo esc_attr($pageBg); ?> !important;}
  *{box-sizing:border-box;}
  body{font-family:'Source Sans Pro',-apple-system,Segoe UI,Roboto,sans-serif;color:<?php echo esc_attr($colors['text']); ?>;}
  .wrap{max-width:56.5rem;margin:0 auto;}
  .heading{font:900 1.6rem/1.2 'Source Sans Pro',sans-serif;margin:0;padding:12px 8px;position:sticky;top:0;z-index:5;background:<?php echo esc_attr($colors['bg']); ?>;border-bottom:1px solid <?php echo esc_attr($colors['line']); ?>;}
  ul.list{list-style:none;margin:0;padding:0;}
  .row{padding:12px 8px;border-bottom:1px solid <?php echo esc_attr($colors['line']); ?>;}
  .row:nth-child(odd){background:<?php echo esc_attr($colors['rowAlt']); ?>;}
  .main{display:flex;align-items:center;justify-content:space-between;gap:12px;}
  .comment{margin-top:6px;font-style:italic;color:<?php echo esc_attr($colors['muted']); ?>;font-size:.9rem;}
  .left{display:flex;flex-direction:column;min-width:120px;}
  .amount{font-weight:700;}
  .date{font-size:.8rem;color:<?php echo esc_attr($colors['muted']); ?>;}
  .right{display:flex;align-items:center;gap:10px;flex-wrap:wrap;justify-content:flex-end;text-align:right;}
  .name{font-weight:700;}
  .badge{display:inline-block;font-size:.72rem;font-weight:700;line-height:1;padding:5px 9px;border-radius:999px;white-space:nowrap;}
  .badge--recurring{background:<?php echo esc_attr($brand); ?>;color:#fff;}
  .badge--onetime{background:transparent;color:<?php echo esc_attr($colors['muted']); ?>;border:1px solid <?php echo esc_attr($colors['line']); ?>;}
  .empty{padding:16px 8px;color:<?php echo esc_attr($colors['muted']); ?>;}
</style>
</head><body>
<div class="wrap">
  <?php echo $heading; // phpcs:ignore ?>
  <?php if ($rowsHtml === ''): ?>
    <p class="empty">Noch keine Spenden.</p>
  <?php else: ?>
    <ul class="list"><?php echo $rowsHtml; // phpcs:ignore ?></ul>
  <?php endif; ?>
</div>
</body></html>
            <?php
            $out = ob_get_clean();

            return new WP_REST_Response($out, 200, ['Content-Type' => 'text/html; charset=UTF-8']);
        },
    ]);
});

// Serve raw HTML + allow embedding (same hosts as the barometer embed).
add_action('rest_pre_serve_request', function ($served, $result, $request, $server) {
    $route = is_object($request) && method_exists($request, 'get_route') ? $request->get_route() : '';
    if (str_contains($route, '/sos/v1/donorlist/embed')) {
        if (function_exists('header_remove')) {
            @header_remove('X-Frame-Options');
        }
        header("Content-Security-Policy: frame-ancestors 'self' https://sos-humanity.org https://*.sos-humanity.org https://*.kinsta.cloud https://secure.fundraisingbox.com https://*.twitch.tv https://*.youtube.com https://studio.youtube.com https://streamlabs.com");
        $data = is_object($result) && method_exists($result, 'get_data') ? $result->get_data() : null;
        if (is_string($data)) {
            header('Content-Type: text/html; charset=UTF-8');
            echo $data;
            return true;
        }
    }
    return $served;
}, 10, 4);
