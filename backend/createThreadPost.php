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

    $threadId = $data['threadId'];
    $userId = $data['userId'];
    $content = $data['content'];

    if ($threadId && $userId && $content) {
        $query = "INSERT INTO thread_posts (thread_id, user_id, content) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("iis", $threadId, $userId, $content);

        if ($stmt->execute()) {
            // 通知を作成
            $notificationContent = "New reply in thread ID " . $threadId;
            $notificationQuery = "INSERT INTO notifications (user_id, type, content) VALUES (?, 'reply', ?)";
            $notificationStmt = $conn->prepare($notificationQuery);
            $notificationStmt->bind_param("is", $userId, $notificationContent);
            $notificationStmt->execute();

            echo json_encode(['success' => true, 'message' => 'Post and notification created successfully']);
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
