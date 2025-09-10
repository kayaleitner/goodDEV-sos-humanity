import $ from 'jquery';

/**
 * Populate donation custom fields with UTM params from sessionStorage ('frb_params').
 * Values are only written if the corresponding input is currently empty.
 * Test params
 * ?utm_source=source&utm_medium=medium&utm_content=content&utm_campaign=campaign&utm_source_platform=platform&utm_term=term
 *
 * @param {jQuery|HTMLElement} root - Donation from element or component root.
 */
export default function setUtmParamsToCustomField(root) {
  const $root = root instanceof $ ? root : $(root);

  // Read and parse UTM params from sessionStorage
  let utm = {};
  try {
    utm = JSON.parse(localStorage.getItem('frb_params') || '{}') || {};
  } catch (e) {
    utm = {};
  }
  if (!utm || typeof utm !== 'object') return;

  // Map UTM keys to custom field selectors from _donationCustomFields.twig
  const mapping = [
    { key: 'utm_campaign', selector: '#payment_donation_custom_field_14576' },
    { key: 'utm_content', selector: '#payment_donation_custom_field_14577' },
    { key: 'utm_medium', selector: '#payment_donation_custom_field_14574' },
    { key: 'utm_source', selector: '#payment_donation_custom_field_14575' },
    { key: 'utm_term', selector: '#payment_donation_custom_field_14578' },
    { key: 'utm_source_platform', selector: '#payment_donation_custom_field_14579' },
  ];

  mapping.forEach(({ key, selector }) => {
    const val = sanitize(utm[key]);
    if (val) {
      const $input = $root.find(selector);
      if ($input.length && ($input.val() == null || `${$input.val()}`.trim() === '')) {
        $input.val(val);
      }
    }
  });
}

function sanitize(v) {
  if (typeof v !== 'string') return '';
  return v.replace(/[<>]/g, '').trim().slice(0, 500);
}