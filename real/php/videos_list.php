<?php
require_once 'config.php';
require_once 'helpers.php';
require_once 'videos_repository.php';

header('Content-Type: application/json');

$videos = videos_get_all();
echo json_encode($videos, JSON_UNESCAPED_SLASHES);
exit;

