<?php
session_start();
include 'config.php';
include 'funcs.php';


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $input = file_get_contents("php://input");
    $data = json_decode($input, true);

    // デバッグ用ログ出力
    error_log("Received data: " . print_r($data, true));

    $title = $data['title'];
    $description = $data['description'];
    $userId = $_SESSION['user_id'] ?? null;

    if ($title && $description && $userId) {
        $pdo = db_conn();
        
        $query = "INSERT INTO threads (title, description, user_id) VALUES (:title, :description, :user_id)";
        $stmt = $pdo->prepare($query);
        $stmt->bindValue(':title', $title, PDO::PARAM_STR);
        $stmt->bindValue(':description', $description, PDO::PARAM_STR);
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);

        if ($stmt->execute()) {
            // 通知を作成
            $notificationContent = "New thread created: " . $title;
            $notificationQuery = "INSERT INTO notifications (user_id, type, content) VALUES (:user_id, 'thread', :content)";
            $notificationStmt = $pdo->prepare($notificationQuery);
            $notificationStmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
            $notificationStmt->bindValue(':content', $notificationContent, PDO::PARAM_STR);
            $notificationStmt->execute();

            echo json_encode(['success' => true, 'message' => 'Thread and notification created successfully']);
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
