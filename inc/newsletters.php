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
            'address' => [
                'street' => 'Straße*',
                'house_number' => 'Hausnummer*',
                'postal_code' => 'PLZ*',
                'city' => 'Ort*',
                'country' => 'Land*'
            ],
            'outreach' => 'Ja, ich möchte auch ein kostenloses Aktionspaket von SOS Humanity bekommen.',
            'submit' => 'Senden',
        ]
        : (($locale === 'it_IT')
            ? [
                'firstname' => ['label' => 'Nome', 'placeholder' => 'Nome*'],
                'lastname' => ['label' => 'Cognome', 'placeholder' => 'Cognome*'],
                'phone' => ['label' => 'Telefono', 'placeholder' => 'Telefono'],
                'email' => ['label' => 'E-mail', 'placeholder' => 'E-mail*'],
                'address' => [
                    'street' => 'Via*',
                    'house_number' => 'Numero civico*',
                    'postal_code' => 'CAP*',
                    'city' => 'Città*',
                    'country' => 'Paese*'
                ],
                'outreach' => "Vorrei ricevere anch'io un pacchetto informativo gratuito di SOS Humanity.",
                'submit' => 'Iscriviti',
            ]
            : [
                'firstname' => ['label' => 'First Name', 'placeholder' => 'First Name*'],
                'lastname' => ['label' => 'Last Name', 'placeholder' => 'Last Name*'],
                'phone' => ['label' => 'Phone Number', 'placeholder' => 'Phone Number'],
                'email' => ['label' => 'Email', 'placeholder' => 'Email*'],
                'address' => [
                    'street' => 'Street*',
                    'house_number' => 'House Number*',
                    'postal_code' => 'ZIP Code*',
                    'city' => 'City*',
                    'country' => 'Country*'
                ],
                'outreach' => 'Yes, I would also like to receive a free outreach package of SOS Humanity.',
                'submit' => 'Submit',
            ]);

    $privacyText = ($locale === 'de_DE')
        ? '<span>Die <a target="_blank" rel="noopener noreferrer" href="' . esc_url($attributes['datenschutz']) . '">Datenschutzerklärung</a> wird mit der Registrierung zur Kenntnis genommen.</span>'
        : (($locale === 'it_IT')
            ? '<span>Registrandosi, l\'utente accetta i termini <a target="_blank" rel="noopener noreferrer" href="' . esc_url($attributes['datenschutz']) . '">dell\'Informativa sulla privacy.</a></span>'
            : '<span>By registering, you agree to the terms of the <a target="_blank" rel="noopener noreferrer" href="' . esc_url($attributes['datenschutz']) . '">Privacy Policy.</a></span>');

    $formFields = '';

    if ($attributes['only-email'] !== 'true') {
        $formFields .= '
            <div class="jc-form-field grow">
                <label class="sr-only" for="newsletter-firstname">' . esc_html($fields['firstname']['label']) . '</label>
                <input type="text" id="newsletter-firstname" name="firstname" placeholder="' . esc_attr($fields['firstname']['placeholder']) . '" required aria-required="true" autocomplete="given-name">
            </div>
            <div class="jc-form-field grow">
                <label class="sr-only" for="newsletter-lastname">' . esc_html($fields['lastname']['label']) . '</label>
                <input type="text" id="newsletter-lastname" name="lastname" placeholder="' . esc_attr($fields['lastname']['placeholder']) . '" required aria-required="true" autocomplete="family-name">
            </div>
            <div class="jc-form-field grow">
                <label class="sr-only" for="newsletter-phone">' . esc_html($fields['phone']['label']) . '</label>
                <input type="tel" id="newsletter-phone" name="phone" placeholder="' . esc_attr($fields['phone']['placeholder']) . '" autocomplete="tel">
            </div>
        ';
    }

    $formFields .= '
        <div class="jc-form-field grow">
            <label class="sr-only" for="newsletter-email">' . esc_html($fields['email']['label']) . '</label>
            <input type="email" id="newsletter-email" name="email" placeholder="' . esc_attr($fields['email']['placeholder']) . '" required aria-required="true" autocomplete="email">
        </div>
    ';

    $outreachField = '';
    $injectOutreachHidden = false;

    if ($attributes['outreach'] === 'true') {
        $outreachField = '
            <div class="jc-form-field grow outreach-checkbox">
                <input type="checkbox" id="newsletter-outreach" name="outreach" value="yes">
                <label for="newsletter-outreach">' . esc_html($fields['outreach']) . '</label>
            </div>
        ';
    } elseif ($attributes['address'] === 'true') {
        $injectOutreachHidden = true;
    }

    $addressToggleScript = '';
    $a = $fields['address'];
    $addressFieldsHTML = '
            <div class="address-wrapper jc-form-field flex grow w-full gap-x-md gap-y-sm flex-wrap">
                <div class="jc-form-field grow" style="width: 70%">
                    <label class="sr-only" for="newsletter-street">' . esc_html($a['street']) . '</label>
                    <input type="text" id="newsletter-street" name="street" placeholder="' . esc_attr($a['street']) . '" required aria-required="true" autocomplete="address-line1">
                </div>
                <div class="jc-form-field grow" style="width: 25%">
                    <label class="sr-only" for="newsletter-house-number">' . esc_html($a['house_number']) . '</label>
                    <input type="text" id="newsletter-house-number" name="house_number" placeholder="' . esc_attr($a['house_number']) . '" required aria-required="true" autocomplete="address-line2">
                </div>
                <div class="jc-form-field grow">
                    <label class="sr-only" for="newsletter-postal-code">' . esc_html($a['postal_code']) . '</label>
                    <input type="text" id="newsletter-postal-code" name="postal_code" placeholder="' . esc_attr($a['postal_code']) . '" required aria-required="true" autocomplete="postal-code">
                </div>
                <div class="jc-form-field grow">
                    <label class="sr-only" for="newsletter-city">' . esc_html($a['city']) . '</label>
                    <input type="text" id="newsletter-city" name="city" placeholder="' . esc_attr($a['city']) . '" required aria-required="true" autocomplete="address-level2">
                </div>
                <div class="jc-form-field grow">
                    <label class="sr-only" for="newsletter-country">' . esc_html($a['country']) . '</label>
                    <input type="text" id="newsletter-country" name="country" placeholder="' . esc_attr($a['country']) . '" required aria-required="true" autocomplete="country-name">
                </div>
            </div>';
    if ($attributes['address'] === 'true' && $attributes['outreach'] === 'true') {
        
        

        $addressToggleScript = '
            <script>
                document.addEventListener("DOMContentLoaded", function () {
                    const outreachCheckbox = document.getElementById("newsletter-outreach");
                    const outreachContainer = outreachCheckbox?.closest(".jc-form-field");
                    const addressHTML = ' . json_encode($addressFieldsHTML) . ';
                    const getAddressWrapper = () => document.querySelector(".address-wrapper");

                    const toggleAddressFields = () => {
                        const existing = getAddressWrapper();
                        if (outreachCheckbox.checked) {
                            if (!existing && outreachContainer) {
                                outreachContainer.insertAdjacentHTML("afterend", addressHTML);
                            }
                        } else {
                            existing?.remove();
                        }
                    };

                    outreachCheckbox?.addEventListener("change", toggleAddressFields);
                    toggleAddressFields();
                });
            </script>
        ';
    }

    $additionalClasses = '';
    $additionalClasses .= ($attributes['only-email'] === 'true') ? ' only-email' : '';
    $additionalClasses .= ($attributes['theme'] === 'dark') ? ' dark' : '';

    return '
        <form class="june-scope' . esc_attr($additionalClasses) . '" data-native-elements="true" data-collect-token="' . esc_attr($attributes['data-collect-token']) . '" data-redirect-url="' . esc_attr($attributes['data-redirect-url']) . '" aria-label="Newsletter Signup Form">
            <fieldset>
                <legend class="sr-only">Newsletter Signup</legend>
                <div class="flex flex-wrap gap-x-md gap-y-sm">
                    ' . $formFields . '
                    ' . $outreachField . '
                    ' . ($injectOutreachHidden ? '<input type="hidden" id="newsletter-outreach" name="outreach" value="yes">' : '') . '
                    ' . ($injectOutreachHidden ? $addressFieldsHTML : '') . '
                </div>
                <div class="submit-div" style="margin-top: 20px">
                    <button type="submit" class="main-action-button je-btn-submit button">' . esc_html($fields['submit']) . '</button>
                </div>
            </fieldset>
            <fieldset>
                <legend class="sr-only">Additional Options</legend>
                <div class="jc-form-field grow font-data">' . $privacyText . '</div>
            </fieldset>
        </form>
        ' . $addressToggleScript . '
    ';
});
