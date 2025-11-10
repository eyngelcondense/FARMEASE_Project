<?php

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Venues and Packages - San Isidro Labrador Resort</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.6.0/css/all.min.css">
  
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f5f3f0;
      color: #3b2a18;
      overflow-x: hidden;
    }

    /* Sidebar */
    .sidebar {
      position: fixed;
      left: 0;
      top: 0;
      width: 220px;
      height: 100vh;
      background-color: #8b7d6b;
      color: white;
      overflow-y: auto;
      z-index: 1000;
      padding-bottom: 20px;
    }

    .sidebar-header {
      padding: 20px 15px;
      border-bottom: 1px solid rgba(255,255,255,0.1);
    }

    .sidebar-logo {
      display: flex;
      align-items: center;
      gap: 12px;
      margin-bottom: 15px;
    }

    .sidebar-logo-icon {
      width: 40px;
      height: 40px;
      background-color: white;
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
      overflow: hidden;
    }

    .sidebar-logo-icon img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .sidebar-logo-icon i {
      color: #8b7d6b;
      font-size: 20px;
    }

    .sidebar-title {
      font-size: 12px;
      font-weight: 600;
      line-height: 1.3;
      color: white;
    }

    .quick-add-btn {
      display: flex;
      align-items: center;
      gap: 10px;
      background-color: rgba(255,255,255,0.15);
      border: none;
      color: white;
      padding: 10px 12px;
      border-radius: 8px;
      width: 100%;
      font-size: 13px;
      font-weight: 500;
      transition: all 0.3s;
      margin-top: 15px;
      cursor: pointer;
    }

    .quick-add-btn:hover {
      background-color: rgba(255,255,255,0.25);
    }

    .quick-add-btn-icon {
      width: 28px;
      height: 28px;
      background-color: white;
      color: #8b7d6b;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 14px;
      flex-shrink: 0;
    }

    .quick-add-text {
      text-align: left;
      flex: 1;
    }

    .quick-add-text-title {
      font-weight: 600;
      font-size: 13px;
    }

    .quick-add-text-sub {
      font-size: 10px;
      opacity: 0.8;
    }

    .nav-section {
      padding: 15px 12px 10px;
    }

    .nav-section-title {
      font-size: 10px;
      font-weight: 600;
      text-transform: uppercase;
      color: rgba(255,255,255,0.5);
      margin-bottom: 8px;
      padding: 0 8px;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .nav-menu {
      list-style: none;
    }

    .nav-item {
      margin-bottom: 3px;
    }

    .nav-link {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 10px 12px;
      color: rgba(255,255,255,0.85);
      text-decoration: none;
      border-radius: 6px;
      font-size: 13px;
      font-weight: 400;
      transition: all 0.3s;
      position: relative;
    }

    .nav-link:hover {
      background-color: rgba(255,255,255,0.1);
      color: white;
      transform: translateX(3px);
    }

    .nav-link.active {
      background-color: #6d5d4d;
      color: white;
      font-weight: 600;
      box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    }

    .nav-link.active::before {
      content: '';
      position: absolute;
      left: 0;
      top: 50%;
      transform: translateY(-50%);
      width: 4px;
      height: 70%;
      background-color: white;
      border-radius: 0 4px 4px 0;
    }

    .nav-link.active i {
      color: white;
      transform: scale(1.1);
    }

    .nav-link i {
      font-size: 16px;
      width: 18px;
      text-align: center;
      transition: transform 0.3s;
    }

    /* Main Layout Container */
    .main-layout {
      margin-left: 220px;
      min-height: 100vh;
      padding: 30px 35px;
      background-color: #f5f3f0;
    }

    /* Page Header Card */
    .page-header-card {
      background-color: white;
      border-radius: 10px;
      padding: 22px 25px;
      margin-bottom: 22px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.04);
    }

    .page-header-card h1 {
      font-size: 20px;
      font-weight: 600;
      color: #3b2a18;
      margin: 0;
    }

    /* Add Event Button */
    .add-event-btn {
      display: inline-flex;
      align-items: center;
      gap: 10px;
      background-color: #3b2a18;
      border: none;
      color: white;
      padding: 10px 12px;
      border-radius: 8px;
      font-size: 13px;
      font-weight: 500;
      transition: all 0.3s;
      cursor: pointer;
      margin-bottom: 20px;
    }

    .add-event-btn:hover {
      background-color: #2a1f12;
    }

    .add-event-btn-icon {
      width: 28px;
      height: 28px;
      background-color: white;
      color: #3b2a18;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 14px;
      flex-shrink: 0;
    }

    .add-event-text {
      text-align: left;
      flex: 1;
    }

    .add-event-text-title {
      font-weight: 600;
      font-size: 13px;
      display: block;
    }

    .add-event-text-sub {
      font-size: 10px;
      opacity: 0.8;
      display: block;
    }

    /* Table Card */
    .table-card {
      background-color: white;
      border-radius: 10px;
      padding: 25px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.04);
      overflow-x: auto;
    }

    /* Table */
    .venues-table {
      width: 100%;
      border-collapse: separate;
      border-spacing: 0;
      min-width: 800px;
    }

    .venues-table thead {
      background-color: #e8e3db;
    }

    .venues-table thead th {
      padding: 12px 18px;
      text-align: left;
      font-size: 13px;
      font-weight: 600;
      color: #3b2a18;
      border: none;
    }

    .venues-table thead th:first-child {
      border-radius: 8px 0 0 8px;
    }

    .venues-table thead th:last-child {
      border-radius: 0 8px 8px 0;
    }

    .venues-table tbody tr {
      border-bottom: 1px solid #f0ede8;
      transition: background-color 0.3s;
    }

    .venues-table tbody tr:hover {
      background-color: #faf8f5;
    }

    .venues-table tbody tr:last-child {
      border-bottom: none;
    }

    .venues-table tbody td {
      padding: 15px 18px;
      font-size: 13px;
      color: #5a4a3a;
    }

    .venue-name {
      font-weight: 500;
      color: #3b2a18;
    }

    /* Action Buttons */
    .action-buttons {
      display: flex;
      gap: 8px;
    }

    .btn-edit,
    .btn-delete {
      padding: 6px 14px;
      border-radius: 6px;
      font-size: 12px;
      font-weight: 500;
      border: none;
      cursor: pointer;
      transition: all 0.3s;
    }

    .btn-edit {
      background-color: #3b2a18;
      color: white;
    }

    .btn-edit:hover {
      background-color: #2a1f12;
    }

    .btn-delete {
      background-color: #d9534f;
      color: white;
    }

    .btn-delete:hover {
      background-color: #c9302c;
    }

    /* Mobile Menu Toggle */
    .mobile-menu-toggle {
      position: fixed;
      top: 20px;
      left: 20px;
      z-index: 1001;
      background: #8b7d6b;
      color: white;
      border: none;
      width: 40px;
      height: 40px;
      border-radius: 8px;
      display: none;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      font-size: 18px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.2);
    }

    /* Responsive */
    @media (max-width: 992px) {
      .sidebar {
        transform: translateX(-100%);
        transition: transform 0.3s;
      }
      .sidebar.active {
        transform: translateX(0);
      }
      .main-layout {
        margin-left: 0;
        padding: 20px 15px;
      }
      .mobile-menu-toggle {
        display: flex;
      }
    }

    @media (max-width: 768px) {
      .add-event-btn {
        width: 100%;
        justify-content: center;
      }
    }
  </style>
</head>
<body>

  <!-- Mobile Menu Toggle -->
  <button class="mobile-menu-toggle" onclick="toggleSidebar()">
    <i class="fas fa-bars"></i>
  </button>

  <!-- Left Sidebar -->
  <aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
      <div class="sidebar-logo">
        <div class="sidebar-logo-icon">
          <img src="LOGO NG SAN ISIDRO.png" alt="San Isidro Logo">
        </div>
        <div class="sidebar-title">San Isidro Labrador<br>Resort and Leisure Farm</div>
      </div>
      <button class="quick-add-btn">
        <div class="quick-add-btn-icon">
          <i class="fas fa-plus"></i>
        </div>
        <div class="quick-add-text">
          <div class="quick-add-text-title">Add Quick Event</div>
          <div class="quick-add-text-sub">Events</div>
        </div>
      </button>
    </div>

    <nav class="nav-section">
      <div class="nav-section-title">
        MAIN NAVIGATION
        <i class="fas fa-chevron-down" style="font-size: 9px;"></i>
      </div>
      <ul class="nav-menu">
        <li class="nav-item">
          <a href="admin-dashboard.php" class="nav-link" data-page="dashboard">
            <i class="fas fa-th-large"></i>
            Dashboard
          </a>
        </li>
        <li class="nav-item">
          <a href="admin-venues-packages.php" class="nav-link active" data-page="venues">
            <i class="fas fa-map-marker-alt"></i>
            Manage Venues and Packages
          </a>
        </li>
        <li class="nav-item">
          <a href="admin-bookings.php" class="nav-link" data-page="bookings">
            <i class="fas fa-calendar-check"></i>
            Bookings
          </a>
        </li>
        <li class="nav-item">
          <a href="admin-payments.php" class="nav-link" data-page="payments">
            <i class="fas fa-credit-card"></i>
            Payments
          </a>
        </li>
        <li class="nav-item">
          <a href="admin-gallery.php" class="nav-link" data-page="gallery">
            <i class="fas fa-images"></i>
            Gallery
          </a>
        </li>
      </ul>
    </nav>

    <nav class="nav-section">
      <div class="nav-section-title">
        SUPPORT AND SETTINGS
        <i class="fas fa-chevron-down" style="font-size: 9px;"></i>
      </div>
      <ul class="nav-menu">
        <li class="nav-item">
          <a href="admin-feedback.php" class="nav-link" data-page="feedback">
            <i class="fas fa-comment-dots"></i>
            Feedback and Inquiries
          </a>
        </li>
        <li class="nav-item">
          <a href="admin-calendar.php" class="nav-link" data-page="calendar">
            <i class="fas fa-calendar-alt"></i>
            Calendar of Events
          </a>
        </li>
      </ul>
    </nav>

    <nav class="nav-section">
      <div class="nav-section-title">
        ACCOUNT MANAGEMENT
        <i class="fas fa-chevron-down" style="font-size: 9px;"></i>
      </div>
      <ul class="nav-menu">
        <li class="nav-item">
          <a href="admin-staffs.php" class="nav-link" data-page="staffs">
            <i class="fas fa-users"></i>
            Manage Staffs
          </a>
        </li>
        <li class="nav-item">
          <a href="logout.php" class="nav-link" data-page="logout">
            <i class="fas fa-sign-out-alt"></i>
            Logout
          </a>
        </li>
      </ul>
    </nav>
  </aside>

  <!-- Main Content Area -->
  <div class="main-layout">
    <!-- Page Header Card -->
    <div class="page-header-card">
      <h1>Manage Venues and Packages</h1>
    </div>

    <!-- Add Event Button -->
    <button class="add-event-btn" onclick="addNewVenue()">
      <div class="add-event-btn-icon">
        <i class="fas fa-plus"></i>
      </div>
      <div class="add-event-text">
        <span class="add-event-text-title">Add Quick Event</span>
        <span class="add-event-text-sub">Events</span>
      </div>
    </button>

    <!-- Venues Table -->
    <div class="table-card">
      <table class="venues-table">
        <thead>
          <tr>
            <th>Venue</th>
            <th>Category</th>
            <th>Type</th>
            <th>Rate</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td class="venue-name">Enclosed Venue</td>
            <td>Enclosed</td>
            <td>Basic</td>
            <td>Php 15,000</td>
            <td>
              <div class="action-buttons">
                <button class="btn-edit" onclick="editVenue(1)">Edit</button>
                <button class="btn-delete" onclick="deleteVenue(1)">Delete</button>
              </div>
            </td>
          </tr>
          <tr>
            <td class="venue-name">Open Venue</td>
            <td>Open</td>
            <td>Premium</td>
            <td>Php 10,000</td>
            <td>
              <div class="action-buttons">
                <button class="btn-edit" onclick="editVenue(2)">Edit</button>
                <button class="btn-delete" onclick="deleteVenue(2)">Delete</button>
              </div>
            </td>
          </tr>
          <tr>
            <td class="venue-name">Playground</td>
            <td>Open</td>
            <td>Premium</td>
            <td>Php 5,000</td>
            <td>
              <div class="action-buttons">
                <button class="btn-edit" onclick="editVenue(3)">Edit</button>
                <button class="btn-delete" onclick="deleteVenue(3)">Delete</button>
              </div>
            </td>
          </tr>
          <tr>
            <td class="venue-name">Cafe 2nd Floor</td>
            <td>Open</td>
            <td>Premium</td>
            <td>Php 20,000</td>
            <td>
              <div class="action-buttons">
                <button class="btn-edit" onclick="editVenue(4)">Edit</button>
                <button class="btn-delete" onclick="deleteVenue(4)">Delete</button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  
  <script>
    // Toggle Sidebar for Mobile
    function toggleSidebar() {
      document.getElementById('sidebar').classList.toggle('active');
    }

    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', (e) => {
      const sidebar = document.getElementById('sidebar');
      const toggle = document.querySelector('.mobile-menu-toggle');
      
      if (window.innerWidth <= 992) {
        if (!sidebar.contains(e.target) && !toggle.contains(e.target)) {
          sidebar.classList.remove('active');
        }
      }
    });

    // Add New Venue Function
    function addNewVenue() {
      alert('Add New Venue functionality\n\nThis will open a modal or redirect to an add venue form.');
    }

    // Edit Venue Function
    function editVenue(id) {
      alert(`Edit Venue ID: ${id}\n\nThis will open a modal or redirect to an edit form with venue details.`);
    }

    // Delete Venue Function
    function deleteVenue(id) {
      if (confirm('Are you sure you want to delete this venue?')) {
        alert(`Delete Venue ID: ${id}\n\nVenue will be deleted from database.`);
      }
    }
  </script>
</body>
</html>