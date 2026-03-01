<?php
require_once 'config.php';
require_once 'helpers.php';
require_once 'fees_repository.php';

header('Content-Type: application/json');

$fees = fees_get_all();
echo json_encode(fees_to_table_payload($fees), JSON_UNESCAPED_SLASHES);
exit;

