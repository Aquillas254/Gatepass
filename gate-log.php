<?php
// Database connection
$host = 'localhost';
$db_name = 'gatepass_system';
$username = 'root';
$password = '';
$conn = new mysqli($host, $username, $password, $db_name);

// Insert log if form submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = $_POST['name'];
  $type = $_POST['type'];
  $purpose = $_POST['purpose'];
  $time_in = $_POST['time_in'];
  $time_out = $_POST['time_out'];

  $stmt = $conn->prepare("INSERT INTO gate_logs (name, type, purpose, time_in, time_out) VALUES (?, ?, ?, ?, ?)");
  $stmt->bind_param("sssss", $name, $type, $purpose, $time_in, $time_out);
  $stmt->execute();
}

// Fetch logs
$filter_type = $_GET['filter_type'] ?? '';
$filter_date = $_GET['filter_date'] ?? '';
$query = "SELECT * FROM gate_logs WHERE 1";

if (!empty($filter_type) && $filter_type !== 'All') {
  $query .= " AND type = '" . $conn->real_escape_string($filter_type) . "'";
}
if (!empty($filter_date)) {
  $query .= " AND DATE(time_in) = '" . $conn->real_escape_string($filter_date) . "'";
}
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Gate Logs</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f8f9fc;
    }
    .sidebar {
      height: 100vh;
      background-color: #151c2e;
      color: white;
    }
    .sidebar a {
      color: white;
      text-decoration: none;
      display: block;
      padding: 12px 20px;
    }
    .sidebar a:hover, .sidebar .active {
      background-color: #1e2b4b;
    }
    .nav-icon {
      margin-right: 10px;
    }
    .top-bar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 15px 20px;
      background-color: white;
      border-bottom: 1px solid #dee2e6;
    }
    .form-section {
      background: white;
      padding: 20px;
      border-radius: 8px;
      margin-bottom: 20px;
      box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
  </style>
</head>
<body>
<div class="d-flex">
  <!-- Sidebar -->
  <div class="sidebar p-3">
    <h4 class="mb-4"><i class="fas fa-shield-alt"></i> GatePass</h4>
    <div class="fw-bold text-uppercase small">Navigation</div>
    <a href="dashboard.php"><i class="fas fa-home nav-icon"></i> Dashboard</a>
    <a href="#"><i class="fas fa-id-badge nav-icon"></i> Employee Gatepass</a>
    <a href="#"><i class="fas fa-user-check nav-icon"></i> Visitor Check-in</a>
    <a href="asset_management.php"><i class="fas fa-boxes nav-icon"></i> Asset Management</a>
    <a href="#"><i class="fas fa-users-cog nav-icon"></i> User Management</a>
    <a href="gate-log.php" class="active"><i class="fas fa-file-alt nav-icon"></i> Gate Logs</a>
    <a href="#"><i class="fas fa-bell nav-icon"></i> Notifications</a>
    <a href="#"><i class="fas fa-cog nav-icon"></i> Settings</a>
    <div class="mt-4">
      <div class="fw-bold">John Admin</div>
      <small>Admin | IT</small><br>
      <a href="#" class="mt-2 d-block"><i class="fas fa-sign-out-alt nav-icon"></i> Sign Out</a>
    </div>
  </div>

  <!-- Main Content -->
  <div class="flex-grow-1">
    <div class="top-bar">
      <h5 class="mb-0">Gate Logs</h5>
      <div>
        <i class="fas fa-bell"></i> <span class="badge bg-danger">3</span>
        <span class="ms-3">John Admin</span>
        <span class="badge bg-secondary rounded-circle ms-2">JA</span>
      </div>
    </div>

    <div class="container mt-4">
      <!-- Log Entry Form -->
      <div class="form-section">
        <h5>Add Gate Log</h5>
        <form method="POST" class="row g-3">
          <div class="col-md-3">
            <input type="text" name="name" class="form-control" placeholder="Name" required>
          </div>
          <div class="col-md-2">
            <select name="type" class="form-select" required>
              <option value="">-- Type --</option>
              <option value="Employee">Employee</option>
              <option value="Visitor">Visitor</option>
            </select>
          </div>
          <div class="col-md-3">
            <input type="text" name="purpose" class="form-control" placeholder="Purpose" required>
          </div>
          <div class="col-md-2">
            <input type="datetime-local" name="time_in" class="form-control" required>
          </div>
          <div class="col-md-2">
            <input type="datetime-local" name="time_out" class="form-control">
          </div>
          <div class="col-md-12 text-end">
            <button type="submit" class="btn btn-primary">Add Log</button>
          </div>
        </form>
      </div>

      <!-- Filters -->
      <div class="form-section">
        <h6>Filter Logs</h6>
        <form method="GET" class="row g-3">
          <div class="col-md-3">
            <select name="filter_type" class="form-select">
              <option value="All">All Types</option>
              <option value="Employee">Employee</option>
              <option value="Visitor">Visitor</option>
            </select>
          </div>
          <div class="col-md-3">
            <input type="date" name="filter_date" class="form-control">
          </div>
          <div class="col-md-3">
            <button type="submit" class="btn btn-outline-primary">Apply Filter</button>
            <a href="gate-log.php" class="btn btn-outline-secondary ms-2">Reset</a>
          </div>
        </form>
      </div>

      <!-- Logs Table -->
      <div class="form-section">
        <h6>Gate Logs Table</h6>
        <div class="table-responsive">
          <table class="table table-bordered table-striped">
            <thead class="table-dark">
              <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Type</th>
                <th>Purpose</th>
                <th>Time In</th>
                <th>Time Out</th>
              </tr>
            </thead>
            <tbody>
              <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                  <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= $row['type'] ?></td>
                    <td><?= htmlspecialchars($row['purpose']) ?></td>
                    <td><?= $row['time_in'] ?></td>
                    <td><?= $row['time_out'] ?? '-' ?></td>
                  </tr>
                <?php endwhile; ?>
              <?php else: ?>
                <tr><td colspan="6" class="text-center">No logs found.</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
</body>
</html>
