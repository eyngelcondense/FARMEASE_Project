<?php
    $current_page = isset($current_page) ? $current_page : 'calendar'; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Calendar of Events - San Isidro Labrador Resort</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.6.0/css/all.min.css">


</head>
<?= $this->include('admin/style');?>

<body>

  <!-- Sidebar Include -->
  <?= $this->include('admin/sidebar') ?>

  <!-- Main Content Area -->
  <div class="main-layout">
    <!-- Page Header -->
    <div class="page-header">
      <h1>Calendar of Events</h1>
      <p class="page-subtitle">View all upcoming events and bookings for your venues</p>
    </div>

    <!-- Calendar Container -->
    <div class="calendar-container">
      <!-- Calendar Header -->
      <div class="calendar-header">
        <button class="calendar-nav-btn" onclick="previousMonth()">
          <i class="fas fa-chevron-left"></i>
        </button>
        <div class="calendar-month-year" id="monthYear">November 2025</div>
        <button class="calendar-nav-btn" onclick="nextMonth()">
          <i class="fas fa-chevron-right"></i>
        </button>
      </div>

      <!-- Calendar Grid -->
      <div class="calendar-grid" id="calendarGrid">
        <!-- Day Headers -->
        <div class="calendar-day-header">SUN</div>
        <div class="calendar-day-header">MON</div>
        <div class="calendar-day-header">TUE</div>
        <div class="calendar-day-header">WED</div>
        <div class="calendar-day-header">THU</div>
        <div class="calendar-day-header">FRI</div>
        <div class="calendar-day-header">SAT</div>

        <!-- Calendar Days - November 2025 -->
        <div class="calendar-day other-month"><div class="calendar-day-number">26</div></div>
        <div class="calendar-day other-month"><div class="calendar-day-number">27</div></div>
        <div class="calendar-day"><div class="calendar-day-number">2</div></div>
        <div class="calendar-day"><div class="calendar-day-number">3</div></div>
        <div class="calendar-day"><div class="calendar-day-number">4</div></div>
        <div class="calendar-day"><div class="calendar-day-number">5</div></div>
        <div class="calendar-day"><div class="calendar-day-number">6</div></div>

        <div class="calendar-day"><div class="calendar-day-number">7</div><div class="event-badge" onclick="viewEvent(1)">Wedding - Rose Hall</div></div>
        <div class="calendar-day"><div class="calendar-day-number">8</div></div>
        <div class="calendar-day"><div class="calendar-day-number">9</div></div>
        <div class="calendar-day today"><div class="calendar-day-number">10</div></div>
        <div class="calendar-day"><div class="calendar-day-number">11</div></div>
        <div class="calendar-day"><div class="calendar-day-number">12</div></div>
        <div class="calendar-day"><div class="calendar-day-number">13</div></div>

        <div class="calendar-day"><div class="calendar-day-number">14</div></div>
        <div class="calendar-day"><div class="calendar-day-number">15</div></div>
        <div class="calendar-day"><div class="calendar-day-number">16</div></div>
        <div class="calendar-day"><div class="calendar-day-number">17</div></div>
        <div class="calendar-day"><div class="calendar-day-number">18</div></div>
        <div class="calendar-day"><div class="calendar-day-number">19</div></div>
        <div class="calendar-day"><div class="calendar-day-number">20</div></div>

        <div class="calendar-day"><div class="calendar-day-number">21</div></div>
        <div class="calendar-day"><div class="calendar-day-number">22</div><div class="event-badge" onclick="viewEvent(2)">Meeting - City Hall</div></div>
        <div class="calendar-day"><div class="calendar-day-number">23</div></div>
        <div class="calendar-day"><div class="calendar-day-number">24</div></div>
        <div class="calendar-day"><div class="calendar-day-number">25</div></div>
        <div class="calendar-day"><div class="calendar-day-number">26</div></div>
        <div class="calendar-day"><div class="calendar-day-number">27</div></div>

        <div class="calendar-day"><div class="calendar-day-number">28</div><div class="event-badge" onclick="viewEvent(3)">Conference - Main Hall</div></div>
        <div class="calendar-day"><div class="calendar-day-number">29</div></div>
        <div class="calendar-day"><div class="calendar-day-number">30</div></div>
        <div class="calendar-day other-month"><div class="calendar-day-number">1</div></div>
        <div class="calendar-day other-month"><div class="calendar-day-number">2</div></div>
        <div class="calendar-day other-month"><div class="calendar-day-number">3</div></div>
        <div class="calendar-day other-month"><div class="calendar-day-number">4</div></div>

        <div class="calendar-day other-month"><div class="calendar-day-number">5</div><div class="event-badge" onclick="viewEvent(4)">Debut - Event Place</div></div>
        <div class="calendar-day other-month"><div class="calendar-day-number">6</div></div>
        <div class="calendar-day other-month"><div class="calendar-day-number">7</div></div>
        <div class="calendar-day other-month"><div class="calendar-day-number">8</div></div>
        <div class="calendar-day other-month"><div class="calendar-day-number">9</div></div>
        <div class="calendar-day other-month"><div class="calendar-day-number">10</div></div>
        <div class="calendar-day other-month"><div class="calendar-day-number">11</div></div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  
  <script>
    // Current date
    let currentMonth = 10; // November (0-indexed)
    let currentYear = 2025;

    // Toggle Sidebar for Mobile
    function toggleSidebar() {
      document.getElementById('sidebar').classList.toggle('active');
    }

    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', (e) => {
      const sidebar = document.getElementById('sidebar');
      const toggle = document.querySelector('.mobile-menu-toggle');
      
      if (window.innerWidth <= 992) {
        if (!sidebar.contains(e.target) && !toggle.contains(e.target)) {
          sidebar.classList.remove('active');
        }
      }
    });

    // Previous Month
    function previousMonth() {
      currentMonth--;
      if (currentMonth < 0) {
        currentMonth = 11;
        currentYear--;
      }
      updateCalendar();
    }

    // Next Month
    function nextMonth() {
      currentMonth++;
      if (currentMonth > 11) {
        currentMonth = 0;
        currentYear++;
      }
      updateCalendar();
    }

    // Update Calendar
    function updateCalendar() {
      const monthNames = ['January', 'February', 'March', 'April', 'May', 'June',
                          'July', 'August', 'September', 'October', 'November', 'December'];
      
      document.getElementById('monthYear').textContent = `${monthNames[currentMonth]} ${currentYear}`;
      
      alert(`Calendar will be updated to: ${monthNames[currentMonth]} ${currentYear}\n\nYour backend developer will implement:\n- Generate calendar days dynamically\n- Fetch events from database\n- Display event badges on correct dates\n- Handle multiple events per day`);
    }

    // View Event
    function viewEvent(id) {
      event.stopPropagation();
      alert(`View Event ID: ${id}\n\nYour backend developer will implement:\n- Open event details modal\n- Show full event information\n- Edit event option\n- Delete event option\n- View booking details`);
    }

    // Add click event to empty calendar days
    document.querySelectorAll('.calendar-day').forEach(day => {
      day.addEventListener('click', function() {
        if (!this.querySelector('.event-badge')) {
          const dayNumber = this.querySelector('.calendar-day-number').textContent;
          alert(`Add event on day ${dayNumber}\n\nYour backend developer will implement:\n- Open "Add Event" modal\n- Pre-fill selected date\n- Save event to database\n- Refresh calendar`);
        }
      });
    });

    // Add animation to calendar on load
    window.addEventListener('load', () => {
      const days = document.querySelectorAll('.calendar-day');
      days.forEach((day, index) => {
        day.style.opacity = '0';
        day.style.transform = 'scale(0.9)';
        day.style.transition = 'all 0.3s ease';
        
        setTimeout(() => {
          day.style.opacity = '1';
          day.style.transform = 'scale(1)';
        }, 20 + (index * 15));
      });
    });
  </script>
</body>
</html>