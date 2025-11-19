<?php
  $current_page = isset($current_page) ? $current_page : 'venues'
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
  

</head>
<body>


  <!-- Sidebar Include -->
  <?= $this->include('admin/sidebar') ?>
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