<?php
header('Content-Type: application/json');

$targetDir = "../images/Toppers/";
$jsonFile = "../json/toppersData.json";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ensure all required fields are present
    $requiredFields = ['year', 'name', 'class', 'rank'];
    foreach ($requiredFields as $field) {
        if (empty($_POST[$field])) {
            echo json_encode(["success" => false, "message" => "Missing required field: $field"]);
            exit;
        }
    }

    $year = $_POST['year'];
    $name = $_POST['name'];
    $class = $_POST['class'];
    $rank = $_POST['rank'];

    // Handle file upload
    if (!isset($_FILES['image']) || $_FILES['image']['error'] !== 0) {
        echo json_encode(["success" => false, "message" => "Image upload failed."]);
        exit;
    }

    $imageName = basename($_FILES['image']['name']);
    $imagePath = $targetDir . $imageName;
    $finalImagePath = "real/images/Toppers/" . $imageName;

    if (!move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
        echo json_encode(["success" => false, "message" => "Failed to save uploaded image."]);
        exit;
    }

    // Load existing toppers data
    $data = file_exists($jsonFile) ? json_decode(file_get_contents($jsonFile), true) : [];

    if (!isset($data[$year])) {
        $data[$year] = [];
    }

    $data[$year][] = [
        "name" => $name,
        "class" => $class,
        "rank" => $rank,
        "image" => $finalImagePath
    ];

    if (file_put_contents($jsonFile, json_encode($data, JSON_PRETTY_PRINT))) {
        echo json_encode(["success" => true, "message" => "Topper added successfully."]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to save topper data."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request method."]);
}
?>
