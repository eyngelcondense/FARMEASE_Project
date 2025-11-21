<?php
  
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login | San Isidro Labrador</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
  <link rel="icon" type="image/png" href="images/LOGO NG SAN ISIDRO.png"/>

  <style>
    .header-bar {
      height: 10px;
      background-color: #b2a187;
    }

    body {
      background-color: #fff;
      font-family: 'Poppins', sans-serif;
      color: #4b4b4b;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }

    .login-container {
      flex: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 40px 15px;
    }

    .login-card {
      max-width: 400px;
      width: 100%;
      text-align: center;
    }

    .login-card img {
      width: 80px;
      margin-bottom: 10px;
    }

    h1 {
      font-weight: 700;
      color: #3e3e3e;
      margin-bottom: 5px;
    }

    p.subtitle {
      color: #6b6b6b;
      font-size: 0.9rem;
      margin-bottom: 30px;
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

    .input-group-text {
      background: none;
      border: none;
      color: #b9a782;
    }

    .forgot {
      display: block;
      text-align: right;
      font-size: 0.85rem;
      color: #4b4b4b;
      text-decoration: none;
      margin-top: 5px;
    }

    .forgot:hover {
      text-decoration: underline;
    }

    .btn-login {
      background-color: #7c6a43;
      color: #fff;
      border-radius: 10px;
      width: 100%;
      padding: 10px;
      margin-top: 20px;
      border: none;
    }
    .btn-login:hover {
      background-color: #6a5938;
      transform: scale(1.02);
      transition: transform 0.5s;
    }

    .btn-signup {
      border: 2px solid #7c6a43;
      color: #7c6a43;
      background: none;
      border-radius: 10px;
      width: 100%;
      padding: 10px;
      margin-top: 10px;
    }

    .btn-signup:hover {
      background-color: #f4f2ed;
      transform: scale(1.02);
      transition: transform 0.5s;
    }

    footer {
      padding: 15px 0;
      font-size: 0.85rem;
      background-color: #b2a187;
    }

    .alert {
      margin-top: 15px;
    }

    /* Back Button */
    .back-btn {
      position: absolute;
      top: 20px;
      left: 25px;
      background-color: #7c6a43;
      color: #fff;
      border: none;
      border-radius: 50px;
      padding: 10px 18px;
      font-size: 1rem;
      font-weight: 600;
      text-decoration: none;
      box-shadow: 0 2px 6px rgba(0,0,0,0.2);
      transition: all 0.2s ease;
      display: flex;
      align-items: center;
      gap: 6px;
      z-index: 1000;
    }

    .back-btn:hover {
      background-color: #6a5938;
      transform: translateX(-3px);
      color: #fff;
    }

    @media (max-width: 576px) {
      .back-arrow {
        top: 10px !important;
        left: 10px !important;
        font-size: 1.6rem;
      }
    }

    .triangle-btn {
    width: 0;
    height: 0;
    border-top: 15px solid transparent;
    border-bottom: 15px solid transparent;
    border-right: 20px solid black;
    background: none;
    cursor: pointer;
}
.triangle-btn:hover {
    border-right-color: gray;
}
  </style>
</head>

<body>

  <div class="header-bar"></div>
  
   <!-- Back btn -->
    <div class="login-container">
    <a href="<?= site_url('/')?>" class="back-btn">
      <i class="fa-solid fa-chevron-left"></i>
  </a>

    <div class="login-card">
      <img src="images/LOGO NG SAN ISIDRO.png" alt="San Isidro Labrador Logo" style="width: 130px;">
      <div class="brand-name" style="font-family:Cambria, Cochin, Georgia, Times, 'Times New Roman', serif; color: #7c6a43;">SAN ISIDRO LABRADOR</div>
      <small style="display: block; margin-bottom: 20px;">RESORT AND LEISURE FARM</small> <br>
      <h1 style="font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;">Welcome Back</h1>
      <p class="subtitle" style="margin-bottom: 30px;">Log in to continue your journey with us</p>

      <?php if (session()->has('error')): ?>
            <div class="alert alert-danger"><?= session('error') ?></div>
        <?php endif; ?>

        <?php if (session()->has('message')): ?>
            <div class="alert alert-success"><?= session('message') ?></div>
        <?php endif; ?>

      <form id="loginForm" method="POST" action="<?= site_url('login') ?>">
        <?= csrf_field() ?>
        <div class="mb-3">
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
            <input 
              type="email" 
              class="form-control" 
              id="email" 
              name="email" 
              placeholder="Email Address" 
              required>
          </div>
        </div>

        <div class="mb-2">
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-lock"></i></span>
            <input 
              type="password" 
              class="form-control" 
              id="password" 
              name="password" 
              placeholder="Password" 
              required>
          </div>
        </div>

        <input type="checkbox" name="remember">
        <label for="rememberme" class="">remember me</label>

        <a href="<?= site_url('forgot-password')?>" class="forgot">Forgot password?</a>

        <button type="submit" class="btn-login">Log in</button>
        <button type="button" class="btn-signup" id="signupBtn">Sign up</button>
        
        <div id="alertBox"></div>
      </form>
    </div>
  </div>

  <footer>
  </footer> 

  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

  <script>
    // Redirect to signup page when "Sign up" button is clicked
    document.getElementById("signupBtn").addEventListener("click", function() {
      window.location.href = "/register";
    });

    document.getElementById("loginForm").addEventListener("submit", function(event) {

      const email = document.getElementById("email").value.trim();
      const password = document.getElementById("password").value.trim();
      const alertBox = document.getElementById("alertBox");

      // Clear previous alert
      alertBox.innerHTML = "";

      // Simple email pattern
      const emailPattern = /^[^ ]+@[^ ]+\.[a-z]{2,3}$/;

      if (email === "" || password === "") {
        showAlert("Please fill in all fields.", "danger");
      } else if (!emailPattern.test(email)) {
        showAlert("Please enter a valid email address.", "warning");
      } else if (password.length < 6) {
        showAlert("Password must be at least 6 characters long.", "warning");
      } else {
        showAlert("Login successful! Redirecting...", "success");
        setTimeout(() => {
          // Example PHP redirect placeholder
          // window.location.href = "dashboard.php";
        }, 1500);
      }

      function showAlert(message, type) {
        alertBox.innerHTML = `
          <div class="alert alert-${type}" role="alert">
            ${message}
          </div>
        `;
      }
    });
  </script>
</body>
</html>
