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
            background:
                radial-gradient(circle at top left, rgba(193,154,107,0.12), transparent 28%),
                linear-gradient(180deg, #fbf8f4 0%, #f6f2ec 100%);
            color: #3b2a18;
            overflow-x: hidden;
            transition: all 0.3s ease;
        }

        /* ===== HEADER STYLES ===== */
        .header-bar {
            background: linear-gradient(135deg, #3b2a18 0%, #7a6a58 72%, #c19a6b 100%);
            padding: 8px 0;
            box-shadow: 0 10px 28px rgba(59,42,24,0.15);
        }

        .header-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 28px;
            gap: 18px;
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
            font-family: 'IM Fell English', serif;
            font-size: 1.1rem;
            color: #f5e3c6;
            letter-spacing: 1.5px;
        }

        .header-logo-text p {
            margin: 0;
            font-size: 0.72rem;
            color: rgba(245,227,198,0.68);
        }

        /* Profile Dropdown */
        .profile-dropdown {
            position: relative;
            display: inline-block;
        }

        .profile-btn {
            background: none;
            border: none;
            color: #f5e3c6;
            font-size: 1.6rem;
            cursor: pointer;
            transition: color 0.3s;
        }

        .profile-btn:hover {
            color: #fff;
        }

        .dropdown-menu {
            display: none;
            position: absolute;
            right: 0;
            background-color: #ffffff;
            border: 1px solid #ddd4c6;
            border-radius: 14px;
            box-shadow: 0 18px 40px rgba(59,42,24,0.16);
            min-width: 190px;
            z-index: 10;
            padding: 8px;
        }

        .dropdown-menu a {
            color: #3b2a18;
            text-decoration: none;
            display: block;
            padding: 10px 12px;
            font-size: 0.9rem;
            transition: background-color 0.3s;
            border-radius: 10px;
        }

        .dropdown-menu a:hover {
            background-color: #f0ece4;
        }

        .profile-dropdown:hover .dropdown-menu {
            display: block;
        }

        /* Navigation */
        .navbar {
            background-color: rgba(255,255,255,0.78);
            border-top: 1px solid #e9e3db;
            border-bottom: 1px solid #e9e3db;
            padding: 0;
            backdrop-filter: blur(8px);
        }

        .navbar-nav {
            margin: 0 auto;
        }

        .navbar-nav .nav-link {
            color: #3b2a18;
            font-weight: 600;
            margin: 0 18px;
            padding: 15px 0;
            font-size: 0.95rem;
            position: relative;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        /* Hover Effect */
        .navbar-nav .nav-link:hover {
            color: #c19a6b;
        }

        /* Active Link (Current Page) */
        .navbar-nav .nav-link.active::after {
            content: "";
            position: absolute;
            bottom: 8px;
            left: 0;
            width: 100%;
            height: 2px;
            background-color: #c19a6b;
            transition: width 0.3s ease;
        }

        .navbar-nav .nav-link.active {
            color: #c19a6b;
        }

        /* Mobile Menu Toggle */
        .mobile-menu-toggle {
            display: none;
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1001;
            background: #c19a6b;
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
                    <a href="<?= base_url('studio/logout') ?>" 
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
                        <a class="nav-link <?= (uri_string() == 'studio/info' || uri_string() == 'studio/profile') ? 'active' : '' ?>" href="<?= base_url('studio/info') ?>">
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
