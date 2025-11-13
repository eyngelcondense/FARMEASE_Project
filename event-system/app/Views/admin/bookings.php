<?php
    $current_page = isset($current_page) ? $current_page : 'bookings';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bookings - San Isidro Labrador Resort</title>
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

    /* Filter Section */
    .filter-section {
      display: flex;
      align-items: center;
      gap: 15px;
      margin-bottom: 22px;
      flex-wrap: wrap;
    }

    .filter-item {
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .filter-label {
      font-size: 13px;
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
      min-width: 140px;
      transition: all 0.3s;
    }

    .filter-dropdown:focus {
      outline: none;
      border-color: #8b7d6b;
    }

    .search-box-bookings {
      position: relative;
      flex: 1;
      max-width: 320px;
    }

    .search-box-bookings input {
      width: 100%;
      padding: 8px 15px 8px 40px;
      border: 1px solid #d4cfc5;
      border-radius: 8px;
      background-color: white;
      font-size: 13px;
      color: #3b2a18;
      transition: all 0.3s;
    }

    .search-box-bookings input::placeholder {
      color: #a89b88;
    }

    .search-box-bookings input:focus {
      outline: none;
      border-color: #8b7d6b;
    }

    .search-box-bookings i {
      position: absolute;
      left: 15px;
      top: 50%;
      transform: translateY(-50%);
      color: #a89b88;
      font-size: 14px;
    }

    /* View Calendar Button */
    .view-calendar-btn {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      background-color: #3b2a18;
      border: none;
      color: white;
      padding: 8px 16px;
      border-radius: 8px;
      font-size: 13px;
      font-weight: 500;
      transition: all 0.3s;
      cursor: pointer;
      margin-left: auto;
    }

    .view-calendar-btn:hover {
      background-color: #2a1f12;
    }

    .view-calendar-btn i {
      font-size: 14px;
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
    .bookings-table {
      width: 100%;
      border-collapse: separate;
      border-spacing: 0;
      min-width: 800px;
    }

    .bookings-table thead {
      background-color: #e8e3db;
    }

    .bookings-table thead th {
      padding: 12px 18px;
      text-align: left;
      font-size: 13px;
      font-weight: 600;
      color: #3b2a18;
      border: none;
    }

    .bookings-table thead th:first-child {
      border-radius: 8px 0 0 8px;
    }

    .bookings-table thead th:last-child {
      border-radius: 0 8px 8px 0;
    }

    .bookings-table tbody tr {
      border-bottom: 1px solid #f0ede8;
      transition: background-color 0.3s;
    }

    .bookings-table tbody tr:hover {
      background-color: #faf8f5;
    }

    .bookings-table tbody tr:last-child {
      border-bottom: none;
    }

    .bookings-table tbody td {
      padding: 15px 18px;
      font-size: 13px;
      color: #5a4a3a;
    }

    .booking-id {
      font-weight: 500;
      color: #8b7d6b;
    }

    .client-name {
      font-weight: 500;
      color: #3b2a18;
    }

    /* Action Buttons */
    .action-buttons {
      display: flex;
      gap: 8px;
    }

    .btn-approve,
    .btn-reject {
      padding: 6px 14px;
      border-radius: 6px;
      font-size: 12px;
      font-weight: 500;
      border: none;
      cursor: pointer;
      transition: all 0.3s;
    }

    .btn-approve {
      background-color: #3b2a18;
      color: white;
    }

    .btn-approve:hover {
      background-color: #2a1f12;
    }

    .btn-reject {
      background-color: #d9534f;
      color: white;
    }

    .btn-reject:hover {
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
      .filter-section {
        flex-direction: column;
        align-items: stretch;
      }
      .filter-dropdown {
        width: 100%;
      }
      .search-box-bookings {
        max-width: 100%;
      }
      .view-calendar-btn {
        width: 100%;
        justify-content: center;
        margin-left: 0;
      }
    }
  </style>
</head>
<body>

  <!-- Sidebar Include -->
  <?= $this->include('admin/sidebar') ?>

  <!-- Main Content Area -->
  <div class="main-layout">
    <!-- Page Header Card -->
    <div class="page-header-card">
      <h1>Bookings</h1>
    </div>

    <!-- Filter Section -->
    <div class="filter-section">
      <div class="filter-item">
        <span class="filter-label">Today</span>
        <select class="filter-dropdown" id="dateFilter" onchange="filterBookings()">
          <option value="today">Today</option>
          <option value="week">This Week</option>
          <option value="month">This Month</option>
          <option value="all">All Time</option>
        </select>
      </div>

      <div class="filter-item">
        <select class="filter-dropdown" id="venueFilter" onchange="filterBookings()">
          <option value="all">All Venues</option>
          <option value="enclosed">Enclosed Venue</option>
          <option value="open">Open Venue</option>
          <option value="playground">Playground</option>
          <option value="cafe">Cafe 2nd Floor</option>
        </select>
      </div>

      <div class="search-box-bookings">
        <i class="fas fa-search"></i>
        <input type="text" placeholder="Search ..." id="searchInput" onkeyup="searchBookings()">
      </div>

      <button class="view-calendar-btn" onclick="viewCalendar()">
        <i class="fas fa-calendar-alt"></i>
        View Calendar
        <span style="font-size: 10px; opacity: 0.9;">Events</span>
      </button>
    </div>

    <!-- Bookings Table -->
    <div class="table-card">
      <table class="bookings-table" id="bookingsTable">
        <thead>
          <tr>
            <th>Booking ID</th>
            <th>Client</th>
            <th>Venue</th>
            <th>Date</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <tr data-venue="enclosed" data-date="2025-05-25">
            <td class="booking-id">001</td>
            <td class="client-name">Apple Templa</td>
            <td>Enclosed Venue</td>
            <td>25 May 2025</td>
            <td>
              <div class="action-buttons">
                <button class="btn-approve" onclick="approveBooking(1)">Approve</button>
                <button class="btn-reject" onclick="rejectBooking(1)">Reject</button>
              </div>
            </td>
          </tr>
          <tr data-venue="open" data-date="2025-06-12">
            <td class="booking-id">002</td>
            <td class="client-name">Earlsin Combenido</td>
            <td>Open Venue</td>
            <td>12 June 2025</td>
            <td>
              <div class="action-buttons">
                <button class="btn-approve" onclick="approveBooking(2)">Approve</button>
                <button class="btn-reject" onclick="rejectBooking(2)">Reject</button>
              </div>
            </td>
          </tr>
          <tr data-venue="playground" data-date="2025-01-13">
            <td class="booking-id">003</td>
            <td class="client-name">Jean Iwayan</td>
            <td>Playground</td>
            <td>13 January 2025</td>
            <td>
              <div class="action-buttons">
                <button class="btn-approve" onclick="approveBooking(3)">Approve</button>
                <button class="btn-reject" onclick="rejectBooking(3)">Reject</button>
              </div>
            </td>
          </tr>
          <tr data-venue="cafe" data-date="2025-01-21">
            <td class="booking-id">004</td>
            <td class="client-name">Ryan Magnaye</td>
            <td>Cafe 2nd Floor</td>
            <td>21 January 2025</td>
            <td>
              <div class="action-buttons">
                <button class="btn-approve" onclick="approveBooking(4)">Approve</button>
                <button class="btn-reject" onclick="rejectBooking(4)">Reject</button>
              </div>
            </td>
          </tr>
          <tr data-venue="cafe" data-date="2025-02-13">
            <td class="booking-id">005</td>
            <td class="client-name">Gilbert Bumanglag</td>
            <td>Cafe 2nd Floor</td>
            <td>13 February 2025</td>
            <td>
              <div class="action-buttons">
                <button class="btn-approve" onclick="approveBooking(5)">Approve</button>
                <button class="btn-reject" onclick="rejectBooking(5)">Reject</button>
              </div>
            </td>
          </tr>
          <tr data-venue="open" data-date="2025-03-25">
            <td class="booking-id">006</td>
            <td class="client-name">Johnmoreen Rol</td>
            <td>Open Venue</td>
            <td>25 March 2025</td>
            <td>
              <div class="action-buttons">
                <button class="btn-approve" onclick="approveBooking(6)">Approve</button>
                <button class="btn-reject" onclick="rejectBooking(6)">Reject</button>
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

    // Filter Bookings Function
    function filterBookings() {
      const venueFilter = document.getElementById('venueFilter').value;
      const dateFilter = document.getElementById('dateFilter').value;
      const table = document.getElementById('bookingsTable');
      const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');

      for (let i = 0; i < rows.length; i++) {
        let showRow = true;
        const row = rows[i];
        const venue = row.getAttribute('data-venue');
        const date = new Date(row.getAttribute('data-date'));
        const today = new Date();

        // Venue filter
        if (venueFilter !== 'all' && venue !== venueFilter) {
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

    // Search Bookings Function
    function searchBookings() {
      const input = document.getElementById('searchInput');
      const filter = input.value.toUpperCase();
      const table = document.getElementById('bookingsTable');
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

    // View Calendar Function
    function viewCalendar() {
      alert('View Calendar functionality\n\nThis will redirect to the Calendar of Events page or open a calendar modal.');

    }

    function approveBooking(id) {
      if (confirm(`Approve booking #${String(id).padStart(3, '0')}?`)) {
        alert(`Booking #${String(id).padStart(3, '0')} has been approved!\n\nYour backend developer will implement:\n- Update booking status in database\n- Send confirmation email to client\n- Update calendar availability`);
      }
    }

    function rejectBooking(id) {
      const reason = prompt(`Reject booking #${String(id).padStart(3, '0')}?\n\nPlease provide a reason:`);
      if (reason) {
        alert(`Booking #${String(id).padStart(3, '0')} has been rejected.\n\nReason: ${reason}\n\nYour backend developer will implement:\n- Update booking status in database\n- Send rejection email to client with reason\n- Free up the date slot`);
      }
    }
  </script>
</body>
</html>