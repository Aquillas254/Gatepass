<?php
include 'config.php';

// Fetch assets with owner information
$sql = "SELECT a.*, u.name AS owner_name, u.user_type 
        FROM assets a 
        LEFT JOIN users u ON a.user_id = u.id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Asset Management</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    .card {
      border: none;
      border-radius: 0.75rem;
    }
    .card-body {
      padding: 1.25rem;
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
    .nav-icon {
      margin-right: 10px;
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
      <a href="#" class="active"><i class="fas fa-boxes nav-icon"></i> Asset Management</a>
      <a href="user_management.php"><i class="fas fa-users-cog nav-icon"></i> User Management</a>
      <a href="gate-log.php"><i class="fas fa-file-alt nav-icon"></i> Gate Logs</a>
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
        <input type="text" class="form-control w-50" placeholder="Search assets...">
        <div>
          <i class="fas fa-bell"></i> <span class="badge bg-danger">3</span>
          <span class="ms-3">John Admin</span>
          <span class="badge bg-secondary rounded-circle ms-2">JA</span>
        </div>
      </div>

      <!-- Welcome Header -->
      <div class="container mt-4">
        <div class="welcome d-flex justify-content-between align-items-center">
          <div>
            <h2>Asset Management</h2>
            <p>Manage your organization's asset tracking and assignments</p>
          </div>
          <button class="btn btn-light text-primary" data-bs-toggle="modal" data-bs-target="#addAssetModal">
            + Add New Asset
          </button>
        </div>

        <!-- Assigned Assets List -->
        <h5 class="mb-3">Assigned Assets</h5>
        <?php while ($row = $result->fetch_assoc()): ?>
          <div class="card mb-3">
            <div class="card-body">
              <h5 class="mb-2"><?= $row['name'] ?></h5>
              <p class="mb-1"><strong>Serial:</strong> <?= $row['serial'] ?></p>
              <p class="mb-1"><strong>Type:</strong> <?= $row['type'] ?></p>
              <p class="mb-0"><strong>Owner:</strong> <?= $row['owner_name'] ?? 'Unassigned' ?> (<?= $row['user_type'] ?? 'N/A' ?>)</p>
            </div>
          </div>
        <?php endwhile; ?>
      </div>
    </div>
  </div>

  <!-- Add Asset Modal -->
  <div class="modal fade" id="addAssetModal" tabindex="-1">
    <div class="modal-dialog">
      <form action="save_asset.php" method="post" class="modal-content p-3">
        <h5 class="mb-3">Add New Asset</h5>
        <div class="mb-3">
          <label class="form-label">Asset Name</label>
          <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Serial Number</label>
          <input type="text" name="serial" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Type</label>
          <select name="type" class="form-select">
            <option value="Laptop">Laptop</option>
            <option value="Tablet">Tablet</option>
            <option value="Phone">Phone</option>
            <option value="Other">Other</option>
          </select>
        </div>
        <hr>
        <div class="mb-3">
          <label class="form-label">Assign to Existing User</label>
          <select name="user_id" class="form-select">
            <option value="">-- Select --</option>
            <?php
              $users = $conn->query("SELECT id, name FROM users");
              while ($u = $users->fetch_assoc()) {
                echo "<option value='{$u['id']}'>{$u['name']}</option>";
              }
            ?>
          </select>
        </div>
        <div class="mb-3">
          <label class="form-label">Or Create New User</label>
          <input type="text" name="new_user_name" class="form-control mb-2" placeholder="Name">
          <input type="email" name="new_user_email" class="form-control mb-2" placeholder="Email">
          <select name="new_user_type" class="form-select">
            <option value="employee">Employee</option>
            <option value="visitor">Visitor</option>
          </select>
        </div>
        <div class="text-end">
          <button type="submit" class="btn btn-success">Save Asset</button>
        </div>
      </form>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
</html>