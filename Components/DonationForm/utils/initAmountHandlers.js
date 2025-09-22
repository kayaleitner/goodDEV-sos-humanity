// Amount/interval utilities
export default function initAmountHandlers(component) {
  const $ = window.jQuery;
  const $root = $(component);

  const $btn = $root.find('#donation-submit-btn');
  const $btnLabel = $btn.find('.label');

  const customAmounts = {};

  /**
   * Set the hidden donation amount and update button label
   */
  function setAmount(value) {
    const $hiddenAmount = $root.find('[name="payment[amount]"]');
    if ($hiddenAmount.length) $hiddenAmount.val(value);
    updateButtonLabel();
  }

  /**
   * Get the current donation amount from hidden field
   */
  function getAmount() {
    const val = $root.find('[name="payment[amount]"]').val();
    return val || '';
  }

  /**
   * Update the donation button label based on selected interval and amount
   */
  function updateButtonLabel() {
    if (!$btn.length) return;

    const amount = getAmount();

    const templates = {
      '0': $btn.data('one-time-text') || '',
      '1': $btn.data('monthly-text') || '',
      '3': $btn.data('quarterly-text') || '',
      '6': $btn.data('half-yearly-text') || '',
      '12': $btn.data('yearly-text') || '',
    };

    // Determine interval: radio checked, or hidden input fallback
    let intervalVal;
    const $checked = $root.find('input[name="payment[interval]"]:checked');
    if ($checked.length) {
      intervalVal = $checked.val();
    } else {
      const $hiddenInterval = $root.find('input[name="payment[interval]"][type="hidden"]');
      intervalVal = $hiddenInterval.length ? $hiddenInterval.val() : '0';
    }

    const applyTemplate = (tpl, amt) => tpl ? tpl.toString().replace(/%amount%/g, amt) : '';

    let nextLabel = '';
    if (amount && Object.prototype.hasOwnProperty.call(templates, intervalVal)) {
      nextLabel = applyTemplate(templates[intervalVal], amount);
    }

    if (nextLabel) {
      $btnLabel.text(nextLabel);
    } else {
      const fallback = $btnLabel.data('fallback') || $btnLabel.text();
      if (!$btnLabel.data('fallback')) $btnLabel.attr('data-fallback', fallback);
      $btnLabel.text(fallback);
    }
  }

  /**
   * Apply an interval and amount from URL parameters if present
   */
  function applyUrlParams() {
    const queryParams = new URLSearchParams(window.location.search || '');
    const urlInterval = queryParams.get('interval');
    const urlAmountRaw = queryParams.get('amount');

    const allIntervals = ['0', '1', '3', '6', '12'];
    const isValidInterval = allIntervals.includes(urlInterval);

    const intervalToSet = isValidInterval
      ? urlInterval
      : $root.find('#payment_interval').data('default-interval') || '0';

    // Set interval
    $root.find(`input[name="payment[interval]"][value="${intervalToSet}"]`)
      .prop('checked', true)
      .trigger('change');

    // Set amount
    if (urlAmountRaw) {
      const normalized = String(urlAmountRaw).replace(/[^0-9.,]/g, '').replace(',', '.');
      if (normalized) {
        const $activeGroup = $root.find('.amount-group-wrapper.active');
        const $matchRadio = $activeGroup.find(`input.amount-radio[value="${normalized}"]`).first();
        if ($matchRadio.length) {
          $matchRadio.prop('checked', true).trigger('change');
        } else {
          const $custom = $activeGroup.find('input.amount-input').first();
          if ($custom.length) $custom.val(normalized).trigger('input');
        }
      }
    }
  }

  /**
   * Event: interval change → show relevant group and restore values
   */
  $root.on('change', 'input[name="payment[interval]"]', function () {
    const interval = $(this).val();

    $root.find('.amount-group-wrapper').removeClass('active').hide();
    const $activeGroup = $root
      .find(`.amount-group-wrapper[data-interval="${interval}"]`)
      .addClass('active')
      .show();

    const $custom = $activeGroup.find('input.amount-input');
    const savedCustom = customAmounts[interval] || '';
    $custom.val(savedCustom);

    if (savedCustom) {
      setAmount(savedCustom);
      $activeGroup.find('input.amount-radio').prop('checked', false);
    } else {
      const $checkedRadio = $activeGroup.find('input.amount-radio:checked').first();
      if ($checkedRadio.length) setAmount($checkedRadio.val());
      else setAmount('');
    }

    updateButtonLabel();
  });

  /**
   * Event: custom input → sync hidden amount, deselect radios
   */
  $root.on('input', '.amount-input', function () {
    const $input = $(this);
    const interval = $input.closest('.amount-group-wrapper').data('interval');
    const val = $input.val().trim().replace(/[^0-9.]/g, '');
    $input.val(val);

    customAmounts[interval] = val;
    $input.closest('.amount-group-wrapper').find('input.amount-radio').prop('checked', false);
    setAmount(val || '');
  });

  /**
   * Event: radio selects → syncs hidden amount, clear custom
   */
  $root.on('change', '.amount-radio', function () {
    const $radio = $(this);
    const interval = $radio.closest('.amount-group-wrapper').data('interval');

    setAmount($radio.val());

    const $custom = $radio.closest('.amount-group-wrapper').find('input.amount-input');
    $custom.val('');
    customAmounts[interval] = '';

    updateButtonLabel();
  });

  // Initialize from URL parameters
  applyUrlParams();

  // Initial label update
  setTimeout(updateButtonLabel, 0);

  return { setAmount };
}