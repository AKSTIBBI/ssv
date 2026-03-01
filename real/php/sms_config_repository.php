<?php
require_once 'config.php';
require_once 'helpers.php';
require_once 'db_sqlsrv.php';

function sms_config_defaults() {
    return [
        'provider_name' => 'Priority SMS',
        'api_url' => 'https://alerts.prioritysms.com/api/v4/',
        'api_key' => '',
        'sender_id' => 'SIDHIS',
        'method' => 'sms',
        'enabled' => false,
        'use_dummy_api' => true,
        'bypass_enabled' => true,
        'bypass_code' => '999999',
        'otp_expiry_seconds' => 300,
        'updated_at' => ''
    ];
}

function sms_config_normalize($config) {
    $defaults = sms_config_defaults();
    $merged = array_merge($defaults, is_array($config) ? $config : []);
    $merged['enabled'] = !empty($merged['enabled']);
    $merged['use_dummy_api'] = !empty($merged['use_dummy_api']);
    $merged['bypass_enabled'] = !empty($merged['bypass_enabled']);
    $merged['bypass_code'] = preg_replace('/\D/', '', (string)$merged['bypass_code']);
    $merged['otp_expiry_seconds'] = (int)$merged['otp_expiry_seconds'];
    if ($merged['otp_expiry_seconds'] < 60) $merged['otp_expiry_seconds'] = 60;
    if ($merged['otp_expiry_seconds'] > 1800) $merged['otp_expiry_seconds'] = 1800;
    return $merged;
}

function sms_config_ensure_db_table($conn) {
    $sql = "
IF OBJECT_ID('dbo.sms_config', 'U') IS NULL
BEGIN
    CREATE TABLE dbo.sms_config (
        id INT IDENTITY(1,1) PRIMARY KEY,
        config_key NVARCHAR(100) NOT NULL UNIQUE,
        config_value NVARCHAR(MAX) NULL,
        updated_at DATETIME2 NOT NULL DEFAULT SYSUTCDATETIME()
    );
END
";
    @sqlsrv_query($conn, $sql);
}

function sms_config_get_from_db($conn) {
    sms_config_ensure_db_table($conn);
    $sql = "SELECT config_value FROM dbo.sms_config WHERE config_key = ?";
    $stmt = @sqlsrv_query($conn, $sql, ['schoolpro_sms']);
    if (!$stmt) return null;
    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    if (!$row || !isset($row['config_value'])) return null;
    $decoded = json_decode((string)$row['config_value'], true);
    return is_array($decoded) ? $decoded : null;
}

function sms_config_save_to_db($conn, $config) {
    sms_config_ensure_db_table($conn);
    $json = json_encode($config, JSON_UNESCAPED_SLASHES);
    if ($json === false) return false;

    $sql = "
MERGE dbo.sms_config AS target
USING (SELECT ? AS config_key, ? AS config_value) AS source
ON target.config_key = source.config_key
WHEN MATCHED THEN
    UPDATE SET config_value = source.config_value, updated_at = SYSUTCDATETIME()
WHEN NOT MATCHED THEN
    INSERT (config_key, config_value, updated_at)
    VALUES (source.config_key, source.config_value, SYSUTCDATETIME());
";
    $stmt = @sqlsrv_query($conn, $sql, ['schoolpro_sms', $json]);
    return $stmt !== false;
}

function sms_config_get() {
    $defaults = sms_config_defaults();
    $conn = sqlsrv_get_connection();

    if ($conn) {
        $dbConfig = sms_config_get_from_db($conn);
        if (is_array($dbConfig)) {
            return sms_config_normalize($dbConfig);
        }
    }

    if (file_exists(SMS_CONFIG_JSON)) {
        $jsonConfig = get_json_data(SMS_CONFIG_JSON, []);
        if (is_array($jsonConfig)) {
            return sms_config_normalize($jsonConfig);
        }
    }

    return $defaults;
}

function sms_config_save($config) {
    $normalized = sms_config_normalize($config);
    $okJson = save_json_file(SMS_CONFIG_JSON, $normalized); // keep backup/fallback in sync

    $conn = sqlsrv_get_connection();
    if ($conn) {
        $okDb = sms_config_save_to_db($conn, $normalized);
        return $okDb && $okJson;
    }

    return $okJson;
}

function sms_send_with_config($config, $mobile, $otp) {
    if (!empty($config['use_dummy_api']) || empty($config['enabled'])) {
        if (function_exists('log_message')) log_message("Dummy SMS send to {$mobile} OTP: {$otp}", 'info');
        return [true, 'Dummy mode: SMS simulated successfully.'];
    }

    if (empty($config['api_url']) || empty($config['api_key']) || empty($config['sender_id'])) {
        return [false, 'Live SMS requires API URL, API Key, and Sender ID.'];
    }

    $apiUrl = rtrim((string)$config['api_url'], '/');
    $message = "Dear user, Your OTP to login SchoolPro is {$otp}. Shri Siddhi Vinayak Shikshan Sansthan";
    $query = http_build_query([
        'api_key' => (string)$config['api_key'],
        'method' => (string)$config['method'],
        'message' => $message,
        'to' => '91' . $mobile,
        'sender' => (string)$config['sender_id']
    ]);
    $url = $apiUrl . '/?' . $query;

    $httpCode = 0;
    $responseBody = '';

    if (function_exists('curl_init')) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        $responseBody = curl_exec($ch);
        $httpCode = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($responseBody === false) {
            return [false, 'Gateway error: ' . $curlError];
        }
    } else {
        $context = stream_context_create([
            'http' => [
                'method' => 'POST',
                'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
                'timeout' => 15
            ]
        ]);
        $responseBody = @file_get_contents($url, false, $context);
        if ($responseBody === false) {
            return [false, 'Gateway request failed.'];
        }
    }

    $ok = stripos((string)$responseBody, 'success') !== false || ($httpCode >= 200 && $httpCode < 300);
    if ($ok) return [true, 'SMS sent successfully.'];

    return [false, 'Gateway rejected request: ' . substr((string)$responseBody, 0, 180)];
}

