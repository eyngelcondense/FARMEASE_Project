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
                <a href="<?= site_url('admin/dashboard')?>" 
                   class="nav-link <?= $current_page === 'dashboard' ? 'active' : '' ?>">
                    <i class="fas fa-th-large"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= site_url('venues')?>" 
                   class="nav-link <?= $current_page === 'venues' ? 'active' : '' ?>">
                    <i class="fas fa-map-marker-alt"></i>
                    <span>Venues</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= site_url('packages-view')?>"
                class="nav-link <?= $current_page === 'pack' ? 'active' : '' ?>">
                    <i class="fas fa-cube"></i>
                    <span>Packages</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= site_url('addons')?>" 
                   class="nav-link <?= $current_page === 'addons' ? 'active' : '' ?>">
                    <i class="fas fa-plus-circle"></i>
                    <span>Add-ons</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= site_url('admin/bookings')?>" 
                   class="nav-link <?= $current_page === 'bookings' ? 'active' : '' ?>">
                    <i class="fas fa-calendar-check"></i>
                    <span>Bookings</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= site_url('admin/payments')?>" 
                   class="nav-link <?= $current_page === 'payments' ? 'active' : '' ?>">
                    <i class="fas fa-credit-card"></i>
                    <span>Payments</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="<?= site_url('admin/contracts')?>" 
                   class="nav-link <?= $current_page === 'contracts' ? 'active' : '' ?>">
                    <i class="fas fa-file-contract"></i>
                    <span>Contracts</span>
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
                <a href="<?= site_url('admin/gallery')?>" 
                   class="nav-link <?= $current_page === 'gallery' ? 'active' : '' ?>">
                    <i class="fas fa-images"></i>
                    <span>Gallery</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= site_url('feedback')?>" 
                   class="nav-link <?= $current_page === 'feedback' ? 'active' : '' ?>">
                    <i class="fas fa-comment-dots"></i>
                    <span>Feedbacks</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= site_url('admin/calendar')?>" 
                   class="nav-link <?= $current_page === 'calendar' ? 'active' : '' ?>">
                    <i class="fas fa-calendar-alt"></i>
                    <span>Calendar</span>
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
                <a href="<?= site_url('admin/users')?>" 
                   class="nav-link <?= $current_page === 'users' ? 'active' : '' ?>">
                    <i class="fas fa-user-cog"></i>
                    <span>User Accounts</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= site_url('admin/client-transactions')?>" 
                   class="nav-link <?= $current_page === 'transactions' ? 'active' : '' ?>">
                    <i class="fas fa-history"></i>
                    <span>Transactions</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= site_url('admin/staffs')?>" 
                   class="nav-link <?= $current_page === 'staffs' ? 'active' : '' ?>">
                    <i class="fas fa-users"></i>
                    <span>Staff Management</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= site_url('admin/manage-staff')?>" 
                   class="nav-link <?= $current_page === 'manage_staff' ? 'active' : '' ?>">
                    <i class="fas fa-user-plus"></i>
                    <span>Staff Assignment</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= site_url('admin/studios')?>" 
                   class="nav-link <?= $current_page === 'studios' ? 'active' : '' ?>">
                    <i class="fas fa-building"></i>
                    <span>Studio Management</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= site_url('logout')?>" class="nav-link">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Log Out</span>
                </a>
            </li>
        </ul>
    </nav>
</aside>