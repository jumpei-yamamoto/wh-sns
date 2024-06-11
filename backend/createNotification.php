<?php
include 'config.php';

include 'funcs.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = $_POST['userId'];
    $type = $_POST['type'];
    $content = $_POST['content'];

    $pdo = db_conn();

    $query = "INSERT INTO notifications (user_id, type, content) VALUES (:user_id, :type, :content)";
    $stmt = $pdo->prepare($query);
    $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
    $stmt->bindValue(':type', $type, PDO::PARAM_STR);
    $stmt->bindValue(':content', $content, PDO::PARAM_STR);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Notification created successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $stmt->errorInfo()[2]]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
