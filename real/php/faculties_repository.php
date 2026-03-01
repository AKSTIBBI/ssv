<?php
require_once 'config.php';
require_once 'helpers.php';
require_once 'db_sqlsrv.php';

function faculty_defaults($overrides = []) {
    $base = [
        'faculty_id' => '',
        'name' => '',
        'title' => '',
        'image' => ''
    ];
    return array_merge($base, is_array($overrides) ? $overrides : []);
}

function faculties_ensure_db_table($conn) {
    $sql = "
IF OBJECT_ID('dbo.faculties', 'U') IS NULL
BEGIN
    CREATE TABLE dbo.faculties (
        id INT IDENTITY(1,1) PRIMARY KEY,
        faculty_id NVARCHAR(80) NOT NULL UNIQUE,
        [name] NVARCHAR(255) NOT NULL,
        [title] NVARCHAR(255) NOT NULL,
        [image] NVARCHAR(500) NOT NULL,
        deleted BIT NOT NULL DEFAULT 0,
        created_at DATETIME2 NOT NULL DEFAULT SYSUTCDATETIME(),
        updated_at DATETIME2 NOT NULL DEFAULT SYSUTCDATETIME()
    );
END
";
    @sqlsrv_query($conn, $sql);
}

function faculty_row_to_array($row) {
    return faculty_defaults([
        'faculty_id' => (string)($row['faculty_id'] ?? ''),
        'name' => (string)($row['name'] ?? ''),
        'title' => (string)($row['title'] ?? ''),
        'image' => (string)($row['image'] ?? '')
    ]);
}

function faculties_get_all($include_deleted = false) {
    $conn = sqlsrv_get_connection();
    if ($conn) {
        faculties_ensure_db_table($conn);
        $sql = "SELECT faculty_id, [name], [title], [image], deleted FROM dbo.faculties";
        if (!$include_deleted) {
            $sql .= " WHERE deleted = 0";
        }
        $sql .= " ORDER BY id ASC";
        $stmt = @sqlsrv_query($conn, $sql);
        if ($stmt !== false) {
            $rows = [];
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                if (!$include_deleted && !empty($row['deleted'])) {
                    continue;
                }
                $rows[] = faculty_row_to_array($row);
            }
            if (!empty($rows)) {
                return $rows;
            }
        }
    }

    $json = get_json_data(FACULTY_JSON, []);
    if (!is_array($json)) {
        return [];
    }

    $mapped = [];
    foreach ($json as $idx => $row) {
        $row = is_array($row) ? $row : [];
        $mapped[] = faculty_defaults([
            'faculty_id' => (string)($row['faculty_id'] ?? ('fac_' . ($idx + 1))),
            'name' => (string)($row['name'] ?? ''),
            'title' => (string)($row['title'] ?? ''),
            'image' => (string)($row['image'] ?? '')
        ]);
    }
    return $mapped;
}

function faculties_save_json_fallback($faculties) {
    $json_payload = [];
    foreach ((array)$faculties as $faculty) {
        $json_payload[] = [
            'name' => (string)($faculty['name'] ?? ''),
            'title' => (string)($faculty['title'] ?? ''),
            'image' => (string)($faculty['image'] ?? '')
        ];
    }
    return save_json_file(FACULTY_JSON, $json_payload);
}

function faculties_add($data) {
    $faculty = faculty_defaults($data);
    if ($faculty['faculty_id'] === '') {
        $faculty['faculty_id'] = 'fac_' . round(microtime(true) * 1000);
    }

    $conn = sqlsrv_get_connection();
    if ($conn) {
        faculties_ensure_db_table($conn);
        $sql = "INSERT INTO dbo.faculties (faculty_id, [name], [title], [image], deleted, created_at, updated_at)
                VALUES (?, ?, ?, ?, 0, SYSUTCDATETIME(), SYSUTCDATETIME())";
        $params = [
            $faculty['faculty_id'],
            $faculty['name'],
            $faculty['title'],
            $faculty['image']
        ];
        $stmt = @sqlsrv_query($conn, $sql, $params);
        if ($stmt === false) {
            return [false, null];
        }
    }

    $faculties = faculties_get_all(true);
    $faculties[] = $faculty;
    faculties_save_json_fallback($faculties);
    return [true, $faculty['faculty_id']];
}

function faculties_update($faculty_id, $data) {
    $faculty_id = (string)$faculty_id;
    if ($faculty_id === '') {
        return false;
    }

    $payload = faculty_defaults($data);
    $updatedDb = false;
    $conn = sqlsrv_get_connection();
    if ($conn) {
        faculties_ensure_db_table($conn);
        $sql = "UPDATE dbo.faculties
                SET [name]=?, [title]=?, [image]=?, updated_at=SYSUTCDATETIME()
                WHERE faculty_id=?";
        $stmt = @sqlsrv_query($conn, $sql, [
            $payload['name'],
            $payload['title'],
            $payload['image'],
            $faculty_id
        ]);
        if ($stmt !== false) {
            $updatedDb = true;
        }
    }

    $faculties = faculties_get_all(true);
    $found = false;
    foreach ($faculties as &$f) {
        if (($f['faculty_id'] ?? '') === $faculty_id) {
            $f['name'] = $payload['name'];
            $f['title'] = $payload['title'];
            $f['image'] = $payload['image'];
            $found = true;
            break;
        }
    }
    if ($found) {
        faculties_save_json_fallback($faculties);
    }

    return $found || $updatedDb;
}

function faculties_set_deleted($faculty_id, $deleted) {
    $faculty_id = (string)$faculty_id;
    if ($faculty_id === '') {
        return false;
    }

    $flag = $deleted ? 1 : 0;
    $updatedDb = false;
    $conn = sqlsrv_get_connection();
    if ($conn) {
        faculties_ensure_db_table($conn);
        $sql = "UPDATE dbo.faculties SET deleted=?, updated_at=SYSUTCDATETIME() WHERE faculty_id=?";
        $stmt = @sqlsrv_query($conn, $sql, [$flag, $faculty_id]);
        if ($stmt !== false) {
            $updatedDb = true;
        }
    }

    $faculties = faculties_get_all(true);
    $found = false;
    if ($deleted) {
        foreach ($faculties as $index => $f) {
            if (($f['faculty_id'] ?? '') === $faculty_id) {
                array_splice($faculties, $index, 1);
                $found = true;
                break;
            }
        }
    } else {
        // Restore support if needed in future; no-op for JSON fallback today.
        $found = $updatedDb;
    }

    if ($found) {
        faculties_save_json_fallback($faculties);
    }

    return $found || $updatedDb;
}

