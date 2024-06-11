<?php
session_start();
include 'config.php';
include 'funcs.php';


if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $input = file_get_contents("php://input");
    $data = json_decode($input, true);

    $senderId = $_SESSION['user_id'] ?? null;
    $receiverId = $data['receiverId'];
    $content = $data['content'];

    if ($senderId && $receiverId && $content) {
        $pdo = db_conn();

        $query = "INSERT INTO messages (sender_id, receiver_id, content) VALUES (:senderId, :receiverId, :content)";
        $stmt = $pdo->prepare($query);
        $stmt->bindValue(':senderId', $senderId, PDO::PARAM_INT);
        $stmt->bindValue(':receiverId', $receiverId, PDO::PARAM_INT);
        $stmt->bindValue(':content', $content, PDO::PARAM_STR);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Message sent successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error: ' . $stmt->errorInfo()[2]]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid input data']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
