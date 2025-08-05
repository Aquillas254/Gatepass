
<?php
// Database connection
$host = 'localhost';
$db_name = 'gatepass_system';
$username = 'root';
$password = '';
$conn = new mysqli($host, $username, $password, $db_name);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Insert log if form submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = $_POST['name'];
  $type = $_POST['type'];
  $purpose = $_POST['purpose'];
  $time_in = $_POST['time_in'];
  $time_out = $_POST['time_out'];
  $asset_description = $_POST['asset_description'] ?: null;
  $asset_quantity = $_POST['asset_quantity'] ?: 1;
  $asset_condition = $_POST['asset_condition'] ?: null;
  $asset_direction = $_POST['asset_direction'] ?: 'Out';

  $stmt = $conn->prepare("
    INSERT INTO gate_logs 
      (name, type, purpose, time_in, time_out, asset_description, asset_quantity, asset_condition, asset_direction)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
  ");
  $stmt->bind_param(
    "sssssssis",
    $name,
    $type,
    $purpose,
    $time_in,
    $time_out,
    $asset_description,
    $asset_quantity,
    $asset_condition,
    $asset_direction
  );
  $stmt->execute();
  $stmt->close();
}

// Fetch logs with filters
$filter_type = $_GET['filter_type'] ?? '';
$filter_date = $_GET['filter_date'] ?? '';
$filter_direction = $_GET['filter_direction'] ?? '';
$query = "SELECT * FROM gate_logs WHERE 1";

if (!empty($filter_type) && $filter_type !== 'All') {
  $query .= " AND type = '" . $conn->real_escape_string($filter_type) . "'";
}
if (!empty($filter_date)) {
  $query .= " AND DATE(time_in) = '" . $conn->real_escape_string($filter_date) . "'";
}
if (!empty($filter_direction) && $filter_direction !== 'All') {
  $query .= " AND asset_direction = '" . $conn->real_escape_string($filter_direction) . "'";
}
$query .= " ORDER BY time_in DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Gate Logs</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Bootstrap & icons -->
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
    .welcome {
      background: linear-gradient(90deg, #0062cc, #0056b3);
      color: white;
      padding: 30px;
      border-radius: 15px;
      margin-bottom: 30px;
    }
    .form-section {
      background: white;
      padding: 20px;
      border-radius: 8px;
      margin-bottom: 20px;
      box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    .badge-direction-in {
      background-color: #198754;
    }
    .badge-direction-out {
      background-color: #0d6efd;
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
    <a href="user_management.php"><i class="fas fa-users-cog nav-icon"></i> User Management</a>
    <a href="gate-logs.php" class="active"><i class="fas fa-file-alt nav-icon"></i> Gate Logs</a>
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
    <!-- Top Bar -->
    <div class="top-bar">
      <input type="text" class="form-control w-50" placeholder="Search gate logs...">
      <div>
        <i class="fas fa-bell"></i> <span class="badge bg-danger">3</span>
        <span class="ms-3">John Admin</span>
        <span class="badge bg-secondary rounded-circle ms-2">JA</span>
      </div>
    </div>

    <div class="container mt-4">
      <!-- Welcome -->
      <div class="welcome">
        <h2>Gate Logs</h2>
        <p>Track all gate entries/exits and items moved.</p>
      </div>

      <!-- Add Gate Log Form -->
      <div class="form-section">
        <h5>Add Gate Log</h5>
        <form method="POST" class="row g-3">
          <div class="col-md-3">
            <label class="form-label">Name</label>
            <input type="text" name="name" class="form-control" placeholder="Full Name" required>
          </div>
          <div class="col-md-2">
            <label class="form-label">Type</label>
            <select name="type" class="form-select" required>
              <option value="">-- Type --</option>
              <option value="Employee">Employee</option>
              <option value="Visitor">Visitor</option>
            </select>
          </div>
          <div class="col-md-3">
            <label class="form-label">Purpose</label>
            <input type="text" name="purpose" class="form-control" placeholder="Purpose of visit" required>
          </div>
          <div class="col-md-2">
            <label class="form-label">Time In</label>
            <input type="datetime-local" name="time_in" class="form-control" required>
          </div>
          <div class="col-md-2">
            <label class="form-label">Time Out</label>
            <input type="datetime-local" name="time_out" class="form-control">
          </div>

          <!-- Asset Fields -->
          <div class="col-md-4">
            <label class="form-label">Asset Description</label>
            <textarea name="asset_description" class="form-control" placeholder="e.g., Laptop, tools" rows="2"></textarea>
          </div>
          <div class="col-md-2">
            <label class="form-label">Quantity</label>
            <input type="number" name="asset_quantity" class="form-control" min="1" value="1">
          </div>
          <div class="col-md-3">
            <label class="form-label">Condition</label>
            <select name="asset_condition" class="form-select">
              <option value="">Select</option>
              <option value="New">New</option>
              <option value="Good">Good</option>
              <option value="Fair">Fair</option>
              <option value="Damaged">Damaged</option>
            </select>
          </div>
          <div class="col-md-3">
            <label class="form-label">Direction</label>
            <select name="asset_direction" class="form-select">
              <option value="Out">Out</option>
              <option value="In">In</option>
            </select>
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
            <label class="form-label">Type</label>
            <select name="filter_type" class="form-select">
              <option value="All">All Types</option>
              <option value="Employee">Employee</option>
              <option value="Visitor">Visitor</option>
            </select>
          </div>
          <div class="col-md-3">
            <label class="form-label">Date</label>
            <input type="date" name="filter_date" class="form-control">
          </div>
          <div class="col-md-3">
            <label class="form-label">Asset Direction</label>
            <select name="filter_direction" class="form-select">
              <option value="All">All</option>
              <option value="In">In</option>
              <option value="Out">Out</option>
            </select>
          </div>
          <div class="col-md-3 d-flex align-items-end gap-2">
            <button type="submit" class="btn btn-outline-primary">Apply Filter</button>
            <a href="gate-log.php" class="btn btn-outline-secondary">Reset</a>
          </div>
        </form>
      </div>

      <!-- Logs Table -->
      <div class="form-section">
        <h6>Gate Logs Table</h6>
        <div class="table-responsive">
          <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark">
              <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Type</th>
                <th>Purpose</th>
                <th>Time In</th>
                <th>Time Out</th>
                <th>Asset</th>
                <th>Qty</th>
                <th>Condition</th>
                <th>Direction</th>
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
                    <td><?= $row['time_out'] ?: '-' ?></td>
                    <td><?= htmlspecialchars($row['asset_description']) ?: '-' ?></td>
                    <td><?= $row['asset_quantity'] ?></td>
                    <td><?= htmlspecialchars($row['asset_condition']) ?: '-' ?></td>
                    <td>
                      <?php if (isset($row['asset_direction'])): ?>
                        <span class="badge <?= $row['asset_direction'] === 'In' ? 'badge-direction-in' : 'badge-direction-out' ?>">
                          <?= $row['asset_direction'] ?>
                        </span>
                      <?php else: ?>
                        -
                      <?php endif; ?>
                    </td>
                  </tr>
                <?php endwhile; ?>
              <?php else: ?>
                <tr><td colspan="10" class="text-center">No logs found.</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
