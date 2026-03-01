<?php
$targetDir = "../images/toppers/";
$response = ["success" => false, "message" => "", "imagePath" => ""];

if (isset($_FILES["topperImage"])) {
    $file = $_FILES["topperImage"];
    $ext = pathinfo($file["name"], PATHINFO_EXTENSION);
    $uniqueName = uniqid("topper_") . "." . $ext;
    $targetFile = $targetDir . $uniqueName;

    if (move_uploaded_file($file["tmp_name"], $targetFile)) {
        $response["success"] = true;
        $response["imagePath"] = "real/images/toppers/" . $uniqueName;
    } else {
        $response["message"] = "Upload failed.";
    }
} else {
    $response["message"] = "No file uploaded.";
}

echo json_encode($response);
?>
