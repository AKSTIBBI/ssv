<?php
require_once 'config.php';
require_once 'helpers.php';
require_once 'notices_repository.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    echo json_encode(notices_get_all(), JSON_UNESCAPED_SLASHES);
    exit;
}

// 1. Get raw JSON input
$input = file_get_contents("php://input");
$data = json_decode($input, true);

// 2. Validate input
if (!$data) {
    echo json_encode(["message" => "Invalid request"]);
    exit;
}

// ==============================
// === DELETE NOTICE LOGIC =====
// ==============================
if (isset($data['action']) && $data['action'] === 'delete') {
    if (!isset($data['notice_id'])) {
        echo json_encode(["message" => "Missing notice_id for deletion"]);
        exit;
    }

    $ok = notices_set_deleted($data['notice_id'], true);
    if (!$ok) {
        echo json_encode(["message" => "Notice ID not found"]);
        exit;
    }

    echo json_encode(["message" => "Notice marked as deleted"]);
    exit;
}

// ==============================
// === ADD NOTICE LOGIC =========
// ==============================

// 6. Validate required fields
if (!isset($data['title']) || !isset($data['description'])) {
    echo json_encode(["message" => "Invalid data received", "error" => $input]);
    exit;
}

[$ok, $notice_id] = notices_add($data);
if (!$ok) {
    echo json_encode(["message" => "Failed to save notice. Check file permissions."]);
} else {
    echo json_encode(["message" => "Notice added successfully!", "notice_id" => $notice_id]);
}
