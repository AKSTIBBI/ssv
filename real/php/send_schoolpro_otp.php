<?php
require_once 'config.php';
require_once 'helpers.php';
require_once 'sms_config_repository.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$raw = file_get_contents('php://input');
$body = json_decode($raw, true);
if (!is_array($body)) {
    $body = $_POST;
}

$mobile = preg_replace('/\D/', '', (string)($body['mobile'] ?? ''));
$otp = preg_replace('/\D/', '', (string)($body['otp'] ?? ''));

if (strlen($mobile) !== 10) {
    echo json_encode(['success' => false, 'message' => 'Invalid mobile number']);
    exit;
}

if (strlen($otp) !== 6) {
    echo json_encode(['success' => false, 'message' => 'Invalid OTP format']);
    exit;
}

$config = sms_config_get();
[$ok, $msg] = sms_send_with_config($config, $mobile, $otp);
if ($ok) {
    echo json_encode(['success' => true, 'message' => $msg]);
} else {
    echo json_encode(['success' => false, 'message' => $msg]);
}
exit;
