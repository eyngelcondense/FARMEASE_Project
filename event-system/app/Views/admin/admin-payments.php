<?php

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Payments - San Isidro Labrador Resort</title>
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
      width: 240px;
      height: 100vh;
      background-color: #8f8472;
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
      color: #8f8472;
      font-size: 20px;
    }

    .sidebar-title {
      font-size: 13px;
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

    /* Enhanced Active State */
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

    /* Main Content */
    .main-content {
      margin-left: 240px;
      min-height: 100vh;
      background-color: #e8e3db;
      padding: 30px 40px;
    }

    /* Page Header */
    .page-header {
      background-color: white;
      border-radius: 12px;
      padding: 25px 30px;
      margin-bottom: 25px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.04);
    }

    .page-header h1 {
      font-size: 22px;
      font-weight: 600;
      color: #8f8472;
      margin: 0;
    }

    /* Filter Section */
    .filter-section {
      display: flex;
      align-items: center;
      gap: 15px;
      margin-bottom: 25px;
      flex-wrap: wrap;
    }

    .filter-item {
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .filter-label {
      font-size: 14px;
      font-weight: 500;
      color: #3b2a18;
    }

    .filter-dropdown {
      padding: 8px 35px 8px 15px;
      border: 1px solid #d4cfc5;
      border-radius: 8px;
      background-color: white;
      font-size: 13px;
      color: #3b2a18;
      cursor: pointer;
      appearance: none;
      background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%233b2a18' d='M6 8L2 4h8z'/%3E%3C/svg%3E");
      background-repeat: no-repeat;
      background-position: right 12px center;
      min-width: 150px;
    }

    .filter-dropdown:focus {
      outline: none;
      border-color: #8f8472;
    }

    .search-box-payments {
      position: relative;
      flex: 1;
      max-width: 350px;
    }

    .search-box-payments input {
      width: 100%;
      padding: 8px 15px 8px 40px;
      border: 1px solid #d4cfc5;
      border-radius: 8px;
      background-color: white;
      font-size: 13px;
      color: #3b2a18;
    }

    .search-box-payments input::placeholder {
      color: #a89b88;
    }

    .search-box-payments input:focus {
      outline: none;
      border-color: #8f8472;
    }

    .search-box-payments i {
      position: absolute;
      left: 15px;
      top: 50%;
      transform: translateY(-50%);
      color: #a89b88;
      font-size: 14px;
    }

    /* Table Card */
    .table-card {
      background-color: white;
      border-radius: 12px;
      padding: 30px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.04);
      overflow-x: auto;
    }

    /* Table */
    .payments-table {
      width: 100%;
      border-collapse: separate;
      border-spacing: 0;
      min-width: 900px;
    }

    .payments-table thead {
      background-color: #e8e3db;
    }

    .payments-table thead th {
      padding: 15px 20px;
      text-align: left;
      font-size: 14px;
      font-weight: 600;
      color: #3b2a18;
      border: none;
    }

    .payments-table thead th:first-child {
      border-radius: 8px 0 0 8px;
    }

    .payments-table thead th:last-child {
      border-radius: 0 8px 8px 0;
    }

    .payments-table tbody tr {
      border-bottom: 1px solid #f0ede8;
      transition: background-color 0.3s;
    }

    .payments-table tbody tr:hover {
      background-color: #faf8f5;
    }

    .payments-table tbody tr:last-child {
      border-bottom: none;
    }

    .payments-table tbody td {
      padding: 18px 20px;
      font-size: 13px;
      color: #8f8472;
    }

    .payment-id {
      font-weight: 500;
      color: #8f8472;
    }

    .client-name {
      font-weight: 500;
      color: #3b2a18;
    }

    .amount {
      font-weight: 600;
      color: #3b2a18;
    }

    /* Status Badges */
    .status-badge {
      display: inline-block;
      padding: 5px 12px;
      border-radius: 6px;
      font-size: 12px;
      font-weight: 500;
    }

    .status-paid {
      background-color: #d4edda;
      color: #155724;
    }

    .status-pending {
      background-color: #fff3cd;
      color: #856404;
    }

    .status-refunded {
      background-color: #f8d7da;
      color: #721c24;
    }

    /* Mobile Menu Toggle */
    .mobile-menu-toggle {
      position: fixed;
      top: 20px;
      left: 20px;
      z-index: 1001;
      background: #8f8472;
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
      .main-content {
        margin-left: 0;
        padding: 20px 15px;
      }
      .mobile-menu-toggle {
        display: flex;
      }
    }

    @media (max-width: 768px) {
      .page-header h1 {
        font-size: 18px;
      }
      .filter-section {
        flex-direction: column;
        align-items: stretch;
      }
      .filter-dropdown {
        width: 100%;
      }
      .search-box-payments {
        max-width: 100%;
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
          <a href="admin-payments.php" class="nav-link active" data-page="payments">
            <i class="fas fa-credit-card"></i>
            Payments
          </a>
        </li>
        <li class="nav-item">
          <a href="admin-analytics.php" class="nav-link" data-page="analytics">
            <i class="fas fa-chart-line"></i>
            Analytics & Reports
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
          <a href="admin-notifications.php" class="nav-link" data-page="notifications">
            <i class="fas fa-bell"></i>
            Notifications
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

  <!-- Main Content -->
  <main class="main-content">
    <!-- Page Header -->
    <div class="page-header">
      <h1>Payments</h1>
    </div>

    <!-- Filter Section -->
    <div class="filter-section">
      <div class="filter-item">
        <span class="filter-label">Today</span>
        <select class="filter-dropdown" id="dateFilter" onchange="filterPayments()">
          <option value="today">Today</option>
          <option value="week">This Week</option>
          <option value="month">This Month</option>
          <option value="all">All Time</option>
        </select>
      </div>

      <div class="filter-item">
        <select class="filter-dropdown" id="statusFilter" onchange="filterPayments()">
          <option value="all">All Payments</option>
          <option value="paid">Paid</option>
          <option value="pending">Pending</option>
          <option value="refunded">Refunded</option>
        </select>
      </div>

      <div class="search-box-payments">
        <i class="fas fa-search"></i>
        <input type="text" placeholder="Search ..." id="searchInput" onkeyup="searchPayments()">
      </div>
    </div>

    <!-- Payments Table -->
    <div class="table-card">
      <table class="payments-table" id="paymentsTable">
        <thead>
          <tr>
            <th>Payment ID</th>
            <th>Client</th>
            <th>Date</th>
            <th>Status</th>
            <th>Amount</th>
          </tr>
        </thead>
        <tbody>
          <tr data-status="paid" data-date="2025-05-25">
            <td class="payment-id">001</td>
            <td class="client-name">Apple Templa</td>
            <td>25 May 2025</td>
            <td><span class="status-badge status-paid">Paid</span></td>
            <td class="amount">Php 15, 000</td>
          </tr>
          <tr data-status="pending" data-date="2025-06-12">
            <td class="payment-id">002</td>
            <td class="client-name">Earlsin Combenido</td>
            <td>12 June 2025</td>
            <td><span class="status-badge status-pending">Pending</span></td>
            <td class="amount">Php 10, 000</td>
          </tr>
          <tr data-status="refunded" data-date="2025-01-13">
            <td class="payment-id">003</td>
            <td class="client-name">Jean Iwayan</td>
            <td>13 January 2025</td>
            <td><span class="status-badge status-refunded">Refunded</span></td>
            <td class="amount">Php 5, 000</td>
          </tr>
          <tr data-status="paid" data-date="2025-01-21">
            <td class="payment-id">004</td>
            <td class="client-name">Ryan Magnaye</td>
            <td>21 January 2025</td>
            <td><span class="status-badge status-paid">Paid</span></td>
            <td class="amount">Php 20, 000</td>
          </tr>
          <tr data-status="pending" data-date="2025-02-13">
            <td class="payment-id">005</td>
            <td class="client-name">Gilbert Bumanglag</td>
            <td>13 February 2025</td>
            <td><span class="status-badge status-pending">Pending</span></td>
            <td class="amount">Php 20, 000</td>
          </tr>
          <tr data-status="pending" data-date="2025-03-25">
            <td class="payment-id">006</td>
            <td class="client-name">Johnmoreen Rol</td>
            <td>25 March 2025</td>
            <td><span class="status-badge status-pending">Pending</span></td>
            <td class="amount">Php 10, 000</td>
          </tr>
        </tbody>
      </table>
    </div>
  </main>

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

    // Filter Payments Function
    function filterPayments() {
      const statusFilter = document.getElementById('statusFilter').value;
      const dateFilter = document.getElementById('dateFilter').value;
      const table = document.getElementById('paymentsTable');
      const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');

      for (let i = 0; i < rows.length; i++) {
        let showRow = true;
        const row = rows[i];
        const status = row.getAttribute('data-status');
        const date = new Date(row.getAttribute('data-date'));
        const today = new Date();

        // Status filter
        if (statusFilter !== 'all' && status !== statusFilter) {
          showRow = false;
        }

        // Date filter
        if (dateFilter === 'today') {
          if (date.toDateString() !== today.toDateString()) {
            showRow = false;
          }
        } else if (dateFilter === 'week') {
          const weekAgo = new Date(today.getTime() - 7 * 24 * 60 * 60 * 1000);
          if (date < weekAgo || date > today) {
            showRow = false;
          }
        } else if (dateFilter === 'month') {
          if (date.getMonth() !== today.getMonth() || date.getFullYear() !== today.getFullYear()) {
            showRow = false;
          }
        }

        row.style.display = showRow ? '' : 'none';
      }
    }

    // Search Payments Function
    function searchPayments() {
      const input = document.getElementById('searchInput');
      const filter = input.value.toUpperCase();
      const table = document.getElementById('paymentsTable');
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

    // Add click event to table rows for viewing payment details
    document.querySelectorAll('.payments-table tbody tr').forEach(row => {
      row.style.cursor = 'pointer';
      row.addEventListener('click', function() {
        const paymentId = this.querySelector('.payment-id').textContent;
        const clientName = this.querySelector('.client-name').textContent;
        const amount = this.querySelector('.amount').textContent;
        const status = this.getAttribute('data-status');
        
        alert(`Payment Details:\n\nPayment ID: ${paymentId}\nClient: ${clientName}\nAmount: ${amount}\nStatus: ${status.toUpperCase()}\n\nYour backend developer will implement:\n- Full payment details modal\n- Receipt download\n- Payment history\n- Refund processing (if needed)`);
      });
    });

    // Add animation to status badges on load
    window.addEventListener('load', () => {
      const badges = document.querySelectorAll('.status-badge');
      badges.forEach((badge, index) => {
        badge.style.opacity = '0';
        badge.style.transform = 'scale(0.8)';
        badge.style.transition = 'all 0.3s ease';
        
        setTimeout(() => {
          badge.style.opacity = '1';
          badge.style.transform = 'scale(1)';
        }, 50 + (index * 50));
      });
    });
  </script>
</body>
</html>