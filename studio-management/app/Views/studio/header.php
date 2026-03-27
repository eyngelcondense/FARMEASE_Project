<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Studio Management - San Isidro Labrador Resort' ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        /* ===== RESET & BASE STYLES ===== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f3f0;
            color: #3b2a18;
            overflow-x: hidden;
            transition: all 0.3s ease;
        }

        /* ===== HEADER STYLES ===== */
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
            background-color: #8b6f47;
            transition: width 0.3s ease;
        }

        .navbar-nav .nav-link.active {
            color: #8b6f47;
        }

        /* Mobile Menu Toggle */
        .mobile-menu-toggle {
            display: none;
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1001;
            background: #8b6f47;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
        }

        @media (max-width: 768px) {
            .header-container {
                padding: 0 20px;
            }
            
            .navbar-nav .nav-link {
                margin: 0 10px;
                font-size: 0.85rem;
            }
            
            .mobile-menu-toggle {
                display: block;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <?php if(isset($user) && $user): ?>
        <?php $studio = $studio ?? null; ?>
    <?php else: ?>
        <?php $studio = null; ?>
    <?php endif; ?>
    
    <div class="header-bar">
        <div class="header-container">
            
            <div class="header-logo">
                <img src="<?= base_url('images/LOGO NG SAN ISIDRO.png') ?>" alt="San Isidro Labrador Logo">
                <div class="header-logo-text">
                    <h5>SAN ISIDRO LABRADOR</h5>
                    <p>RESORT AND LEISURE FARM</p>
                </div>
            </div>
            
            <!-- Profile Dropdown -->
            <div class="profile-dropdown">
                <div class="profile-pic-container">
                    <?php if (!empty($studio['profile_pic'])): ?>
                        <img src="/uploads/profile_pics/<?= esc($studio['profile_pic']) ?>" 
                             class="rounded-circle border" 
                             width="40" 
                             height="40" 
                             style="object-fit: cover; border: 3px solid #7c6a43 !important;">
                    <?php else: ?>
                        <img src="https://ui-avatars.com/api/?name=<?= urlencode($user->username ?? 'Studio') ?>&background=7c6a43&color=fff&size=120" 
                             class="rounded-circle border" 
                             width="40" 
                             height="40" 
                             style="object-fit: cover; border: 3px solid #7c6a43 !important;">
                    <?php endif; ?>
                </div>
                <div class="dropdown-menu">
                    <a href="<?= base_url('studio/profile') ?>">Profile Settings</a>
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
                        <a class="nav-link <?= (uri_string() == 'studio/dashboard' || uri_string() == 'studio') ? 'active' : '' ?>" href="<?= base_url('studio/dashboard') ?>">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= (uri_string() == 'studio/bookings') ? 'active' : '' ?>" href="<?= base_url('studio/bookings') ?>">
                            <i class="fas fa-calendar-alt"></i> My Bookings
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= (uri_string() == 'studio/info') ? 'active' : '' ?>" href="<?= base_url('studio/info') ?>">
                            <i class="fas fa-building"></i> Studio Info
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= (uri_string() == 'studio/gallery') ? 'active' : '' ?>" href="<?= base_url('studio/gallery') ?>">
                            <i class="fas fa-images"></i> Gallery
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= (uri_string() == 'studio/schedule') ? 'active' : '' ?>" href="<?= base_url('studio/schedule') ?>">
                            <i class="fas fa-clock"></i> Schedule
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <button class="mobile-menu-toggle" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>
    
    <main class="container-fluid mt-4">
        <?= $this->renderSection('content') ?>
    </main>
</body>
</html>
