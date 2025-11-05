<?php if (session()->has('errors')): ?>
    <div style="color: red; margin-bottom: 1em;">
        <ul>
        <?php foreach (session('errors') as $error): ?>
            <li><?= esc($error) ?></li>
        <?php endforeach ?>
        </ul>
    </div>
<?php endif ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign Up | San Isidro Labrador</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    html, body {
        height: 100%;
        margin: 0;
        display: flex;
        flex-direction: column;
    }

    body {
      background-color: #f8f6f3;
      font-family: "Poppins", sans-serif;
    }

    .header-bar {
      height: 10px;
      background-color: #b2a187;
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

    .logo {
      text-align: center;
      margin-top: 40px;
    }

    .logo img {
      width: 60px;
      margin-bottom: 10px;
    }

    .brand-name {
      color: #a6795d;
      font-weight: 500;
      font-size: 15px;
      letter-spacing: 1px;
    }

    .signup-container {
      max-width: 400px;
      margin: 40px auto;
      background: #fff;
      text-align: center;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.05);
      flex: 1;
    }

    h2 {
      font-weight: 700;
      color: #000;
      text-shadow: 1px 1px #cfcfcf;
    }

    .form-control {
      border: 1px solid #b2a187;
      border-radius: 4px;
      padding-left: 35px;
    }

    .input-group-text {
      background: transparent;
      border: 1px solid #b2a187;
      border-right: none;
      color: #b2a187;
    }

    .btn-signup {
      background-color: #7c6a43;
      border: none;
      border-radius: 25px;
      padding: 10px;
      width: 100%;
      color: #fff;
      font-weight: 500;
      transition: 0.3s;
    }

    .btn-signup:hover {
      background-color: #8f836f;
    }

    footer {
      padding: 15px 0;
      font-size: 0.85rem;
      background-color: #b2a187;
    }
  </style>
</head>

<body>
  <div class="header-bar"></div>
  
  <!-- Back btn -->
    <a href="javascript:history.back()" class="back-btn">
    <i class="bi bi-arrow-left"></i> Back
  </a>

  <div class="signup-container">
    <div class="logo">
      <img src="images/LOGO NG SAN ISIDRO.png" alt="Logo" style="width: 130px;">
      <div class="brand-name" style="font-family:Cambria, Cochin, Georgia, Times, 'Times New Roman', serif">SAN ISIDRO LABRADOR</div>
      <small>RESORT AND LEISURE FARM</small>
    </div>

    <h2 class="mt-3 mb-2" style="font-family: 'Times New Roman', Times, serif;">Join Us</h2>
    <p class="text-muted mb-4">Create your account to book and enjoy our venue</p>

    <form id="signupForm" method="POST" action="<?= site_url('register') ?>">
      <?= csrf_field() ?>
        <div class="mb-3 input-group">
            <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
            <input type="text" class="form-control" id="name" name="name" placeholder="Name" required>
        </div>

        <div class="mb-3 input-group">
            <span class="input-group-text"><i class="bi bi-envelope-fill"></i></span>
            <input type="email" class="form-control" id="email" name="email" placeholder="Email Address" required>
        </div>

        <div class="mb-3 input-group">
            <span class="input-group-text"><i class="bi bi-envelope-fill"></i></span>
            <input type="tel" class="form-control" id="phone" name="phone" placeholder="Phone Number" required>
        </div>

        <div class="mb-3 input-group">
            <span class="input-group-text"><i class="bi bi-envelope-fill"></i></span>
            <input type="text" class="form-control" id="address" name="address" placeholder="Address" required>
        </div>

        <div class="mb-3 input-group">
            <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
            <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
        </div>

        <div class="mb-4 input-group">
            <span class="input-group-text"><i class="bi bi-lock"></i></span>
            <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required>
        </div>

        <button type="submit" class="btn btn-signup" id="signupBtn">Sign up</button>
    </form>

  </div>

  <footer>
  </footer>

  <!-- Validation by JS - not the actual logic -->
   <script>
    document.getElementById("signupForm").addEventListener("submit", function (event) {
      const name = document.getElementById("name").value.trim();
      const email = document.getElementById("email").value.trim();
      const phone = document.getElementById("phone").value.trim();
      const address = document.getElementById("address").value.trim();
      const password = document.getElementById("password").value.trim();
      const confirm = document.getElementById("confirm_password").value.trim();
      //verify values
      console.log("name " + name);
      console.log("email " + email);
      console.log("phone " + phone);
      console.log("address " + address); 
      console.log("password " + password);
      console.log("confirm " + confirm);  

      // Simple validation
      if (!name || !email || !phone || !address || !password || !confirm) {
        event.preventDefault();
        alert("⚠️ Please fill in all fields.");
        return;
      }

      // Basic email validation
      const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailPattern.test(email)) {
        event.preventDefault();
        alert("⚠️ Please enter a valid email address.");
        return;
      }

      // Check if passwords match
      if (password !== confirm) {
        event.preventDefault();
        alert("⚠️ Passwords do not match.");
        return;
      }

      // Optionally check password strength
      if (password.length < 8) {
        event.preventDefault();
        alert("⚠️ Password must be at least 8 characters long.");
        return;
      }

      // If all good - form submits normally to backend
    });
    </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
</body>
</html>
