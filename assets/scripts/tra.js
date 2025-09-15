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

  // Borlabs consent checker (Borlabs API first not working ????, fallback cookie)
  const hasBorlabsConsent = (serviceId) => {
    try {
      const BC = typeof window.BorlabsCookie === 'object' ? window.BorlabsCookie : null;

      // Known v3/v2 public APIs
      if (BC) {
        if (typeof BC.checkConsent === 'function') return BC.checkConsent(serviceId);
        if (typeof BC.hasConsent === 'function') return BC.hasConsent(serviceId);
        if (BC.Consents && typeof BC.Consents.hasConsent === 'function') return BC.Consents.hasConsent(serviceId);
        if (BC.Consent && typeof BC.Consent.hasConsent === 'function') return BC.Consent.hasConsent(serviceId);
        if (typeof BC.isConsentGiven === 'function') return BC.isConsentGiven(serviceId);
      }

      // fallback: parse cookie (v3 stores JSON in borlabs-cookie)
      const raw = document.cookie
        .split('; ')
        .find(row => row.startsWith('borlabs-cookie='));
      if (!raw) return false;

      const cookieValue = decodeURIComponent(raw.split('=')[1]);
      const data = JSON.parse(cookieValue);

      if (!data.consents) return false;

      // iterate all consent groups safely
      const consentGroups = Object.values(data.consents);
      for (let i = 0; i < consentGroups.length; i += 1) {
        const group = consentGroups[i];
        if (Array.isArray(group) && group.includes(serviceId)) return true;
      }

      return false;
    } catch (e) {
      // eslint-disable-next-line no-console
      console.error('Consent check error', e);
      return false;
    }
  };

  // check if we are on the thank-you pages
  const path = window.location.pathname;
  const validPages = window?.TRACKING_DATA?.thanksPages || [];
  const normalizedPath = path.replace(/\/$/, '');
  if (!validPages.includes(normalizedPath)) return;

  // required url parameters on thank-you pages
  const search = new URLSearchParams(window.location.search);
  const token = search.get('fb_transaction_id');
  const amount = parseFloat(search.get('amount'));
  const interval = parseInt(search.get('interval'), 10);
  if (!token?.startsWith('FB-T-') || Number.isNaN(amount) || amount <= 0 || (interval !== 0 && interval !== 1)) return;

  // run tracking
  const runTracking = () => {
    // Read Meta Pixel cookies (optional; server also falls back to cookies)
    const fbp = getCookie('_fbp');
    const fbc = getCookie('_fbc');

    const variant = interval === 0 ? 'Einmalspende' : 'Dauerspende';
    const donateTyp = search.get('donate_typ') || 'Spende';

    // Determine events to fire
    const events = [];
    const purchasePaths = ['/doo', '/en/doo-en', '/it/doo-it'];
    if (purchasePaths.includes(normalizedPath)) {
      events.push('Purchase');
    }

    const donatePaths = ['/ddd', '/en/ddd-en', '/it/ddd-it'];
    if (donatePaths.includes(normalizedPath)) {
      events.push('Purchase', 'Donate');
    }

    // Send each event
    events.forEach(eventName => {
      const payload = {
        event_name: eventName,
        event_time: Math.floor(Date.now()/1000),
        action_source: 'website',
        event_id: token,
        event_source_url: window.location.href,
        user_data: {
          em: sessionStorage.getItem('em')
        },
        custom_data: {
          content_name: donateTyp,
          value: amount,
          currency: 'EUR',
          num_items: 1,
          content_type: variant
        }
      };

      if (fbp) payload.user_data.fbp = fbp;
      if (fbc) payload.user_data.fbc = fbc;
      // eslint-disable-next-line no-console
      console.log('Debug tra.js payload: ', payload);

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
    });
  };

  // Wait for Borlabs to initialize (retry for a short period) and require consent
  const SERVICE_ID = 'meta-pixel';
  const MAX_TRIES = 15; // ~3s at 200ms
  const DELAY = 200;
  (function waitForConsent(attempt = 0) {
    const bcLoaded = typeof window.BorlabsCookie === 'object';
    const consent = hasBorlabsConsent(SERVICE_ID);
    if (consent) {
      runTracking();
      return;
    }
    if (!bcLoaded && attempt < MAX_TRIES) {
      setTimeout(() => waitForConsent(attempt + 1), DELAY);
      return;
    }
    // Borlabs loaded but no consent, or not loaded after retries
    // eslint-disable-next-line no-console
    console.warn('No Meta Pixel consent or Borlabs not loaded');
  })();


})();
