<?php
session_start();
require 'db.php';

// Get lecturer's full name
$email = $_SESSION['email'];
$stmt = $pdo->prepare("SELECT full_name FROM lecturers WHERE email = ?");
$stmt->execute([$email]);
$lecturer = $stmt->fetch(PDO::FETCH_ASSOC);

// Get assets
$assets = $pdo->query("SELECT asset_id, asset_name, serial_number FROM assets")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Employee QR Code Generator</title>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/qrious/4.0.2/qrious.min.js"></script>
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f4f6f9;
      margin: 0;
      padding: 0;
    }

    .sidebar {
      width: 220px;
      background-color: #2c3e50;
      color: white;
      position: fixed;
      height: 100%;
      padding: 30px 20px;
    }

    .sidebar h2 {
      margin-top: 0;
      font-size: 24px;
      color: #ecf0f1;
    }

    .sidebar p {
      color: #bdc3c7;
      margin-top: 20px;
    }

    .content {
      margin-left: 240px;
      padding: 30px;
    }

    .card {
      background-color: white;
      border-radius: 12px;
      padding: 30px;
      box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
    }

    .card h1 {
      margin-top: 0;
      font-size: 26px;
      color: #2c3e50;
    }

    .form-group {
      margin-bottom: 20px;
    }

    .form-group label {
      display: block;
      font-weight: 600;
      margin-bottom: 8px;
      color: #34495e;
    }

    .form-group input {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccd1d9;
      border-radius: 6px;
      font-size: 15px;
    }

    .dropdown-container {
      border: 1px solid #ccd1d9;
      border-radius: 6px;
      background: #fdfdfd;
      padding: 10px;
      cursor: pointer;
      user-select: none;
      font-weight: 500;
    }

    .asset-list {
      margin-top: 8px;
      max-height: 200px;
      overflow-y: auto;
      display: none;
      border-top: 1px solid #ccc;
      padding-top: 10px;
    }

    .asset-list div {
      display: flex;
      align-items: center;
      margin-bottom: 8px;
    }

    .asset-list input[type="checkbox"] {
      margin-right: 10px;
      transform: scale(1.2);
    }

    .qr-display {
      margin-top: 30px;
      text-align: center;
    }

    .qr-display h3 {
      margin-bottom: 10px;
      color: #34495e;
    }

    .print-button {
      margin: 8px 4px;
      background-color: #3498db;
      color: white;
      padding: 10px 18px;
      border: none;
      border-radius: 6px;
      font-size: 14px;
      cursor: pointer;
    }

    .print-button:hover {
      background-color: #2980b9;
    }

    .button-container a {
      display: inline-block;
      padding: 10px 20px;
      background-color: #007bff;
      color: white;
      text-decoration: none;
      border-radius: 5px;
      font-weight: bold;
      font-size: 16px;
      margin-top: 20px;
    }

    .button-container a:hover {
      background-color: #0056b3;
    }

    .grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 40px;
      margin-top: 30px;
    }
  </style>
</head>
<body>
  <div class="sidebar">
    <h2>Employee Panel</h2>
    <p>Welcome, <?= $_SESSION['full_name'] ?? 'User' ?></p>
  </div>

  <div class="content">
    <div class="card">
      <h1>Generate QR Code for Employee</h1>
      <div class="grid">
        <div>
          <div class="form-group">
            <label>Employee Name:</label>
            <input type="text" id="employeeName" placeholder="e.g., John Doe">
          </div>

          <div class="form-group">
            <label>Department:</label>
            <input type="text" id="department" placeholder="e.g., IT Support">
          </div>

          <div class="form-group">
            <label>Assign Assets:</label>
            <div class="dropdown-container" onclick="toggleDropdown()">Click to select assets ▼</div>
            <div class="asset-list" id="assetDropdown">
              <?php foreach ($assets as $asset): ?>
                <div>
                  <input type="checkbox" name="assets[]" value="<?= htmlspecialchars($asset['asset_name'] . ' (' . $asset['serial_number'] . ')') ?>">
                  <label><?= htmlspecialchars($asset['asset_name']) ?> (<?= htmlspecialchars($asset['serial_number']) ?>)</label>
                </div>
              <?php endforeach; ?>
            </div>
          </div>

          <button class="print-button" onclick="generateQRCode()">Generate QR Code</button>
        </div>

        <div>
          <div class="qr-display">
            <h3>QR Code Preview</h3>
            <canvas id="qr-code"></canvas>
            <div>
              <button class="print-button" onclick="downloadQRCode()">Download</button>
              <button class="print-button" onclick="shareQRCode()">Share</button>
              <button class="print-button" onclick="window.print()">Print</button>
            </div>
          </div>
        </div>
      </div>

      <div class="button-container">
        <a href="logout.php">Log Out</a>
      </div>
    </div>
  </div>

  <script>
    let qr;

    function toggleDropdown() {
      const dropdown = document.getElementById("assetDropdown");
      dropdown.style.display = dropdown.style.display === "block" ? "none" : "block";
    }

    function generateQRCode() {
      const employeeName = document.getElementById("employeeName").value.trim();
      const department = document.getElementById("department").value.trim();
      const assetCheckboxes = document.querySelectorAll('input[name="assets[]"]:checked');
      const selectedAssets = Array.from(assetCheckboxes).map(cb => cb.value);

      if (!employeeName || !department || selectedAssets.length === 0) {
        alert("Please fill in all fields and select at least one asset.");
        return;
      }

      const qrData = {
        name: employeeName,
        department: department,
        assets: selectedAssets
      };

      const qrText = JSON.stringify(qrData);

      qr = new QRious({
        element: document.getElementById("qr-code"),
        value: qrText,
        size: 200
      });
    }

    function downloadQRCode() {
      const canvas = document.getElementById("qr-code");
      const link = document.createElement('a');
      link.download = "employee_qr.png";
      link.href = canvas.toDataURL("image/png");
      link.click();
    }

    function shareQRCode() {
      const canvas = document.getElementById("qr-code");
      canvas.toBlob(blob => {
        const file = new File([blob], "employee_qr.png", { type: "image/png" });

        if (navigator.canShare && navigator.canShare({ files: [file] })) {
          navigator.share({
            title: "Employee QR Code",
            text: "Here is the generated QR Code.",
            files: [file]
          }).catch(err => console.error("Share failed:", err));
        } else {
          alert("Sharing not supported on this device/browser.");
        }
      });
    }
  </script>
</body>
</html>
