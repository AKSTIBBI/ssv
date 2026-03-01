<?php
require_once 'config.php';
require_once 'helpers.php';
require_once 'db_sqlsrv.php';

function notice_defaults($overrides = []) {
    $base = [
        'notice_id' => '',
        'title' => '',
        'description' => '',
        'author' => 'Admin',
        'date' => date('d'),
        'month' => date('M'),
        'publish_date' => date('d-M-Y'),
        'deleted' => false
    ];
    $merged = array_merge($base, is_array($overrides) ? $overrides : []);
    $merged['deleted'] = !empty($merged['deleted']);
    return $merged;
}

function notices_ensure_db_table($conn) {
    $sql = "
IF OBJECT_ID('dbo.notices', 'U') IS NULL
BEGIN
    CREATE TABLE dbo.notices (
        id INT IDENTITY(1,1) PRIMARY KEY,
        notice_id NVARCHAR(80) NOT NULL UNIQUE,
        title NVARCHAR(255) NOT NULL,
        description NVARCHAR(MAX) NOT NULL,
        author NVARCHAR(255) NULL,
        [date] NVARCHAR(10) NULL,
        [month] NVARCHAR(10) NULL,
        publish_date NVARCHAR(30) NULL,
        deleted BIT NOT NULL DEFAULT 0,
        created_at DATETIME2 NOT NULL DEFAULT SYSUTCDATETIME(),
        updated_at DATETIME2 NOT NULL DEFAULT SYSUTCDATETIME()
    );
END
";
    @sqlsrv_query($conn, $sql);
}

function notice_row_to_array($row) {
    return notice_defaults([
        'notice_id' => (string)($row['notice_id'] ?? ''),
        'title' => (string)($row['title'] ?? ''),
        'description' => (string)($row['description'] ?? ''),
        'author' => (string)($row['author'] ?? 'Admin'),
        'date' => (string)($row['date'] ?? date('d')),
        'month' => (string)($row['month'] ?? date('M')),
        'publish_date' => (string)($row['publish_date'] ?? date('d-M-Y')),
        'deleted' => !empty($row['deleted'])
    ]);
}

function notices_get_all() {
    $conn = sqlsrv_get_connection();
    if ($conn) {
        notices_ensure_db_table($conn);
        $sql = "SELECT notice_id, title, description, author, [date], [month], publish_date, deleted FROM dbo.notices ORDER BY id DESC";
        $stmt = @sqlsrv_query($conn, $sql);
        if ($stmt !== false) {
            $rows = [];
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                $rows[] = notice_row_to_array($row);
            }
            if (!empty($rows)) {
                return $rows;
            }
        }
    }

    $json = get_json_data(NOTICES_JSON, []);
    return is_array($json) ? $json : [];
}

function notices_save_json_fallback($notices) {
    return save_json_file(NOTICES_JSON, $notices);
}

function notices_add($data) {
    $notice = notice_defaults($data);
    if (empty($notice['notice_id'])) {
        $notice['notice_id'] = 'notice_' . round(microtime(true) * 1000);
    }

    $conn = sqlsrv_get_connection();
    if ($conn) {
        notices_ensure_db_table($conn);
        $sql = "INSERT INTO dbo.notices (notice_id, title, description, author, [date], [month], publish_date, deleted, created_at, updated_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, SYSUTCDATETIME(), SYSUTCDATETIME())";
        $params = [
            $notice['notice_id'],
            $notice['title'],
            $notice['description'],
            $notice['author'],
            $notice['date'],
            $notice['month'],
            $notice['publish_date'],
            $notice['deleted'] ? 1 : 0
        ];
        $stmt = @sqlsrv_query($conn, $sql, $params);
        if ($stmt === false) return [false, null];
    }

    $notices = notices_get_all();
    array_unshift($notices, $notice);
    notices_save_json_fallback($notices);
    return [true, $notice['notice_id']];
}

function notices_update($notice_id, $data) {
    $notice_id = (string)$notice_id;
    if ($notice_id === '') return false;

    $conn = sqlsrv_get_connection();
    $updatedDb = false;
    if ($conn) {
        notices_ensure_db_table($conn);
        $sql = "UPDATE dbo.notices
                SET title=?, description=?, author=?, [date]=?, [month]=?, publish_date=?, updated_at=SYSUTCDATETIME()
                WHERE notice_id=?";
        $params = [
            (string)$data['title'],
            (string)$data['description'],
            (string)$data['author'],
            (string)$data['date'],
            (string)$data['month'],
            (string)$data['publish_date'],
            $notice_id
        ];
        $stmt = @sqlsrv_query($conn, $sql, $params);
        if ($stmt !== false) $updatedDb = true;
    }

    $notices = notices_get_all();
    $found = false;
    foreach ($notices as &$n) {
        if (($n['notice_id'] ?? '') === $notice_id) {
            $n['title'] = (string)$data['title'];
            $n['description'] = (string)$data['description'];
            $n['author'] = (string)$data['author'];
            $n['date'] = (string)$data['date'];
            $n['month'] = (string)$data['month'];
            $n['publish_date'] = (string)$data['publish_date'];
            $found = true;
            break;
        }
    }
    if ($found) notices_save_json_fallback($notices);
    return $found || $updatedDb;
}

function notices_set_deleted($notice_id, $deleted) {
    $notice_id = (string)$notice_id;
    if ($notice_id === '') return false;
    $flag = $deleted ? 1 : 0;

    $conn = sqlsrv_get_connection();
    $updatedDb = false;
    if ($conn) {
        notices_ensure_db_table($conn);
        $sql = "UPDATE dbo.notices SET deleted=?, updated_at=SYSUTCDATETIME() WHERE notice_id=?";
        $stmt = @sqlsrv_query($conn, $sql, [$flag, $notice_id]);
        if ($stmt !== false) $updatedDb = true;
    }

    $notices = notices_get_all();
    $found = false;
    foreach ($notices as &$n) {
        if (($n['notice_id'] ?? '') === $notice_id) {
            $n['deleted'] = (bool)$deleted;
            $found = true;
            break;
        }
    }
    if ($found) notices_save_json_fallback($notices);
    return $found || $updatedDb;
}

