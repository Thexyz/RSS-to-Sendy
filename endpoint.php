<?php

// Make sure the request is coming from Sendy
if ($_SERVER['HTTP_USER_AGENT'] !== 'Sendy') {
    header('HTTP/1.0 403 Forbidden');
    exit();
}

// Extract the API key and other parameters from the request
$api_key = $_POST['api_key'];
$list_ids = $_POST['list_ids'];
$from_name = $_POST['from_name'];
$from_email = $_POST['from_email'];
$reply_to = $_POST['reply_to'];
$subject = $_POST['subject'];
$html_text = $_POST['html_text'];

// Authenticate the API key against your Sendy installation
if ($api_key !== 'YOUR_SENDY_API_KEY') {
    header('HTTP/1.0 401 Unauthorized');
    exit();
}

// Create a new campaign in Sendy
require_once('path/to/sendy/includes/helpers/create/send-now.php');
$sendy_campaign_id = create_send_now_campaign(
    $list_ids,
    $from_name,
    $from_email,
    $reply_to,
    $subject,
    $html_text
);

// Return the campaign ID to Sendy
echo $sendy_campaign_id;
