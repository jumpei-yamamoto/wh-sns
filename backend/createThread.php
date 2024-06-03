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

    $title = $data['title'];
    $description = $data['description'];
    $userId = $data['userId'];

    if ($title && $description && $userId) {
        $query = "INSERT INTO threads (title, description, user_id) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssi", $title, $description, $userId);

        if ($stmt->execute()) {
            // 通知を作成
            $notificationContent = "New thread created: " . $title;
            $notificationQuery = "INSERT INTO notifications (user_id, type, content) VALUES (?, 'thread', ?)";
            $notificationStmt = $conn->prepare($notificationQuery);
            $notificationStmt->bind_param("is", $userId, $notificationContent);
            $notificationStmt->execute();

            echo json_encode(['success' => true, 'message' => 'Thread and notification created successfully']);
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
