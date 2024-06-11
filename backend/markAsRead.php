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

    $notificationId = $data['notificationId'];

    if ($notificationId) {
        $pdo = db_conn();

        $query = "UPDATE notifications SET is_read = 1 WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindValue(':id', $notificationId, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Notification marked as read']);
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
