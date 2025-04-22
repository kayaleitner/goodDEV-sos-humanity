<?php

function enqueue_react_app_assets() {

    global $post;
    $content = do_shortcode($post->post_content);

    if (is_singular() && strpos($content, 'react-apply-button') !== false) {
         // Enqueue the main CSS file
        wp_enqueue_style('react-app-css', get_stylesheet_directory_uri() . '/public/react-form/css/main.a3309844.css');

        // Enqueue the main JavaScript file (make sure to list any dependencies your React app may have, e.g., 'wp-element' for React)
        wp_enqueue_script('react-app-js', get_stylesheet_directory_uri() . '/public/react-form/js/main.3bd9520c.js', array(), null, true);

        // If your React app is split into chunks (as create-react-app does), enqueue each chunk
        wp_enqueue_script('react-app-chunk-js', get_stylesheet_directory_uri() . '/assets/public/js/453.ba332da2.chunk.js', array('react-app-js'), null, true);
    }
}

add_action('wp_enqueue_scripts', 'enqueue_react_app_assets');


add_shortcode('apply-button', function ($atts, $content = null) {
    $attributes = shortcode_atts(array(
        'label' => 'Jetzt Bewerben!',
        'redirect-url' => 'https://sos-humanity.org',
    ), $atts);

    return '<button class="react-apply-button button button--blue" data-redirect-url="' . $attributes['redirect-url'] . '">' . $attributes['label'] . '</button>';
});

// add api endpoint for sending email
add_action('rest_api_init', function () {
    register_rest_route('flynt/v1', '/submit_form', array(
        'methods' => 'POST',
        'callback' => 'send_custom_email_with_attachment',
        'permission_callback' => '__return_true',
    ));
});


function send_custom_email_with_attachment($request) {

    error_log('job-application-form: send_custom_email_with_attachment function called.');

    global $wpdb;

    // Mapping keys from $request to their corresponding field names
    $salesforceFieldmap = array(
        'firstName' => '00N2o000009CT4m',
        'lastName' => '00N2o000009CT4r',
        'pronouns' => '00NOj000000icOA',
        'email' => 'email',
        'phone' => 'phone',
        'nationality' => '00N9J000000QEZE',
        'residency' => '00NOj000000igEr',
        'positions' => '00N9J000000QEav',
        'startDate' => '00N9J000000QEa2',
        'moreInfoDate' => '00N9J000000QEa7',
        'motivation' => '00N2o000009CUG0',
        'english' => '00NOj000000sMEr',
        'italian' => '00NOj000000sMGT',
        'arabic' => '00NOj000000sMLJ',
        'french' => '00NOj000000sMI5',
        'additionalLanguages' => '00N9J000000QEZY',
        'basicSafety' => '00N9J000000QEZO',
        'medicalCertificate' => '00N9J000000QEZJ',
        'rhibLicense' => '00N2o000009CSQw',
        'additionalCertificates' => '00N9J000000QEZx',
        'relevantSkills' => '00NOj000000igl7',
        'otherRelevantSkills' => '00NOj000002K4MT',
        'medicalBackground' => '00NOj000000igRl',
        'dataprotection' => '00N9J000000QEZs',
        'orgid' => 'orgid',
        'retURL' => 'retURL',
        'crewRole' => '00NOj0000026b5N'
    );

    // Retrieve and sanitize input parameters
    $firstName = sanitize_text_field($request->get_param('00N2o000009CT4m'));
    $lastName = sanitize_text_field($request->get_param('00N2o000009CT4r'));
    $email = sanitize_email($request->get_param('email'));
    $phone = sanitize_text_field($request->get_param('phone'));
    $positionApplied = sanitize_text_field($request->get_param('00N9J000000QEav'));

    // Additional data (serialize the data for storage)
    $additionalData = [];
    foreach ($salesforceFieldmap as $key => $sfField) {
        $value = $request->get_param($sfField);
        if (!empty($value)) {
            $additionalData[$key] = sanitize_text_field($value);
        }
    }

    // Convert additional data to JSON or serialized string
    $additionalDataSerialized = maybe_serialize($additionalData);

    // Insert data into the database
    $table_name = $wpdb->prefix . 'job_application_forms';
    $wpdb->insert(
        $table_name,
        array(
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $email,
            'phone' => $phone,
            'position_applied' => $positionApplied,
            'additional_data' => $additionalDataSerialized,
        ),
        array(
            '%s', // first_name
            '%s', // last_name
            '%s', // email
            '%s', // phone
            '%s', // position_applied
            '%s', // additional_data
        )
    );

    if ($wpdb->last_error) {
        error_log('job-application-form: Database insert error: ' . $wpdb->last_error);
        return new WP_REST_Response('Failed to save data to the database.', 500);
    } else {
        error_log('job-application-form: Data saved to the database successfully.');
    }

    error_log('job-application-form: Request parameters: ' . print_r($request->get_params(), true));

    // Build the HTML content for the email
    $html_content = '
        <h1>Bewerbung für Position "' . esc_html($positionApplied) . '"</h1>
        <p>Liebes Crewing Team,</p>
        <p>Eine Bewerbung ist eingetroffen. Bitte überprüft diese in Salesforce.</p>
        <p>Anbei der mitgeschickte Lebenslauf und eine Übersicht der Bewerbungsdaten:</p>
        <p></p>
        <p>Liebe Grüße</p>
        <p></p>
        <p>
    ';

    foreach ($salesforceFieldmap as $key => $sfField) {
        $value = $request->get_param($sfField);
        if (!empty($value)) {
            $html_content .= '<strong>' . ucfirst($key) . ':</strong> ' . sanitize_text_field($value) . '<br />';
        }
    }
    $html_content .= '</p>';

    error_log('job-application-form: HTML content: ' . $html_content);

    $files = $request->get_file_params();    

    error_log('job-application-form: File parameters: ' . print_r($files, true)); 

    if (empty($files['cv']['tmp_name']) || !is_uploaded_file($files['cv']['tmp_name'])) {
        error_log('job-application-form: No valid file uploaded.');
        return new WP_REST_Response('No valid file uploaded.', 500);
    }

    // Check MIME type to confirm it's a PDF
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime_type = $finfo->file($files['cv']['tmp_name']);
    error_log('job-application-form: MIME type of uploaded file: ' . $mime_type);

    if ($mime_type !== 'application/pdf') {
        error_log('job-application-form: Uploaded file is not a PDF.');
        return new WP_REST_Response('Uploaded file is not a PDF.', 500);
    }

    // Define new filename and path for the uploaded file
    $upload_dir = wp_upload_dir();
    $date = date('Ymd');

    // Construct new filename with date, 'cv', and name
    $new_filename = $date . '-cv-' . $firstName . '-' . $lastName . '.pdf';
    $new_path = $upload_dir['path'] . '/' . $new_filename;

    // Attempt to move the uploaded file to the new path
    if (!move_uploaded_file($files['cv']['tmp_name'], $new_path)) {
        error_log('job-application-form: Failed to move the file to: ' . $new_path);
        return new WP_REST_Response('Failed to move the file.', 500);
    } else {
        error_log('job-application-form: File moved successfully to: ' . $new_path);
    }

    $attachments = array($new_path);
    
    // Prepare email parameters
    $to = 'crewing@sos-humanity.org';
    $subject = 'Neue Bewerbung von ' . $firstName . ' ' . $lastName;
    $headers = array(
        'Content-Type: text/html; charset=UTF-8',
        'From: SOS HUMANITY <no-reply@sos-humanity.org>'
    );

    error_log('job-application-form: Attempting to send email to: ' . $to . ' with subject: ' . $subject);

    // Send the email
    if (!wp_mail($to, $subject, $html_content, $headers, $attachments)) {
        error_log('job-application-form: Failed to send email to: ' . $to);
        return new WP_REST_Response('Failed to send email.', 500);
    } else {
        error_log('job-application-form: Email sent successfully to: ' . $to);
    }

    // Delete the file after attempting to send the email
    unlink($new_path);

    return new WP_REST_Response('Email sent successfully.', 200);
}
