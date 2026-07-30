<?php
header('Content-Type: application/json');
require ('api.php');

// EC2 has no local MTA and AWS blocks outbound port 25 by default, so
// PHP's mail() silently fails there. Route through portal's actionSendEmail
// API instead (same secret-auth as every other endpoint here) - see
// portal's send-email-api branch, modules/api/controllers/ApiController.php.

if (empty($_REQUEST['email']) || empty($_REQUEST['message'])) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit();
}

if (!filter_var($_REQUEST['email'], FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Invalid email address']);
    exit();
}

$api = new Portal();

if (isset($_REQUEST['is_live']) && !$_REQUEST['is_live']) {
    $api_domain = 'http://dev.portal.seasonmarketing.co.uk';
} else {
    $api_domain = 'https://portal.seasonmarketing.co.uk';
}

$message = sprintf(
    '<p><strong>Name:</strong> %s %s</p><p><strong>Markets:</strong> %s</p><p><strong>Phone:</strong> %s</p><p><strong>Message:</strong> %s</p>',
    htmlspecialchars($_REQUEST['first_name'] ?? ''),
    htmlspecialchars($_REQUEST['last_name'] ?? ''),
    htmlspecialchars($_REQUEST['markets'] ?? ''),
    htmlspecialchars($_REQUEST['phone'] ?? ''),
    nl2br(htmlspecialchars($_REQUEST['message']))
);

$response = $api->requestApi($api_domain . '/api/api/send-email', [
    'to' => 'contact@seasonfinance.com',
    'subject' => 'Season Finance Contact',
    'message' => $message,
    'reply_to' => $_REQUEST['email'],
]);

echo $response;
exit();
