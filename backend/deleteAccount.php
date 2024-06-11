<?php
session_start();  // セッションを開始

include 'config.php';
include 'funcs.php';

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $userId = $_SESSION['user_id'] ?? null;

        if ($userId) {
            $pdo = db_conn();

            // トランザクションの開始
            $pdo->beginTransaction();

            // 先に関連するデータを削除
            // notificationsテーブルから削除
            $deleteNotificationsQuery = "DELETE FROM notifications WHERE user_id = :id";
            $deleteNotificationsStmt = $pdo->prepare($deleteNotificationsQuery);
            $deleteNotificationsStmt->bindValue(':id', $userId, PDO::PARAM_INT);
            $deleteNotificationsStmt->execute();

            // thread_postsテーブルから削除
            $deleteThreadPostsQuery = "DELETE FROM thread_posts WHERE user_id = :id";
            $deleteThreadPostsStmt = $pdo->prepare($deleteThreadPostsQuery);
            $deleteThreadPostsStmt->bindValue(':id', $userId, PDO::PARAM_INT);
            $deleteThreadPostsStmt->execute();

            // threadsテーブルから削除
            $deleteThreadsQuery = "DELETE FROM threads WHERE user_id = :id";
            $deleteThreadsStmt = $pdo->prepare($deleteThreadsQuery);
            $deleteThreadsStmt->bindValue(':id', $userId, PDO::PARAM_INT);
            $deleteThreadsStmt->execute();

            // postsテーブルから削除
            $deletePostsQuery = "DELETE FROM posts WHERE user_id = :id";
            $deletePostsStmt = $pdo->prepare($deletePostsQuery);
            $deletePostsStmt->bindValue(':id', $userId, PDO::PARAM_INT);
            $deletePostsStmt->execute();

            // messagesテーブルから削除
            $deleteMessagesQuery = "DELETE FROM messages WHERE sender_id = :id OR receiver_id = :id";
            $deleteMessagesStmt = $pdo->prepare($deleteMessagesQuery);
            $deleteMessagesStmt->bindValue(':id', $userId, PDO::PARAM_INT);
            $deleteMessagesStmt->execute();

            // ユーザーを削除
            $query = "DELETE FROM users WHERE id = :id";
            $stmt = $pdo->prepare($query);
            $stmt->bindValue(':id', $userId, PDO::PARAM_INT);

            if ($stmt->execute()) {
                // トランザクションのコミット
                $pdo->commit();
                // セッションを破棄
                session_destroy();
                echo json_encode(['success' => true, 'message' => 'Account deleted successfully']);
            } else {
                // トランザクションのロールバック
                $pdo->rollBack();
                $errorInfo = $stmt->errorInfo();
                echo json_encode(['success' => false, 'message' => 'Error: ' . $errorInfo[2]]);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid user ID']);
        }
    } catch (Exception $e) {
        // トランザクションのロールバック
        $pdo->rollBack();
        error_log("Error in deleteAccount.php: " . $e->getMessage());
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
