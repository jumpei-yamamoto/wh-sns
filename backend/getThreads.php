<?php
include 'config.php';
include 'funcs.php';

$pdo = db_conn();

if (isset($_GET['id'])) {
    $threadId = $_GET['id'];

    $query = "SELECT threads.id, threads.title, threads.description, threads.created_at, users.username 
              FROM threads 
              JOIN users ON threads.user_id = users.id 
              WHERE threads.id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->bindValue(':id', $threadId, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode(['thread' => $result]);
} else {
    $query = "SELECT threads.id, threads.title, threads.description, threads.created_at, users.username 
              FROM threads 
              JOIN users ON threads.user_id = users.id 
              ORDER BY threads.created_at DESC";
    $stmt = $pdo->query($query);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['threads' => $result]);
}
?>
