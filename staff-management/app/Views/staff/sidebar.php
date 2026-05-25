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
                <a href="<?= site_url('staff/dashboard')?>" 
                   class="nav-link <?= $current_page === 'dashboard' ? 'active' : '' ?>">
                    <i class="fas fa-th-large"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= site_url('staff/schedule')?>" 
                   class="nav-link <?= $current_page === 'schedule' ? 'active' : '' ?>">
                    <i class="fas fa-calendar-alt"></i>
                    <span>My Schedule</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= site_url('assignment')?>" 
                   class="nav-link <?= in_array($current_page, ['assignments', 'assign-booking'], true) ? 'active' : '' ?>">
                    <i class="fas fa-tasks"></i>
                    <span>Assignments</span>
                </a>
            </li>
        </ul>
    </nav>

    <nav class="nav-section">
        <div class="nav-section-title">
            STAFF MANAGEMENT
            <i class="fas fa-chevron-down" style="font-size: 9px;"></i>
        </div>
        <ul class="nav-menu">
            <li class="nav-item">
                <a href="<?= site_url('staff')?>" 
                   class="nav-link <?= in_array($current_page, ['staff_list', 'staff_create', 'staff_edit', 'staff_show'], true) ? 'active' : '' ?>">
                    <i class="fas fa-user-friends"></i>
                    <span>Staff Directory</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= site_url('availability')?>" 
                   class="nav-link <?= $current_page === 'availability' ? 'active' : '' ?>">
                    <i class="fas fa-clock"></i>
                    <span>Availability</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= site_url('staff-management')?>" 
                   class="nav-link <?= $current_page === 'team' ? 'active' : '' ?>">
                    <i class="fas fa-users"></i>
                    <span>Team Members</span>
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
                <a href="<?= site_url('staff/profile')?>" 
                   class="nav-link <?= $current_page === 'profile' ? 'active' : '' ?>">
                    <i class="fas fa-user-cog"></i>
                    <span>My Profile</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= site_url('staff/logout')?>" 
                   class="nav-link">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
            </li>
        </ul>
    </nav>
</aside>
