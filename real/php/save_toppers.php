<?php
require_once 'config.php';
require_once 'helpers.php';
require_once 'toppers_repository.php';

header('Content-Type: application/json');

$input = file_get_contents("php://input");
$data = json_decode($input, true);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["success" => false, "message" => "Invalid request method."]);
    exit;
}

if (!is_array($data)) {
    echo json_encode(["success" => false, "message" => "Invalid data."]);
    exit;
}

$ok = toppers_replace_all_grouped($data);
if ($ok) {
    echo json_encode(["success" => true, "message" => "Toppers updated successfully."]);
} else {
    echo json_encode(["success" => false, "message" => "Failed to save topper data."]);
}
?>
