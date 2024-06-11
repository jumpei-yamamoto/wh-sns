<?php
session_start();

include 'config.php';


if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

include 'funcs.php';
$pdo = db_conn();

$data = json_decode(file_get_contents('php://input'), true);
$email = $data['email'];
$password = $data['password'];

$query = "SELECT * FROM users WHERE email = :email";
$stmt = $pdo->prepare($query);
$stmt->bindValue(':email', $email, PDO::PARAM_STR);
$status = $stmt->execute();

$response = ['success' => false, 'message' => 'Invalid email or password'];
if ($status == false) {
    sql_error($stmt);
} else {
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user && password_verify($password, $user['password'])) {
        // セッションにユーザー情報を保存
        $_SESSION["chk_ssid"] = session_id();
        $_SESSION["user_id"] = $user['id'];
        $_SESSION["name"] = $user['name'];
        $response = ['success' => true, 'message' => 'Login successful', 'userId' => $user['id']];
    }
}

echo json_encode($response);

$stmt->closeCursor();
?>
