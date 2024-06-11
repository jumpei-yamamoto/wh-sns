<?php
require_once __DIR__ . '/load_env.php';
load_env(__DIR__ . '/.env');

$allowed_origin = getenv('ALLOWED_ORIGIN');

header("Access-Control-Allow-Origin: $allowed_origin");
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
header('Access-Control-Allow-Credentials: true');
header('Content-Type: application/json');
?>
