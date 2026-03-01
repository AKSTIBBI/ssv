<?php
header('Content-Type: application/json');

// 1. Get raw JSON input
$input = file_get_contents("php://input");
$data = json_decode($input, true);

// 2. Validate input
if (!$data) {
    echo json_encode(["message" => "Invalid request"]);
    exit;
}

// 3. File path
$file = '../json/notices.json';

// 4. Check file existence
if (!file_exists($file)) {
    file_put_contents($file, json_encode([]));
}

// 5. Read existing notices
$notices = json_decode(file_get_contents($file), true);
if (!is_array($notices)) {
    $notices = [];
}

// ==============================
// === DELETE NOTICE LOGIC =====
// ==============================
if (isset($data['action']) && $data['action'] === 'delete') {
    if (!isset($data['notice_id'])) {
        echo json_encode(["message" => "Missing notice_id for deletion"]);
        exit;
    }

    $found = false;
    foreach ($notices as &$notice) {
        if (isset($notice['notice_id']) && $notice['notice_id'] === $data['notice_id']) {
            $notice['deleted'] = true;
            $found = true;
            break;
        }
    }

    if (!$found) {
        echo json_encode(["message" => "Notice ID not found"]);
        exit;
    }

    // 6. Save back to file
    $result = file_put_contents($file, json_encode($notices, JSON_PRETTY_PRINT));
    if ($result === false) {
        echo json_encode(["message" => "Failed to soft delete notice"]);
    } else {
        echo json_encode(["message" => "Notice marked as deleted"]);
    }
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

// 7. Generate unique ID
$notice_id = 'notice_' . round(microtime(true) * 1000);

// 8. Append ID and default deleted flag
$data['notice_id'] = $notice_id;
$data['deleted'] = false;

// 9. Prepend new notice
array_unshift($notices, $data);

// 10. Save back to file
$result = file_put_contents($file, json_encode($notices, JSON_PRETTY_PRINT));
if ($result === false) {
    echo json_encode(["message" => "Failed to save notice. Check file permissions."]);
} else {
    echo json_encode(["message" => "Notice added successfully!", "notice_id" => $notice_id]);
}
