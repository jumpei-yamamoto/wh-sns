<?php
include 'config.php';
include 'funcs.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = $_SESSION['user_id'] ?? null;
    $content = $_POST['content'];
    $image = null;

    if ($userId === null) {
        echo json_encode(['success' => false, 'message' => 'User not authenticated']);
        exit();
    }

    if (!is_dir('img')) {
        mkdir('img', 0777, true);
    }

    if (isset($_FILES['image'])) {
        $target_dir = "img/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image = $target_file;
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to upload image']);
            exit();
        }
    }

    $pdo = db_conn();

    $query = "INSERT INTO posts (user_id, content, image) VALUES (:user_id, :content, :image)";
    $stmt = $pdo->prepare($query);
    $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
    $stmt->bindValue(':content', $content, PDO::PARAM_STR);
    $stmt->bindValue(':image', $image, PDO::PARAM_STR);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Post created successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $stmt->errorInfo()[2]]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
