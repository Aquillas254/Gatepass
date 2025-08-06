<?php
require 'db.php';

// Get all assets
$assets = $pdo->query("SELECT id, name, serial FROM assets")->fetchAll();
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
    }
    .sidebar {
      width: 220px;
      background-color: #2c3e50;
      color: white;
      position: fixed;
      height: 100%;
      padding: 30px 20px;
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
    .form-group label {
      font-weight: 600;
      margin-bottom: 8px;
      display: block;
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
    .qr-display {
      margin-top: 30px;
      text-align: center;
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
    <h2>QR Generator</h2>
    <p>Welcome to the Gatepass System</p>
  </div>

  <div class="content">
    <div class="card">
      <h1>Generate Employee QR Code</h1>
      <div class="grid">
        <div>
          <div class="form-group">
            <label>Employee Name:</label>
            <input type="text" id="employeeName" placeholder="e.g., Jane Doe">
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
                  <input type="checkbox" name="assets[]" value="<?= htmlspecialchars($asset['name'] . ' - ' . $asset['serial']) ?>">
                  <label><?= htmlspecialchars($asset['name']) ?> (<?= htmlspecialchars($asset['serial']) ?>)</label>
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
    </div>
  </div>

  <script>
    function toggleDropdown() {
      const dropdown = document.getElementById("assetDropdown");
      dropdown.style.display = dropdown.style.display === "block" ? "none" : "block";
    }

    function generateQRCode() {
      const name = document.getElementById("employeeName").value.trim();
      const dept = document.getElementById("department").value.trim();
      const assets = Array.from(document.querySelectorAll('input[name="assets[]"]:checked')).map(cb => cb.value);

      if (!name || !dept || assets.length === 0) {
        alert("Please complete all fields and select at least one asset.");
        return;
      }

      const qrData = {
        name: name,
        department: dept,
        assets: assets
      };

      new QRious({
        element: document.getElementById("qr-code"),
        value: JSON.stringify(qrData),
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
          alert("Sharing is not supported by your browser.");
        }
      });
    }
  </script>
</body>
</html>
