<?php
if (!isset($current_page)) {
    $current_page = 'dashboard';
}
?>

<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="sidebar-logo">
            <div class="sidebar-logo-icon">
                <img onclick="toggleSidebar()" src="<?= base_url('images/LOGO NG SAN ISIDRO.png') ?>" alt="San Isidro Logo">
            </div>
            <div class="sidebar-title">San Isidro Labrador<br>Resort and Leisure Farm</div>
        </div>
    </div>

    <nav class="nav-section">
        <div class="nav-section-title">
            MAIN NAVIGATION
            <i class="fas fa-chevron-down" style="font-size: 9px;"></i>
        </div>
        <ul class="nav-menu">
            <li class="nav-item">
                <a href="<?= site_url('studio/dashboard')?>" 
                   class="nav-link <?= $current_page === 'dashboard' ? 'active' : '' ?>">
                    <i class="fas fa-th-large"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= site_url('studio/bookings')?>" 
                   class="nav-link <?= $current_page === 'bookings' ? 'active' : '' ?>">
                    <i class="fas fa-calendar-check"></i>
                    <span>Bookings</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= site_url('studio/schedule')?>" 
                   class="nav-link <?= $current_page === 'schedule' ? 'active' : '' ?>">
                    <i class="fas fa-clock"></i>
                    <span>Schedule</span>
                </a>
            </li>
        </ul>
    </nav>

    <nav class="nav-section">
        <div class="nav-section-title">
            STUDIO MANAGEMENT
            <i class="fas fa-chevron-down" style="font-size: 9px;"></i>
        </div>
        <ul class="nav-menu">
            <li class="nav-item">
                <a href="<?= site_url('studio/gallery')?>" 
                   class="nav-link <?= $current_page === 'gallery' ? 'active' : '' ?>">
                    <i class="fas fa-images"></i>
                    <span>Gallery</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= site_url('studio/info')?>" 
                   class="nav-link <?= $current_page === 'info' ? 'active' : '' ?>">
                    <i class="fas fa-building"></i>
                    <span>Studio Information</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= site_url('studio/feedback')?>" 
                   class="nav-link <?= $current_page === 'feedback' ? 'active' : '' ?>">
                    <i class="fas fa-comment-dots"></i>
                    <span>Reviews & Feedback</span>
                </a>
            </li>
        </ul>
    </nav>

    <nav class="nav-section">
        <div class="nav-section-title">
            ACCOUNT
            <i class="fas fa-chevron-down" style="font-size: 9px;"></i>
        </div>
        <ul class="nav-menu">
            <li class="nav-item">
                <a href="<?= site_url('studio/profile')?>" 
                   class="nav-link <?= $current_page === 'profile' ? 'active' : '' ?>">
                    <i class="fas fa-user-cog"></i>
                    <span>My Profile</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= site_url('studio/logout')?>" 
                   class="nav-link">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
            </li>
        </ul>
    </nav>
</aside>
