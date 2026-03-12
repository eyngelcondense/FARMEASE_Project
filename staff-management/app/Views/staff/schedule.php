<?= $this->extend('staff/header') ?>
<?= $this->section('content') ?>
<div class="container mt-5">
  <h1>My Schedule</h1>
  <!-- Calendar/schedule view adapted from admin/calendar.php or client/bookings.php -->
  <div id="schedule-calendar"></div>
</div>
<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script>
// Simple schedule JS
</script>
<?= $this->endSection() ?>
