<?= $this->extend('staff/header') ?>
<?= $this->section('content') ?>
<div class="container mt-5">
  <h1>Staff Profile</h1>
  <!-- Adapt from client/profile_settings.php: Staff details (name, phone, address, profile pic upload) -->
  <form method="post" action="<?= site_url('staff/profile/update') ?>">
    <?= csrf_field() ?>
    <div class="mb-3">
      <label>Full Name</label>
      <input type="text" name="fullname" class="form-control" value="<?= esc($staff['fullname'] ?? '') ?>">
    </div>
    <!-- More fields: phone, email, address -->
    <button type="submit" class="btn btn-primary">Update Profile</button>
  </form>
</div>
<?= $this->endSection() ?>
