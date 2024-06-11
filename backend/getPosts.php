<?php
include 'config.php';

include 'funcs.php';

$pdo = db_conn();

$query = "SELECT posts.id, posts.user_id, posts.content, posts.created_at, users.username, users.profile_picture 
          FROM posts 
          JOIN users ON posts.user_id = users.id 
          ORDER BY posts.created_at DESC";
$stmt = $pdo->query($query);
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode(['posts' => $result]);
?>
