<?php
session_start();
include 'config.php';
include 'funcs.php';


if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $bio = $_POST['bio'];
    $userId = $_SESSION['user_id'] ?? null;
    $profilePicture = null;

    if ($userId) {
        if (!is_dir('img')) {
            mkdir('img', 0777, true);
        }

        if (isset($_FILES['profilePicture'])) {
            $target_dir = "img/";
            $target_file = $target_dir . basename($_FILES["profilePicture"]["name"]);

            if (move_uploaded_file($_FILES["profilePicture"]["tmp_name"], $target_file)) {
                $profilePicture = $target_file;
            } else {
                error_log('Failed to move uploaded file.');
                echo json_encode(['success' => false, 'message' => 'Failed to upload profile picture']);
                exit();
            }
        }

        $pdo = db_conn();

        $query = "UPDATE users SET username = :name, bio = :bio, profile_picture = :profile_picture, profile_complete = 1 WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindValue(':name', $name, PDO::PARAM_STR);
        $stmt->bindValue(':bio', $bio, PDO::PARAM_STR);
        $stmt->bindValue(':profile_picture', $profilePicture, PDO::PARAM_STR);
        $stmt->bindValue(':id', $userId, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Profile setup successful']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error: ' . $stmt->errorInfo()[2]]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'User not authenticated']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
