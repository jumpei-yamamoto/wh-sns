<?php
include 'config.php';

include 'funcs.php';

$threadId = $_GET['threadId'];

$pdo = db_conn();

$query = "SELECT thread_posts.id, thread_posts.content, thread_posts.created_at, users.username 
          FROM thread_posts 
          JOIN users ON thread_posts.user_id = users.id 
          WHERE thread_posts.thread_id = :threadId 
          ORDER BY thread_posts.created_at DESC";
$stmt = $pdo->prepare($query);
$stmt->bindValue(':threadId', $threadId, PDO::PARAM_INT);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode(['posts' => $result]);
?>
