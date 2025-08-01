<?php
session_start();
require 'db.php';

$email = $_POST['email'];
$password = $_POST['password'];
$role = $_POST['role'];

$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND role = ?");
$stmt->execute([$email, $role]);
$user = $stmt->fetch();

if ($user && password_verify($password, $user['password'])) {
    $_SESSION['user_name'] = $user['name'];
    header("Location: dashboard/{$role}.php");
    exit;
} else {
    echo "Invalid credentials.";
}
?>