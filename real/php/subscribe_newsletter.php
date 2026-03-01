<?php
/**
 * Newsletter Subscription Handler
 * Processes newsletter subscription requests and stores emails in JSON
 */

require_once 'config.php';

header('Content-Type: application/json');

// Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    send_response(false, 'Invalid request method');
}

// Get the email from POST data
$email = isset($_POST['email']) ? trim($_POST['email']) : '';

// Validate email
if (empty($email)) {
    send_response(false, 'Email address is required');
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    send_response(false, 'Please enter a valid email address');
}

// Define newsletter JSON file
$newsletter_json = JSON_PATH . 'newsletter_subscribers.json';

// Load existing subscribers
$subscribers = [];
if (file_exists($newsletter_json)) {
    $content = file_get_contents($newsletter_json);
    if (!empty($content)) {
        $subscribers = json_decode($content, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $subscribers = [];
        }
    }
}

// Check if email already exists
foreach ($subscribers as $subscriber) {
    if (strtolower($subscriber['email']) === strtolower($email)) {
        send_response(false, 'This email is already subscribed to our newsletter');
    }
}

// Add new subscriber
$new_subscriber = [
    'id' => count($subscribers) + 1,
    'email' => $email,
    'subscribed_date' => date('Y-m-d H:i:s'),
    'ip_address' => $_SERVER['REMOTE_ADDR'],
    'status' => 'active'
];

$subscribers[] = $new_subscriber;

// Save to JSON file
if (file_put_contents($newsletter_json, json_encode($subscribers, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES))) {
    send_response(true, 'Thank you for subscribing! You will receive our latest updates at your email address.', [
        'email' => $email,
        'subscribed_date' => $new_subscriber['subscribed_date']
    ]);
} else {
    send_response(false, 'Failed to save subscription. Please try again later.');
}
?>
