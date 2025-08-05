<?php
$host = 'localhost';
$db = 'gatepass_system';
$user = 'root'; // change if not default
$pass = '';     // change if password is set

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>