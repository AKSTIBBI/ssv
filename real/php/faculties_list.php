<?php
require_once 'config.php';
require_once 'helpers.php';
require_once 'faculties_repository.php';

header('Content-Type: application/json');

$faculties = faculties_get_all();
echo json_encode($faculties, JSON_UNESCAPED_SLASHES);
exit;

