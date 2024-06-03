<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

include 'config.php';

$threadId = $_GET['threadId'];

$query = "SELECT thread_posts.id, thread_posts.content, thread_posts.created_at, users.username 
          FROM thread_posts 
          JOIN users ON thread_posts.user_id = users.id 
          WHERE thread_posts.thread_id = ? 
          ORDER BY thread_posts.created_at DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $threadId);
$stmt->execute();
$result = $stmt->get_result();

$posts = [];
while ($row = $result->fetch_assoc()) {
    $posts[] = $row;
}

echo json_encode(['posts' => $posts]);

$stmt->close();
$conn->close();
