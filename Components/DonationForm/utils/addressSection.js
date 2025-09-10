import $ from 'jquery'
import setMandatory from './setMandatory'

export default function initAddressSection(component) {
  const $root = $(component)

  function syncReceiptHidden() {
    const $ui = $root.find('#payment_wants_receipt')
    const $hidden = $root.find('#hiddenreceipt')
    if ($ui.length && $hidden.length) {
      $hidden.val($ui.is(':checked') ? 'receipt_end_of_year' : 'no_receipt')
    }
  }

  function toggleAddressSection() {
    const donateAsCompany = $root.find('#donate-as-company').is(':checked')
    const hasCompanyValue = ($root.find('[name="payment[company_name]"]').val() || '').trim().length > 0
    const wantsReceipt = $root.find('#payment_wants_receipt').is(':checked')

    const $addressWrapper = $root.find('.payment-address-data')
    const $companyWrapper = $root.find('.payment-company-name')

    const showAddress = donateAsCompany || wantsReceipt

    if (showAddress) {
      $addressWrapper.stop(true, true).slideDown(400, () => setMandatory($addressWrapper, true))
    } else {
      $addressWrapper.stop(true, true).slideUp(400, () => setMandatory($addressWrapper, false))
    }

    if (donateAsCompany || hasCompanyValue) {
      $companyWrapper.stop(true, true).slideDown(400, () => setMandatory($companyWrapper, donateAsCompany))
    } else {
      $companyWrapper.stop(true, true).slideUp(400, () => setMandatory($companyWrapper, false))
    }
  }

  // public API to allow script.js to trigger once on init
  function initOnce() {
    $root.find('#payment_wants_receipt').addClass('auto-receipt')
    syncReceiptHidden()
    toggleAddressSection()
  }

  // change listeners
  $root.on('change', '[name="donate_as_company"], #payment_wants_receipt', () => {
    syncReceiptHidden()
    toggleAddressSection()
  })

  return { syncReceiptHidden, toggleAddressSection, initOnce }
}
