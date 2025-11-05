<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $title ?? 'San Isidro Labrador Resort and Leisure Farm' ?></title>
   <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="bootstrap5/css/bootstrap.min.css">

  <!-- Google Font -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.6.0/css/all.min.css" crossorigin="anonymous">
 
<style>
     /* Header */
    .header-bar {
      background-color: #b2a187;
      padding: 5px 0;
    }

    .header-container {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 50px;
    }

    .header-logo {
      display: flex;
      align-items: center;
      gap: 15px;
    }

    .header-logo img {
      height: 70px;
    }

    .header-logo-text {
      text-align: left;
      font-family: 'Times New Roman', Times, serif;
    }

    .header-logo-text h5 {
      margin: 0;
      font-size: 1rem;
      color: #3b2a18;
      letter-spacing: 2px;
    }

    .header-logo-text p {
      margin: 0;
      font-size: 0.7rem;
      color: #8b6f47;
    }

     /* Logout Icon */
    .logout-btn {
      color: #3b2a18;
      font-size: 1.5rem;
      text-decoration: none;
      transition: color 0.3s;
    }

    .logout-btn:hover {
      color: #fffcf8ff;
    }

    /* Navigation */
    .navbar {
      background-color: #ffffff;
      border-top: 1px solid #ddd;
      border-bottom: 1px solid #ddd;
      padding: 0;
    }

    .navbar-nav {
      margin: 0 auto;
    }

    .navbar-nav .nav-link {
      color: #3b2a18;
      font-weight: 600;
      margin: 0 20px;
      padding: 15px 0;
      transition: color 0.3s;
      font-size: 0.95rem;
    }

    .navbar-nav .nav-link:hover,
    .navbar-nav .nav-link.active {
      color: #3b2a18;
    }

    .navbar-nav .nav-link:hover {
    color: #8b6f47;          /* gold-brown hover color */
    border-bottom: 2px solid #8b6f47; /* small underline effect */
    transition: 0.3s ease;
    }

</style>
</head>

<body>
    <!-- Header -->
  <div class="header-bar">
    <div class="header-container">
      <div class="header-logo">
        <img src="images/LOGO NG SAN ISIDRO.png" alt="San Isidro Labrador Logo">
        <div class="header-logo-text">
          <h5>SAN ISIDRO LABRADOR</h5>
          <p>RESORT AND LEISURE FARM</p>
        </div>
      </div>
   <!-- Right: Logout icon -->
      <a href="<?= base_url('logout') ?>" class="logout-btn" title="Logout">
        <i class="fas fa-sign-out-alt"></i>
      </a>
    </div>
  </div>

    <!-- Navbar -->
  <nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link active" href="<?= site_url('home') ?>">HOME</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" href="<?= site_url('packages') ?>">PACKAGES</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" href="<?= site_url('gallery') ?>">VIDEO/GALLERIES</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" href="<?= site_url('testimonials') ?>">TESTIMONIALS</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" href="<?= site_url('contact') ?>">CONTACT</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

</body>
</html>