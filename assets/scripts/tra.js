(function(){
  // Stores UTM parameters globally in sessionStorage.
  const params = new URLSearchParams(window.location.search);
  const utms = {};
  ['utm_source', 'utm_medium', 'utm_content', 'utm_campaign', 'utm_source_platform', 'utm_term'].forEach(key => {
    if (params.has(key)) utms[key] = params.get(key).replace(/[<>]/g, '');
  });
  if (Object.keys(utms).length) localStorage.setItem('frb_utm_params', JSON.stringify(utms));

  // cookie reader
  const getCookie = (name) => {
    if (!document.cookie) return undefined;
    const parts = document.cookie.split('; ');
    for (let i = 0; i < parts.length; i += 1) {
      const idx = parts[i].indexOf('=');
      const key = idx >= 0 ? parts[i].substring(0, idx) : parts[i];
      if (key === name) {
        return decodeURIComponent(idx >= 0 ? parts[i].substring(idx + 1) : '');
      }
    }
    return undefined;
  };

  // check if we are on the thank-you pages
  // TRACKING_DATA you can find in /boilerplate-flynt-next/functions.php
  const path = window.location.pathname;
  const validPages = window?.TRACKING_DATA?.thanksPages || [];
  if (!validPages.includes(path)) return;

  // required url parameters on thank-you pages
  const search = new URLSearchParams(window.location.search);
  const token = search.get('fb_transaction_id');
  const amount = parseFloat(search.get('amount'));
  const interval = parseInt(search.get('interval'), 10);
  if (!token?.startsWith('FB-T-') || Number.isNaN(amount) || amount <= 0 || (interval !== 0 && interval !== 1)) return;

  // Borlabs Cookie Consent
  if (typeof window.BorlabsCookie !== 'object' || !window.BorlabsCookie.Consents.hasConsent('meta-pixel')) {
    // eslint-disable-next-line no-console
    console.warn('No Meta Pixel consent or Borlabs not loaded');
    return;
  }

  // Read Meta Pixel cookies (optional; server also falls back to cookies)
  const fbp = getCookie('_fbp');
  const fbc = getCookie('_fbc');

  // Payload
  const utmData = JSON.parse(sessionStorage.getItem('frb_utm_params') || '{}');
  const payload = {
    event_name: 'Purchase',
    event_time: Math.floor(Date.now()/1000),
    action_source: 'website',
    event_id: token,
    event_source_url: window.location.href,
    user_data: {},
    custom_data: {
      value: amount,
      currency: 'EUR',
      interval,
      utm: utmData
    }
  };

  if (fbp) payload.user_data.fbp = fbp;
  if (fbc) payload.user_data.fbc = fbc;

  // Fetch
  fetch(window.TRACKING_DATA.endpoint, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-WP-Nonce': window.TRACKING_DATA.nonce
    },
    body: JSON.stringify(payload),
    credentials: 'same-origin'
  })
    .then(r => {
      if (!r.ok) throw new Error(`HTTP ${r.status}: ${r.statusText}`);
      return r.json();
    })
    // eslint-disable-next-line no-console
    .then(d => console.log('Tracking response', d))
    // eslint-disable-next-line no-console
    .catch(e => console.error('Tracking error', e));

  // Tag Manager
  if (window.dataLayer) {
    window.dataLayer.push({
      event: 'purchase',
      value: amount,
      currency: 'EUR',
      interval
    });
  }
})();
