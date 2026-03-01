<?php
header('Content-Type: application/json');

$input = file_get_contents("php://input");
$data = json_decode($input, true);

if (!$data || !isset($data['rows']) || !is_array($data['rows'])) {
    echo json_encode(["message" => "Invalid data received"]);
    exit;
}

$file = '../json/fees.json';

$result = file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));

if ($result === false) {
    echo json_encode(["message" => "Failed to save fee structure."]);
} else {
    echo json_encode(["message" => "Fee structure updated successfully!"]);
}
?>
