<?php
include 'config.php';

include 'funcs.php';

$searchQuery = $_GET['query'];
$searchPattern = '%' . $searchQuery . '%';

$pdo = db_conn();

// ユーザー検索クエリ
$usersQuery = "SELECT id, username, profile_picture FROM users WHERE username LIKE :searchPattern";
$usersStmt = $pdo->prepare($usersQuery);
$usersStmt->bindValue(':searchPattern', $searchPattern, PDO::PARAM_STR);
$usersStmt->execute();
$users = $usersStmt->fetchAll(PDO::FETCH_ASSOC);

// 投稿検索クエリ
$postsQuery = "SELECT posts.id, posts.content, posts.created_at, users.username, users.profile_picture 
               FROM posts 
               JOIN users ON posts.user_id = users.id 
               WHERE posts.content LIKE :searchPattern";
$postsStmt = $pdo->prepare($postsQuery);
$postsStmt->bindValue(':searchPattern', $searchPattern, PDO::PARAM_STR);
$postsStmt->execute();
$posts = $postsStmt->fetchAll(PDO::FETCH_ASSOC);

// スレッド検索クエリ
$threadsQuery = "SELECT threads.id, threads.title, threads.description, threads.created_at, users.username 
                 FROM threads 
                 JOIN users ON threads.user_id = users.id 
                 WHERE threads.title LIKE :searchPattern OR threads.description LIKE :searchPattern";
$threadsStmt = $pdo->prepare($threadsQuery);
$threadsStmt->bindValue(':searchPattern', $searchPattern, PDO::PARAM_STR);
$threadsStmt->execute();
$threads = $threadsStmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode(['users' => $users, 'posts' => $posts, 'threads' => $threads]);

$usersStmt->closeCursor();
$postsStmt->closeCursor();
$threadsStmt->closeCursor();
?>
