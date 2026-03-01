<?php
require_once 'config.php';
require_once 'helpers.php';
require_once 'fees_repository.php';

header('Content-Type: application/json');

$input = file_get_contents("php://input");
$data = json_decode($input, true);

if (!$data || !is_array($data)) {
    echo json_encode(["success" => false, "message" => "Invalid data received"]);
    exit;
}

$rows = [];

// Legacy payload support: {columns, rows}
if (isset($data['rows']) && is_array($data['rows'])) {
    foreach ($data['rows'] as $row) {
        if (!is_array($row)) continue;
        $rows[] = [
            'class' => (string)($row[0] ?? ''),
            'monthly_fee' => (string)($row[1] ?? '0'),
            'annual_fee' => (string)($row[2] ?? '0'),
            'special_charges' => (string)($row[3] ?? ''),
            'discount' => (string)($row[4] ?? '0'),
            'description' => (string)($row[5] ?? '')
        ];
    }
} elseif (isset($data['items']) && is_array($data['items'])) {
    $rows = $data['items'];
} elseif (array_values($data) === $data) {
    $rows = $data;
}

if (empty($rows)) {
    echo json_encode(["success" => false, "message" => "No valid fee rows found."]);
} else {
    $ok = fees_replace_all($rows);
    echo json_encode([
        "success" => $ok,
        "message" => $ok ? "Fee structure updated successfully!" : "Failed to save fee structure."
    ]);
}
?>
