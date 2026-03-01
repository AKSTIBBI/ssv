<?php
require_once 'config.php';
require_once 'helpers.php';
require_once 'db_sqlsrv.php';

function photos_ensure_db_table($conn) {
    $sql = "
IF OBJECT_ID('dbo.photos', 'U') IS NULL
BEGIN
    CREATE TABLE dbo.photos (
        id INT IDENTITY(1,1) PRIMARY KEY,
        photo_id NVARCHAR(80) NOT NULL UNIQUE,
        title NVARCHAR(255) NOT NULL,
        image_path NVARCHAR(1000) NOT NULL,
        category NVARCHAR(100) NOT NULL,
        deleted BIT NOT NULL DEFAULT 0,
        created_at DATETIME2 NOT NULL DEFAULT SYSUTCDATETIME(),
        updated_at DATETIME2 NOT NULL DEFAULT SYSUTCDATETIME()
    );
END
";
    @sqlsrv_query($conn, $sql);
}

function photos_get_all() {
    $conn = sqlsrv_get_connection();
    if ($conn) {
        photos_ensure_db_table($conn);
        $stmt = @sqlsrv_query($conn, "SELECT photo_id, title, image_path, category FROM dbo.photos WHERE deleted=0 ORDER BY id DESC");
        if ($stmt !== false) {
            $rows = [];
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                $rows[] = [
                    'photo_id' => (string)($row['photo_id'] ?? ''),
                    'title' => (string)($row['title'] ?? ''),
                    'image_path' => (string)($row['image_path'] ?? ''),
                    'category' => strtolower((string)($row['category'] ?? 'uncategorized'))
                ];
            }
            if (!empty($rows)) return $rows;
        }
    }

    $json = get_json_data(PHOTOS_JSON, []);
    if (!is_array($json)) return [];
    $mapped = [];
    foreach ($json as $i => $row) {
        $mapped[] = [
            'photo_id' => (string)($row['photo_id'] ?? ('photo_' . ($i + 1))),
            'title' => (string)($row['title'] ?? ''),
            'image_path' => (string)($row['image_path'] ?? ''),
            'category' => strtolower((string)($row['category'] ?? 'uncategorized'))
        ];
    }
    return $mapped;
}

function photos_save_json($photos) {
    $payload = [];
    foreach ((array)$photos as $p) {
        $payload[] = [
            'title' => (string)($p['title'] ?? ''),
            'image_path' => (string)($p['image_path'] ?? ''),
            'category' => strtolower((string)($p['category'] ?? 'uncategorized'))
        ];
    }
    return save_json_file(PHOTOS_JSON, $payload);
}

function photos_add($data) {
    $photo = [
        'photo_id' => 'photo_' . round(microtime(true) * 1000),
        'title' => (string)($data['title'] ?? ''),
        'image_path' => (string)($data['image_path'] ?? ''),
        'category' => strtolower((string)($data['category'] ?? 'uncategorized'))
    ];

    $conn = sqlsrv_get_connection();
    if ($conn) {
        photos_ensure_db_table($conn);
        $stmt = @sqlsrv_query($conn,
            "INSERT INTO dbo.photos (photo_id,title,image_path,category,deleted,created_at,updated_at) VALUES (?,?,?,?,0,SYSUTCDATETIME(),SYSUTCDATETIME())",
            [$photo['photo_id'], $photo['title'], $photo['image_path'], $photo['category']]
        );
        if ($stmt === false) return false;
    }

    $all = photos_get_all();
    array_unshift($all, $photo);
    return photos_save_json($all);
}

function photos_update($photo_id, $data) {
    $photo_id = trim((string)$photo_id);
    if ($photo_id === '') return false;

    $all = photos_get_all();
    $found = false;
    foreach ($all as &$p) {
        if (($p['photo_id'] ?? '') === $photo_id) {
            $p['title'] = (string)($data['title'] ?? $p['title']);
            $p['category'] = strtolower((string)($data['category'] ?? $p['category']));
            if (!empty($data['image_path'])) $p['image_path'] = (string)$data['image_path'];
            $found = true;
            break;
        }
    }
    if (!$found) return false;

    $conn = sqlsrv_get_connection();
    if ($conn) {
        photos_ensure_db_table($conn);
        @sqlsrv_query($conn,
            "UPDATE dbo.photos SET title=?, image_path=?, category=?, updated_at=SYSUTCDATETIME() WHERE photo_id=?",
            [$p['title'], $p['image_path'], $p['category'], $photo_id]
        );
    }

    return photos_save_json($all);
}

function photos_delete($photo_id) {
    $photo_id = trim((string)$photo_id);
    if ($photo_id === '') return false;

    $all = photos_get_all();
    $found = false;
    foreach ($all as $i => $p) {
        if (($p['photo_id'] ?? '') === $photo_id) {
            array_splice($all, $i, 1);
            $found = true;
            break;
        }
    }
    if (!$found) return false;

    $conn = sqlsrv_get_connection();
    if ($conn) {
        photos_ensure_db_table($conn);
        @sqlsrv_query($conn, "UPDATE dbo.photos SET deleted=1, updated_at=SYSUTCDATETIME() WHERE photo_id=?", [$photo_id]);
    }

    return photos_save_json($all);
}

