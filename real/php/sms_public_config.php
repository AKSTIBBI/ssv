<?php
require_once 'config.php';
require_once 'helpers.php';
require_once 'sms_config_repository.php';

header('Content-Type: application/json');

$config = sms_config_get();

echo json_encode([
    'success' => true,
    'config' => [
        'enabled' => !empty($config['enabled']),
        'use_dummy_api' => !empty($config['use_dummy_api']),
        'bypass_enabled' => !empty($config['bypass_enabled']),
        'bypass_code' => (string)($config['bypass_code'] ?? '999999'),
        'otp_expiry_seconds' => (int)($config['otp_expiry_seconds'] ?? 300)
    ]
], JSON_UNESCAPED_SLASHES);
exit;
