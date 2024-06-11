<?php
require_once __DIR__ . '/load_env.php';
load_env(__DIR__ . '/.env');

function db_conn() {
    $db_name = getenv('DB_NAME');
    $db_id = getenv('DB_USER');
    $db_pw = getenv('DB_PASS');
    $db_host = getenv('DB_HOST');
    
    try {
        return new PDO('mysql:dbname=' . $db_name . ';charset=utf8;host=' . $db_host, $db_id, $db_pw);
    } catch (PDOException $e) {
        exit('DB Connection Error:' . $e->getMessage());
    }
}

function sql_error($stmt) {
    $error = $stmt->errorInfo();
    exit("SQLError:" . $error[2]);
}

function redirect($file_name) {
    header("Location: " . $file_name);
    exit();
}

function sschk() {
    if (!isset($_SESSION["chk_ssid"]) || $_SESSION["chk_ssid"] != session_id()) {
        exit("Login Error");
    } else {
        session_regenerate_id(true);
        $_SESSION["chk_ssid"] = session_id();
    }
}
?>
