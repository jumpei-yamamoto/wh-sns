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

    $query = "SELECT messages.id, messages.content, messages.created_at, users.username as sender 
              FROM messages 
              JOIN users ON messages.sender_id = users.id 
              WHERE messages.receiver_id = :userId 
              ORDER BY messages.created_at DESC";
    $stmt = $pdo->prepare($query);
    $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['messages' => $result]);
} else {
    echo json_encode(['messages' => []]);
}
?>
