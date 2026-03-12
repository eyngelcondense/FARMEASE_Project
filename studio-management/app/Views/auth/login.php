<?php 
  
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>FARMEASE Studio Portal - Login</title>
  <!-- Same head as staff login but Studio branding -->
  <style>/* Same styles */</style>
</head>
<body>
  <div class="header-bar"></div>
  <div class="login-container">
    <div class="login-card">
      <img src="<?= base_url('images/farmease-logo.png') ?>" alt="FARMEASE Logo">
      <div class="brand-name">FARMEASE</div>
      <small>Studio Management Portal</small>
      <h1>Studio Portal</h1>
      <p class="subtitle">Log in to manage studio bookings and classes</p>
      <?= form_open('studio/login') ?>
        <?= csrf_field() ?>
        <!-- Form fields -->
        <button type="submit" class="btn-login">Log in as Studio User</button>
      <?= form_close() ?>
    </div>
  </div>
</body>
</html>
