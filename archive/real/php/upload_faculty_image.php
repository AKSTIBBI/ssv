<?php
/**
 * Handle faculty photo uploads
 * Uploads images to real/images/faculties/
 */

header('Content-Type: application/json');

$targetDir = "../images/faculties/";
$response = ["success" => false, "message" => "", "imagePath" => ""];

// Allowed file types for images
$allowed_types = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
$max_file_size = 5 * 1024 * 1024; // 5 MB

if (!isset($_FILES["facultyImage"])) {
    $response["message"] = "No file uploaded.";
    echo json_encode($response);
    exit;
}

$file = $_FILES["facultyImage"];

// Check for upload errors
if ($file["error"] !== UPLOAD_ERR_OK) {
    switch ($file["error"]) {
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            $response["message"] = "File is too large. Maximum size: 5 MB.";
            break;
        case UPLOAD_ERR_NO_FILE:
            $response["message"] = "No file was uploaded.";
            break;
        default:
            $response["message"] = "Upload error. Please try again.";
    }
    echo json_encode($response);
    exit;
}

// Validate file size
if ($file["size"] > $max_file_size) {
    $response["message"] = "File is too large. Maximum size: 5 MB.";
    echo json_encode($response);
    exit;
}

// Get file extension
$ext = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));

// Validate file type
if (!in_array($ext, $allowed_types)) {
    $response["message"] = "File type not allowed. Allowed: " . implode(", ", $allowed_types);
    echo json_encode($response);
    exit;
}

// Create unique filename
$uniqueName = uniqid("faculty_") . "." . $ext;
$targetFile = $targetDir . $uniqueName;

// Ensure directory exists
if (!is_dir($targetDir)) {
    mkdir($targetDir, 0755, true);
}

// Move uploaded file
if (move_uploaded_file($file["tmp_name"], $targetFile)) {
    $response["success"] = true;
    $response["imagePath"] = "images/faculties/" . $uniqueName;
} else {
    $response["message"] = "Failed to save file. Please check directory permissions.";
}

echo json_encode($response);
?>
