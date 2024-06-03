<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

include 'config.php';

$query = "SELECT threads.id, threads.title, threads.description, threads.created_at, users.username 
          FROM threads 
          JOIN users ON threads.user_id = users.id 
          ORDER BY threads.created_at DESC";
$result = $conn->query($query);

$threads = [];
while ($row = $result->fetch_assoc()) {
    $threads[] = $row;
}

echo json_encode(['threads' => $threads]);

$conn->close();
