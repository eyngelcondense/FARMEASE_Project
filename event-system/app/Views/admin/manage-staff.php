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

    /* Two Card Layout */
    .two-card-layout {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 25px;
      margin-bottom: 30px;
    }

    @media (max-width: 992px) {
      .two-card-layout {
        grid-template-columns: 1fr;
      }
    }

    /* Card Styles */
    .card {
      background-color: white;
      border-radius: 12px;
      padding: 25px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.06);
      overflow: hidden;
    }

    .card-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 20px;
    }

    .card-title {
      font-size: 16px;
      font-weight: 600;
      color: #8b7d6b;
      margin: 0;
    }

    .card-actions {
      display: flex;
      gap: 10px;
    }

    .card-action-btn {
      background: none;
      border: none;
      color: #8b7d6b;
      font-size: 14px;
      cursor: pointer;
      transition: all 0.3s;
    }

    .card-action-btn:hover {
      color: #3b2a18;
    }

    /* Staff List */
    .staff-list {
      display: flex;
      flex-direction: column;
      gap: 15px;
      max-height: 500px;
      overflow-y: auto;
      padding-right: 5px;
    }

    .staff-list::-webkit-scrollbar {
      width: 6px;
    }

    .staff-list::-webkit-scrollbar-track {
      background: #f1f1f1;
      border-radius: 10px;
    }

    .staff-list::-webkit-scrollbar-thumb {
      background: #c4b9a8;
      border-radius: 10px;
    }

    .staff-list::-webkit-scrollbar-thumb:hover {
      background: #8b7d6b;
    }

    .staff-item {
      display: flex;
      align-items: center;
      gap: 15px;
      padding: 15px;
      background-color: #faf8f5;
      border-radius: 10px;
      transition: all 0.3s;
      cursor: pointer;
    }

    .staff-item:hover {
      background-color: #f0ede7;
      transform: translateY(-2px);
    }

    .staff-item.selected {
      background-color: #e8e3db;
      border: 1px solid #8b7d6b;
    }

    .staff-avatar {
      width: 50px;
      height: 50px;
      border-radius: 50%;
      background-color: #8b7d6b;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-weight: 600;
      font-size: 18px;
      flex-shrink: 0;
    }

    .staff-info {
      flex: 1;
    }

    .staff-name {
      font-size: 14px;
      font-weight: 600;
      color: #3b2a18;
      margin-bottom: 4px;
    }

    .staff-details {
      display: flex;
      gap: 15px;
    }

    .staff-detail {
      display: flex;
      align-items: center;
      gap: 5px;
      font-size: 12px;
      color: #8b7d6b;
    }

    .staff-detail i {
      font-size: 12px;
    }

    .staff-status {
      display: flex;
      align-items: center;
      gap: 5px;
      font-size: 12px;
      font-weight: 500;
      padding: 4px 10px;
      border-radius: 20px;
    }

    .status-available {
      background-color: rgba(76, 175, 80, 0.15);
      color: #4caf50;
    }

    .status-assigned {
      background-color: rgba(255, 152, 0, 0.15);
      color: #ff9800;
    }

    .status-unavailable {
      background-color: rgba(244, 67, 54, 0.15);
      color: #f44336;
    }

    /* Assignment Card */
    .assignment-card {
      display: flex;
      flex-direction: column;
    }

    .assignment-form {
      flex: 1;
      display: flex;
      flex-direction: column;
    }

    .form-group {
      margin-bottom: 18px;
    }

    .form-label {
      display: block;
      font-size: 13px;
      font-weight: 500;
      margin-bottom: 8px;
      color: #8b7d6b;
    }

    .form-input,
    .form-select,
    .form-textarea {
      width: 100%;
      padding: 11px 14px;
      border: 1px solid #c4b9a8;
      border-radius: 8px;
      background-color: white;
      font-size: 13px;
      color: #3b2a18;
      font-family: 'Poppins', sans-serif;
      transition: all 0.3s;
    }

    .form-input::placeholder,
    .form-textarea::placeholder {
      color: #a89b88;
    }

    .form-input:focus,
    .form-select:focus,
    .form-textarea:focus {
      outline: none;
      border-color: #8b7d6b;
      background-color: #faf8f5;
    }

    .form-select {
      cursor: pointer;
      appearance: none;
      background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%233b2a18' d='M6 8L2 4h8z'/%3E%3C/svg%3E");
      background-repeat: no-repeat;
      background-position: right 14px center;
      padding-right: 40px;
    }

    .form-textarea {
      resize: vertical;
      min-height: 100px;
    }

    .assign-btn {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      background-color: #8b7d6b;
      border: none;
      color: white;
      padding: 12px 20px;
      border-radius: 8px;
      font-size: 14px;
      font-weight: 500;
      cursor: pointer;
      transition: all 0.3s;
      margin-top: auto;
      justify-content: center;
    }

    .assign-btn:hover {
      background-color: #7a6a58;
      transform: translateY(-2px);
    }

    .assign-btn:disabled {
      background-color: #c4b9a8;
      cursor: not-allowed;
      transform: none;
    }

    /* Selected Staff Info */
    .selected-staff-info {
      background-color: #faf8f5;
      border-radius: 10px;
      padding: 15px;
      margin-bottom: 20px;
      display: none;
    }

    .selected-staff-info.active {
      display: block;
    }

    .selected-staff-header {
      display: flex;
      align-items: center;
      gap: 12px;
      margin-bottom: 10px;
    }

    .selected-staff-avatar {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background-color: #8b7d6b;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-weight: 600;
      font-size: 16px;
    }

    .selected-staff-name {
      font-size: 16px;
      font-weight: 600;
      color: #3b2a18;
    }

    .selected-staff-details {
      display: flex;
      gap: 15px;
      font-size: 13px;
      color: #8b7d6b;
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

    @media (max-width: 768px) {
      .staff-details {
        flex-direction: column;
        gap: 5px;
      }
      .card {
        padding: 20px;
      }
    }
  </style>
</head>
<body>

    <?= $this->include('admin/sidebar') ?>

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

    <!-- Two Card Layout -->
    <div class="two-card-layout">
      <!-- Staff List Card -->
      <div class="card">
        <div class="card-header">
          <h2 class="card-title">Staff Members</h2>
          <div class="card-actions">
            <button class="card-action-btn" title="Refresh">
              <i class="fas fa-sync-alt"></i>
            </button>
          </div>
        </div>
        
        <div class="staff-list" id="staffList">
          <!-- Staff items will be populated here -->
        </div>
      </div>

      <!-- Assignment Card -->
      <div class="card assignment-card">
        <div class="card-header">
          <h2 class="card-title">Assignment</h2>
          <div class="card-actions">
            <button class="card-action-btn" title="Clear" onclick="clearAssignment()">
              <i class="fas fa-times"></i>
            </button>
          </div>
        </div>
        
        <!-- Selected Staff Info -->
        <div class="selected-staff-info" id="selectedStaffInfo">
          <div class="selected-staff-header">
            <div class="selected-staff-avatar" id="selectedStaffAvatar">AT</div>
            <div>
              <div class="selected-staff-name" id="selectedStaffName">Apple Templa</div>
              <div class="selected-staff-details">
                <span id="selectedStaffRole">Cleaner</span>
                <span id="selectedStaffContact">09123456789</span>
              </div>
            </div>
          </div>
        </div>
        
        <form class="assignment-form" id="assignmentForm">
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

          <button type="submit" class="assign-btn" id="assignBtn" disabled>
            <i class="fas fa-check"></i>
            Assign Staff
          </button>
        </form>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  
  <script>
    // Sample staff data
    const staffData = [
      { id: 1, name: "Apple Templa", role: "Cleaner", contact: "09123456789", status: "available" },
      { id: 2, name: "Earlsin Combenido", role: "Waiter", contact: "09123456788", status: "available" },
      { id: 3, name: "Jean Iwayan", role: "Security", contact: "09123456787", status: "available" },
      { id: 4, name: "Ryan Magnaye", role: "Coordinator", contact: "09123456786", status: "assigned" },
      { id: 5, name: "Gilbert Bumanglag", role: "Cleaner", contact: "09123456785", status: "available" },
      { id: 6, name: "Johnmoreen Rol", role: "Waiter", contact: "09123456784", status: "unavailable" },
      { id: 7, name: "Maria Santos", role: "Cleaner", contact: "09123456783", status: "available" },
      { id: 8, name: "Juan Dela Cruz", role: "Security", contact: "09123456782", status: "available" },
      { id: 9, name: "Ana Reyes", role: "Coordinator", contact: "09123456781", status: "assigned" },
      { id: 10, name: "Pedro Garcia", role: "Waiter", contact: "09123456780", status: "available" }
    ];

    let selectedStaff = null;

    // Initialize the page
    document.addEventListener('DOMContentLoaded', function() {
      renderStaffList();
    });

    // Render staff list
    function renderStaffList() {
      const staffList = document.getElementById('staffList');
      staffList.innerHTML = '';
      
      staffData.forEach(staff => {
        const staffItem = document.createElement('div');
        staffItem.className = 'staff-item';
        staffItem.setAttribute('data-id', staff.id);
        staffItem.setAttribute('data-status', staff.status);
        
        // Get initials for avatar
        const initials = staff.name.split(' ').map(n => n[0]).join('');
        
        // Determine status class and text
        let statusClass, statusText;
        switch(staff.status) {
          case 'available':
            statusClass = 'status-available';
            statusText = 'Available';
            break;
          case 'assigned':
            statusClass = 'status-assigned';
            statusText = 'Assigned';
            break;
          case 'unavailable':
            statusClass = 'status-unavailable';
            statusText = 'Unavailable';
            break;
        }
        
        staffItem.innerHTML = `
          <div class="staff-avatar">${initials}</div>
          <div class="staff-info">
            <div class="staff-name">${staff.name}</div>
            <div class="staff-details">
              <div class="staff-detail">
                <i class="fas fa-briefcase"></i>
                <span>${staff.role}</span>
              </div>
              <div class="staff-detail">
                <i class="fas fa-phone"></i>
                <span>${staff.contact}</span>
              </div>
            </div>
          </div>
          <div class="staff-status ${statusClass}">
            <i class="fas fa-circle"></i>
            <span>${statusText}</span>
          </div>
        `;
        
        staffItem.addEventListener('click', () => selectStaff(staff));
        staffList.appendChild(staffItem);
      });
    }

    // Select a staff member
    function selectStaff(staff) {
      // Deselect previously selected staff
      document.querySelectorAll('.staff-item').forEach(item => {
        item.classList.remove('selected');
      });
      
      // Select the clicked staff
      document.querySelector(`.staff-item[data-id="${staff.id}"]`).classList.add('selected');
      
      // Update selected staff
      selectedStaff = staff;
      
      // Update selected staff info
      document.getElementById('selectedStaffInfo').classList.add('active');
      document.getElementById('selectedStaffAvatar').textContent = staff.name.split(' ').map(n => n[0]).join('');
      document.getElementById('selectedStaffName').textContent = staff.name;
      document.getElementById('selectedStaffRole').textContent = staff.role;
      document.getElementById('selectedStaffContact').textContent = staff.contact;
      
      // Enable assign button
      document.getElementById('assignBtn').disabled = false;
    }

    // Handle assignment form submission
    document.getElementById('assignmentForm').addEventListener('submit', function(e) {
      e.preventDefault();
      
      if (!selectedStaff) {
        alert('Please select a staff member first.');
        return;
      }
      
      const eventName = document.getElementById('eventName').value;
      const venue = document.getElementById('venue').value;
      const date = document.getElementById('eventDate').value;
      const time = document.getElementById('eventTime').value;
      const notes = document.getElementById('notes').value;
      
      // Here you would typically send this data to your backend
      alert(`Assignment Successful!\n\nStaff: ${selectedStaff.name}\nEvent: ${eventName}\nVenue: ${venue}\nDate: ${date}\nTime: ${time}\nNotes: ${notes || 'None'}`);
      
      // Reset form
      clearAssignment();
    });

    // Clear assignment form
    function clearAssignment() {
      document.getElementById('assignmentForm').reset();
      document.getElementById('selectedStaffInfo').classList.remove('active');
      document.getElementById('assignBtn').disabled = true;
      
      // Deselect staff
      document.querySelectorAll('.staff-item').forEach(item => {
        item.classList.remove('selected');
      });
      
      selectedStaff = null;
    }

    // Filter staff by status
    function filterStaff() {
      const filter = document.getElementById('statusFilter').value;
      const staffItems = document.querySelectorAll('.staff-item');
      
      staffItems.forEach(item => {
        const status = item.getAttribute('data-status');
        if (filter === 'all' || status === filter) {
          item.style.display = 'flex';
        } else {
          item.style.display = 'none';
        }
      });
    }

    // Search staff
    function searchStaff() {
      const input = document.getElementById('searchStaff');
      const filter = input.value.toUpperCase();
      const staffItems = document.querySelectorAll('.staff-item');
      
      staffItems.forEach(item => {
        const name = item.querySelector('.staff-name').textContent;
        const role = item.querySelector('.staff-detail span').textContent;
        
        if (name.toUpperCase().indexOf(filter) > -1 || role.toUpperCase().indexOf(filter) > -1) {
          item.style.display = 'flex';
        } else {
          item.style.display = 'none';
        }
      });
    }

    // Add Staff Function
    function addStaff() {
      alert('Add New Staff\n\nYour backend developer will implement:\n- Open add staff modal/form\n- Input fields: Name, Role, Contact, Email\n- Save to database\n- Refresh staff table');
    }

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
  </script>
</body>
</html>