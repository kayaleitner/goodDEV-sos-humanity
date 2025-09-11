/**
 * Transforms a select field into a checkbox.
 *
 * @param {object} field - The original FundraisingBox field object
 * @param {jQuery} $container - The container where the field is rendered
 * @param {object} options - Transformation options
 * @param {string} options.checkboxLabel - Label for the new checkbox
 * @param {string} options.checkedValue - Value that should trigger checked=true
 * @param {boolean} [options.hideOriginalLabel=true] - Remove the original <label> associated with the select
 */

export function transformSelectToCheckbox(field, $container, { checkboxLabel, checkedValue, hideOriginalLabel = true }) {
  const $ = window.jQuery
  if (!$) {
    // eslint-disable-next-line no-console
    console.warn('utils: jQuery not available on window — cannot transform select to checkbox')
    return
  }
  if (!field?.id || !$container || !checkboxLabel || !checkedValue) {
    // eslint-disable-next-line no-console
    console.warn('utils: transformSelectToCheckbox called with invalid arguments')
    return
  }

  const $select = $container.find(`#${field.id}`)
  if (!$select.length) return

  // Optionally remove/hide the original label rendered for the select
  removeAssociatedLabel(field.id, $container, hideOriginalLabel)

  const isChecked = $select.val() === checkedValue

  // Neues Checkbox-Element
  const $checkbox = $('<input>', {
    type: 'checkbox',
    name: $select.attr('name'),
    id: field.id,
    class: 'mr-2',
    checked: isChecked,
    value: checkedValue
  })

  // Neues Label hinter Checkbox
  const $label = $('<label>', {
    for: field.id,
    text: checkboxLabel,
  })

  const $wrapper = $('<div>')
    .append($checkbox)
    .append($label)

  // Select ersetzen
  $select.replaceWith($wrapper)
}

/**
 * Transforms a select field into a group of radio inputs (all options).
 * Keeps the original name attribute (e.g., payment[wants_receipt]) so the
 * FundraisingBox plugin continues to capture values/validation.
 *
 * @param {object} field - FundraisingBox field object ({ id, label, ... })
 * @param {jQuery} $container - Scope where the select is rendered
 * @param {object} options
 * @param {object} [options.classes] - Optional class map
 * @param {string} [options.classes.wrapper] - Wrapper div classes
 * @param {string} [options.classes.radio] - Radio input class
 * @param {string} [options.classes.label] - Label span/text class
 * @param {string} [options.classes.fieldWrapper] - Label span/text class
 * @param {boolean} [options.hideOriginalLabel=true] - Remove the original <label> associated with the select
 */
export function transformSelectToRadios(field, $container, { classes = {}, hideOriginalLabel = true, itemDataAttr = null, itemClassPrefix = null } = {}) {
  const $ = window.jQuery
  if (!$) {
    // eslint-disable-next-line no-console
    console.warn('utils: jQuery not available on window — cannot transform select to radios')
    return
  }
  if (!field?.id || !$container) {
    // eslint-disable-next-line no-console
    console.warn('utils: transformSelectToRadios called with invalid arguments')
    return
  }

  const $select = $container.find(`#${field.id}`)
  if (!$select.length) return

  // Optionally remove/hide the original label rendered for the select
  removeAssociatedLabel(field.id, $container, hideOriginalLabel)

  const name = $select.attr('name') // e.g. payment[wants_receipt]
  const current = $select.val()

  // Determine effective tagging defaults for payment methods
  const isPaymentMethodField = field.id === 'payment_payment_method'
  const effectiveDataAttr = itemDataAttr ?? (isPaymentMethodField ? 'payment-method' : null)
  const effectiveClassPrefix = itemClassPrefix ?? (isPaymentMethodField ? 'pm-' : null)
  // Build wrapper for radios
  const $group = $('<div>', {
    class: classes.wrapper || ''
  })

  // Iterate options and create radios
  $select.find('option').each((_, opt) => {
    const value = $(opt).attr('value')
    const text = $(opt).text()
    if (value == null || value === '') return // skip placeholder

    const id = `${field.id}__${value}` // unique per option

    const $fieldWrapper = $('<div>', {
      class: classes.fieldWrapper || 'radioWrapper',
    })
    // Add method-specific data attribute and/or class for styling hooks
    if (effectiveDataAttr) {
      $fieldWrapper.attr(`data-${effectiveDataAttr}`, value)
    }
    if (effectiveClassPrefix) {
      const safeVal = String(value).toLowerCase().replace(/[^a-z0-9_-]+/g, '-')
      $fieldWrapper.addClass(`${effectiveClassPrefix}${safeVal}`)
    }

    const $radio = $('<input>', {
      type: 'radio',
      name,
      id,
      value,
      class: classes.radio || '',
      checked: current === value
    })

    const $label = $('<label>', {
      for: id,
      class: classes.label || '',
      text,
    })
    $fieldWrapper.append($radio).append($label)
    $group.append($fieldWrapper)
  })

  // Replace select with a radio group
  $select.replaceWith($group)
}

function removeAssociatedLabel(fieldId, $container, hideOriginalLabel) {
  if (!hideOriginalLabel) return;
  const $directLabel = $container.find(`label[for="${fieldId}"]`).first();
  if ($directLabel.length) {
    $directLabel.remove();
  } else {
    const $select = $container.find(`#${fieldId}`);
    const $prev = $select.prev();
    if ($prev.length && $prev.prop('tagName')?.toLowerCase() === 'label') {
      $prev.remove();
    } else {
      const $wrapper = $container.find(`#${fieldId}_wrapper`);
      if ($wrapper.length) $wrapper.children(`label[for="${fieldId}"]`).remove();
    }
  }
}
