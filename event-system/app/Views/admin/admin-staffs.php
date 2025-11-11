<?php

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Staff Management - San Isidro Labrador Resort</title>
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
      background-color: #e8e3db;
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
      background-color: #e8e3db;
    }

    /* Page Header */
    .page-header {
      margin-bottom: 25px;
    }

    .page-header h1 {
      font-size: 20px;
      font-weight: 600;
      color: #8b7d6b;
      margin: 0;
    }

    /* Staff Controls */
    .staff-controls {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 25px;
      flex-wrap: wrap;
      gap: 15px;
    }

    .staff-stats {
      display: flex;
      gap: 20px;
    }

    .stat-item {
      display: flex;
      align-items: center;
      gap: 12px;
      background-color: rgba(139, 125, 107, 0.15);
      padding: 12px 18px;
      border-radius: 50px;
    }

    .stat-icon {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background-color: #8b7d6b;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-size: 16px;
    }

    .stat-info h3 {
      font-size: 11px;
      font-weight: 500;
      color: #8b7d6b;
      margin: 0 0 2px 0;
    }

    .stat-info p {
      font-size: 20px;
      font-weight: 600;
      color: #3b2a18;
      margin: 0;
    }

    .controls-right {
      display: flex;
      gap: 12px;
      align-items: center;
    }

    .status-filter {
      padding: 10px 35px 10px 15px;
      border: 1px solid #c4b9a8;
      border-radius: 8px;
      background-color: white;
      font-size: 13px;
      color: #3b2a18;
      cursor: pointer;
      appearance: none;
      background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%233b2a18' d='M6 8L2 4h8z'/%3E%3C/svg%3E");
      background-repeat: no-repeat;
      background-position: right 12px center;
      min-width: 120px;
    }

    .search-box-staff {
      position: relative;
    }

    .search-box-staff input {
      padding: 10px 15px 10px 40px;
      border: 1px solid #c4b9a8;
      border-radius: 8px;
      background-color: white;
      font-size: 13px;
      color: #3b2a18;
      width: 250px;
    }

    .search-box-staff input::placeholder {
      color: #a89b88;
    }

    .search-box-staff input:focus {
      outline: none;
      border-color: #8b7d6b;
    }

    .search-box-staff i {
      position: absolute;
      left: 15px;
      top: 50%;
      transform: translateY(-50%);
      color: #a89b88;
      font-size: 14px;
    }

    .add-staff-btn {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      background-color: #3b2a18;
      border: none;
      color: white;
      padding: 10px 18px;
      border-radius: 8px;
      font-size: 13px;
      font-weight: 500;
      cursor: pointer;
      transition: all 0.3s;
    }

    .add-staff-btn:hover {
      background-color: #2a1f12;
      transform: translateY(-2px);
    }

    /* Table Card */
    .table-card {
      background-color: white;
      border-radius: 12px;
      padding: 0;
      box-shadow: 0 2px 8px rgba(0,0,0,0.06);
      overflow: hidden;
    }

    /* Table */
    .staff-table {
      width: 100%;
      border-collapse: collapse;
    }

    .staff-table thead {
      background-color: #e8e3db;
    }

    .staff-table thead th {
      padding: 15px 20px;
      text-align: left;
      font-size: 13px;
      font-weight: 600;
      color: #3b2a18;
      border: none;
    }

    .staff-table tbody tr {
      border-bottom: 1px solid #e8e3db;
      transition: background-color 0.3s;
    }

    .staff-table tbody tr:hover {
      background-color: #faf8f5;
    }

    .staff-table tbody tr:last-child {
      border-bottom: none;
    }

    .staff-table tbody td {
      padding: 18px 20px;
      font-size: 13px;
      color: #8b7d6b;
    }

    .staff-name {
      font-weight: 500;
      color: #8b7d6b;
    }

    .role-dropdown,
    .status-dropdown,
    .event-dropdown {
      padding: 6px 28px 6px 12px;
      border: 1px solid #c4b9a8;
      border-radius: 6px;
      background-color: white;
      font-size: 12px;
      color: #3b2a18;
      cursor: pointer;
      appearance: none;
      background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='10' viewBox='0 0 10 10'%3E%3Cpath fill='%233b2a18' d='M5 7L2 3h6z'/%3E%3C/svg%3E");
      background-repeat: no-repeat;
      background-position: right 10px center;
      min-width: 120px;
    }

    .assign-btn {
      padding: 7px 16px;
      background-color: #8b7d6b;
      color: white;
      border: none;
      border-radius: 6px;
      font-size: 12px;
      font-weight: 500;
      cursor: pointer;
      transition: all 0.3s;
    }

    .assign-btn:hover {
      background-color: #7a6a58;
      transform: translateY(-1px);
    }

    /* Assignment Modal */
    .modal-overlay {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0,0,0,0.6);
      z-index: 2000;
      align-items: center;
      justify-content: center;
      backdrop-filter: blur(5px);
    }

    .modal-overlay.active {
      display: flex;
    }

    .assignment-modal {
      background-color: #8b7d6b;
      border-radius: 12px;
      padding: 35px;
      width: 90%;
      max-width: 450px;
      box-shadow: 0 10px 40px rgba(0,0,0,0.4);
      position: relative;
      color: white;
    }

    .modal-header {
      display: flex;
      align-items: center;
      gap: 18px;
      margin-bottom: 20px;
    }

    .modal-icon {
      width: 60px;
      height: 60px;
      background-color: white;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
    }

    .modal-icon img {
      width: 35px;
      height: 35px;
    }

    .modal-title-section h2 {
      font-size: 19px;
      font-weight: 600;
      margin: 0 0 5px 0;
      color: white;
    }

    .modal-subtitle {
      font-size: 12px;
      color: rgba(255,255,255,0.85);
      margin: 0;
      line-height: 1.4;
    }

    .modal-divider {
      width: 100%;
      height: 1px;
      background-color: rgba(255,255,255,0.25);
      margin: 20px 0 25px 0;
    }

    .form-group {
      margin-bottom: 18px;
    }

    .form-label {
      display: block;
      font-size: 13px;
      font-weight: 500;
      margin-bottom: 8px;
      color: white;
    }

    .form-input,
    .form-select,
    .form-textarea {
      width: 100%;
      padding: 11px 14px;
      border: 1px solid rgba(255,255,255,0.3);
      border-radius: 8px;
      background-color: rgba(255,255,255,0.15);
      font-size: 13px;
      color: white;
      font-family: 'Poppins', sans-serif;
      transition: all 0.3s;
    }

    .form-input::placeholder,
    .form-textarea::placeholder {
      color: rgba(255,255,255,0.6);
    }

    .form-input:focus,
    .form-select:focus,
    .form-textarea:focus {
      outline: none;
      border-color: white;
      background-color: rgba(255,255,255,0.22);
    }

    .form-select {
      cursor: pointer;
      appearance: none;
      background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='white' d='M6 8L2 4h8z'/%3E%3C/svg%3E");
      background-repeat: no-repeat;
      background-position: right 14px center;
      padding-right: 40px;
    }

    .form-select option {
      background-color: #8b7d6b;
      color: white;
    }

    .form-textarea {
      resize: vertical;
      min-height: 75px;
    }

    .modal-actions {
      display: flex;
      gap: 12px;
      margin-top: 28px;
    }

    .btn-assign-modal {
      flex: 1;
      padding: 13px;
      background-color: white;
      color: #8b7d6b;
      border: none;
      border-radius: 8px;
      font-size: 14px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
    }

    .btn-assign-modal:hover {
      background-color: #f5f3f0;
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    }

    .btn-cancel-modal {
      flex: 1;
      padding: 13px;
      background-color: transparent;
      color: white;
      border: 2px solid rgba(255,255,255,0.5);
      border-radius: 8px;
      font-size: 14px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s;
    }

    .btn-cancel-modal:hover {
      background-color: rgba(255,255,255,0.12);
      border-color: white;
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
      .staff-controls {
        flex-direction: column;
        align-items: stretch;
      }
      .staff-stats {
        flex-wrap: wrap;
      }
      .controls-right {
        flex-wrap: wrap;
      }
      .search-box-staff input {
        width: 100%;
      }
    }

    @media (max-width: 768px) {
      .assignment-modal {
        width: 95%;
        padding: 28px 22px;
      }
      .staff-table {
        font-size: 12px;
      }
      .staff-table thead th,
      .staff-table tbody td {
        padding: 12px 10px;
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
          <a href="admin-venues-packages.php" class="nav-link" data-page="venues">
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
            Feedback
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
          <a href="admin-staffs.php" class="nav-link active" data-page="staffs">
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
    <!-- Page Header -->
    <div class="page-header">
      <h1>Staff Management</h1>
    </div>

    <!-- Staff Stats and Controls -->
    <div class="staff-controls">
      <div class="staff-stats">
        <div class="stat-item">
          <div class="stat-icon">
            <i class="fas fa-user-check"></i>
          </div>
          <div class="stat-info">
            <h3>Assigned Staffs</h3>
            <p>3</p>
          </div>
        </div>
        <div class="stat-item">
          <div class="stat-icon">
            <i class="fas fa-user-clock"></i>
          </div>
          <div class="stat-info">
            <h3>Available Staffs</h3>
            <p>12</p>
          </div>
        </div>
      </div>

      <div class="controls-right">
        <select class="status-filter" id="statusFilter" onchange="filterStaff()">
          <option value="all">Status</option>
          <option value="available">Available</option>
          <option value="assigned">Assigned</option>
          <option value="unavailable">Unavailable</option>
        </select>

        <div class="search-box-staff">
          <i class="fas fa-search"></i>
          <input type="text" placeholder="Search ..." id="searchStaff" onkeyup="searchStaff()">
        </div>

        <button class="add-staff-btn" onclick="addStaff()">
          <i class="fas fa-plus"></i>
          Add Staffs
        </button>
      </div>
    </div>

    <!-- Staff Table -->
    <div class="table-card">
      <table class="staff-table" id="staffTable">
        <thead>
          <tr>
            <th>Staff Name</th>
            <th>Role</th>
            <th>Status</th>
            <th>Event</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr data-status="available">
            <td class="staff-name">Apple Templa</td>
            <td>
              <select class="role-dropdown">
                <option>Cleaner</option>
                <option>Waiter</option>
                <option>Security</option>
                <option>Coordinator</option>
              </select>
            </td>
            <td>
              <select class="status-dropdown">
                <option>Available</option>
                <option>Assigned</option>
                <option>Unavailable</option>
              </select>
            </td>
            <td>
              <select class="event-dropdown">
                <option>Wedding</option>
                <option>Birthday</option>
                <option>Corporate</option>
                <option>Meeting</option>
              </select>
            </td>
            <td>
              <button class="assign-btn" onclick="openAssignModal('Apple Templa')">Assign</button>
            </td>
          </tr>
          <tr data-status="available">
            <td class="staff-name">Earlsin Combenido</td>
            <td>
              <select class="role-dropdown">
                <option>Waiter</option>
                <option>Cleaner</option>
                <option>Security</option>
                <option>Coordinator</option>
              </select>
            </td>
            <td>
              <select class="status-dropdown">
                <option>Available</option>
                <option>Assigned</option>
                <option>Unavailable</option>
              </select>
            </td>
            <td>
              <select class="event-dropdown">
                <option>-</option>
                <option>Wedding</option>
                <option>Birthday</option>
                <option>Corporate</option>
              </select>
            </td>
            <td>
              <button class="assign-btn" onclick="openAssignModal('Earlsin Combenido')">Assign</button>
            </td>
          </tr>
          <tr data-status="available">
            <td class="staff-name">Jean Iwayan</td>
            <td>
              <select class="role-dropdown">
                <option>Security</option>
                <option>Cleaner</option>
                <option>Waiter</option>
                <option>Coordinator</option>
              </select>
            </td>
            <td>
              <select class="status-dropdown">
                <option>Available</option>
                <option>Assigned</option>
                <option>Unavailable</option>
              </select>
            </td>
            <td>
              <select class="event-dropdown">
                <option>-</option>
                <option>Wedding</option>
                <option>Birthday</option>
                <option>Corporate</option>
              </select>
            </td>
            <td>
              <button class="assign-btn" onclick="openAssignModal('Jean Iwayan')">Assign</button>
            </td>
          </tr>
          <tr data-status="available">
            <td class="staff-name">Ryan Magnaye</td>
            <td>
              <select class="role-dropdown">
                <option>Coordinator</option>
                <option>Cleaner</option>
                <option>Waiter</option>
                <option>Security</option>
              </select>
            </td>
            <td>
              <select class="status-dropdown">
                <option>Available</option>
                <option>Assigned</option>
                <option>Unavailable</option>
              </select>
            </td>
            <td>
              <select class="event-dropdown">
                <option>-</option>
                <option>Wedding</option>
                <option>Birthday</option>
                <option>Corporate</option>
              </select>
            </td>
            <td>
              <button class="assign-btn" onclick="openAssignModal('Ryan Magnaye')">Assign</button>
            </td>
          </tr>
          <tr data-status="available">
            <td class="staff-name">Gilbert Bumanglag</td>
            <td>
              <select class="role-dropdown">
                <option>Cleaner</option>
                <option>Waiter</option>
                <option>Security</option>
                <option>Coordinator</option>
              </select>
            </td>
            <td>
              <select class="status-dropdown">
                <option>Available</option>
                <option>Assigned</option>
                <option>Unavailable</option>
              </select>
            </td>
            <td>
              <select class="event-dropdown">
                <option>-</option>
                <option>Wedding</option>
                <option>Birthday</option>
                <option>Corporate</option>
              </select>
            </td>
            <td>
              <button class="assign-btn" onclick="openAssignModal('Gilbert Bumanglag')">Assign</button>
            </td>
          </tr>
          <tr data-status="available">
            <td class="staff-name">Johnmoreen Rol</td>
            <td>
              <select class="role-dropdown">
                <option>Waiter</option>
                <option>Cleaner</option>
                <option>Security</option>
                <option>Coordinator</option>
              </select>
            </td>
            <td>
              <select class="status-dropdown">
                <option>Available</option>
                <option>Assigned</option>
                <option>Unavailable</option>
              </select>
            </td>
            <td>
              <select class="event-dropdown">
                <option>-</option>
                <option>Wedding</option>
                <option>Birthday</option>
                <option>Corporate</option>
              </select>
            </td>
            <td>
              <button class="assign-btn" onclick="openAssignModal('Johnmoreen Rol')">Assign</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Assignment Modal -->
  <div class="modal-overlay" id="assignModal">
    <div class="assignment-modal">
      <div class="modal-header">
        <div class="modal-icon">
          <img src="LOGO NG SAN ISIDRO.png" alt="Logo">
        </div>
        <div class="modal-title-section">
          <h2 id="staffNameModal">Apple Templa</h2>
          <p class="modal-subtitle">Status: Available<br>Contact: 09123456789</p>
        </div>
      </div>

      <div class="modal-divider"></div>

      <form id="assignmentForm">
        <div class="form-group">
          <label class="form-label">Event Name:</label>
          <select class="form-select" id="eventName" required>
            <option value="">Select Event</option>
            <option value="wedding">Wedding</option>
            <option value="birthday">Birthday Party</option>
            <option value="corporate">Corporate Event</option>
            <option value="meeting">Meeting</option>
          </select>
        </div>

        <div class="form-group">
          <label class="form-label">Venue:</label>
          <select class="form-select" id="venue" required>
            <option value="">Select Venue</option>
            <option value="enclosed">Enclosed</option>
            <option value="open">Open Venue</option>
            <option value="cafe">Cafe 2nd Floor</option>
            <option value="playground">Playground</option>
          </select>
        </div>

        <div class="form-group">
          <label class="form-label">Date:</label>
          <input type="date" class="form-input" id="eventDate" required>
        </div>

        <div class="form-group">
          <label class="form-label">Time:</label>
          <input type="text" class="form-input" id="eventTime" placeholder="e.g., 6:00 PM - 7:00 PM" required>
        </div>

        <div class="form-group">
          <label class="form-label">Notes:</label>
          <textarea class="form-textarea" id="notes" placeholder="Optional"></textarea>
        </div>

        <div class="modal-actions">
          <button type="submit" class="btn-assign-modal">
            <i class="fas fa-check"></i>
            Assign
          </button>
          <button type="button" class="btn-cancel-modal" onclick="closeAssignModal()">Cancel</button>
        </div>
      </form>
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

    // Open Assignment Modal
    function openAssignModal(staffName) {
      document.getElementById('staffNameModal').textContent = staffName;
      document.getElementById('assignModal').classList.add('active');
      document.body.style.overflow = 'hidden';
    }

    // Close Assignment Modal
    function closeAssignModal() {
      document.getElementById('assignModal').classList.remove('active');
      document.body.style.overflow = 'auto';
      document.getElementById('assignmentForm').reset();
    }

    // Close modal when clicking outside
    document.getElementById('assignModal').addEventListener('click', (e) => {
      if (e.target.id === 'assignModal') {
        closeAssignModal();
      }
    });

    // Handle Assignment Form Submit
    document.getElementById('assignmentForm').addEventListener('submit', (e) => {
      e.preventDefault();
      
      const staffName = document.getElementById('staffNameModal').textContent;
      const eventName = document.getElementById('eventName').value;
      const venue = document.getElementById('venue').value;
      const date = document.getElementById('eventDate').value;
      const time = document.getElementById('eventTime').value;
      const notes = document.getElementById('notes').value;

      alert(`Staff Assignment Successful!\n\nStaff: ${staffName}\nEvent: ${eventName}\nVenue: ${venue}\nDate: ${date}\nTime: ${time}\nNotes: ${notes || 'None'}\n\nYour backend developer will implement:\n- Save to database\n- Update staff status\n- Send notification to staff\n- Update calendar\n- Refresh staff table`);

      closeAssignModal();
    });

    // Add Staff Function
    function addStaff() {
      alert('Add New Staff\n\nYour backend developer will implement:\n- Open add staff modal/form\n- Input fields: Name, Role, Contact, Email\n- Save to database\n- Refresh staff table');
    }

    // Filter Staff by Status
    function filterStaff() {
      const filter = document.getElementById('statusFilter').value;
      const rows = document.querySelectorAll('#staffTable tbody tr');

      rows.forEach(row => {
        const status = row.getAttribute('data-status');
        if (filter === 'all' || status === filter) {
          row.style.display = '';
        } else {
          row.style.display = 'none';
        }
      });
    }

    // Search Staff
    function searchStaff() {
      const input = document.getElementById('searchStaff');
      const filter = input.value.toUpperCase();
      const table = document.getElementById('staffTable');
      const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');

      for (let i = 0; i < rows.length; i++) {
        const cells = rows[i].getElementsByTagName('td');
        let found = false;

        for (let j = 0; j < cells.length; j++) {
          const cellText = cells[j].textContent || cells[j].innerText;
          if (cellText.toUpperCase().indexOf(filter) > -1) {
            found = true;
            break;
          }
        }

        rows[i].style.display = found ? '' : 'none';
      }
    }

    // Add animation to table rows on load
    window.addEventListener('load', () => {
      const rows = document.querySelectorAll('.staff-table tbody tr');
      rows.forEach((row, index) => {
        row.style.opacity = '0';
        row.style.transform = 'translateX(-20px)';
        row.style.transition = 'all 0.4s ease';
        
        setTimeout(() => {
          row.style.opacity = '1';
          row.style.transform = 'translateX(0)';
        }, 50 + (index * 80));
      });
    });

    // Prevent dropdown changes from triggering row animations
    document.querySelectorAll('.role-dropdown, .status-dropdown, .event-dropdown').forEach(dropdown => {
      dropdown.addEventListener('change', function(e) {
        e.stopPropagation();
        console.log(`${this.className.split('-')[0]} changed to: ${this.value}`);
        // Backend will handle the update
      });
    });
  </script>
</body>
</html>