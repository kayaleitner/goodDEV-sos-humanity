// Amount/interval utilities
export default function initAmountHandlers(component) {
  const $ = window.jQuery;
  const $root = $(component);

  function setAmount(value) {
    const $hiddenAmount = $root.find('[name="payment[amount]"]');
    if ($hiddenAmount.length) $hiddenAmount.val(value);
  }

  // Remember the custom amount per interval
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
  });

  return { setAmount };
}