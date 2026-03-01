<?php
require_once 'config.php';
require_once 'helpers.php';
require_once 'financials_repository.php';

header('Content-Type: application/json');

$docs = financials_get_all();
echo json_encode($docs, JSON_UNESCAPED_SLASHES);
exit;

