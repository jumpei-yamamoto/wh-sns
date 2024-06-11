<?php
session_start();
include 'config.php';
include 'funcs.php';



if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

$userId = $_SESSION['user_id'] ?? null;

if ($userId) {
    $pdo = db_conn();

    $query = "SELECT * FROM notifications WHERE user_id = :userId ORDER BY created_at DESC";
    $stmt = $pdo->prepare($query);
    $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['notifications' => $result]);
} else {
    echo json_encode(['notifications' => []]);
}
?>
