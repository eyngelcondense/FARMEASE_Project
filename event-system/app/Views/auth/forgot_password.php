<?php
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Forgot Password | San Isidro Labrador</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

  <style>
    body {
      background-color: #fff;
      font-family: 'Poppins', sans-serif;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
      color: #4b4b4b;
    }

    .header-bar {
      height: 10px;
      background-color: #b2a187;
    }

    .forgot-container {
      flex: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 40px 15px;
    }

    .forgot-card {
      max-width: 420px;
      width: 100%;
      text-align: center;
      border-radius: 15px;
      padding: 35px 25px;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
      background-color: #fff;
    }

    .forgot-card img {
      width: 100px;
      margin-bottom: 10px;
    }

    h1 {
      font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
      font-weight: 700;
      color: #3e3e3e;
      margin-bottom: 8px;
    }

    p.subtitle {
      color: #6b6b6b;
      font-size: 0.9rem;
      margin-bottom: 25px;
    }

    .form-control {
      border: 2px solid #b9a782;
      border-radius: 10px;
      padding: 10px 15px;
      font-size: 0.95rem;
    }

    .form-control:focus {
      border-color: #7c6a43;
      box-shadow: none;
    }

    .btn-reset {
      background-color: #7c6a43;
      color: #fff;
      border: none;
      border-radius: 10px;
      width: 100%;
      padding: 10px;
      margin-top: 15px;
    }

    .btn-reset:hover {
      background-color: #6a5938;
    }

    .back-link {
      display: block;
      margin-top: 15px;
      color: #4b4b4b;
      font-size: 0.9rem;
      text-decoration: none;
    }

    .back-link:hover {
      text-decoration: underline;
    }

    footer {
      text-align: center;
      padding: 15px 0;
      font-size: 0.85rem;
      background-color: #b2a187;
      color: #4b4b4b;
    }

    .back-arrow {
      position: fixed !important;
      top: 15px !important;
      left: 20px !important;
      font-size: 1.8rem;
      color: #7c6a43;
      text-decoration: none;
      z-index: 9999 !important;
      transition: all 0.2s ease;
    }

    .back-arrow:hover {
      color: #6a5938;
      transform: translateX(-3px);
    }

    @media (max-width: 576px) {
      .forgot-card {
        padding: 25px 20px;
      }
      .back-arrow {
        top: 10px !important;
        left: 10px !important;
        font-size: 1.6rem;
      }
    }
  </style>
</head>

<body>
  <div class="header-bar"></div>

  <div class="forgot-container">
    <div class="forgot-card">
        <img src="images/LOGO NG SAN ISIDRO.png" alt="San Isidro Labrador Logo">
        <div style="font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif; color:#7c6a43;">SAN ISIDRO LABRADOR</div>
        <small>RESORT AND LEISURE FARM</small>
        <h1>Forgot Password</h1>
        <p class="subtitle">Enter your registered email, and we'll send you a reset link.</p>

        <!-- Display error/success messages -->
        <?php if (session()->has('error')): ?>
            <div class="alert alert-danger"><?= session('error') ?></div>
        <?php endif; ?>

        <?php if (session()->has('success')): ?>
            <div class="alert alert-success"><?= session('success') ?></div>
        <?php endif; ?>

        <form method="POST" action="<?= site_url('forgot-password') ?>">
            <?= csrf_field() ?>
            
            <div class="mb-3">
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                    <input name="email" id="email" type="email" class="form-control" placeholder="Email Address" required value="<?= old('email') ?>">
                </div>
            </div>
            <button type="submit" class="btn-reset">Send Reset Link</button>
        </form>

        <a href="<?= site_url('login') ?>" class="back-link">Back to Login</a>
    </div>
</div>

  <footer>
    Phone: 0912-345-6789 | Email: sanisidro@gmail.com
  </footer>
</body>
</html>
