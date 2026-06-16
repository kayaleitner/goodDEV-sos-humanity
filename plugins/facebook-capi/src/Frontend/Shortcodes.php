<?php
namespace FacebookCapiPlugin\Frontend;

class Shortcodes
{
    public function register(): void
    {
        add_shortcode('fb_capi_thanks_example', [$this, 'thanksExample']);
    }

    public function thanksExample(): string
    {
        // Use REST endpoint and include REST nonce.
        $endpoint = esc_url_raw(rest_url('fb-capi/v1/event'));
        $nonce = wp_create_nonce('wp_rest');
        $endpoint_js = esc_js($endpoint);
        $nonce_js = esc_js($nonce);
        $script = <<<HTML
<script>
(function(){
  try {
    var payload = {
      event_name: 'Donate',
      event_time: Math.floor(Date.now()/1000),
      action_source: 'website',
      event_id: (Math.random().toString(36).slice(2)),
      event_source_url: window.location.href,
      user_data: { em: 'example@mail.de' },
      custom_data: { content_name: 'Donation', value: 10, currency: 'EUR', num_items: 1, content_type: 'donation' }
    };
    fetch('{$endpoint_js}', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-WP-Nonce': '{$nonce_js}'
      },
      body: JSON.stringify(payload),
      credentials: 'same-origin'
    })
    .then(function(r){
      if (!r.ok) throw new Error('HTTP '+r.status+': '+r.statusText);
      return r.json();
    })
    .then(function(d){ console.log('FB CAPI response', d); })
    .catch(function(e){ console.error('FB CAPI error', e); });
  } catch (e) { console.error('FB CAPI init error', e); }
})();
</script>
HTML;
        return $script;
    }
}
