<?php
include 'config.php';
include 'funcs.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // セッションからユーザーIDを取得
    if (isset($_SESSION['user_id'])) {
        $userId = $_SESSION['user_id'];
    } else {
        echo json_encode(['success' => false, 'message' => 'User not authenticated']);
        exit();
    }

    $input = file_get_contents("php://input");
    $data = json_decode($input, true);

    // デバッグ用ログ出力
    error_log("Received data: " . print_r($data, true));

    $threadId = $data['threadId'];
    $content = $data['content'];

    if ($threadId && $userId && $content) {
        $pdo = db_conn();

        $query = "INSERT INTO thread_posts (thread_id, user_id, content) VALUES (:thread_id, :user_id, :content)";
        $stmt = $pdo->prepare($query);
        $stmt->bindValue(':thread_id', $threadId, PDO::PARAM_INT);
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':content', $content, PDO::PARAM_STR);

        if ($stmt->execute()) {
            // 通知を作成
            $notificationContent = "New reply in thread ID " . $threadId;
            $notificationQuery = "INSERT INTO notifications (user_id, type, content) VALUES (:user_id, 'reply', :content)";
            $notificationStmt = $pdo->prepare($notificationQuery);
            $notificationStmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
            $notificationStmt->bindValue(':content', $notificationContent, PDO::PARAM_STR);
            $notificationStmt->execute();

            echo json_encode(['success' => true, 'message' => 'Post and notification created successfully']);
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
