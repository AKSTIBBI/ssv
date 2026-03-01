<?php
require_once 'config.php';
require_once 'helpers.php';
require_once 'notices_repository.php';

header('Content-Type: application/json');

$notices = notices_get_all();
echo json_encode($notices, JSON_UNESCAPED_SLASHES);
exit;

