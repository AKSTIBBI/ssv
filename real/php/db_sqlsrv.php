<?php
require_once 'config.php';

/**
 * Return SQL Server connection resource or null.
 * Uses sqlsrv extension when enabled via env/config.
 */
function sqlsrv_get_connection() {
    static $conn = null;
    static $attempted = false;

    if ($conn !== null) return $conn;
    if ($attempted) return null;
    $attempted = true;

    if (!SQLSRV_ENABLED) return null;
    if (!function_exists('sqlsrv_connect')) {
        if (function_exists('log_message')) log_message('SQLSRV extension not available; using JSON fallback.', 'warning');
        return null;
    }

    $connectionInfo = [
        'Database' => SQLSRV_DATABASE,
        'UID' => SQLSRV_USERNAME,
        'PWD' => SQLSRV_PASSWORD,
        'CharacterSet' => 'UTF-8'
    ];

    // For integrated auth, allow empty UID/PWD by removing keys.
    if (SQLSRV_USERNAME === '' && SQLSRV_PASSWORD === '') {
        unset($connectionInfo['UID'], $connectionInfo['PWD']);
    }

    $conn = @sqlsrv_connect(SQLSRV_HOST, $connectionInfo);
    if ($conn === false) {
        if (function_exists('log_message')) log_message('SQLSRV connection failed; using JSON fallback.', 'warning');
        $conn = null;
    }

    return $conn;
}
