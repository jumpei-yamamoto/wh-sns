<?php
session_start();
include 'config.php';
include 'funcs.php';



if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

$senderId = $_SESSION['user_id'] ?? null;
$receiverId = $_GET['receiverId'] ?? null;

if ($senderId && $receiverId) {
    $pdo = db_conn();

    $query = "SELECT messages.id, messages.content, messages.created_at, sender.username as sender, receiver.username as receiver 
              FROM messages 
              JOIN users as sender ON messages.sender_id = sender.id 
              JOIN users as receiver ON messages.receiver_id = receiver.id 
              WHERE (messages.sender_id = :senderId AND messages.receiver_id = :receiverId) 
              OR (messages.sender_id = :receiverId AND messages.receiver_id = :senderId)
              ORDER BY messages.created_at ASC";
    $stmt = $pdo->prepare($query);
    $stmt->bindValue(':senderId', $senderId, PDO::PARAM_INT);
    $stmt->bindValue(':receiverId', $receiverId, PDO::PARAM_INT);
    $stmt->execute();
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['messages' => $messages]);
} else {
    echo json_encode(['messages' => []]);
}
?>
