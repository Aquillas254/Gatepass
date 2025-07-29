<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>GatePass Dashboard</title>
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
    .top-bar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 15px 20px;
      background-color: white;
      border-bottom: 1px solid #dee2e6;
    }
    .metrics {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
    }
    .metrics .card {
      flex: 1;
      min-width: 180px;
    }
  </style>
</head>
<body>
  <div class="d-flex">
    <!-- Sidebar -->
    <div class="sidebar p-3">
      <h4 class="mb-4"> <i class="fas fa-shield-alt"></i> GatePass</h4>
      <div class="fw-bold text-uppercase small">Navigation</div>
      <a href="#" class="active"><i class="fas fa-home nav-icon"></i> Dashboard</a>
      <a href="#"><i class="fas fa-id-badge nav-icon"></i> Employee Gatepass</a>
      <a href="#"><i class="fas fa-user-check nav-icon"></i> Visitor Check-in</a>
      <a href="asset_management.php"><i class="fas fa-boxes nav-icon"></i> Asset Management</a>
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
      <div class="top-bar">
        <input type="text" class="form-control w-50" placeholder="Search gatepasses, visitors...">
        <div>
          <i class="fas fa-bell"></i> <span class="badge bg-danger">3</span>
          <span class="ms-3">John Admin</span>
          <span class="badge bg-secondary rounded-circle ms-2">JA</span>
        </div>
      </div>

      <div class="container mt-4">
        <div class="welcome">
          <h2>Welcome back, John Admin</h2>
          <p>Manage your organization's security and access control</p>
        </div>

        <!-- Metric Cards -->
        <div class="metrics">
          <div class="card">
            <div class="card-body">
              <h5>Total Employees</h5>
              <h3>142</h3>
              <p class="text-success">+12% from last week</p>
            </div>
          </div>
          <div class="card">
            <div class="card-body">
              <h5>Active Gatepasses</h5>
              <h3>8</h3>
              <p class="text-muted">0% from last week</p>
            </div>
          </div>
          <div class="card">
            <div class="card-body">
              <h5>Today's Visitors</h5>
              <h3>23</h3>
              <p class="text-success">+12% from last week</p>
            </div>
          </div>
          <div class="card">
            <div class="card-body">
              <h5>Pending Approvals</h5>
              <h3>5</h3>
              <p class="text-danger">-5% from last week</p>
            </div>
          </div>
          <div class="card">
            <div class="card-body">
              <h5>Assets Out</h5>
              <h3>12</h3>
              <p class="text-muted">0% from last week</p>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</body>
</html>