<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json');

include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $input = file_get_contents("php://input");
    $data = json_decode($input, true);

    // デバッグ用ログ出力
    error_log("Received data: " . print_r($data, true));

    $senderId = $data['senderId'];
    $receiverId = $data['receiverId'];
    $content = $data['content'];

    if ($senderId && $receiverId && $content) {
        $query = "INSERT INTO messages (sender_id, receiver_id, content) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("iis", $senderId, $receiverId, $content);

        if ($stmt->execute()) {
            // 通知を作成
            $notificationContent = "You have received a new message";
            $notificationQuery = "INSERT INTO notifications (user_id, type, content) VALUES (?, 'message', ?)";
            $notificationStmt = $conn->prepare($notificationQuery);
            $notificationStmt->bind_param("is", $receiverId, $notificationContent);
            $notificationStmt->execute();

            echo json_encode(['success' => true, 'message' => 'Message and notification sent successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error: ' . $stmt->error]);
        }

        $stmt->close();
        $conn->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid input data']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
