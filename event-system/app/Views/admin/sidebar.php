<?php
    // Ensure $current_page is defined
    $current_page = isset($current_page) ? $current_page : null;
    if (!isset($current_page)) {
        $current_page = 'dashboard';
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
                <img src="<?= base_url('images/LOGO NG SAN ISIDRO.png') ?>" alt="San Isidro Logo">
            </div>
            <div class="sidebar-title">San Isidro Labrador<br>Resort and Leisure Farm</div>
        </div>

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
                    Feedbacks
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
                <a href="<?= site_url('manage-staff')?>"
                class="nav-link <?= ($current_page === 'staffs' || $current_page === 'manage_staff') ? 'active' : '' ?>">
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

<!-- Quick Event Modal -->
<div id="quickEventModal" class="quick-event-modal">
    <div class="quick-event-modal-content">
        <span class="close" onclick="closeQuickEventModal()">&times;</span>
        <h2>Add Quick Event</h2>
        <form action="<?= site_url('admin/calendar/add_event') ?>" method="post">
            <label for="event_title">Event Title:</label>
            <input type="text" id="event_title" name="event_title" required>

            <label for="event_date">Date:</label>
            <input type="date" id="event_date" name="event_date" required>

            <label for="event_time">Time:</label>
            <input type="time" id="event_time" name="event_time">

            <label for="event_desc">Description:</label>
            <textarea id="event_desc" name="event_desc" rows="3"></textarea>

            <button type="submit" class="submit-btn">Add Event</button>
        </form>
    </div>
</div>

<!-- JS for Sidebar and Modal -->
<script>
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    sidebar.classList.toggle('active');
}

// Open modal
function openQuickEventModal() {
    document.getElementById('quickEventModal').style.display = 'block';
}

// Close modal
function closeQuickEventModal() {
    document.getElementById('quickEventModal').style.display = 'none';
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('quickEventModal');
    if (event.target === modal) {
        modal.style.display = 'none';
    }
}
</script>

<!-- Basic Styles for Modal -->
<style>
.quick-event-modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0; top: 0;
    width: 100%; height: 100%;
    background: rgba(0,0,0,0.5);
}

.quick-event-modal-content {
    background: #fff;
    margin: 10% auto;
    padding: 20px;
    width: 90%;
    max-width: 400px;
    border-radius: 10px;
    box-shadow: 0 0 15px rgba(0,0,0,0.2);
}

.quick-event-modal-content h2 {
    margin-top: 0;
    text-align: center;
    color: #333;
}

.quick-event-modal-content label {
    display: block;
    margin-top: 10px;
    font-weight: bold;
}

.quick-event-modal-content input,
.quick-event-modal-content textarea {
    width: 100%;
    padding: 8px;
    margin-top: 5px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

.quick-event-modal-content .submit-btn {
    width: 100%;
    margin-top: 15px;
    padding: 10px;
    background: #28a745;
    color: #fff;
    border: none;
    border-radius: 6px;
    cursor: pointer;
}

.quick-event-modal-content .submit-btn:hover {
    background: #218838;
}

.quick-event-modal-content .close {
    float: right;
    font-size: 22px;
    cursor: pointer;
    color: #555;
}

.quick-event-modal-content .close:hover {
    color: red;
}
</style>
