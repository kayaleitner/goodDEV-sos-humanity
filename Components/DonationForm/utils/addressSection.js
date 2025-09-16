import $ from 'jquery'
import setMandatory from './setMandatory'

export default function initAddressSection(component) {
  const $root = $(component)
  const internalToggle = false

  /**
   * Update the receipt checkbox UI: labels and auto-receipt classes
   */
  function updateReceiptUI() {
    const $receipt = $root.find('input#payment_wants_receipt')
    const $receiptWrapper = $root.find('[for="payment_wants_receipt"]')
    const $receiptLabel = $receiptWrapper.find('span.receipt.switch__label')
    const $company = $root.find('#donate-as-company')
    if (!$receipt.length || !$receiptLabel.length || !$company.length) return

    const autoClass = 'auto-receipt'
    const defaultLabel = $receiptLabel.data('default-label') || 'Ja, ich möchte eine Jahresspendenbescheinigung erhalten.'
    const autoLabel = $receiptLabel.data('auto-receipt') || 'Die Spendenbescheinigung wird automatisch zugeschickt.'

    if ($company.is(':checked')) {
      if (!$receipt.is(':checked')) $receipt.prop('checked', true)
      $receipt.addClass(autoClass)
      $receiptLabel.addClass(autoClass)
      $receiptWrapper.addClass(autoClass)
      syncReceiptHidden()
    } else {
      // Reset receipt when the company is unchecked
      $receipt.prop('checked', false)
      $receipt.removeClass(autoClass)
      $receiptLabel.removeClass(autoClass)
      $receiptWrapper.removeClass(autoClass)
      syncReceiptHidden()
    }

    // Update receipt label text
    $receiptLabel.text($receipt.hasClass(autoClass) ? autoLabel : defaultLabel)
  }

  /**
   * Update the company switch label text
   * Currently not executed automatically; optional execution with active=true
   */
  function updateCompanyLabel(active = false) {
    if (!active) return
    const $company = $root.find('#donate-as-company')
    const $companyLabel = $root.find('.company .switch__label')
    if (!$company.length || !$companyLabel.length) return

    const checkedTxt = $companyLabel.data('checked') || "Ich möchte doch als Privatperson spenden"
    const uncheckedTxt = $companyLabel.data('default') || "Ich möchte als Unternehmen spenden"
    $companyLabel.text($company.is(':checked') ? checkedTxt : uncheckedTxt)
  }

  /**
   * Update hidden input for receipt
   */
  function syncReceiptHidden() {
    const $ui = $root.find('input#payment_wants_receipt')
    const $hidden = $root.find('#hiddenreceipt')
    if ($ui.length && $hidden.length) {
      $hidden.val($ui.is(':checked') ? 'receipt_end_of_year' : 'no_receipt')
    }
  }

  /**
   * Update hidden input for LNOBNewsletter
   */
  function syncLNOBNewsletterHidden() {
    const $ui = $root.find('input#payment_wants_lnob_newsletter')
    const $hidden = $root.find('#payment_donation_custom_field_14610')
    if ($ui.length && $hidden.length) {
      $hidden.val($ui.is(':checked') ? 'on' : '')
    }
  }

  /**
   * Update hidden input for address
   */
  function syncAddressHiddenField() {
    const $street = $root.find('input[name="street"]')
    const $houseNumber = $root.find('input[name="houseNumber"]')
    const $hiddenAddress = $root.find('input[name="payment[address]"]')
    if ($street.length && $houseNumber.length) {
      $hiddenAddress.val(`${$street.val()} ${$houseNumber.val()}`)
    }
  }

  /**
   * Sync Salutation hidden field
   */
  function syncSalutationHiddenAndSyncFields(source) {
    const $hidden = $root.find('#payment_salutation');
    const $radios = $root.find('input[name="salutation_radio"]');
    const $select = $root.find('#salutation_select');

    const $checkedRadio = $radios.filter(':checked');
    const selectVal = $select.val();
    const hasSelectVal = selectVal !== undefined && selectVal !== null && selectVal !== '';

    let valToSet = '';

    if (source === 'radio' && $checkedRadio.length) {
      valToSet = $checkedRadio.val();
      if ($select.val() !== valToSet) $select.val(valToSet);
    } else if (source === 'select' && hasSelectVal) {
      valToSet = selectVal;
      $radios.prop('checked', false);
      const $radioToCheck = $radios.filter(`[value="${valToSet}"]`);
      if ($radioToCheck.length) $radioToCheck.prop('checked', true);
    } else if ($checkedRadio.length) {
      valToSet = $checkedRadio.val();
      if ($select.val() !== valToSet) $select.val(valToSet);
    } else if (hasSelectVal) {
      valToSet = selectVal;
      $radios.prop('checked', false);
      const $radioToCheck = $radios.filter(`[value="${valToSet}"]`);
      if ($radioToCheck.length) $radioToCheck.prop('checked', true);
    }

    // Hidden-Feld setzen (leer, falls kein Wert)
    $hidden.val(valToSet);
  }

  /**
   * Toggle visibility of company fields
   */
  function toggleCompanySection() {
    if (internalToggle) return
    const donateAsCompany = $root.find('#donate-as-company').is(':checked')
    const $companyWrapper = $root.find('.payment-company-name')

    if (donateAsCompany) {
      $companyWrapper.stop(true, true).slideDown(400, () => setMandatory($companyWrapper, true))
    } else {
      $companyWrapper.stop(true, true).slideUp(400, () => setMandatory($companyWrapper, false))
    }
  }

  /**
   * Toggle visibility of address fields
   */
  function toggleAddressSection() {
    if (internalToggle) return
    const $receipt = $root.find('input#payment_wants_receipt')
    const $addressWrapper = $root.find('.payment-address-data')

    // Show address if receipt is checked
    const showAddress = $receipt.is(':checked')
    if (showAddress) {
      $addressWrapper.stop(true, true).slideDown(400, () => setMandatory($addressWrapper, true))
    } else {
      $addressWrapper.stop(true, true).slideUp(400, () => setMandatory($addressWrapper, false))
    }
  }

  /**
   * Initialize UI once
   */
  function initOnce() {
    updateReceiptUI()
    toggleCompanySection()
    toggleAddressSection()
    syncReceiptHidden()
    syncAddressHiddenField()
    syncLNOBNewsletterHidden()
    syncSalutationHiddenAndSyncFields()
    // updateCompanyLabel() bleibt aktuell inaktiv
  }

  // Listen to changes on company and receipt checkboxes
  $root.on('change', '#donate-as-company', () => {
    updateReceiptUI()
    updateCompanyLabel()
    toggleCompanySection()
    toggleAddressSection()
  })

  $root.on('change', '#payment_wants_receipt', () => {
    syncReceiptHidden()
    toggleAddressSection()
  })

  $root.on('change input', '#payment_wants_lnob_newsletter', () => {
    syncLNOBNewsletterHidden()
  })

  $root.on('change input', '[name="street"], [name="houseNumber"]', () => {
    syncAddressHiddenField()
  })

  $root.on('change input', 'input[name="salutation_radio"], #salutation_select', (e) => {
    const source = $(e.target).is('#salutation_select') ? 'select' : 'radio';
    syncSalutationHiddenAndSyncFields(source);
  });

  return { syncReceiptHidden, toggleAddressSection, initOnce }
}
