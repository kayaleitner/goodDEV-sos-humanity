import $ from 'jquery';

/**
 * Toggle required attribute for conditionally mandatory fields.
 *
 * @param {jQuery|HTMLElement} wrapper - The wrapper element that contains .js-conditionally-mandatory fields.
 * @param {boolean} isRequired - Whether the fields should be required or not.
 */
export default function setMandatory(wrapper, isRequired) {
  const $wrapper = wrapper instanceof $ ? wrapper : $(wrapper);
  let lang = (document.documentElement.lang || 'de').slice(0, 2).toUpperCase();
  if( lang === 'EN' ) {
    lang = 'GB';
  }
  $wrapper.find('.js-conditionally-mandatory').each((_, el) => {
    if (isRequired) {
      $(el).attr('required', 'required');
      if ($(el).attr('name') === 'payment[country]') {
        $(el).val(lang).trigger('change');
      }
    } else {
      $(el).removeAttr('required').removeClass('is-valid').removeClass('is-invalid').val('');
      $(el).siblings('div.error-text').html('');
    }
  });
}
