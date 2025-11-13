<?php
    // Ensure $current_page is defined
    if (!isset($current_page)) {
        $current_page = '';
    }
?>
    
<!-- Mobile Menu Toggle -->
<button class="mobile-menu-toggle" onclick="toggleSidebar()">
    <i class="fas fa-bars"></i>
</button>

<!-- Left Sidebar -->
<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="sidebar-logo">
            <div class="sidebar-logo-icon">
                <img src="images/LOGO NG SAN ISIDRO.png" alt="San Isidro Logo">
            </div>
            <div class="sidebar-title">San Isidro Labrador<br>Resort and Leisure Farm</div>
        </div>
        <button class="quick-add-btn">
            <div class="quick-add-btn-icon">
                <i class="fas fa-plus"></i>
            </div>
            <div class="quick-add-text">
                <div class="quick-add-text-title">Add Quick Event</div>
                <div class="quick-add-text-sub">Events</div>
            </div>
        </button>
    </div>

    <nav class="nav-section">
        <div class="nav-section-title">
            MAIN NAVIGATION
            <i class="fas fa-chevron-down" style="font-size: 9px;"></i>
        </div>
        <ul class="nav-menu">
            <li class="nav-item">
                <a href="<?= site_url('dashboard')?>" 
                   class="nav-link <?= $current_page === 'dashboard' ? 'active' : '' ?>">
                    <i class="fas fa-th-large"></i>
                    Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= site_url('venue/packages')?>" 
                   class="nav-link <?= $current_page === 'venues' ? 'active' : '' ?>">
                    <i class="fas fa-map-marker-alt"></i>
                    Manage Venues and Packages
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= site_url('admin/bookings')?>" 
                   class="nav-link <?= $current_page === 'bookings' ? 'active' : '' ?>">
                    <i class="fas fa-calendar-check"></i>
                    Bookings
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= site_url('admin/payments')?>" 
                   class="nav-link <?= $current_page === 'payments' ? 'active' : '' ?>">
                    <i class="fas fa-credit-card"></i>
                    Payments
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= site_url('admin/gallery')?>" 
                   class="nav-link <?= $current_page === 'gallery' ? 'active' : '' ?>">
                    <i class="fas fa-images"></i>
                    Gallery
                </a>
            </li>
        </ul>
    </nav>

    <nav class="nav-section">
        <div class="nav-section-title">
            SUPPORT AND SETTINGS
            <i class="fas fa-chevron-down" style="font-size: 9px;"></i>
        </div>
        <ul class="nav-menu">
            <li class="nav-item">
                <a href="<?= site_url('feedback')?>" 
                   class="nav-link <?= $current_page === 'feedback' ? 'active' : '' ?>">
                    <i class="fas fa-comment-dots"></i>
                    Feedback and Inquiries
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= site_url('admin/calendar')?>" 
                   class="nav-link <?= $current_page === 'calendar' ? 'active' : '' ?>">
                    <i class="fas fa-calendar-alt"></i>
                    Calendar of Events
                </a>
            </li>
        </ul>
    </nav>

    <nav class="nav-section">
        <div class="nav-section-title">
            ACCOUNT MANAGEMENT
            <i class="fas fa-chevron-down" style="font-size: 9px;"></i>
        </div>
        <ul class="nav-menu">
            <li class="nav-item">
                <a href="<?= site_url('admin/staffs')?>" 
                   class="nav-link <?= $current_page === 'staffs' ? 'active' : '' ?>">
                    <i class="fas fa-users"></i>
                    Manage Staffs
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= site_url('logout')?>" class="nav-link">
                    <i class="fas fa-sign-out-alt"></i>
                    Logout
                </a>
            </li>
        </ul>
    </nav>
</aside>

<script>
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    sidebar.classList.toggle('active');
}
</script>