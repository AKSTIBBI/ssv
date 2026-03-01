<?php
require_once 'config.php';
require_once 'helpers.php';
require_once 'photos_repository.php';

header('Content-Type: application/json');

$photos = photos_get_all();
echo json_encode($photos, JSON_UNESCAPED_SLASHES);
exit;

