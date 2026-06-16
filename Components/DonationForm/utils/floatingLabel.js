import $ from 'jquery';

/**
 * Floating label UI toggling.
 * Works on all .form-item.floating-label wrappers
 * with exactly one input, textarea or select inside.
 *
 * @param {jQuery|HTMLElement} myForm
 */
export default function floatingLabel(myForm) {
  const $form = myForm instanceof $ ? myForm : $(myForm);

  $form.find('.form-item.floating-label').each((_, wrapper) => {
    const $wrapper = $(wrapper);
    const $field = $wrapper.find('input, textarea, select');

    if (!$field.length) return;

    const toggle = () => {
      $wrapper.toggleClass(
        'noEmpty',
        $field.val().trim() !== '' || document.activeElement === $field[0]
      );
    };

    $field.on('input change focus blur', toggle);
    toggle();
  });
}
