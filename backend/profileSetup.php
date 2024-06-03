<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json');

include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $bio = $_POST['bio'];
    $userId = $_POST['userId'];
    $profilePicture = null;

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

    $query = "UPDATE users SET username = ?, bio = ?, profile_picture = ?, profile_complete = 1 WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssi", $name, $bio, $profilePicture, $userId);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Profile setup successful']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $stmt->error]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
