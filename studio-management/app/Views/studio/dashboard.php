<?= $this->extend('studio/header') ?>
<?= $this->section('content') ?>
<div class="container mt-5">
<h1>Studio Dashboard</h1>
  <div class="row">
    <div class="col-md-3">
      <div class="card text-white bg-primary">
        <div class="card-body">
          <h5>Upcoming Bookings</h5>
          <h2>5</h2>
          <a href="<?= base_url('assignment') ?>" class="text-white">View</a>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card text-white bg-success">
        <div class="card-body">
          <h5>Available Slots</h5>
          <h2>12</h2>
          <a href="<?= base_url('availability') ?>" class="text-white">Manage</a>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card text-white bg-info">
        <div class="card-body">
          <h5>Schedule</h5>
          <h2>Calendar</h2>
          <a href="<?= base_url('scheduling') ?>" class="text-white">View</a>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card text-white bg-warning">
        <div class="card-body">
          <h5>Staff Management</h5>
          <h2>8</h2>
          <a href="<?= base_url('staff-management') ?>" class="text-white">Directory</a>
        </div>
      </div>
    </div>
  </div>
  <p>Welcome to FARMEASE Studio Portal.</p>
  <!-- Studio cards: Upcoming classes, Equipment status, etc. -->
</div>
<?= $this->endSection() ?>
