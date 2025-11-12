<?php
    $current_page = isset($current_page) ? $current_page : 'dashboard'; 
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard - San Isidro Labrador Resort</title>
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
      margin-right: 320px;
      min-height: 100vh;
    }

    /* Top Header */
    .top-header {
      background-color: #f5f3f0;
      padding: 18px 30px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      border-bottom: none;
      gap: 20px;
    }

    .welcome-section {
      display: flex;
      align-items: center;
      gap: 12px;
      flex-shrink: 0;
    }

    .admin-avatar {
      width: 45px;
      height: 45px;
      border-radius: 50%;
      background-color: #8b7d6b;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-size: 20px;
      overflow: hidden;
      border: 2px solid #d4cfc5;
    }

    .admin-avatar img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .welcome-text h2 {
      font-size: 18px;
      font-weight: 600;
      color: #3b2a18;
      margin: 0;
    }

    .welcome-text p {
      font-size: 12px;
      color: #8b7d6b;
      margin: 0;
    }

    .header-actions {
      display: flex;
      align-items: center;
      gap: 15px;
      flex: 1;
      justify-content: flex-end;
    }

    .search-box {
      position: relative;
      flex: 1;
      max-width: 400px;
    }

    .search-box input {
      width: 100%;
      padding: 10px 15px 10px 40px;
      border: 1px solid #d4cfc5;
      border-radius: 8px;
      background-color: white;
      font-size: 13px;
      color: #3b2a18;
    }

    .search-box input::placeholder {
      color: #a89b88;
    }

    .search-box input:focus {
      outline: none;
      border-color: #8b7d6b;
      background-color: white;
    }

    .search-box i {
      position: absolute;
      left: 15px;
      top: 50%;
      transform: translateY(-50%);
      color: #a89b88;
      font-size: 14px;
    }

    .icon-btn {
      width: 38px;
      height: 38px;
      border-radius: 8px;
      background-color: white;
      border: none;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      transition: all 0.3s;
      position: relative;
      flex-shrink: 0;
    }

    .icon-btn:hover {
      background-color: #e8e3db;
    }

    .icon-btn i {
      font-size: 16px;
      color: #3b2a18;
    }

    .icon-btn .badge {
      position: absolute;
      top: -3px;
      right: -3px;
      width: 16px;
      height: 16px;
      background-color: #d9534f;
      color: white;
      border-radius: 50%;
      font-size: 9px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 600;
    }

    /* Dashboard Content */
    .dashboard-content {
      padding: 25px 30px;
      background-color: #f5f3f0;
    }

    /* Stats Cards */
    .stats-row {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 18px;
      margin-bottom: 25px;
    }

    .stat-card {
      background-color: white;
      border-radius: 10px;
      padding: 20px;
      display: flex;
      align-items: center;
      gap: 15px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.04);
    }

    .stat-icon {
      width: 50px;
      height: 50px;
      border-radius: 10px;
      background-color: #8b7d6b;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-size: 22px;
      flex-shrink: 0;
    }

    .stat-info h3 {
      font-size: 12px;
      font-weight: 400;
      color: #8b7d6b;
      margin: 0 0 5px 0;
    }

    .stat-info p {
      font-size: 26px;
      font-weight: 600;
      color: #3b2a18;
      margin: 0;
    }

    /* Chart Cards */
    .chart-row {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 18px;
      margin-bottom: 25px;
    }

    .chart-card {
      background-color: white;
      border-radius: 10px;
      padding: 22px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.04);
    }

    .chart-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 18px;
    }

    .chart-header h3 {
      font-size: 15px;
      font-weight: 600;
      color: #3b2a18;
      margin: 0;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .filter-btn {
      padding: 6px 12px;
      border: 1px solid #d4cfc5;
      border-radius: 6px;
      background-color: white;
      font-size: 11px;
      color: #3b2a18;
      cursor: pointer;
      display: flex;
      align-items: center;
      gap: 5px;
      font-weight: 500;
    }

    .filter-btn:hover {
      background-color: #f5f3f0;
    }

    .chart-stats {
      display: flex;
      gap: 25px;
      margin-bottom: 18px;
    }

    .chart-stat-item h4 {
      font-size: 11px;
      font-weight: 400;
      color: #8b7d6b;
      margin: 0 0 4px 0;
    }

    .chart-stat-item p {
      font-size: 20px;
      font-weight: 600;
      color: #3b2a18;
      margin: 0;
    }

    .chart-container {
      height: 250px;
      position: relative;
    }

    /* Packages Bar Chart */
    .packages-chart {
      grid-column: 1 / -1;
    }

    .bar-chart {
      display: flex;
      align-items: flex-end;
      justify-content: space-around;
      height: 220px;
      gap: 8px;
      padding: 15px 0;
    }

    .bar-item {
      flex: 1;
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 8px;
    }

    .bar {
      width: 100%;
      background-color: #8b7d6b;
      border-radius: 4px 4px 0 0;
      transition: all 0.3s;
      position: relative;
      min-height: 20px;
    }

    .bar:hover {
      background-color: #7a6a58;
    }

    .bar-label {
      font-size: 9px;
      text-align: center;
      color: #5a4a3a;
      line-height: 1.2;
      max-width: 70px;
    }

    /* Right Sidebar */
    .sidebar-right {
      position: fixed;
      right: 0;
      top: 0;
      width: 320px;
      height: 100vh;
      background-color: white;
      border-left: 1px solid #d4cfc5;
      padding: 25px 20px;
      overflow-y: auto;
      z-index: 999;
    }

    .widget {
      margin-bottom: 30px;
    }

    .widget-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 15px;
    }

    .widget-title {
      font-size: 14px;
      font-weight: 600;
      color: #3b2a18;
    }

    .see-all-btn {
      font-size: 11px;
      color: #8b7d6b;
      text-decoration: none;
      display: flex;
      align-items: center;
      gap: 4px;
      font-weight: 500;
    }

    .see-all-btn:hover {
      color: #6d5d4d;
    }

    .event-item {
      display: flex;
      gap: 12px;
      padding: 12px 0;
      border-bottom: 1px solid #f0ede8;
    }

    .event-item:last-child {
      border-bottom: none;
    }

    .event-avatar {
      width: 42px;
      height: 42px;
      border-radius: 50%;
      flex-shrink: 0;
      overflow: hidden;
      border: 2px solid #f0ede8;
    }

    .event-avatar img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .event-info {
      flex: 1;
    }

    .event-info h4 {
      font-size: 12px;
      font-weight: 500;
      color: #3b2a18;
      margin: 0 0 2px 0;
    }

    .event-info p {
      font-size: 10px;
      color: #8b7d6b;
      margin: 0;
      line-height: 1.4;
    }

    .notification-item {
      display: flex;
      gap: 10px;
      padding: 10px;
      background-color: #f8f6f3;
      border-radius: 8px;
      margin-bottom: 8px;
      align-items: flex-start;
    }

    .notification-icon {
      width: 32px;
      height: 32px;
      border-radius: 8px;
      background-color: white;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 14px;
      flex-shrink: 0;
    }

    .notification-text {
      flex: 1;
    }

    .notification-text p {
      font-size: 11px;
      color: #3b2a18;
      margin: 0;
      line-height: 1.5;
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
    @media (max-width: 1400px) {
      .main-layout {
        margin-right: 0;
      }
      .sidebar-right {
        display: none;
      }
    }

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
      }
      .chart-row {
        grid-template-columns: 1fr;
      }
      .stats-row {
        grid-template-columns: 1fr;
      }
      .mobile-menu-toggle {
        display: flex;
      }
    }

    @media (max-width: 768px) {
      .top-header {
        padding: 15px 20px;
        flex-wrap: wrap;
      }
      .search-box {
        order: 3;
        max-width: 100%;
        width: 100%;
        flex-basis: 100%;
      }
      .dashboard-content {
        padding: 20px 15px;
      }
    }
  </style>
</head>
<body>

    <?= $this->include('admin/sidebar') ?>

  <!-- Main Content Area -->
  <div class="main-layout">
    <!-- Top Header -->
    <header class="top-header">
      <div class="welcome-section">
        <div class="admin-avatar">
          <img src="formal.jpg" alt="Admin">
        </div>
        <div class="welcome-text">
          <h2>Welcome back, Admin !</h2>
          <p>Management/Administrator</p>
        </div>
      </div>
      <div class="header-actions">
        <div class="search-box">
          <i class="fas fa-search"></i>
          <input type="text" placeholder="Search ...">
        </div>
      </div>
    </header>

    <!-- Dashboard Content -->
    <div class="dashboard-content">
      <!-- Stats Cards -->
      <div class="stats-row">
        <div class="stat-card">
          <div class="stat-icon">
            <i class="fas fa-calendar-alt"></i>
          </div>
          <div class="stat-info">
            <h3>Total Events</h3>
            <p>280</p>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-icon">
            <i class="fas fa-ticket-alt"></i>
          </div>
          <div class="stat-info">
            <h3>Total Bookings</h3>
            <p>125</p>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-icon">
            <i class="fas fa-dollar-sign"></i>
          </div>
          <div class="stat-info">
            <h3>Revenue</h3>
            <p>Php 22000</p>
          </div>
        </div>
      </div>

      <!-- Charts Row -->
      <div class="chart-row">
        <!-- Net Sales Chart -->
        <div class="chart-card">
          <div class="chart-header">
            <h3>
              Net Sales
              <i class="fas fa-chevron-down" style="font-size: 11px; color: #a89b88;"></i>
            </h3>
            <button class="filter-btn">
              <i class="fas fa-filter"></i>
              Filter: Weekly
            </button>
          </div>
          <div class="chart-stats">
            <div class="chart-stat-item">
              <h4>Total Revenue</h4>
              <p>156,500</p>
            </div>
            <div class="chart-stat-item">
              <h4>Total Tickets</h4>
              <p>2438</p>
            </div>
            <div class="chart-stat-item">
              <h4>Total Events</h4>
              <p>32</p>
            </div>
          </div>
          <div class="chart-container">
            <canvas id="salesChart"></canvas>
          </div>
        </div>

        <!-- Venue Utilization Chart -->
        <div class="chart-card">
          <div class="chart-header">
            <h3>Venue Utilization</h3>
          </div>
          <div class="chart-container">
            <canvas id="venueChart"></canvas>
          </div>
        </div>
      </div>

      <!-- Most Availed Packages -->
      <div class="chart-card packages-chart">
        <div class="chart-header">
          <h3>Most Availed Packages</h3>
        </div>
        <div class="bar-chart">
          <div class="bar-item">
            <div class="bar" style="height: 75%;"></div>
            <div class="bar-label">Enclosed Venue</div>
          </div>
          <div class="bar-item">
            <div class="bar" style="height: 90%;"></div>
            <div class="bar-label">Open Venue</div>
          </div>
          <div class="bar-item">
            <div class="bar" style="height: 55%;"></div>
            <div class="bar-label">Playground (Exclusive)</div>
          </div>
          <div class="bar-item">
            <div class="bar" style="height: 30%;"></div>
            <div class="bar-label">Playground (Non-Exclusive)</div>
          </div>
          <div class="bar-item">
            <div class="bar" style="height: 65%;"></div>
            <div class="bar-label">Cafe 2nd Floor (Basic)</div>
          </div>
          <div class="bar-item">
            <div class="bar" style="height: 45%;"></div>
            <div class="bar-label">Cafe 2nd Floor (Premium)</div>
          </div>
          <div class="bar-item">
            <div class="bar" style="height: 25%;"></div>
            <div class="bar-label">Café Meeting Room</div>
          </div>
          <div class="bar-item">
            <div class="bar" style="height: 35%;"></div>
            <div class="bar-label">Prep & Photoshoot (Basic)</div>
          </div>
          <div class="bar-item">
            <div class="bar" style="height: 32%;"></div>
            <div class="bar-label">Prep & Photoshoot (Premium)</div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Right Sidebar -->
  <aside class="sidebar-right">
    <!-- Upcoming Events Widget -->
    <div class="widget">
      <div class="widget-header">
        <h3 class="widget-title">Upcoming Events</h3>
        <a href="#" class="see-all-btn">
          See All <i class="fas fa-arrow-right"></i>
        </a>
      </div>
      <div class="event-item">
        <div class="event-avatar">
          <img src="halloween.jpg" alt="Halloween Party">
        </div>
        <div class="event-info">
          <h4>Event : Halloween Party</h4>
          <p>Date : 1 November 2025</p>
          <p>Package: Café 2nd Floor (Premium)</p>
        </div>
      </div>
      <div class="event-item">
        <div class="event-avatar">
          <img src="CICS.jpg" alt="CICS Night">
        </div>
        <div class="event-info">
          <h4>Event : CICS Night</h4>
          <p>Date : 28 December 2025</p>
          <p>Package: Enclosed Venue</p>
        </div>
      </div>
      <div class="event-item">
        <div class="event-avatar">
          <img src="angel.jpg" alt="Cortino's Birthday">
        </div>
        <div class="event-info">
          <h4>Event : Cortino's Birthday</h4>
          <p>Date : 13 January 2026</p>
          <p>Package: Enclosed Venue</p>
        </div>
      </div>
      <div class="event-item">
        <div class="event-avatar">
          <img src="apple.jpg" alt="Apple's Wedding">
        </div>
        <div class="event-info">
          <h4>Event : Apple's Wedding</h4>
          <p>Date : 14 February 2026</p>
          <p>Package: Open Venue</p>
        </div>
      </div>
    </div>

    <!-- Notifications Widget -->
    <div class="widget">
      <div class="widget-header">
        <h3 class="widget-title">Notifications</h3>
        <a href="#" class="see-all-btn">
          See All <i class="fas fa-arrow-right"></i>
        </a>
      </div>
      <div class="notification-item">
        <div class="notification-icon">
          <i class="fas fa-money-bill-wave" style="color: #52b788;"></i>
        </div>
        <div class="notification-text">
          <p>Payment received from Angel Cortino (₱25,000)</p>
        </div>
      </div>
      <div class="notification-item">
        <div class="notification-icon">
          <i class="fas fa-calendar-check" style="color: #4a5899;"></i>
        </div>
        <div class="notification-text">
          <p>New booking request for Café 2nd Floor Venue</p>
        </div>
      </div>
      <div class="notification-item">
        <div class="notification-icon">
          <i class="fas fa-user-clock" style="color: #ff6b35;"></i>
        </div>
        <div class="notification-text">
          <p>@Alan Walker Event in 3 days</p>
        </div>
      </div>
      <div class="notification-item">
        <div class="notification-icon">
          <i class="fas fa-receipt" style="color: #8b7d6b;"></i>
        </div>
        <div class="notification-text">
          <p>Paycheck released for artists @Cynderex Event</p>
        </div>
      </div>
      <div class="notification-item">
        <div class="notification-icon">
          <i class="fas fa-receipt" style="color: #8b7d6b;"></i>
        </div>
        <div class="notification-text">
          <p>Paycheck released for artists @Get Together Event</p>
        </div>
      </div>
    </div>
  </aside>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
  
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

    // Sales Line Chart
    const salesCtx = document.getElementById('salesChart');
    if (salesCtx) {
      new Chart(salesCtx, {
        type: 'line',
        data: {
          labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4', 'Week 5', 'Week 6'],
          datasets: [{
            label: 'Net Sales',
            data: [35000, 22000, 46000, 15000, 28000, 34000],
            borderColor: '#8b7d6b',
            backgroundColor: 'rgba(139, 125, 107, 0.05)',
            tension: 0.4,
            fill: true,
            pointBackgroundColor: '#8b7d6b',
            pointBorderColor: '#fff',
            pointBorderWidth: 2,
            pointRadius: 5,
            pointHoverRadius: 7
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              display: false
            },
            tooltip: {
              backgroundColor: '#3b2a18',
              padding: 12,
              titleColor: '#fff',
              bodyColor: '#fff',
              borderColor: '#8b7d6b',
              borderWidth: 1,
              titleFont: {
                size: 12,
                family: 'Poppins'
              },
              bodyFont: {
                size: 11,
                family: 'Poppins'
              }
            }
          },
          scales: {
            y: {
              beginAtZero: true,
              ticks: {
                color: '#a89b88',
                font: {
                  family: 'Poppins',
                  size: 10
                },
                callback: function(value) {
                  return value.toLocaleString();
                }
              },
              grid: {
                color: 'rgba(139, 125, 107, 0.08)'
              }
            },
            x: {
              ticks: {
                color: '#a89b88',
                font: {
                  family: 'Poppins',
                  size: 10
                }
              },
              grid: {
                display: false
              }
            }
          }
        }
      });
    }

    // Venue Utilization Doughnut Chart
    const venueCtx = document.getElementById('venueChart');
    if (venueCtx) {
      new Chart(venueCtx, {
        type: 'doughnut',
        data: {
          labels: ['Enclosed', 'Open', 'Playground', 'Cafe 2nd Floor'],
          datasets: [{
            data: [250, 170, 290, 370],
            backgroundColor: [
              '#8b7d6b',
              '#a89b88',
              '#7a6a58',
              '#6d5d4d'
            ],
            borderWidth: 0,
            hoverOffset: 8
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              position: 'bottom',
              labels: {
                color: '#3b2a18',
                font: {
                  family: 'Poppins',
                  size: 10
                },
                padding: 12,
                usePointStyle: true,
                pointStyle: 'circle',
                boxWidth: 8,
                boxHeight: 8
              }
            },
            tooltip: {
              backgroundColor: '#3b2a18',
              padding: 10,
              titleColor: '#fff',
              bodyColor: '#fff',
              borderColor: '#8b7d6b',
              borderWidth: 1,
              titleFont: {
                size: 11,
                family: 'Poppins'
              },
              bodyFont: {
                size: 10,
                family: 'Poppins'
              },
              callbacks: {
                label: function(context) {
                  let label = context.label || '';
                  if (label) {
                    label += ': ';
                  }
                  label += context.parsed;
                  const total = context.dataset.data.reduce((a, b) => a + b, 0);
                  const percentage = ((context.parsed / total) * 100).toFixed(1);
                  label += ` (${percentage}%)`;
                  return label;
                }
              }
            }
          },
          cutout: '65%'
        }
      });
    }

    // Animate stat cards on load
    window.addEventListener('load', () => {
      const statCards = document.querySelectorAll('.stat-card');
      statCards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'all 0.5s ease';
        
        setTimeout(() => {
          card.style.opacity = '1';
          card.style.transform = 'translateY(0)';
        }, 100 + (index * 100));
      });
    });

    // Add number animation for stats
    function animateValue(element, start, end, duration) {
      let startTimestamp = null;
      const step = (timestamp) => {
        if (!startTimestamp) startTimestamp = timestamp;
        const progress = Math.min((timestamp - startTimestamp) / duration, 1);
        const value = Math.floor(progress * (end - start) + start);
        element.textContent = value.toLocaleString();
        if (progress < 1) {
          window.requestAnimationFrame(step);
        }
      };
      window.requestAnimationFrame(step);
    }

    // Animate numbers when page loads
    setTimeout(() => {
      const statValues = document.querySelectorAll('.stat-info p');
      statValues.forEach((stat, index) => {
        const text = stat.textContent.trim();
        // Skip if it contains "Php" or other non-numeric text
        if (!text.includes('Php') && !isNaN(text)) {
          const num = parseInt(text);
          stat.textContent = '0';
          setTimeout(() => {
            animateValue(stat, 0, num, 1500);
          }, 200 + (index * 200));
        }
      });
    }, 500);

    // Bar chart hover effect
    document.querySelectorAll('.bar').forEach(bar => {
      bar.addEventListener('mouseenter', function() {
        this.style.transform = 'scaleY(1.05)';
        this.style.transformOrigin = 'bottom';
      });
      bar.addEventListener('mouseleave', function() {
        this.style.transform = 'scaleY(1)';
      });
    });
  </script>
</body>
</html>