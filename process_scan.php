<?php
require 'db.php';

// Get input data
$data = json_decode(file_get_contents("php://input"));

if (!isset($data->code)) {
    echo json_encode(["message" => "Invalid QR data."]);
    exit;
}

$code = $data->code;
$timestamp = date("Y-m-d H:i:s");

// Check if the QR code exists
$stmt = $mysqli->prepare("SELECT * FROM gatepass_users WHERE qr_code = ?");
$stmt->bind_param("s", $code);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(["message" => "QR Code not recognized."]);
    exit;
}

$user = $result->fetch_assoc();
$userId = $user['id'];

// Check if user is already checked in
$stmt = $mysqli->prepare("SELECT * FROM check_logs WHERE user_id = ? ORDER BY id DESC LIMIT 1");
$stmt->bind_param("i", $userId);
$stmt->execute();
$logResult = $stmt->get_result();
$lastLog = $logResult->fetch_assoc();

if (!$lastLog || $lastLog['check_out'] != null) {
    // New check-in
    $stmt = $mysqli->prepare("INSERT INTO check_logs (user_id, check_in) VALUES (?, ?)");
    $stmt->bind_param("is", $userId, $timestamp);
    $stmt->execute();
    echo json_encode(["message" => "✅ Checked in: " . $user['name'] . " at $timestamp"]);
} else {
    // Update check-out
    $stmt = $mysqli->prepare("UPDATE check_logs SET check_out = ? WHERE id = ?");
    $stmt->bind_param("si", $timestamp, $lastLog['id']);
    $stmt->execute();
    echo json_encode(["message" => "✅ Checked out: " . $user['name'] . " at $timestamp"]);
}
?>
