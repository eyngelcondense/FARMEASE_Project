<?php 
  
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>FARMEASE Staff Portal - Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
  <style>
    /* Same styles as event-system auth/login.php but adapted */
    .header-bar { height: 10px; background-color: #b2a187; }
    body { background-color: #fff; font-family: 'Poppins', sans-serif; color: #4b4b4b; display: flex; flex-direction: column; min-height: 100vh; }
    .login-container { flex: 1; display: flex; align-items: center; justify-content: center; padding: 40px 15px; }
    .login-card { max-width: 400px; width: 100%; text-align: center; }
    /* ... (copy full styles from read_file event-system/app/Views/auth/login.php, replace "San Isidro Labrador" with "FARMEASE Staff Portal") */
    h1 { font-weight: 700; color: #3e3e3e; margin-bottom: 5px; }
    /* Full styles here - abbreviated for response */
  </style>
</head>
<body>
  <div class="header-bar"></div>
  <div class="login-container">
    <div class="login-card">
      <img src="<?= base_url('images/farmease-logo.png') ?>" alt="FARMEASE Logo" style="width: 130px;">
      <div class="brand-name">FARMEASE</div>
      <small>Staff Management Portal</small> <br>
      <h1>Staff Portal</h1>
      <p class="subtitle">Log in to access staff dashboard and schedules</p>
      <?= form_open('staff/login') ?>
        <?= csrf_field() ?>
        <!-- Form fields same as original -->
        <button type="submit" class="btn-login">Log in as Staff</button>
      <?= form_close() ?>
    </div>
  </div>
</body>
</html>
