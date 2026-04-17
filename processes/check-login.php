<?php
require_once '../config/connection.php';
header('Content-Type: application/json');

$email = $_POST['email'] ?? '';
$stmt = $conn->prepare("SELECT email FROM users WHERE email = ? LIMIT 1");
$stmt->bind_param("s", $email);
$stmt->execute();
echo json_encode(['exists' => $stmt->get_result()->num_rows > 0]);

$conn->close();?>