<?php
require_once 'config.php';
require_once 'helpers.php';
require_once 'db_sqlsrv.php';

function toppers_ensure_db_table($conn) {
    $sql = "
IF OBJECT_ID('dbo.toppers', 'U') IS NULL
BEGIN
    CREATE TABLE dbo.toppers (
        id INT IDENTITY(1,1) PRIMARY KEY,
        topper_id NVARCHAR(80) NOT NULL UNIQUE,
        session_year INT NOT NULL,
        student_name NVARCHAR(255) NOT NULL,
        class_name NVARCHAR(255) NOT NULL,
        rank_text NVARCHAR(100) NOT NULL,
        image_path NVARCHAR(1000) NOT NULL,
        deleted BIT NOT NULL DEFAULT 0,
        created_at DATETIME2 NOT NULL DEFAULT SYSUTCDATETIME(),
        updated_at DATETIME2 NOT NULL DEFAULT SYSUTCDATETIME()
    );
END
";
    @sqlsrv_query($conn, $sql);
}

function toppers_normalize_grouped($grouped) {
    $normalized = [];
    if (!is_array($grouped)) return $normalized;

    foreach ($grouped as $year => $rows) {
        $yearKey = preg_match('/^\d{4}$/', (string)$year) ? (string)$year : '';
        if ($yearKey === '' || !is_array($rows)) continue;
        $normalized[$yearKey] = [];
        foreach ($rows as $row) {
            if (!is_array($row)) continue;
            $normalized[$yearKey][] = [
                'name' => (string)($row['name'] ?? ''),
                'class' => (string)($row['class'] ?? ''),
                'rank' => (string)($row['rank'] ?? ''),
                'image' => (string)($row['image'] ?? ''),
                'deleted' => !empty($row['deleted'])
            ];
        }
    }
    return $normalized;
}

function toppers_save_json_grouped($grouped) {
    $normalized = toppers_normalize_grouped($grouped);
    return save_json_file(TOPPERS_JSON, $normalized);
}

function toppers_get_grouped() {
    $conn = sqlsrv_get_connection();
    if ($conn) {
        toppers_ensure_db_table($conn);
        $sql = "SELECT session_year, student_name, class_name, rank_text, image_path, deleted
                FROM dbo.toppers
                ORDER BY session_year DESC, id ASC";
        $stmt = @sqlsrv_query($conn, $sql);
        if ($stmt !== false) {
            $grouped = [];
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                $year = (string)($row['session_year'] ?? '');
                if (!isset($grouped[$year])) $grouped[$year] = [];
                $grouped[$year][] = [
                    'name' => (string)($row['student_name'] ?? ''),
                    'class' => (string)($row['class_name'] ?? ''),
                    'rank' => (string)($row['rank_text'] ?? ''),
                    'image' => (string)($row['image_path'] ?? ''),
                    'deleted' => !empty($row['deleted'])
                ];
            }
            if (!empty($grouped)) {
                return $grouped;
            }
        }
    }

    $json = get_json_data(TOPPERS_JSON, []);
    return toppers_normalize_grouped($json);
}

function toppers_replace_all_grouped($grouped) {
    $normalized = toppers_normalize_grouped($grouped);

    $conn = sqlsrv_get_connection();
    if ($conn) {
        toppers_ensure_db_table($conn);
        @sqlsrv_query($conn, "DELETE FROM dbo.toppers");

        foreach ($normalized as $year => $rows) {
            foreach ($rows as $row) {
                $sql = "INSERT INTO dbo.toppers
                        (topper_id, session_year, student_name, class_name, rank_text, image_path, deleted, created_at, updated_at)
                        VALUES (?, ?, ?, ?, ?, ?, ?, SYSUTCDATETIME(), SYSUTCDATETIME())";
                $params = [
                    'top_' . round(microtime(true) * 1000) . '_' . mt_rand(100, 999),
                    (int)$year,
                    (string)$row['name'],
                    (string)$row['class'],
                    (string)$row['rank'],
                    (string)$row['image'],
                    !empty($row['deleted']) ? 1 : 0
                ];
                @sqlsrv_query($conn, $sql, $params);
            }
        }
    }

    return toppers_save_json_grouped($normalized);
}

function toppers_count_total($grouped = null, $exclude_deleted = false) {
    $data = is_array($grouped) ? $grouped : toppers_get_grouped();
    $count = 0;
    foreach ($data as $rows) {
        if (!is_array($rows)) continue;
        foreach ($rows as $row) {
            if ($exclude_deleted && !empty($row['deleted'])) continue;
            $count++;
        }
    }
    return $count;
}

