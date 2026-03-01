<?php
header('Content-Type: application/json');

$jsonFile = "../json/toppersData.json";
$data = json_decode(file_get_contents($jsonFile), true);

$input = json_decode(file_get_contents("php://input"), true);
$year = $input['year'];
$index = $input['index'];
$restore = $input['restore'];

if (isset($data[$year][$index])) {
    $data[$year][$index]['deleted'] = $restore ? false : true;
    file_put_contents($jsonFile, json_encode($data, JSON_PRETTY_PRINT));
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => "Invalid data."]);
}
