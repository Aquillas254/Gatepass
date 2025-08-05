<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userId = 1; // simulate logged-in user
    $name = $_POST['asset_name'];
    $serial = $_POST['serial_number'];
    $type = $_POST['asset_type'];

    $stmt = $conn->prepare("INSERT INTO assets (user_id, asset_name, serial_number, asset_type) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $userId, $name, $serial, $type);

    if ($stmt->execute()) {
        header("Location: asset_management.php");
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>