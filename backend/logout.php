<?php
session_start();
session_destroy();

include 'config.php';

echo json_encode(['success' => true, 'message' => 'Logged out successfully']);
?>
