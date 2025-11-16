
<?php if(session()->getFlashdata('message') || session()->getFlashdata('success') || session()->getFlashdata('error')): ?>
  <div class="container mt-2">
    <?php if(session()->getFlashdata('message')): ?>
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= session()->getFlashdata('message') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    <?php endif; ?>
    
    <?php if(session()->getFlashdata('success')): ?>
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= session()->getFlashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    <?php endif; ?>
    
    <?php if(session()->getFlashdata('error')): ?>
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= session()->getFlashdata('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    <?php endif; ?>
  </div>
<?php endif; ?>

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

    /* Profile Dropdown */
    .profile-dropdown {
      position: relative;
      display: inline-block;
    }

    .profile-btn {
      background: none;
      border: none;
      color: #3b2a18;
      font-size: 1.6rem;
      cursor: pointer;
      transition: color 0.3s;
    }

    .profile-btn:hover {
      color: #fffcf8ff;
    }

    .dropdown-menu {
      display: none;
      position: absolute;
      right: 0;
      background-color: #ffffff;
      border: 1px solid #ddd;
      border-radius: 8px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
      min-width: 160px;
      z-index: 10;
    }

    .dropdown-menu a {
      color: #3b2a18;
      text-decoration: none;
      display: block;
      padding: 10px 15px;
      font-size: 0.9rem;
      transition: background-color 0.3s;
    }

    .dropdown-menu a:hover {
      background-color: #f5f1eb;
    }

    .profile-dropdown:hover .dropdown-menu {
      display: block;
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
  font-size: 0.95rem;
  position: relative;
  text-decoration: none;
  transition: color 0.3s ease;
}

/* Hover Effect */
.navbar-nav .nav-link:hover {
  color: #8b6f47;
}

/* Active Link (Current Page) */
.navbar-nav .nav-link.active::after {
  content: "";
  position: absolute;
  bottom: 8px;
  left: 0;
  width: 100%;
  height: 2px;
  background-color: #8b6f47; /* gold-brown underline */
  transition: width 0.3s ease;
}

.navbar-nav .nav-link.active {
  color: #8b6f47;
}


</style>
</head>

<body>
    <!-- Header -->
    <?php if(isset($user) && $user): ?>
        <?php $client = $client ?? null; ?>
    <?php else: ?>
        <?php $client = null; ?>
    <?php endif; ?>
  <div class="header-bar">
    <div class="header-container">
      <div class="header-logo">
        <img src="images/LOGO NG SAN ISIDRO.png" alt="San Isidro Labrador Logo">
        <div class="header-logo-text">
          <h5>SAN ISIDRO LABRADOR</h5>
          <p>RESORT AND LEISURE FARM</p>
        </div>
      </div>
   <!-- Profile Dropdown -->
      <div class="profile-dropdown">
        <div class="profile-pic-container">
                    <?php if (!empty($client['profile_pic'])): ?>
                            <img src="/uploads/profile_pics/<?= esc($client['profile_pic']) ?>" 
                                 class="rounded-circle border" 
                                 width="40" 
                                 height="40" 
                                 style="object-fit: cover; border: 3px solid #7c6a43 !important;">
                        <?php else: ?>
                            <img src="https://ui-avatars.com/api/?name=<?= urlencode($user->username ?? 'User') ?>&background=7c6a43&color=fff&size=120" 
                                 class="rounded-circle border" 
                                 width="40" 
                                 height="40" 
                                 style="object-fit: cover; border: 3px solid #7c6a43 !important;">
                        <?php endif; ?>
                </div>
        <div class="dropdown-menu">
          <a href="<?= route_to('profile') ?>">Profile Settings</a>
          <hr style="margin: 5px 0;">
          <a href="<?= base_url('logout') ?>" 
            class="logout-btn" 
            title="Logout"
            onclick="return confirmLogout(event)">
            <i class="fas fa-sign-out-alt"></i> Logout
          </a>
        </div>
      </div>
    </div>
  </div>

    <nav class="navbar navbar-expand-lg">
  <div class="container-fluid">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link <?= (uri_string() == 'home' || uri_string() == '') ? 'active' : '' ?>" href="<?= site_url('home') ?>">HOME</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= (uri_string() == 'packages') ? 'active' : '' ?>" href="<?= site_url('packages') ?>">PACKAGES</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= (uri_string() == 'gallery') ? 'active' : '' ?>" href="<?= site_url('gallery') ?>">VIDEO/GALLERIES</a>
        </li>
        <li class="nav-item">
        <a class="nav-link <?= (uri_string() == 'testimonials') ? 'active' : '' ?>" href="<?= site_url('testimonials') ?>">TESTIMONIALS</a>
      </li>
      <li class="nav-item"><a class="nav-link <?= (uri_string() == 'booking') ? 'active' : '' ?>" href="<?= site_url('booking') ?>">CONTACT</a>
      </li>
      </ul>
    </div>
  </div>
</nav>

<script>
  function confirmLogout(event) {
    event.preventDefault(); // stop the default link action
    const confirmAction = confirm("Are you sure you want to log out?");
    if (confirmAction) {
      window.location.href = event.currentTarget.href;
    }
    return false;
  }
</script>

</body>
</html>