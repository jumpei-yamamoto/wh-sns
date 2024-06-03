<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

include 'config.php';

$searchQuery = $_GET['query'];

$usersQuery = "SELECT id, username, profile_picture FROM users WHERE username LIKE ?";
$postsQuery = "SELECT posts.id, posts.content, posts.created_at, users.username, users.profile_picture FROM posts JOIN users ON posts.user_id = users.id WHERE posts.content LIKE ?";
$threadsQuery = "SELECT threads.id, threads.title, threads.description, threads.created_at, users.username FROM threads JOIN users ON threads.user_id = users.id WHERE threads.title LIKE ? OR threads.description LIKE ?";

$searchPattern = '%' . $searchQuery . '%';

$usersStmt = $conn->prepare($usersQuery);
$usersStmt->bind_param("s", $searchPattern);
$usersStmt->execute();
$usersResult = $usersStmt->get_result();
$users = [];
while ($row = $usersResult->fetch_assoc()) {
    $users[] = $row;
}

$postsStmt = $conn->prepare($postsQuery);
$postsStmt->bind_param("s", $searchPattern);
$postsStmt->execute();
$postsResult = $postsStmt->get_result();
$posts = [];
while ($row = $postsResult->fetch_assoc()) {
    $posts[] = $row;
}

$threadsStmt = $conn->prepare($threadsQuery);
$threadsStmt->bind_param("ss", $searchPattern, $searchPattern);
$threadsStmt->execute();
$threadsResult = $threadsStmt->get_result();
$threads = [];
while ($row = $threadsResult->fetch_assoc()) {
    $threads[] = $row;
}

echo json_encode(['users' => $users, 'posts' => $posts, 'threads' => $threads]);

$usersStmt->close();
$postsStmt->close();
$threadsStmt->close();
$conn->close();
