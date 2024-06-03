<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

include 'config.php';

$senderId = $_GET['senderId'];
$receiverId = $_GET['receiverId'];

$query = "SELECT messages.id, messages.content, messages.created_at, sender.username as sender, receiver.username as receiver 
          FROM messages 
          JOIN users as sender ON messages.sender_id = sender.id 
          JOIN users as receiver ON messages.receiver_id = receiver.id 
          WHERE (messages.sender_id = ? AND messages.receiver_id = ?) OR (messages.sender_id = ? AND messages.receiver_id = ?)
          ORDER BY messages.created_at ASC";
$stmt = $conn->prepare($query);
$stmt->bind_param("iiii", $senderId, $receiverId, $receiverId, $senderId);
$stmt->execute();
$result = $stmt->get_result();

$messages = [];
while ($row = $result->fetch_assoc()) {
    $messages[] = $row;
}

echo json_encode(['messages' => $messages]);

$stmt->close();
$conn->close();
