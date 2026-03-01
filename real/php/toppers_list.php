<?php
require_once 'config.php';
require_once 'helpers.php';
require_once 'toppers_repository.php';

header('Content-Type: application/json');

$grouped = toppers_get_grouped();
echo json_encode($grouped, JSON_UNESCAPED_SLASHES);
exit;

