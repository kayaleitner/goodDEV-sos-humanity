<?php

/**
 * Flynt Add Newsletter Shortcodes - Accessible Version
 */

namespace Flynt\Newsletters;

add_shortcode('newsletter', function ($atts, $content = null) {
    $attributes = shortcode_atts([
        'data-collect-token' => '',
        'data-redirect-url' => '',
        'only-email' => 'false',
        'address' => 'false',
        'outreach' => 'false',
        'theme' => '',
        'datenschutz' => 'https://sos-humanity.org/datenschutzerklaerung/',
    ], $atts);

    $locale = get_locale();

    // Define placeholders and labels based on locale
    $fields = ($locale === 'de_DE')
        ? [
            'firstname' => ['label' => 'Vorname', 'placeholder' => 'Vorname*'],
            'lastname' => ['label' => 'Nachname', 'placeholder' => 'Nachname*'],
            'phone' => ['label' => 'Telefonnummer', 'placeholder' => 'Telefonnummer'],
            'email' => ['label' => 'E-Mail', 'placeholder' => 'E-Mail*'],
            'address' => ['label' => 'Postadresse', 'placeholder' => 'Postadresse'],
            'outreach' => 'Ich möchte per Telefon kontaktiert werden',
            'submit' => 'Senden',
        ]
        : (($locale === 'it_IT')
            ? [
                'firstname' => ['label' => 'Nome', 'placeholder' => 'Nome*'],
                'lastname' => ['label' => 'Cognome', 'placeholder' => 'Cognome*'],
                'phone' => ['label' => 'Telefono', 'placeholder' => 'Telefono'],
                'email' => ['label' => 'E-mail', 'placeholder' => 'E-mail*'],
                'address' => ['label' => 'Indirizzo postale', 'placeholder' => 'Indirizzo postale'],
                'outreach' => 'Vorrei essere contattato telefonicamente',
                'submit' => 'Iscriviti',
            ]
            : [
                'firstname' => ['label' => 'First Name', 'placeholder' => 'First Name*'],
                'lastname' => ['label' => 'Last Name', 'placeholder' => 'Last Name*'],
                'phone' => ['label' => 'Phone Number', 'placeholder' => 'Phone Number'],
                'email' => ['label' => 'Email', 'placeholder' => 'Email*'],
                'address' => ['label' => 'Postal Address', 'placeholder' => 'Postal Address'],
                'outreach' => 'I would like to be contacted via phone',
                'submit' => 'Submit',
            ]);

    // Privacy text based on locale
    $privacyText = ($locale === 'de_DE')
        ? '<span>Die <a target="_blank" rel="noopener noreferrer" href="' . esc_url($attributes['datenschutz']) . '">Datenschutzerklärung</a> wird mit der Registrierung zur Kenntnis genommen.</span>'
        : (($locale === 'it_IT')
            ? '<span>Registrandosi, l\'utente accetta i termini <a target="_blank" rel="noopener noreferrer" href="' . esc_url($attributes['datenschutz']) . '">dell\'Informativa sulla privacy.</a></span>'
            : '<span>By registering, you agree to the terms of the <a target="_blank" rel="noopener noreferrer" href="' . esc_url($attributes['datenschutz']) . '">Privacy Policy.</a></span>');

    // Additional fields if not only-email
    $additionalFields = '';
    if ($attributes['only-email'] !== 'true') {
        $additionalFields .= '
            <div class="jc-form-field">
                <label class="sr-only jc-form-field__wrapper" for="newsletter-firstname">' . esc_html($fields['firstname']['label']) . ' <span aria-hidden="true">*</span></label>
                <input type="text" id="newsletter-firstname" name="firstname" placeholder="' . esc_attr($fields['firstname']['placeholder']) . '" required aria-required="true" autocomplete="given-name">
            </div>
            <div class="jc-form-field">
                <label class="sr-only jc-form-field__wrapper" for="newsletter-lastname">' . esc_html($fields['lastname']['label']) . ' <span aria-hidden="true">*</span></label>
                <input type="text" id="newsletter-lastname" name="lastname" placeholder="' . esc_attr($fields['lastname']['placeholder']) . '" required aria-required="true" autocomplete="family-name">
            </div>
            <div class="jc-form-field">
                <label class="sr-only jc-form-field__wrapper" for="newsletter-phone">' . esc_html($fields['phone']['label']) . '</label>
                <input type="tel" id="newsletter-phone" name="phone" placeholder="' . esc_attr($fields['phone']['placeholder']) . '" autocomplete="tel">
            </div>
        ';
    }

    // Append address field if enabled
    $adressField = '';
    if ($attributes['address'] === 'true') {
        $adressField = '
            <div class="jc-form-field">
                <label class="sr-only jc-form-field__wrapper" for="newsletter-address">' . esc_html($fields['address']['label']) . '</label>
                <textarea 
                    id="newsletter-address" name="adresse" placeholder="' . esc_attr($fields['address']['placeholder']) . '" 
                    rows="3"
                    style="background-color: var(--yellow);"
                ></textarea>
            </div>
        ';
    }

    // Outreach checkbox if enabled
    $outreachField = '';
    if ($attributes['outreach'] === 'true') {
        $outreachField = '
            <div class="jc-form-field outreach-checkbox" style="margin-bottom: 20px;">
                <input type="checkbox" id="newsletter-outreach" name="outreach" value="yes">
                <label for="newsletter-outreach">' . esc_html($fields['outreach']) . '</label>
            </div>
        ';
    }

    // Additional classes
    $additionalClasses = '';
    $additionalClasses .= ($attributes['only-email'] === 'true') ? ' only-email' : '';
    $additionalClasses .= ($attributes['theme'] === 'dark') ? ' dark' : '';

    // Layout classes
    $flexOrBlock = ($attributes['only-email'] === 'true') ? '' : 'flex flex-row justify-between';

    // Render final HTML
    return '
        <form class="june-scope' . esc_attr($additionalClasses) . '" data-native-elements="true" data-collect-token="' . esc_attr($attributes['data-collect-token']) . '" data-redirect-url="' . esc_attr($attributes['data-redirect-url']) . '" aria-label="Newsletter Signup Form">
            <fieldset>
                <legend class="sr-only">Newsletter Signup</legend>
                <div class="flex flex-row">
                    ' . $additionalFields . '
                </div>
                ' . $adressField . '
                <div class="' . esc_attr($flexOrBlock) . '">
                    <div class="jc-form-field email-div">
                        <label class="jc-form-field__wrapp  er sr-only" for="newsletter-email">' . esc_html($fields['email']['label']) . ' <span aria-hidden="true">*</span></label>
                        <input type="email" id="newsletter-email" name="email" placeholder="' . esc_attr($fields['email']['placeholder']) . '" required aria-required="true" autocomplete="email">
                    </div>
                    <div class="submit-div">
                        <button type="submit" class="main-action-button je-btn-submit">' . esc_html($fields['submit']) . '</button>
                    </div>
                </div>
            </fieldset>
            <fieldset>
                <legend class="sr-only">Additional Options</legend>
                ' . $outreachField . '
                <div class="jc-form-field font-data">
                    ' . $privacyText . '
                </div>
            </fieldset>
        </form>
    ';
});
