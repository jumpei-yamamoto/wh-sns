<?php
session_start();

include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

if (!isset($_SESSION["chk_ssid"]) || $_SESSION["chk_ssid"] != session_id()) {
    echo json_encode(["profileComplete" => false]);
    exit();
}

$user_id = $_SESSION["user_id"];

include 'funcs.php';
$pdo = db_conn();

// Prepare and execute query to check profile completion status
$query = "SELECT profile_complete FROM users WHERE id = :id";
$stmt = $pdo->prepare($query);
$stmt->bindValue(':id', $user_id, PDO::PARAM_INT);
$status = $stmt->execute();

$response = ["profileComplete" => false];
if ($status == false) {
    sql_error($stmt);
} else {
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($result) {
        $response["profileComplete"] = $result["profile_complete"];
    }
}

echo json_encode($response);
?>
