<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

include 'config.php';

$userId = $_GET['userId'];

$query = "SELECT profile_complete FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$response = ['profileComplete' => false];
if ($user && $user['profile_complete']) {
  $response['profileComplete'] = true;
}

echo json_encode($response);

$stmt->close();
$conn->close();
