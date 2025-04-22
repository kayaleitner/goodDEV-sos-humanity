<?php

/**
 * Flynt Add Newsletter Shortcodes
 */

namespace Flynt\Newsletters;

add_shortcode('newsletter', function ($atts, $content = null) {
    $attributes = shortcode_atts([
        'data-collect-token' => '',
        'data-redirect-url' => '',
        'only-email' => 'false',
        'theme' => '',
        'datenschutz' => 'https://sos-humanity.org/datenschutzerklaerung/',
    ], $atts);

    $locale = get_locale();

    // Define placeholders based on locale
    $placeholders = ($locale === 'de_DE') 
        ? [
            'firstname' => 'Vorname*',
            'lastname' => 'Nachname*',
            'phone' => 'Telefonnummer',
            'email' => 'E-Mail*',
        ] 
        : (($locale === 'it_IT') 
            ? [
                'firstname' => 'Nome*',
                'lastname' => 'Cognome*',
                'phone' => 'Telefono',
                'email' => 'E-mail*',
            ]
            : [
                'firstname' => 'First Name*',
                'lastname' => 'Last Name*',
                'phone' => 'Phone Number',
                'email' => 'Email*',
            ]);

    // Privacy text based on locale
    $privacyText = ($locale === 'de_DE')
        ? '<span>Die <a target="_blank" href="' . esc_url($attributes['datenschutz']) . '">Datenschutzerklärung</a> wird mit der Registrierung zur Kenntnis genommen.</span>'
        : (($locale === 'it_IT')
            ? '<span>Registrandosi, l\'utente accetta i termini <a target="_blank" href="' . esc_url($attributes['datenschutz']) . '">dell\'Informativa sulla privacy.</a></span>'
            : '<span>By registering, you agree to the terms of the <a target="_blank" href="' . esc_url($attributes['datenschutz']) . '">Privacy Policy.</a></span>');

    // Button label based on locale
    $buttonLabel = ($locale === 'de_DE') 
        ? 'Senden' 
        : (($locale === 'it_IT') ? 'Iscriviti' : 'Submit');

    // Additional fields if not only-email
    $additionalFields = '';
    if ($attributes['only-email'] !== 'true') {
        $additionalFields = '
            <div class="jc-form-field">
                <label class="jc-form-field__wrapper">
                    <input type="text" name="firstname" placeholder="' . esc_attr($placeholders['firstname']) . '" required="">
                </label>
            </div>
            <div class="jc-form-field">
                <label class="jc-form-field__wrapper">
                    <input type="text" name="lastname" placeholder="' . esc_attr($placeholders['lastname']) . '" required="">
                </label>
            </div>
            <div class="jc-form-field">
                <label class="jc-form-field__wrapper">
                    <input type="text" name="phone" placeholder="' . esc_attr($placeholders['phone']) . '">
                </label>
            </div>
        ';
    }

    // Additional classes based on attributes
    $additionalClasses = '';
    $additionalClasses .= ($attributes['only-email'] === 'true') ? ' only-email' : '';
    $additionalClasses .= ($attributes['theme'] === 'dark') ? ' dark' : '';

    // Layout adjustment
    $flexOrBlock = ($attributes['only-email'] === 'true') ? '' : 'flex flex-row justify-between';

    // Return the rendered HTML
    return '
        <form class="june-scope' . esc_attr($additionalClasses) . '" data-native-elements="true" data-collect-token="' . esc_attr($attributes['data-collect-token']) . '" data-redirect-url="' . esc_attr($attributes['data-redirect-url']) . '">
            <fieldset>
                <div class="flex flex-row">
                    ' . $additionalFields . '
                </div>
                <div class="' . esc_attr($flexOrBlock) . '">
                    <div class="jc-form-field email-div">
                        <label class="jc-form-field__wrapper">
                            <input type="text" name="email" placeholder="' . esc_attr($placeholders['email']) . '" required="">
                        </label>
                    </div>
                    <div class="submit-div">
                        <button type="submit" class="main-action-button je-btn-submit">' . esc_html($buttonLabel) . '</button>
                    </div>
                </div>
            </fieldset>
            <fieldset>
                <div class="jc-form-field font-data">
                    ' . $privacyText . '
                </div>
            </fieldset>
        </form>
    ';
});
