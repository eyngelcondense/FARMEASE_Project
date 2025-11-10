<?php

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Calendar of Events - San Isidro Labrador Resort</title>
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

    /* Page Header */
    .page-header {
      margin-bottom: 12px;
    }

    .page-header h1 {
      font-size: 20px;
      font-weight: 600;
      color: #3b2a18;
      margin: 0 0 8px 0;
    }

    .page-subtitle {
      font-size: 14px;
      font-weight: 400;
      color: #8b7d6b;
      margin: 0 0 30px 0;
    }

    /* Calendar Container */
    .calendar-container {
      background-color: white;
      border-radius: 10px;
      padding: 30px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.04);
      max-width: 1100px;
    }

    /* Calendar Header */
    .calendar-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 30px;
    }

    .calendar-nav-btn {
      background-color: transparent;
      border: none;
      color: #8b7d6b;
      font-size: 20px;
      cursor: pointer;
      padding: 8px 12px;
      border-radius: 6px;
      transition: all 0.3s;
    }

    .calendar-nav-btn:hover {
      background-color: #f5f3f0;
      color: #3b2a18;
    }

    .calendar-month-year {
      font-size: 18px;
      font-weight: 600;
      color: #8b7d6b;
    }

    /* Calendar Grid */
    .calendar-grid {
      display: grid;
      grid-template-columns: repeat(7, 1fr);
      gap: 2px;
      background-color: #e8e3db;
      border-radius: 8px;
      overflow: hidden;
    }

    .calendar-day-header {
      background-color: #f5f3f0;
      padding: 15px 10px;
      text-align: center;
      font-size: 12px;
      font-weight: 600;
      color: #8b7d6b;
      text-transform: uppercase;
    }

    .calendar-day {
      background-color: white;
      min-height: 100px;
      padding: 12px 10px;
      position: relative;
      cursor: pointer;
      transition: all 0.3s;
    }

    .calendar-day:hover {
      background-color: #faf8f5;
    }

    .calendar-day.other-month {
      background-color: #faf8f5;
      opacity: 0.5;
    }

    .calendar-day-number {
      font-size: 14px;
      font-weight: 500;
      color: #8b7d6b;
      margin-bottom: 8px;
    }

    .calendar-day.other-month .calendar-day-number {
      color: #c4b9a8;
    }

    /* Event Badge */
    .event-badge {
      background-color: #8b7d6b;
      color: white;
      padding: 6px 10px;
      border-radius: 6px;
      font-size: 11px;
      font-weight: 500;
      margin-top: 5px;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      transition: all 0.3s;
    }

    .event-badge:hover {
      background-color: #7a6a58;
      transform: scale(1.02);
    }

    /* Today Highlight */
    .calendar-day.today {
      background-color: #e8e3db;
    }

    .calendar-day.today .calendar-day-number {
      background-color: #8b7d6b;
      color: white;
      width: 28px;
      height: 28px;
      border-radius: 50%;
      display: inline-flex;
      align-items: center;
      justify-content: center;
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
      .calendar-container {
        padding: 20px 15px;
      }
      .calendar-day {
        min-height: 80px;
        padding: 8px 6px;
      }
      .event-badge {
        font-size: 9px;
        padding: 4px 6px;
      }
    }

    @media (max-width: 768px) {
      .calendar-day-header {
        font-size: 10px;
        padding: 10px 5px;
      }
      .calendar-day-number {
        font-size: 12px;
      }
      .calendar-month-year {
        font-size: 16px;
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
            Feedback and Inquiries
          </a>
        </li>
        <li class="nav-item">
          <a href="admin-calendar.php" class="nav-link active" data-page="calendar">
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
    <!-- Page Header -->
    <div class="page-header">
      <h1>Calendar of Events</h1>
      <p class="page-subtitle">View all upcoming events and bookings for your venues</p>
    </div>

    <!-- Calendar Container -->
    <div class="calendar-container">
      <!-- Calendar Header -->
      <div class="calendar-header">
        <button class="calendar-nav-btn" onclick="previousMonth()">
          <i class="fas fa-chevron-left"></i>
        </button>
        <div class="calendar-month-year" id="monthYear">November 2025</div>
        <button class="calendar-nav-btn" onclick="nextMonth()">
          <i class="fas fa-chevron-right"></i>
        </button>
      </div>

      <!-- Calendar Grid -->
      <div class="calendar-grid" id="calendarGrid">
        <!-- Day Headers -->
        <div class="calendar-day-header">SUN</div>
        <div class="calendar-day-header">MON</div>
        <div class="calendar-day-header">TUE</div>
        <div class="calendar-day-header">WED</div>
        <div class="calendar-day-header">THU</div>
        <div class="calendar-day-header">FRI</div>
        <div class="calendar-day-header">SAT</div>

        <!-- Calendar Days - November 2025 -->
        <div class="calendar-day other-month"><div class="calendar-day-number">26</div></div>
        <div class="calendar-day other-month"><div class="calendar-day-number">27</div></div>
        <div class="calendar-day"><div class="calendar-day-number">2</div></div>
        <div class="calendar-day"><div class="calendar-day-number">3</div></div>
        <div class="calendar-day"><div class="calendar-day-number">4</div></div>
        <div class="calendar-day"><div class="calendar-day-number">5</div></div>
        <div class="calendar-day"><div class="calendar-day-number">6</div></div>

        <div class="calendar-day"><div class="calendar-day-number">7</div><div class="event-badge" onclick="viewEvent(1)">Wedding - Rose Hall</div></div>
        <div class="calendar-day"><div class="calendar-day-number">8</div></div>
        <div class="calendar-day"><div class="calendar-day-number">9</div></div>
        <div class="calendar-day today"><div class="calendar-day-number">10</div></div>
        <div class="calendar-day"><div class="calendar-day-number">11</div></div>
        <div class="calendar-day"><div class="calendar-day-number">12</div></div>
        <div class="calendar-day"><div class="calendar-day-number">13</div></div>

        <div class="calendar-day"><div class="calendar-day-number">14</div></div>
        <div class="calendar-day"><div class="calendar-day-number">15</div></div>
        <div class="calendar-day"><div class="calendar-day-number">16</div></div>
        <div class="calendar-day"><div class="calendar-day-number">17</div></div>
        <div class="calendar-day"><div class="calendar-day-number">18</div></div>
        <div class="calendar-day"><div class="calendar-day-number">19</div></div>
        <div class="calendar-day"><div class="calendar-day-number">20</div></div>

        <div class="calendar-day"><div class="calendar-day-number">21</div></div>
        <div class="calendar-day"><div class="calendar-day-number">22</div><div class="event-badge" onclick="viewEvent(2)">Meeting - City Hall</div></div>
        <div class="calendar-day"><div class="calendar-day-number">23</div></div>
        <div class="calendar-day"><div class="calendar-day-number">24</div></div>
        <div class="calendar-day"><div class="calendar-day-number">25</div></div>
        <div class="calendar-day"><div class="calendar-day-number">26</div></div>
        <div class="calendar-day"><div class="calendar-day-number">27</div></div>

        <div class="calendar-day"><div class="calendar-day-number">28</div><div class="event-badge" onclick="viewEvent(3)">Conference - Main Hall</div></div>
        <div class="calendar-day"><div class="calendar-day-number">29</div></div>
        <div class="calendar-day"><div class="calendar-day-number">30</div></div>
        <div class="calendar-day other-month"><div class="calendar-day-number">1</div></div>
        <div class="calendar-day other-month"><div class="calendar-day-number">2</div></div>
        <div class="calendar-day other-month"><div class="calendar-day-number">3</div></div>
        <div class="calendar-day other-month"><div class="calendar-day-number">4</div></div>

        <div class="calendar-day other-month"><div class="calendar-day-number">5</div><div class="event-badge" onclick="viewEvent(4)">Debut - Event Place</div></div>
        <div class="calendar-day other-month"><div class="calendar-day-number">6</div></div>
        <div class="calendar-day other-month"><div class="calendar-day-number">7</div></div>
        <div class="calendar-day other-month"><div class="calendar-day-number">8</div></div>
        <div class="calendar-day other-month"><div class="calendar-day-number">9</div></div>
        <div class="calendar-day other-month"><div class="calendar-day-number">10</div></div>
        <div class="calendar-day other-month"><div class="calendar-day-number">11</div></div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  
  <script>
    // Current date
    let currentMonth = 10; // November (0-indexed)
    let currentYear = 2025;

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

    // Previous Month
    function previousMonth() {
      currentMonth--;
      if (currentMonth < 0) {
        currentMonth = 11;
        currentYear--;
      }
      updateCalendar();
    }

    // Next Month
    function nextMonth() {
      currentMonth++;
      if (currentMonth > 11) {
        currentMonth = 0;
        currentYear++;
      }
      updateCalendar();
    }

    // Update Calendar
    function updateCalendar() {
      const monthNames = ['January', 'February', 'March', 'April', 'May', 'June',
                          'July', 'August', 'September', 'October', 'November', 'December'];
      
      document.getElementById('monthYear').textContent = `${monthNames[currentMonth]} ${currentYear}`;
      
      alert(`Calendar will be updated to: ${monthNames[currentMonth]} ${currentYear}\n\nYour backend developer will implement:\n- Generate calendar days dynamically\n- Fetch events from database\n- Display event badges on correct dates\n- Handle multiple events per day`);
    }

    // View Event
    function viewEvent(id) {
      event.stopPropagation();
      alert(`View Event ID: ${id}\n\nYour backend developer will implement:\n- Open event details modal\n- Show full event information\n- Edit event option\n- Delete event option\n- View booking details`);
    }

    // Add click event to empty calendar days
    document.querySelectorAll('.calendar-day').forEach(day => {
      day.addEventListener('click', function() {
        if (!this.querySelector('.event-badge')) {
          const dayNumber = this.querySelector('.calendar-day-number').textContent;
          alert(`Add event on day ${dayNumber}\n\nYour backend developer will implement:\n- Open "Add Event" modal\n- Pre-fill selected date\n- Save event to database\n- Refresh calendar`);
        }
      });
    });

    // Add animation to calendar on load
    window.addEventListener('load', () => {
      const days = document.querySelectorAll('.calendar-day');
      days.forEach((day, index) => {
        day.style.opacity = '0';
        day.style.transform = 'scale(0.9)';
        day.style.transition = 'all 0.3s ease';
        
        setTimeout(() => {
          day.style.opacity = '1';
          day.style.transform = 'scale(1)';
        }, 20 + (index * 15));
      });
    });
  </script>
</body>
</html>