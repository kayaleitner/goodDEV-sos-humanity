// Amount/interval utilities
export default function initAmountHandlers(component) {
  const $ = window.jQuery;
  const $root = $(component);

  const $btn = $root.find('#donation-submit-btn');
  const $btnLabel = $btn.find('.label');

  function setAmount(value) {
    const $hiddenAmount = $root.find('[name="payment[amount]"]');
    if ($hiddenAmount.length) $hiddenAmount.val(value);
    updateButtonLabel();
  }


  function getAmount() {
    const val = $root.find('[name="payment[amount]"]').val();
    return val || '';
  }

  function updateButtonLabel() {
    if (!$btn.length) return;

    const amount = getAmount();
    // Use per-interval templates coming from data attributes on the button
    // data-one-time-text, data-montly-text (note: montly as provided in markup)
    const oneTimeTpl = ($btn.data('one-time-text') || '').toString();
    const monthlyTpl = ($btn.data('monthly-text') || '').toString();

    // Determine selected interval; default to '0' if not found
    const $checked = $root.find('input[name="payment[interval]"]:checked');
    const intervalVal = $checked.length ? $checked.val() : '0';

    // Helper to apply template
    const applyTemplate = (tpl, amt) => {
      if (!tpl) return '';
      // Replace %amount% token with current amount
      return tpl.replace(/%amount%/g, amt);
    };

    let nextLabel = '';
    if (amount) {
      if (intervalVal === '1') {
        nextLabel = applyTemplate(monthlyTpl, amount);
      } else {
        nextLabel = applyTemplate(oneTimeTpl, amount);
      }
    }

    if (nextLabel) {
      $btnLabel.text(nextLabel);
    } else {
      // fallback to the default label if any
      const fallback = $btnLabel.data('fallback') || $btnLabel.text();
      if (!$btnLabel.data('fallback')) $btnLabel.attr('data-fallback', fallback);
      $btnLabel.text(fallback);
    }
  }

  const customAmounts = {};

  // When an interval changes, show relevant group and restore value
  $root.on('change', 'input[name="payment[interval]"]', function () {
    const interval = $(this).val();

    $root.find('.amount-group-wrapper').removeClass('active').hide();
    const $activeGroup = $root
      .find(`.amount-group-wrapper[data-interval="${interval}"]`)
      .addClass('active')
      .show();

    // active custom input for this interval
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

  // Sync custom amount inputs and deselect radios
  $root.on('input', '.amount-input', function () {
    const $input = $(this);
    const interval = $input.closest('.amount-group-wrapper').data('interval');
    const val = $input.val().trim().replace(/[^0-9.]/g, '');
    $input.val(val);

    customAmounts[interval] = val;

    $input.closest('.amount-group-wrapper').find('input.amount-radio').prop('checked', false);

    setAmount(val || '');
  });

  // Radio buttons update the amount and clear custom for that interval
  $root.on('change', '.amount-radio', function () {
    const $radio = $(this);
    const interval = $radio.closest('.amount-group-wrapper').data('interval');

    setAmount($radio.val());

    const $custom = $radio.closest('.amount-group-wrapper').find('input.amount-input');
    $custom.val('');
    customAmounts[interval] = '';

    updateButtonLabel();
  });

  // Initial label update on load
  setTimeout(updateButtonLabel, 0);

  return { setAmount };
}