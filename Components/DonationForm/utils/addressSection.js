import $ from 'jquery'
import setMandatory from './setMandatory'

export default function initAddressSection(component) {
  const $root = $(component)

  function updateTexts() {
    const $companySwitchLabel = $root.find('.company .switch__label')
    const $receiptLabel = $root.find('#payment_wants_receipt_wrapper label[for="payment_wants_receipt"]')
    const donateAsCompany = $root.find('#donate-as-company').is(':checked')

    // Update the company switch label text: show company text by default, and when checked show the private text
    if ($companySwitchLabel.length) {
      const privateText = $companySwitchLabel.data('checked')
      let companyText = $companySwitchLabel.data('default')
      if (!companyText) {
        companyText = $companySwitchLabel.text().trim()
        $companySwitchLabel.data('default', companyText)
      }
      $companySwitchLabel.text(donateAsCompany ? privateText : companyText)
    }

    // Update receipt label text depending on auto-receipt state
    if ($receiptLabel.length) {
      const defaultLabel = $receiptLabel.data('default-label')
      const autoReceipt = $receiptLabel.data('auto-receipt')
      const isAuto = $root.find('#payment_wants_receipt').hasClass('auto-receipt')
      $receiptLabel.text(isAuto ? autoReceipt : defaultLabel)
    }
  }

  function syncReceiptHidden() {
    const $ui = $root.find('#payment_wants_receipt')
    const $hidden = $root.find('#hiddenreceipt')
    if ($ui.length && $hidden.length) {
      $hidden.val($ui.is(':checked') ? 'receipt_end_of_year' : 'no_receipt')
    }
  }

  function toggleAddressSection() {
    const donateAsCompany = $root.find('#donate-as-company').is(':checked')
    const $receipt = $root.find('#payment_wants_receipt')

    const $addressWrapper = $root.find('.payment-address-data')
    const $companyWrapper = $root.find('.payment-company-name')

    // Business rule:
    // - If donating as a company: force wants_receipt checked and mark as auto-receipt
    // - If not donating as a company: do not force, just remove auto-receipt and keep user choice
    if (donateAsCompany) {
      if (!$receipt.is(':checked')) {
        $receipt.prop('checked', true).addClass('auto-receipt').trigger('change')
      } else {
        $receipt.addClass('auto-receipt')
      }
    } else {
      // allow user to choose when donating as a private person
      $receipt.removeClass('auto-receipt')
    }

    const showAddress = donateAsCompany || $receipt.is(':checked')

    if (showAddress) {
      $addressWrapper.stop(true, true).slideDown(400, () => setMandatory($addressWrapper, true))
    } else {
      $addressWrapper.stop(true, true).slideUp(400, () => setMandatory($addressWrapper, false))
    }

    if (donateAsCompany) {
      $companyWrapper.stop(true, true).slideDown(400, () => setMandatory($companyWrapper, donateAsCompany))
    } else {
      $companyWrapper.stop(true, true).slideUp(400, () => setMandatory($companyWrapper, false))
    }
  }

  function initOnce() {
    // Initialize according to the current company switch state
    toggleAddressSection()
    syncReceiptHidden()
    updateTexts()
  }

  // change listeners
  $root.on('change', '[name="donate_as_company"], #payment_wants_receipt', () => {
    syncReceiptHidden()
    toggleAddressSection()
    updateTexts()
  })

  return { syncReceiptHidden, toggleAddressSection, initOnce }
}
