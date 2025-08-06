<?php
// db.php
$mysqli = new mysqli("localhost", "root", "", "gatepass_system");
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>QR Code Check-In/Out | GatePass</title>
  <script src="https://unpkg.com/html5-qrcode"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
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

    .sidebar h4 {
      color: #ecf0f1;
      font-size: 22px;
    }

    .sidebar a {
      display: block;
      margin: 15px 0;
      color: #bdc3c7;
      text-decoration: none;
      font-size: 15px;
    }

    .sidebar a:hover, .sidebar a.active {
      color: #ffffff;
      font-weight: bold;
    }

    .content {
      margin-left: 240px;
      padding: 40px;
    }

    .card {
      background: white;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
    }

    #reader {
      width: 100%;
      max-width: 400px;
      margin: auto;
    }

    #details {
      margin-top: 30px;
      font-size: 16px;
      text-align: center;
      color: #2c3e50;
    }

    .card-title {
      color: #2c3e50;
      font-weight: 600;
    }
  </style>
</head>
<body>

  <!-- Sidebar -->
  <div class="sidebar">
    <h4><i class="fas fa-shield-alt me-2"></i>GatePass</h4>
    <a href="#" class="active"><i class="fas fa-home me-2"></i>Dashboard</a>
    <a href="#"><i class="fas fa-id-badge me-2"></i>Employee Gatepass</a>
    <a href="#"><i class="fas fa-user-check me-2"></i>Visitor Check-in</a>
    <a href="asset_management.php"><i class="fas fa-boxes me-2"></i>Asset Management</a>
    <a href="user_management.php"><i class="fas fa-users-cog me-2"></i>User Management</a>
    <a href="gate-log.php"><i class="fas fa-file-alt me-2"></i>Gate Logs</a>
    <a href="#"><i class="fas fa-bell me-2"></i>Notifications</a>
    <a href="#"><i class="fas fa-cog me-2"></i>Settings</a>
  </div>

  <!-- Main Content -->
  <div class="content">
    <div class="card text-center">
      <h3 class="card-title mb-4"><i class="fas fa-qrcode me-2"></i>QR Code Check-In/Out</h3>
      <div id="reader"></div>
      <div id="details" class="mt-4"></div>
    </div>
  </div>

  <script>
    function processScan(qrCodeMessage) {
      fetch("process_scan.php", {
        method: "POST",
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ code: qrCodeMessage })
      })
      .then(response => response.json())
      .then(data => {
        document.getElementById("details").innerHTML = data.message;
      })
      .catch(err => {
        document.getElementById("details").innerHTML = "Scan failed. Please try again.";
        console.error(err);
      });
    }

    const scanner = new Html5QrcodeScanner("reader", { fps: 10, qrbox: 250 });
    scanner.render(processScan);
  </script>

</body>
</html>
