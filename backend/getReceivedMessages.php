<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json');

include 'config.php';

$userId = $_GET['userId'];

$query = "SELECT messages.id, messages.content, messages.created_at, users.username as sender 
          FROM messages 
          JOIN users ON messages.sender_id = users.id 
          WHERE messages.receiver_id = ? 
          ORDER BY messages.created_at DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

$messages = [];
while ($row = $result->fetch_assoc()) {
    $messages[] = $row;
}

echo json_encode(['messages' => $messages]);

$stmt->close();
$conn->close();
?>
