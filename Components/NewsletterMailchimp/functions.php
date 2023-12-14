<?php

namespace Flynt\Components\NewsletterMailchimp;

use Flynt\Utils\Options;

// Make function available for requests
add_action('wp_ajax_subscribe_to_mailchimp_list', function() { subscribe_to_mailchimp_list(); });
add_action('wp_ajax_nopriv_subscribe_to_mailchimp_list', function() { subscribe_to_mailchimp_list(); });

// Handle subscription request, the output of this function is returned to javascript (must be json)!
function subscribe_to_mailchimp_list() {
    $options = Options::getTranslatable('NewsletterMailchimp');

    // Verify that necessary options are set
    if (empty($options) || empty($options['apiKey'] || empty($options['datacenter'] || empty($options['listId'])))) {
        echo json_encode([
            'error' => true,
            'errorBody' => "Please check Mailchimp setting (API key, datacenter, list ID).",
        ]);

        wp_die();
        exit;
    }

    // Check the security nonce to prevent external requests
    $nonce = $_POST['nonce'];

    // Verify nonce field passed from javascript code
    if (!wp_verify_nonce( $nonce, 'subcribe-to-mailchimp-list-now')) {
        die ('Busted!');
    }

    // echo json_encode([
    //     'success' => true,
    //     'apikey' => $options['apiKey'],
    //     'datacenter' => $options['datacenter'],
    //     'listId' => $options['listId'],
    //     'data' => $_POST,
    // ]);

    // wp_die();
    // exit;


    // Get Mailchimp options
    // the last part of the API key determines which url to use (something like 'us16')
    $baseUrl = 'https://anystring:'.$options['apiKey'].'@'.$options['datacenter'].'.api.mailchimp.com/3.0';

    // Request URL contains list ID
    $url = $baseUrl.'/lists/'.$options['listId'].'/members';

    // Request headers
    $headers = array(
        'Accept: application/vnd.api+json',
        'Content-Type: application/vnd.api+json',
        'Authorization: apikey ' . $options['apiKey'],
    );

    // Set all payload entries as data, add status pending so that Mailchimp can handle confirmation
    $the_data = array_merge(
        $_POST['payload'],
        ['status' => 'pending'],
    );

    // Encode message body
    $body = json_encode($the_data);

    // Send the request to Mailchimp
    $response = wp_remote_post(
        $url,
        array(
            'method' => 'POST',
            'timeout' => 10,
            'redirection' => 5,
            'httpversion' => '1.0',
            'blocking' => true,
            'headers' => $headers,
            'body' => $body
        )
    );

    // Custom return object
    $return = [
        'success' => false,
        'error' => true
    ];

    $status = $response['response']['code'];

    if ($status >= 200 && $status <= 299) {
        $return['success'] = true;
    } else {
        $return['success'] = false;
        $return['error'] = true;
        $return['ms-response'] = $response;

        if (!empty($response['body'])) {
            $return['error_body'] = json_decode($response['body']);
        } else {
            $return['error_message'] = 'There was en error. Please try again later.';
        }
    }

    echo json_encode($return);
    wp_die();
    exit;
}

Options::addTranslatable('NewsletterMailchimp', [
    [
        'label' => __('Mailchimp Options', 'flynt'),
        'name' => 'mailchimpOptionsTab',
        'type' => 'tab',
        'placement' => 'top',
        'endpoint' => 0
    ],
    [
        'label' => __('API Key', 'flynt'),
        'instructions' => 'Keep this secret!<br>See <a href="https://mailchimp.com/help/about-api-keys/#Generate_an_API_key" target="_blank" rel="noopener">Mailchimp docs</a> for information on how to get an API key.',
        'name' => 'apiKey',
        'type' => 'text',
        'required' => 0,
        'wrapper' => [
            'width' => '60',
        ],
    ],
    [
        'label' => __('Data center', 'flynt'),
        'instructions' => 'Should be something like "us18" or "us24"',
        'name' => 'datacenter',
        'type' => 'text',
        'required' => 0,
        'wrapper' => [
            'width' => '60',
        ],
    ],
    [
        'label' => __('List ID', 'flynt'),
        'instructions' => 'Should be a 10-character string',
        'name' => 'listId',
        'type' => 'text',
        'required' => 0,
        'wrapper' => [
            'width' => '60',
        ],
    ],
]);
