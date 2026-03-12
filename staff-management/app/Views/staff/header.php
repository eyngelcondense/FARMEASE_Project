<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $title ?? 'FARMEASE Staff Portal' ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.6.0/css/all.min.css">
  <style>
    /* Adapted from event-system client/header.php - FARMEASE Staff theme */
    .header-bar { background-color: #b2a187; padding: 5px 0; }
    /* Full header styles with nav items: Dashboard, My Schedule, Shifts, Requests, Profile */
  </style>
</head>
<body>
  <!-- FARMEASE Staff Header/Nav adapted -->
  <div class="header-bar">
    <div class="header-container">
      <div class="header-logo">
        <img src="<?= base_url('images/farmease-logo.png') ?>" alt="FARMEASE">
        <div class="header-logo-text">
          <h5>FARMEASE STAFF</h5>
          <p>Management Portal</p>
        </div>
      </div>
      <!-- Profile dropdown for staff -->
    </div>
  </div>
  <nav class="navbar navbar-expand-lg">
    <ul class="navbar-nav">
      <li><a class="nav-link <?= (uri_string() == 'staff/dashboard') ? 'active' : '' ?>" href="<?= site_url('staff/dashboard') ?>">DASHBOARD</a></li>
      <li><a class="nav-link <?= (uri_string() == 'staff/schedule') ? 'active' : '' ?>" href="<?= site_url('staff/schedule') ?>">MY SCHEDULE</a></li>
      <li><a class="nav-link <?= (uri_string() == 'staff/shifts') ? 'active' : '' ?>" href="<?= site_url('staff/shifts') ?>">SHIFTS</a></li>
      <li><a class="nav-link" href="<?= base_url('logout') ?>">LOGOUT</a></li>
    </ul>
  </nav>
</body>
</html>
