<?php

require 'vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use SimplePie\SimplePie;

// Set the RSS feed URL and Sendy API endpoint URL
$rss_feed_url = 'https://example.com/feed';
$sendy_api_url = 'https://sendy.example.com/api/campaigns/create.php';

// Set your Sendy API key and other required parameters
$sendy_api_key = 'YOUR_SENDY_API_KEY';
$list_id = 'YOUR_LIST_ID';
$from_name = 'YOUR_NAME';
$from_email = 'YOUR_EMAIL';
$subject = 'YOUR_SUBJECT';

// Fetch the RSS feed data
$feed = new SimplePie();
$feed->set_feed_url($rss_feed_url);
$feed->init();

// Format the RSS feed data for Sendy
$sendy_data = array(
    'api_key' => $sendy_api_key,
    'from_name' => $from_name,
    'from_email' => $from_email,
    'reply_to' => $from_email,
    'subject' => $subject,
    'list_ids[]' => $list_id,
    'html_text' => '<html><body>' .
        '<h1>' . $feed->get_title() . '</h1>' .
        '<ul>' .
        implode(array_map(function ($entry) {
            return '<li><a href="' . $entry->get_permalink() . '">' . $entry->get_title() . '</a></li>';
        }, $feed->get_items())) .
        '</ul>' .
        '</body></html>'
);

// Send the formatted data to Sendy via the API endpoint
$client = new Client();
try {
    $response = $client->post($sendy_api_url, array(
        'form_params' => $sendy_data
    ));
    echo $response->getBody();
} catch (RequestException $e) {
    echo $e->getMessage();
}
