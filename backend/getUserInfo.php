<?php
session_start();

include 'config.php';



if (!isset($_SESSION["user_id"])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit();
}

echo json_encode(['success' => true, 'userId' => $_SESSION["user_id"], 'username' => $_SESSION["name"]]);
?>
