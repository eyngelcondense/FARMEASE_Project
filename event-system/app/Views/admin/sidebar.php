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
        <!-- <button class="sidebar-toggle" onclick="toggleSidebar()">
            <i class="fas fa-chevron-left"></i>
        </button> -->

        <!-- Add Quick Event Button -->
        <button class="quick-add-btn" onclick="openQuickEventModal()">
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
                <a href="<?= site_url('admin/transactions')?>" 
                   class="nav-link <?= $current_page === 'transactions' ? 'active' : '' ?>">
                    <i class="fas fa-history"></i>
                    <span>Transactions</span>
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

<!-- Quick Event Modal -->
<div id="quickEventModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Quick Event</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="<?= site_url('admin/calendar/add_event') ?>" method="post">
                    <div class="form-group">
                        <label for="event_title">Event Title:</label>
                        <input type="text" id="event_title" name="event_title" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="event_date">Date:</label>
                        <input type="date" id="event_date" name="event_date" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="event_time">Time:</label>
                        <input type="time" id="event_time" name="event_time" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="event_desc">Description:</label>
                        <textarea id="event_desc" name="event_desc" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="text-right">
                        <button type="submit" class="btn btn-primary">Add Event</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Open modal
function openQuickEventModal() {
    $('#quickEventModal').modal('show');
}
</script>