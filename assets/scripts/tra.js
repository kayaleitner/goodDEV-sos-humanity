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


  // Borlabs consent checker with robust fallback to cookie parsing
  // Accepts a single ID or an array of possible service IDs (aliases)
  const hasBorlabsConsent = (serviceIds) => {
    try {
      const ids = Array.isArray(serviceIds) ? serviceIds : [serviceIds];
      const BC = typeof window.BorlabsCookie === 'object' ? window.BorlabsCookie : null;

      // Try known v3/v2 public APIs first
      if (BC) {
        try {
          // test all provided ids against the API methods; return on first true
          for (let i = 0; i < ids.length; i += 1) {
            const sid = ids[i];
            if (typeof BC.checkConsent === 'function' && BC.checkConsent(sid) === true) return true;
            if (typeof BC.hasConsent === 'function' && BC.hasConsent(sid) === true) return true;
            if (BC.Consents && typeof BC.Consents.hasConsent === 'function' && BC.Consents.hasConsent(sid) === true) return true;
            if (BC.Consent && typeof BC.Consent.hasConsent === 'function' && BC.Consent.hasConsent(sid) === true) return true;
            if (typeof BC.isConsentGiven === 'function' && BC.isConsentGiven(sid) === true) return true;
          }
          // If API exists but returned false/undefined, we continue to cookie/storage fallback below.
        } catch (apiErr) {
          // Continue to cookie/storage fallback
        }
      }

      // Fallback: parse consent from cookie (Borlabs v3 stores JSON in borlabs-cookie)
      const raw = document.cookie
        .split('; ')
        .find(row => row.startsWith('borlabs-cookie='));
      if (!raw) return false;

      // Some setups double-encode the value; decode twice defensively.
      let cookieValue = raw.split('=')[1] || '';
      try { cookieValue = decodeURIComponent(cookieValue); } catch (_) { /* noop */ }
      try { cookieValue = decodeURIComponent(cookieValue); } catch (_) { /* noop */ }

      // If a value looks like JSON, parse it; otherwise abort.
      let data;
      try {
        data = JSON.parse(cookieValue);
      } catch (jsonErr) {
        // eslint-disable-next-line no-console
        console.warn('Borlabs consent data present but not JSON-parsable');
        return false;
      }

      if (!data || !data.consents || typeof data.consents !== 'object') return false;

      // Iterate all consent groups safely
      const consentGroups = Object.values(data.consents);
      for (let i = 0; i < consentGroups.length; i += 1) {
        const group = consentGroups[i];
        if (Array.isArray(group) && ids.some((sid) => group.includes(sid))) return true;
      }

      return false;
    } catch (e) {
      // eslint-disable-next-line no-console
      console.error('Consent check error', e);
      return false;
    }
  };

  // normalize current path
  const path = window.location.pathname;
  const normalizedPath = path.replace(/\/$/, '');

  // Define known thank-you page paths here (single source of truth)
  const purchasePaths = ['/doo', '/en/doo-en', '/it/doo-it'];
  const donatePaths = ['/ddd', '/en/ddd-en', '/it/ddd-it'];

  // If the current page is not a known thank-you page, abort early
  if (!purchasePaths.includes(normalizedPath) && !donatePaths.includes(normalizedPath)) return;

  // required url parameters on thank-you pages
  const search = new URLSearchParams(window.location.search);
  const token = search.get('fb_transaction_id');
  const amount = parseFloat(search.get('amount'));
  const interval = parseInt(search.get('interval'), 10);
  if (!token?.startsWith('FB-T-') || Number.isNaN(amount) || amount <= 0 || (interval !== 0 && interval !== 1)) return;

  const SERVICE_IDS = ['meta-pixel', 'facebook-pixel', 'facebook'];

  // run tracking
  const runTracking = () => {
    // Read Meta Pixel cookies (optional; server also falls back to cookies)
    const fbp = getCookie('_fbp');
    const fbc = getCookie('_fbc');

    const variant = interval === 0 ? 'Einmalspende' : 'Dauerspende';
    const donateTyp = search.get('donate_typ') || 'Spende';

    // Determine events to fire
    const events = [];
    if (purchasePaths.includes(normalizedPath)) {
      events.push('Purchase');
    }

    if (donatePaths.includes(normalizedPath)) {
      events.push('Purchase', 'Donate');
    }

    // Send each event
    events.forEach(eventName => {
      // Include a consent hint for the server in case it cannot read the cookie on this endpoint
      const borlabsConsent = hasBorlabsConsent(SERVICE_IDS);
      const payload = {
        event_name: eventName,
        event_time: Math.floor(Date.now()/1000),
        action_source: 'website',
        event_id: token,
        event_source_url: window.location.href,
        borlabs_consent: !!borlabsConsent,
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
        credentials: 'include'
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

  // Wait for Borlabs to initialize
  const MAX_TRIES = 15;
  const DELAY = 200;
  (function waitForConsent(attempt = 0) {
    const consent = hasBorlabsConsent(SERVICE_IDS);
    // eslint-disable-next-line no-console
    // console.log('consent (meta/facebook pixel aliases): ', consent, 'attempt:', attempt);
    if (consent) {
      runTracking();
      return;
    }
    if (attempt < MAX_TRIES) {
      setTimeout(() => waitForConsent(attempt + 1), DELAY);
      return;
    }
    // After retries, still no consent — proceed and rely on server-side consent enforcement
    // eslint-disable-next-line no-console
    console.warn('No Meta/Facebook Pixel consent or Borlabs not loaded (fallback: server will enforce consent)');
    runTracking();
  })();


})();
