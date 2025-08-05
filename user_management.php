<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>User Management</title>
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
    .user-card {
      background: white;
      border-radius: 10px;
      padding: 20px;
      margin-bottom: 20px;
      box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    .user-role { font-size: 14px; color: gray; }
    .user-status {
      display: inline-block;
      padding: 4px 10px;
      font-size: 12px;
      border-radius: 20px;
      color: white;
      background: green;
    }
    .asset-toggle {
      background: #007bff;
      color: #fff;
      padding: 5px 10px;
      border-radius: 6px;
      text-decoration: none;
      cursor: pointer;
      display: inline-block;
      margin-top: 10px;
    }
    .asset-section {
      display: none;
      margin-top: 15px;
      border-top: 1px solid #ccc;
      padding-top: 10px;
    }
    .asset-list li { margin-bottom: 6px; }
    .asset-list .actions i {
      margin-left: 8px;
      color: #007bff;
      cursor: pointer;
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
    <a href="#" class="active"><i class="fas fa-users-cog nav-icon"></i> User Management</a>
    <a href="gate-log.php"><i class="fas fa-file-alt nav-icon"></i> Gate Logs</a>
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
      <input type="text" class="form-control w-50" placeholder="Search users...">
      <div>
        <i class="fas fa-bell"></i> <span class="badge bg-danger">3</span>
        <span class="ms-3">John Admin</span>
        <span class="badge bg-secondary rounded-circle ms-2">JA</span>
      </div>
    </div>

    <div class="container mt-4">
      <!-- Welcome Section -->
      <div class="welcome d-flex justify-content-between align-items-center">
        <div>
          <h2>User Management</h2>
          <p>Manage registered users and their assigned assets</p>
        </div>
        <button class="btn btn-light text-primary">+ Add User</button>
      </div>

      <!-- Stats (Optional Static for Now) -->
      <div class="row mb-4">
        <div class="col-md-4">
          <div class="bg-white p-4 rounded shadow-sm text-center">
            <h6>Total Users</h6>
            <h3>4</h3>
          </div>
        </div>
        <div class="col-md-4">
          <div class="bg-white p-4 rounded shadow-sm text-center">
            <h6>Total Assets</h6>
            <h3>6</h3>
          </div>
        </div>
        <div class="col-md-4">
          <div class="bg-white p-4 rounded shadow-sm text-center">
            <h6>Avg Assets/User</h6>
            <h3>1.5</h3>
          </div>
        </div>
      </div>

      <!-- Sample User Card -->
      <div class="user-card">
        <div class="d-flex justify-content-between">
          <div>
            <h5 class="mb-0">Sarah Johnson</h5>
            <span class="user-role">IT Technician</span><br>
            <small>sarah.johnson@company.com</small><br>
            <span class="user-status mt-2">Active</span>
          </div>
          <div>
            <button class="asset-toggle" onclick="toggleAssets(this)">2 Assets</button>
          </div>
        </div>
        <div class="asset-section">
          <ul class="asset-list mt-3">
            <li>
              Dell Latitude 5420 — SN: DELL12345
              <span class="actions">
                <i class="fas fa-edit" title="Edit"></i>
                <i class="fas fa-trash-alt" title="Delete"></i>
              </span>
            </li>
            <li>
              MacBook Pro 14" — SN: MAC78900
              <span class="actions">
                <i class="fas fa-edit" title="Edit"></i>
                <i class="fas fa-trash-alt" title="Delete"></i>
              </span>
            </li>
          </ul>
        </div>
      </div>

    </div>
  </div>
</div>

<script>
  function toggleAssets(btn) {
    const section = btn.closest('.user-card').querySelector('.asset-section');
    section.style.display = section.style.display === 'block' ? 'none' : 'block';
  }
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
