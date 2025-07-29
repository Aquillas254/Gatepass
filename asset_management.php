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
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
    .top-bar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 15px 20px;
      background-color: white;
      border-bottom: 1px solid #dee2e6;
    }
  </style>
</head>
<body>
  <div class="d-flex">
    <!-- Sidebar -->
    <div class="sidebar p-3">
      <h4 class="mb-4"><i class="fas fa-shield-alt"></i> GatePass</h4>
      <a href="dashboard.php"><i class="fas fa-home nav-icon"></i> Dashboard</a>
      <a href="#"><i class="fas fa-id-badge nav-icon"></i> Employee Gatepass</a>
      <a href="#"><i class="fas fa-user-check nav-icon"></i> Visitor Check-in</a>
      <a href="asset_management.php" class="active"><i class="fas fa-boxes nav-icon"></i> Asset Management</a>
      <a href="#"><i class="fas fa-users-cog nav-icon"></i> User Management</a>
      <a href="#"><i class="fas fa-file-alt nav-icon"></i> Gate Logs</a>
      <a href="#"><i class="fas fa-bell nav-icon"></i> Notifications</a>
      <a href="#"><i class="fas fa-cog nav-icon"></i> Settings</a>
      <div class="mt-4">
        <div class="fw-bold">Admin</div>
        <small>Admin | IT</small><br>
        <a href="#"><i class="fas fa-sign-out-alt nav-icon"></i> Sign Out</a>
      </div>
    </div>

    <!-- Main Content -->
    <div class="flex-grow-1">
      <div class="top-bar">
        <h4 class="mb-0">Asset Management</h4>
        <a href="#addAssetModal" class="btn btn-primary" data-bs-toggle="modal">+ Add New Asset</a>
      </div>

      <div class="container mt-4">
        <div class="mb-4">
          <input type="text" class="form-control" placeholder="Search assets...">
        </div>

        <h5>Assigned Assets</h5>
        <?php while ($row = $result->fetch_assoc()): ?>
          <div class="card mb-3">
            <div class="card-body">
              <h5><?= $row['name'] ?></h5>
              <p><strong>Serial:</strong> <?= $row['serial'] ?></p>
              <p><strong>Type:</strong> <?= $row['type'] ?></p>
              <p><strong>Owner:</strong> <?= $row['owner_name'] ?? 'Unassigned' ?> (<?= $row['user_type'] ?? 'N/A' ?>)</p>
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
        <h5>Add New Asset</h5>
        <div class="mb-3">
          <label>Asset Name</label>
          <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
          <label>Serial Number</label>
          <input type="text" name="serial" class="form-control" required>
        </div>
        <div class="mb-3">
          <label>Type</label>
          <select name="type" class="form-select">
            <option value="Laptop">Laptop</option>
            <option value="Tablet">Tablet</option>
            <option value="Phone">Phone</option>
            <option value="Other">Other</option>
          </select>
        </div>

        <hr>
        <div class="mb-3">
          <label>Assign to Existing User</label>
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
          <label>Or Create New User</label>
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