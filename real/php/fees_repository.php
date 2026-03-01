<?php
require_once 'config.php';
require_once 'helpers.php';
require_once 'db_sqlsrv.php';

function fee_defaults($overrides = []) {
    $base = [
        'fee_id' => '',
        'class' => '',
        'monthly_fee' => '0',
        'annual_fee' => '0',
        'special_charges' => '',
        'discount' => '0',
        'description' => ''
    ];
    return array_merge($base, is_array($overrides) ? $overrides : []);
}

function fees_ensure_db_table($conn) {
    $sql = "
IF OBJECT_ID('dbo.fees', 'U') IS NULL
BEGIN
    CREATE TABLE dbo.fees (
        id INT IDENTITY(1,1) PRIMARY KEY,
        fee_id NVARCHAR(80) NOT NULL UNIQUE,
        class_name NVARCHAR(100) NOT NULL UNIQUE,
        monthly_fee DECIMAL(12,2) NOT NULL DEFAULT 0,
        annual_fee DECIMAL(12,2) NOT NULL DEFAULT 0,
        special_charges NVARCHAR(500) NULL,
        discount DECIMAL(6,2) NOT NULL DEFAULT 0,
        description NVARCHAR(MAX) NULL,
        deleted BIT NOT NULL DEFAULT 0,
        created_at DATETIME2 NOT NULL DEFAULT SYSUTCDATETIME(),
        updated_at DATETIME2 NOT NULL DEFAULT SYSUTCDATETIME()
    );
END
";
    @sqlsrv_query($conn, $sql);
}

function fee_row_to_array($row) {
    return fee_defaults([
        'fee_id' => (string)($row['fee_id'] ?? ''),
        'class' => (string)($row['class_name'] ?? ''),
        'monthly_fee' => (string)((float)($row['monthly_fee'] ?? 0)),
        'annual_fee' => (string)((float)($row['annual_fee'] ?? 0)),
        'special_charges' => (string)($row['special_charges'] ?? ''),
        'discount' => (string)((float)($row['discount'] ?? 0)),
        'description' => (string)($row['description'] ?? '')
    ]);
}

function fees_get_all() {
    $conn = sqlsrv_get_connection();
    if ($conn) {
        fees_ensure_db_table($conn);
        $sql = "SELECT fee_id, class_name, monthly_fee, annual_fee, special_charges, discount, description
                FROM dbo.fees
                WHERE deleted = 0
                ORDER BY id ASC";
        $stmt = @sqlsrv_query($conn, $sql);
        if ($stmt !== false) {
            $rows = [];
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                $rows[] = fee_row_to_array($row);
            }
            if (!empty($rows)) {
                return $rows;
            }
        }
    }

    $json = get_json_data(FEES_JSON, []);
    if (!is_array($json)) return [];

    $mapped = [];
    foreach ($json as $idx => $row) {
        $row = is_array($row) ? $row : [];
        $mapped[] = fee_defaults([
            'fee_id' => (string)($row['fee_id'] ?? ('fee_' . ($idx + 1))),
            'class' => (string)($row['class'] ?? ''),
            'monthly_fee' => (string)($row['monthly_fee'] ?? '0'),
            'annual_fee' => (string)($row['annual_fee'] ?? '0'),
            'special_charges' => (string)($row['special_charges'] ?? ''),
            'discount' => (string)($row['discount'] ?? '0'),
            'description' => (string)($row['description'] ?? '')
        ]);
    }
    return $mapped;
}

function fees_save_json_fallback($fees) {
    $payload = [];
    foreach ((array)$fees as $fee) {
        $payload[] = [
            'class' => (string)($fee['class'] ?? ''),
            'monthly_fee' => (string)($fee['monthly_fee'] ?? '0'),
            'annual_fee' => (string)($fee['annual_fee'] ?? '0'),
            'special_charges' => (string)($fee['special_charges'] ?? ''),
            'discount' => (string)($fee['discount'] ?? '0'),
            'description' => (string)($fee['description'] ?? '')
        ];
    }
    return save_json_file(FEES_JSON, $payload);
}

function fees_upsert_by_class($data) {
    $fee = fee_defaults($data);
    if ($fee['class'] === '') return false;

    $all = fees_get_all();
    $existing = null;
    foreach ($all as $f) {
        if (strcasecmp((string)$f['class'], (string)$fee['class']) === 0) {
            $existing = $f;
            break;
        }
    }

    if ($fee['fee_id'] === '') {
        $fee['fee_id'] = $existing['fee_id'] ?? ('fee_' . round(microtime(true) * 1000));
    }

    $conn = sqlsrv_get_connection();
    if ($conn) {
        fees_ensure_db_table($conn);
        if ($existing) {
            $sql = "UPDATE dbo.fees
                    SET monthly_fee=?, annual_fee=?, special_charges=?, discount=?, description=?, deleted=0, updated_at=SYSUTCDATETIME()
                    WHERE class_name=?";
            $stmt = @sqlsrv_query($conn, $sql, [
                (float)$fee['monthly_fee'],
                (float)$fee['annual_fee'],
                (string)$fee['special_charges'],
                (float)$fee['discount'],
                (string)$fee['description'],
                (string)$fee['class']
            ]);
            if ($stmt === false) return false;
        } else {
            $sql = "INSERT INTO dbo.fees
                    (fee_id, class_name, monthly_fee, annual_fee, special_charges, discount, description, deleted, created_at, updated_at)
                    VALUES (?, ?, ?, ?, ?, ?, ?, 0, SYSUTCDATETIME(), SYSUTCDATETIME())";
            $stmt = @sqlsrv_query($conn, $sql, [
                (string)$fee['fee_id'],
                (string)$fee['class'],
                (float)$fee['monthly_fee'],
                (float)$fee['annual_fee'],
                (string)$fee['special_charges'],
                (float)$fee['discount'],
                (string)$fee['description']
            ]);
            if ($stmt === false) return false;
        }
    }

    $updated = false;
    foreach ($all as &$row) {
        if (strcasecmp((string)$row['class'], (string)$fee['class']) === 0) {
            $row = $fee;
            $updated = true;
            break;
        }
    }
    if (!$updated) $all[] = $fee;
    fees_save_json_fallback($all);
    return true;
}

function fees_delete_by_class($className) {
    $className = trim((string)$className);
    if ($className === '') return false;

    $conn = sqlsrv_get_connection();
    if ($conn) {
        fees_ensure_db_table($conn);
        $sql = "UPDATE dbo.fees SET deleted=1, updated_at=SYSUTCDATETIME() WHERE class_name=?";
        @sqlsrv_query($conn, $sql, [$className]);
    }

    $all = fees_get_all();
    $found = false;
    foreach ($all as $i => $row) {
        if (strcasecmp((string)$row['class'], $className) === 0) {
            array_splice($all, $i, 1);
            $found = true;
            break;
        }
    }
    if ($found) fees_save_json_fallback($all);
    return $found;
}

function fees_replace_all($rows) {
    if (!is_array($rows)) return false;

    $normalized = [];
    foreach ($rows as $row) {
        if (!is_array($row)) continue;
        $className = trim((string)($row['class'] ?? ''));
        if ($className === '') continue;
        $normalized[] = fee_defaults([
            'fee_id' => (string)($row['fee_id'] ?? ''),
            'class' => $className,
            'monthly_fee' => (string)($row['monthly_fee'] ?? '0'),
            'annual_fee' => (string)($row['annual_fee'] ?? '0'),
            'special_charges' => (string)($row['special_charges'] ?? ''),
            'discount' => (string)($row['discount'] ?? '0'),
            'description' => (string)($row['description'] ?? '')
        ]);
    }

    $conn = sqlsrv_get_connection();
    if ($conn) {
        fees_ensure_db_table($conn);
        @sqlsrv_query($conn, "UPDATE dbo.fees SET deleted=1, updated_at=SYSUTCDATETIME()");
        foreach ($normalized as $fee) {
            $feeId = $fee['fee_id'] !== '' ? $fee['fee_id'] : ('fee_' . round(microtime(true) * 1000) . '_' . mt_rand(100, 999));
            $sql = "
IF EXISTS (SELECT 1 FROM dbo.fees WHERE class_name=?)
BEGIN
    UPDATE dbo.fees
    SET fee_id=?, monthly_fee=?, annual_fee=?, special_charges=?, discount=?, description=?, deleted=0, updated_at=SYSUTCDATETIME()
    WHERE class_name=?
END
ELSE
BEGIN
    INSERT INTO dbo.fees (fee_id, class_name, monthly_fee, annual_fee, special_charges, discount, description, deleted, created_at, updated_at)
    VALUES (?, ?, ?, ?, ?, ?, ?, 0, SYSUTCDATETIME(), SYSUTCDATETIME())
END
";
            $params = [
                $fee['class'],
                $feeId, (float)$fee['monthly_fee'], (float)$fee['annual_fee'], (string)$fee['special_charges'], (float)$fee['discount'], (string)$fee['description'], $fee['class'],
                $feeId, $fee['class'], (float)$fee['monthly_fee'], (float)$fee['annual_fee'], (string)$fee['special_charges'], (float)$fee['discount'], (string)$fee['description']
            ];
            @sqlsrv_query($conn, $sql, $params);
        }
    }

    return fees_save_json_fallback($normalized);
}

function fees_to_table_payload($fees) {
    $columns = ['Class', 'Monthly Fee', 'Annual Fee', 'Special Charges', 'Discount (%)', 'Description'];
    $rows = [];
    foreach ((array)$fees as $fee) {
        $rows[] = [
            (string)($fee['class'] ?? ''),
            (float)($fee['monthly_fee'] ?? 0),
            (float)($fee['annual_fee'] ?? 0),
            (string)($fee['special_charges'] ?? ''),
            (float)($fee['discount'] ?? 0),
            (string)($fee['description'] ?? '')
        ];
    }

    return [
        'subtitle' => 'Updated fee structure for current academic session',
        'columns' => $columns,
        'rows' => $rows,
        'items' => array_values((array)$fees)
    ];
}

