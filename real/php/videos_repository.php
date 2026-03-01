<?php
require_once 'config.php';
require_once 'helpers.php';
require_once 'db_sqlsrv.php';

function videos_ensure_db_table($conn) {
    $sql = "
IF OBJECT_ID('dbo.videos', 'U') IS NULL
BEGIN
    CREATE TABLE dbo.videos (
        id INT IDENTITY(1,1) PRIMARY KEY,
        video_id NVARCHAR(80) NOT NULL UNIQUE,
        title NVARCHAR(255) NOT NULL,
        image_path NVARCHAR(1000) NOT NULL,
        video_path NVARCHAR(1000) NOT NULL,
        category NVARCHAR(100) NOT NULL,
        is_youtube BIT NOT NULL DEFAULT 0,
        deleted BIT NOT NULL DEFAULT 0,
        created_at DATETIME2 NOT NULL DEFAULT SYSUTCDATETIME(),
        updated_at DATETIME2 NOT NULL DEFAULT SYSUTCDATETIME()
    );
END
";
    @sqlsrv_query($conn, $sql);
}

function videos_get_all() {
    $conn = sqlsrv_get_connection();
    if ($conn) {
        videos_ensure_db_table($conn);
        $stmt = @sqlsrv_query($conn, "SELECT video_id,title,image_path,video_path,category,is_youtube FROM dbo.videos WHERE deleted=0 ORDER BY id DESC");
        if ($stmt !== false) {
            $rows = [];
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                $rows[] = [
                    'video_id' => (string)($row['video_id'] ?? ''),
                    'title' => (string)($row['title'] ?? ''),
                    'image_path' => (string)($row['image_path'] ?? ''),
                    'video_path' => (string)($row['video_path'] ?? ''),
                    'category' => strtolower((string)($row['category'] ?? 'other')),
                    'youtube' => !empty($row['is_youtube'])
                ];
            }
            if (!empty($rows)) return $rows;
        }
    }

    $json = get_json_data(VIDEOS_JSON, []);
    if (!is_array($json)) return [];
    $mapped = [];
    foreach ($json as $i => $row) {
        $mapped[] = [
            'video_id' => (string)($row['video_id'] ?? ('video_' . ($i + 1))),
            'title' => (string)($row['title'] ?? ''),
            'image_path' => (string)($row['image_path'] ?? ''),
            'video_path' => (string)($row['video_path'] ?? ''),
            'category' => strtolower((string)($row['category'] ?? 'other')),
            'youtube' => !empty($row['youtube'])
        ];
    }
    return $mapped;
}

function videos_save_json($videos) {
    $payload = [];
    foreach ((array)$videos as $v) {
        $payload[] = [
            'title' => (string)($v['title'] ?? ''),
            'image_path' => (string)($v['image_path'] ?? ''),
            'video_path' => (string)($v['video_path'] ?? ''),
            'category' => strtolower((string)($v['category'] ?? 'other')),
            'youtube' => !empty($v['youtube'])
        ];
    }
    return save_json_file(VIDEOS_JSON, $payload);
}

function videos_add($data) {
    $video = [
        'video_id' => 'video_' . round(microtime(true) * 1000),
        'title' => (string)($data['title'] ?? ''),
        'image_path' => (string)($data['image_path'] ?? ''),
        'video_path' => (string)($data['video_path'] ?? ''),
        'category' => strtolower((string)($data['category'] ?? 'other')),
        'youtube' => !empty($data['youtube'])
    ];

    $conn = sqlsrv_get_connection();
    if ($conn) {
        videos_ensure_db_table($conn);
        $stmt = @sqlsrv_query($conn,
            "INSERT INTO dbo.videos (video_id,title,image_path,video_path,category,is_youtube,deleted,created_at,updated_at) VALUES (?,?,?,?,?,?,0,SYSUTCDATETIME(),SYSUTCDATETIME())",
            [$video['video_id'], $video['title'], $video['image_path'], $video['video_path'], $video['category'], $video['youtube'] ? 1 : 0]
        );
        if ($stmt === false) return false;
    }

    $all = videos_get_all();
    array_unshift($all, $video);
    return videos_save_json($all);
}

function videos_update($video_id, $data) {
    $video_id = trim((string)$video_id);
    if ($video_id === '') return false;

    $all = videos_get_all();
    $found = false;
    foreach ($all as &$v) {
        if (($v['video_id'] ?? '') === $video_id) {
            $v['title'] = (string)($data['title'] ?? $v['title']);
            $v['category'] = strtolower((string)($data['category'] ?? $v['category']));
            $v['video_path'] = (string)($data['video_path'] ?? $v['video_path']);
            $v['youtube'] = isset($data['youtube']) ? !empty($data['youtube']) : !empty($v['youtube']);
            if (!empty($data['image_path'])) $v['image_path'] = (string)$data['image_path'];
            $found = true;
            break;
        }
    }
    if (!$found) return false;

    $conn = sqlsrv_get_connection();
    if ($conn) {
        videos_ensure_db_table($conn);
        @sqlsrv_query($conn,
            "UPDATE dbo.videos SET title=?, image_path=?, video_path=?, category=?, is_youtube=?, updated_at=SYSUTCDATETIME() WHERE video_id=?",
            [$v['title'], $v['image_path'], $v['video_path'], $v['category'], $v['youtube'] ? 1 : 0, $video_id]
        );
    }

    return videos_save_json($all);
}

function videos_delete($video_id) {
    $video_id = trim((string)$video_id);
    if ($video_id === '') return false;

    $all = videos_get_all();
    $found = false;
    foreach ($all as $i => $v) {
        if (($v['video_id'] ?? '') === $video_id) {
            array_splice($all, $i, 1);
            $found = true;
            break;
        }
    }
    if (!$found) return false;

    $conn = sqlsrv_get_connection();
    if ($conn) {
        videos_ensure_db_table($conn);
        @sqlsrv_query($conn, "UPDATE dbo.videos SET deleted=1, updated_at=SYSUTCDATETIME() WHERE video_id=?", [$video_id]);
    }

    return videos_save_json($all);
}

