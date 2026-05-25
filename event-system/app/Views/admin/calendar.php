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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <style>
    :root {
      --primary-color: #8B4513;        /* Saddle Brown */
      --primary-light: #A0522D;       /* Sienna */
      --primary-dark: #5D4037;        /* Brown */
      --secondary-color: #D2B48C;     /* Tan */
      --text-primary: #3E2723;        /* Dark Brown */
      --text-secondary: #795548;      /* Brown */
      --border-color: #D7CCC8;        /* Light Brown */
      --light-bg: #EFEBE9;            /* Light Brown Background */
      --white: #FFFFFF;               /* White */
      --success: #5D4037;             /* Dark Brown */
      --warning: #BCAAA4;             /* Light Brown */
      --danger: #8D6E63;              /* Medium Brown */
    }
    
    /* Page Header */
    .page-header-card h1 {
        color: #5c3a21;
        font-weight: 700;
    }

    body {
      font-family: 'Poppins', sans-serif;
      color: var(--text-primary);
      background-color: #F5F5F0;  /* Light beige background */
    }
    
    .calendar-container {
      background: var(--white);
      border-radius: 12px;
      padding: 24px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
      margin-bottom: 24px;
      border: 1px solid var(--border-color);
    }

    .calendar-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 24px;
      padding: 0 16px;
    }

    .calendar-nav-btn {
      background: var(--light-bg);
      border: 1px solid var(--border-color);
      border-radius: 8px;
      padding: 8px 16px;
      cursor: pointer;
      transition: all 0.3s ease;
      color: var(--primary-dark);
      font-weight: 500;
    }

    .calendar-nav-btn:hover {
      background: var(--primary-color);
      color: var(--white);
      border-color: var(--primary-dark);
    }

    .calendar-month-year {
      font-size: 1.5rem;
      font-weight: 600;
      color: var(--primary-color);
      margin: 0;
    }

    .calendar-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 8px;
    }

    .calendar-day-header {
        text-align: center;
        font-weight: 600;
        color: #6c757d;
        padding: 12px 8px;
        font-size: 0.875rem;
        text-transform: uppercase;
    }

    .calendar-day {
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        min-height: 120px;
        padding: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
    }

    .calendar-day:hover {
        background: rgba(139, 69, 19, 0.1);
        transform: translateY(-2px);
        border-color: var(--primary-light);
    }

    .calendar-day.current-month {
        background: white;
    }

    .calendar-day.other-month {
        background: #f8f9fa;
        color: #adb5bd;
    }

    .calendar-day.today {
        background: rgba(139, 69, 19, 0.1);
        border: 2px solid var(--primary-color);
        font-weight: 600;
    }

    .calendar-day-number {
        font-weight: 600;
        margin-bottom: 4px;
        font-size: 0.9rem;
    }

    .event-badge {
        background: var(--primary-color);
        color: white;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 0.75rem;
        margin-bottom: 4px;
        cursor: pointer;
        transition: all 0.3s ease;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .event-badge:hover {
        background: #0056b3;
        transform: scale(1.05);
    }

    .event-badge.pending {
        background: var(--warning);
        color: var(--text-primary);
    }

    .event-badge.confirmed {
        background: var(--primary-light);
        color: white;
    }

    .event-badge.approved {
        background: var(--primary-dark);
        color: white;
    }

    .event-badge.completed {
        background: #4f6d7a;
        color: white;
    }

    .event-badge.refunded {
        background: #6c757d;
        color: white;
    }

    /* Graph Grid Styles */
    .calendar-grid-container {
        background: white;
        border-radius: 12px;
        padding: 24px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        margin-bottom: 24px;
    }

    .time-grid-wrapper {
        overflow-x: auto;
    }

    .time-grid {
        display: grid;
        grid-template-columns: 80px auto;
        min-width: 600px;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        overflow: hidden;
    }

    .time-column {
        background: #f8f9fa;
        border-right: 1px solid #dee2e6;
    }

    .time-slot-cell {
        padding: 6px 4px;
        border-bottom: 1px solid #dee2e6;
        font-size: 0.75rem;
        font-weight: 500;
        height: 40px;
        display: flex;
        align-items: center;
        background: white;
    }

    .time-slot-cell.header {
        background: #f8f9fa;
        font-weight: 600;
        height: 40px;
    }

    .events-column {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    }

    .event-column {
        border-right: 1px solid #dee2e6;
        position: relative;
    }

    .event-header {
        padding: 8px 6px;
        border-bottom: 1px solid #dee2e6;
        background: #f8f9fa;
        font-weight: 600;
        text-align: center;
        height: 40px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        position: sticky;
        top: 0;
        z-index: 10;
        font-size: 0.8rem;
        cursor: pointer;
    }

    .event-header:hover {
        background: rgba(139, 69, 19, 0.1);
        color: var(--primary-color);
    }

    .event-header small {
        font-size: 0.7rem;
    }

    .event-slot {
        padding: 0;
        border-bottom: 1px solid #dee2e6;
        height: 40px;
        position: relative;
        transition: all 0.3s ease;
        cursor: pointer;
        background: transparent;
    }

    .event-slot.booked {
        background: rgba(210, 180, 140, 0.2);
    }

    .event-slot.booked.confirmed {
        background: rgba(160, 82, 45, 0.2);
    }

    .event-slot.booked.approved {
        background: rgba(93, 64, 55, 0.2);
    }

    /* Compact Event Block */
    .event-block {
        position: absolute;
        left: 1px;
        right: 1px;
        background: var(--primary-color);
        border-radius: 3px;
        padding: 3px 6px;
        color: white;
        font-size: 0.65rem;
        z-index: 5;
        cursor: pointer;
        transition: all 0.2s ease;
        overflow: hidden;
        line-height: 1.3;
        font-weight: 500;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }

    .event-block.pending {
        background: var(--warning);
        color: var(--text-primary);
    }

    .event-block.confirmed {
        background: var(--primary-light);
        color: white;
    }

    .event-block.approved {
        background: var(--primary-dark);
        color: white;
    }

    .event-block.completed {
        background: #4f6d7a;
        color: white;
    }

    .event-block.refunded {
        background: #6c757d;
        color: white;
    }

    .event-block:hover {
        transform: scale(1.02);
        box-shadow: 0 1px 4px rgba(0,0,0,0.2);
        z-index: 20;
    }

    /* Compact booking info - show on hover */
    .event-block .booking-info {
        display: none;
    }

    .event-block:hover .booking-info {
        display: block;
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: rgba(0, 0, 0, 0.9);
        color: white;
        padding: 6px;
        border-radius: 4px;
        font-size: 0.7rem;
        z-index: 100;
        margin-top: 2px;
    }

    .booking-actions {
        position: absolute;
        top: 50%;
        right: 6px;
        transform: translateY(-50%);
        display: none;
        gap: 4px;
        background: rgba(255, 255, 255, 0.9);
        padding: 2px 4px;
        border-radius: 4px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }

    .event-slot:hover .booking-actions {
        display: flex;
    }

    .btn-approve, .btn-reject {
        padding: 3px 8px;
        font-size: 0.6rem;
        border: none;
        border-radius: 3px;
        cursor: pointer;
        line-height: 1.2;
        transition: all 0.2s ease;
        font-weight: 500;
        min-width: 20px;
        text-align: center;
        box-shadow: 0 1px 2px rgba(0,0,0,0.1);
    }

    .btn-approve {
        background: #5D4037;  /* Dark Brown */
        color: white;
    }

    .btn-approve:hover {
        background: #3E2723;  /* Darker Brown */
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0,0,0,0.15);
    }

    .btn-reject {
        background: #DC3545;  /* Red */
        color: white;
    }

    .btn-reject:hover {
        background: #C82333;  /* Darker Red */
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0,0,0,0.15);
    }

    .status-badge {
        font-size: 0.6rem;
        padding: 1px 4px;
        border-radius: 3px;
        margin-left: 2px;
    }

    .time-slot-cell:last-child,
    .event-slot:last-child {
        border-bottom: none;
    }

    .package-filter {
        max-width: 300px;
    }

    .loading-spinner {
        display: none;
        text-align: center;
        padding: 20px;
    }

    .grid-header {
        border-bottom: 1px solid #dee2e6;
        padding-bottom: 15px;
        margin-bottom: 15px;
    }
  </style>
</head>
<?= $this->include('admin/style');?>

<body>

  <!-- Sidebar Include -->
  <?= $this->include('admin/sidebar') ?>

  <!-- Main Content Area -->
  <div class="main-layout">
    <!-- Page Header -->
    <div class="page-header-card">
      <h1>Calendar of Events</h1>
      <p class="page-subtitle">View all upcoming events and bookings for your packages</p>
    </div>

    <!-- Package Filter -->
    <div class="package-filter mb-3">
        <select class="form-select" id="packageFilter">
            <option value="">All Packages</option>
            <?php foreach ($packages as $package): ?>
                <option value="<?= $package['id'] ?>"><?= esc($package['name']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <!-- Calendar Container -->
    <div class="calendar-container">
      <!-- Calendar Header -->
      <div class="calendar-header">
        <button class="calendar-nav-btn" onclick="previousMonth()">
          <i class="fas fa-chevron-left"></i>
        </button>
        <div class="calendar-month-year" id="monthYear">Loading...</div>
        <button class="calendar-nav-btn" onclick="nextMonth()">
          <i class="fas fa-chevron-right"></i>
        </button>
      </div>

      <!-- Loading Spinner -->
      <div class="loading-spinner" id="calendarLoading">
        <div class="spinner-border text-primary" role="status">
          <span class="visually-hidden">Loading...</span>
        </div>
      </div>

      <!-- Calendar Grid -->
      <div class="calendar-grid" id="calendarGrid">
        <!-- Calendar will be loaded dynamically -->
      </div>
    </div>

    <!-- Graph Style Calendar Grid -->
    <div class="calendar-grid-container" id="calendarGridContainer" style="display: none;">
      <div class="grid-header d-flex justify-content-between align-items-center">
        <h4 id="gridDateTitle">Schedule for </h4>
        <div class="package-filter">
          <select class="form-select form-select-sm" id="gridPackageFilter">
            <option value="">All Packages</option>
            <?php foreach ($packages as $package): ?>
              <option value="<?= $package['id'] ?>"><?= esc($package['name']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
      
      <div class="loading-spinner" id="gridLoading">
        <div class="spinner-border text-primary" role="status">
          <span class="visually-hidden">Loading...</span>
        </div>
      </div>
      
      <div class="time-grid-wrapper">
        <div class="time-grid" id="timeGrid">
          <!-- Grid will be loaded here -->
        </div>
      </div>
    </div>
  </div>

  <!-- Booking Details Modal -->
  <div class="modal fade" id="bookingModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header" style="background-color: #5D4037; color: white;">
          <h5 class="modal-title">Booking Details</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body" id="bookingModalBody" style="background-color: #F5F5F0;">
          Loading...
        </div>
        <div class="modal-footer" style="background-color: #EFEBE9;">
          <button type="button" class="btn btn-brown" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
  
  <style>
    .btn-brown {
      background-color: #8B4513;
      color: white;
      border: none;
    }
    .btn-brown:hover {
      background-color: #5D4037;
      color: white;
    }
  </style>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  
  <script>
    // Current date
    let currentMonth = new Date().getMonth() + 1;
    let currentYear = new Date().getFullYear();
    let selectedDate = null;
    let selectedPackage = '';
    const initialCalendarData = <?= json_encode($initialCalendarData ?? null, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?>;

    // Initialize calendar
    document.addEventListener('DOMContentLoaded', function() {
        setupEventListeners();

        if (initialCalendarData && initialCalendarData.data) {
            updateCalendarDisplay(initialCalendarData);
        } else {
            loadCalendar();
        }
    });

    function setupEventListeners() {
        // Package filter change
        document.getElementById('packageFilter').addEventListener('change', function() {
            selectedPackage = this.value;
            loadCalendar();
        });
        
        // Grid package filter change
        document.getElementById('gridPackageFilter').addEventListener('change', function() {
            selectedPackage = this.value;
            if (selectedDate) {
                loadCalendarGrid(selectedDate);
            }
        });
    }

    // Load calendar data
    async function loadCalendar() {
        showLoading('calendarLoading', true);
        
        try {
            const params = new URLSearchParams({
                month: currentMonth,
                year: currentYear,
                package_id: selectedPackage
            });

            const response = await fetch(`/admin/calendar/data?${params}`);
            const data = await response.json();

            if (data.success) {
                updateCalendarDisplay(data);
            } else {
                alert('Error loading calendar data');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Error loading calendar data');
        } finally {
            showLoading('calendarLoading', false);
        }
    }

    // Update calendar display
    function updateCalendarDisplay(data) {
        const monthNames = ['January', 'February', 'March', 'April', 'May', 'June',
                          'July', 'August', 'September', 'October', 'November', 'December'];
        
        document.getElementById('monthYear').textContent = `${monthNames[data.month - 1]} ${data.year}`;
        
        const calendarGrid = document.getElementById('calendarGrid');
        calendarGrid.innerHTML = '';

        // Add day headers
        const dayHeaders = ['SUN', 'MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT'];
        dayHeaders.forEach(day => {
            const dayHeader = document.createElement('div');
            dayHeader.className = 'calendar-day-header';
            dayHeader.textContent = day;
            calendarGrid.appendChild(dayHeader);
        });

        // Add calendar days
        data.data.forEach(day => {
            const dayElement = document.createElement('div');
            dayElement.className = `calendar-day ${day.month === 'current' ? 'current-month' : 'other-month'} ${day.is_today ? 'today' : ''}`;
            dayElement.onclick = () => selectDate(day.date, day.month === 'current');

            const dayNumber = document.createElement('div');
            dayNumber.className = 'calendar-day-number';
            dayNumber.textContent = day.day;
            dayElement.appendChild(dayNumber);

            // Add event badges
            day.bookings.forEach(booking => {
                const eventBadge = document.createElement('div');
                eventBadge.className = `event-badge ${booking.status}`;
                eventBadge.textContent = `${booking.package_name}`;
                eventBadge.onclick = (e) => {
                    e.stopPropagation();
                    viewBooking(booking.id);
                };
                dayElement.appendChild(eventBadge);
            });

            calendarGrid.appendChild(dayElement);
        });
    }

    function parseTimeString(timeStr) {
      // If the time includes AM/PM, Date can parse it correctly
      if (/am|pm/i.test(timeStr)) {
          return new Date(`1970-01-01 ${timeStr}`);
      }

      // If time is HH:MM (24-hour)
      return new Date(`1970-01-01T${timeStr}`);
  }


    // Select date and show grid
    async function selectDate(date, isCurrentMonth) {
        if (!isCurrentMonth) return;
        
        selectedDate = date;
        await loadCalendarGrid(date);
    }

    // Load calendar grid data
    async function loadCalendarGrid(date) {
        showLoading('gridLoading', true);
        document.getElementById('calendarGridContainer').style.display = 'block';
        document.getElementById('gridDateTitle').textContent = `Schedule for ${formatDate(date)}`;

        try {
            const params = new URLSearchParams({
                date: date,
                package_id: selectedPackage
            });

            const response = await fetch(`/admin/calendar/grid-data?${params}`);
            const data = await response.json();

            if (data.success) {
                updateGridDisplay(data);
            } else {
                alert('Error loading schedule data');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Error loading schedule data');
        } finally {
            showLoading('gridLoading', false);
        }
    }

    // Update grid display
    function updateGridDisplay(data) {
        const timeGrid = document.getElementById('timeGrid');
        timeGrid.innerHTML = '';

        if (data.bookings.length === 0) {
            timeGrid.innerHTML = `
                <div class="empty-state">
                    <i class="fas fa-calendar-times fa-3x mb-3"></i>
                    <h5>No Bookings</h5>
                    <p>No bookings found for this date.</p>
                </div>
            `;
            return;
        }

        // Create time column
        const timeColumn = document.createElement('div');
        timeColumn.className = 'time-column';
        
        // Add header
        const timeHeader = document.createElement('div');
        timeHeader.className = 'time-slot-cell header';
        timeHeader.textContent = 'Time';
        timeColumn.appendChild(timeHeader);
        
        // Add time slots
        data.time_slots.forEach(slot => {
            const timeCell = document.createElement('div');
            timeCell.className = 'time-slot-cell';
            timeCell.textContent = slot.display_time.split(' - ')[0];
            timeColumn.appendChild(timeCell);
        });
        
        timeGrid.appendChild(timeColumn);

        // Create events columns
        const eventsColumn = document.createElement('div');
        eventsColumn.className = 'events-column';
        
        // Add event headers
        data.bookings.forEach(booking => {
            const eventColumn = document.createElement('div');
            eventColumn.className = 'event-column';
            
            // Event header
            const eventHeader = document.createElement('div');
            eventHeader.className = 'event-header';
            eventHeader.innerHTML = `
                <div>${booking.package_name}</div>
                <small class="text-muted">${booking.client_name}</small>
                <span class="status-badge badge bg-${getStatusBadgeColor(booking.status)}">
                    ${booking.status}
                </span>
            `;
            eventHeader.onclick = () => viewBooking(booking.id);
            eventColumn.appendChild(eventHeader);
            
            // Event slots
            data.grid_data.forEach(row => {
                const eventSlot = document.createElement('div');
                eventSlot.className = 'event-slot';
                
                const eventData = row.events.find(e => e.booking_id === booking.id);
                
                if (eventData && eventData.is_booked) {
                    eventSlot.classList.add('booked', eventData.booking_data.status);
                    
                    // Show booking block for first slot of each booking
                    if (eventData.is_first_slot) {
                        const eventBlock = document.createElement('div');
                        eventBlock.className = `event-block ${eventData.booking_data.status}`;
                        
                        const startTime = formatTime(eventData.booking_data.start_time);
                        const endTime = formatTime(eventData.booking_data.end_time);
                        
                        // Compact content - only show time initially
                        eventBlock.innerHTML = `
                            <div class="compact-view">
                                <strong>${startTime}</strong>
                            </div>
                            <div class="booking-info">
                                <strong>${eventData.booking_data.package_name}</strong><br>
                                ${startTime} - ${endTime}<br>
                                <small>${eventData.booking_data.client_name}</small>
                                ${eventData.booking_data.venue_name ? `<br><small>Venue: ${eventData.booking_data.venue_name}</small>` : ''}
                            </div>
                        `;
                        
                        eventBlock.onclick = (e) => {
                            e.stopPropagation();
                            viewBooking(booking.id);
                        };
                        
                        // Calculate height based on duration
                          const start = parseTimeString(eventData.booking_data.start_time);
                          const end = parseTimeString(eventData.booking_data.end_time);

                          // Fix if JS interprets 12:00 as midnight
                          if (end < start) {
                              end.setDate(end.getDate() + 1);
                          }

                          const duration = (end - start) / 1000 / 3600;
                          eventBlock.style.height = `calc(${duration * 40}px - 2px)`;

                        eventBlock.style.top = '1px';
                        
                        eventSlot.appendChild(eventBlock);
                        
                        // Add approval actions for pending bookings
                        if (eventData.booking_data.status === 'pending') {
                            const actions = document.createElement('div');
                            actions.className = 'booking-actions';
                            actions.innerHTML = `
                                <button class="btn-approve" onclick="updateBookingStatus(${booking.id}, 'approved', event)" title="Approve">
                                    <i class="fas fa-check"></i>
                                </button>
                                <button class="btn-reject" onclick="updateBookingStatus(${booking.id}, 'cancelled', event)" title="Reject">
                                    <i class="fas fa-times"></i>
                                </button>
                            `;
                            eventSlot.appendChild(actions);
                        }
                    }
                }
                
                eventColumn.appendChild(eventSlot);
            });
            
            eventsColumn.appendChild(eventColumn);
        });
        
        timeGrid.appendChild(eventsColumn);
    }

    // Update booking status
    async function updateBookingStatus(bookingId, status, event, actionType = 'status') {
        if (event) {
            event.stopPropagation();
        }

        const prompt = actionType === 'payment'
            ? 'mark this booking payment as refunded'
            : `set this booking to ${status}`;

        if (!confirm(`Are you sure you want to ${prompt}?`)) {
            return;
        }

        try {
            const formData = new FormData();
            formData.append('booking_id', bookingId);
            if (actionType === 'payment') {
                formData.append('payment_status', status);
            } else {
                formData.append('status', status);
            }

            const response = await fetch('/admin/calendar/update-status', {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            if (result.success) {
                alert(result.message || 'Booking status updated successfully');
                // Reload the grid to reflect changes
                if (selectedDate) {
                    loadCalendarGrid(selectedDate);
                }
            } else {
                alert('Error updating booking status: ' + result.message);
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Error updating booking status');
        }
    }

    // View booking details
    async function viewBooking(bookingId) {
        try {
            const response = await fetch(`/admin/calendar/booking/${bookingId}`);
            const data = await response.json();

            if (data.success) {
                showBookingModal(data.booking);
            } else {
                alert('Error loading booking details');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Error loading booking details');
        }
    }

    // Show booking modal
    function showBookingModal(booking) {
        const modalBody = document.getElementById('bookingModalBody');
        modalBody.innerHTML = `
            <div class="row">
                <div class="col-md-6">
                    <h6>Event Details</h6>
                    <p><strong>Package:</strong> ${booking.package_name || 'N/A'}</p>
                    <p><strong>Date:</strong> ${formatDate(booking.event_date)}</p>
                    <p><strong>Time:</strong> ${formatTime(booking.start_time)} - ${formatTime(booking.end_time)}</p>
                    <p><strong>Venue:</strong> ${booking.venue_name || 'N/A'}</p>
                    <p><strong>Guests:</strong> ${booking.total_guests}</p>
                </div>
                <div class="col-md-6">
                    <h6>Client Details</h6>
                    <p><strong>Name:</strong> ${booking.client_name}</p>
                    <p><strong>Email:</strong> ${booking.client_email}</p>
                    <p><strong>Phone:</strong> ${booking.client_phone || 'N/A'}</p>
                    <p><strong>Reference:</strong> ${booking.booking_reference}</p>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-12">
                    <h6>Booking Information</h6>
                    <p><strong>Status:</strong> <span class="badge bg-${getStatusBadgeColor(booking.status)}">${booking.status}</span></p>
                    <p><strong>Payment Status:</strong> <span class="badge bg-${getStatusBadgeColor(booking.payment_status)}">${booking.payment_status || 'pending'}</span></p>
                    <p><strong>Total Amount:</strong> ₱${parseFloat(booking.total_amount || 0).toLocaleString()}</p>
                    ${booking.special_requests ? `<p><strong>Special Requests:</strong> ${booking.special_requests}</p>` : ''}
                </div>
            </div>
            ${booking.status === 'pending' ? `
            <div class="row mt-3">
                <div class="col-12">
                    <h6>Quick Actions</h6>
                    <button class="btn btn-success btn-sm" onclick="updateBookingStatus(${booking.id}, 'approved', event)">
                        <i class="fas fa-check"></i> Approve Booking
                    </button>
                    <button class="btn btn-danger btn-sm ms-2" onclick="updateBookingStatus(${booking.id}, 'cancelled', event)">
                        <i class="fas fa-times"></i> Reject Booking
                    </button>
                </div>
            </div>
            ` : ''}
            ${['approved', 'confirmed'].includes(booking.status) ? `
            <div class="row mt-3">
                <div class="col-12">
                    <h6>Lifecycle Actions</h6>
                    <button class="btn btn-primary btn-sm" onclick="updateBookingStatus(${booking.id}, 'completed', event)">
                        <i class="fas fa-check-double"></i> Mark Completed
                    </button>
                    ${booking.payment_status !== 'refunded' ? `
                    <button class="btn btn-warning btn-sm ms-2" onclick="updateBookingStatus(${booking.id}, 'refunded', event, 'payment')">
                        <i class="fas fa-undo"></i> Mark Refunded
                    </button>
                    ` : ''}
                </div>
            </div>
            ` : ''}
            ${!['pending', 'approved', 'confirmed'].includes(booking.status) && booking.payment_status !== 'refunded' ? `
            <div class="row mt-3">
                <div class="col-12">
                    <h6>Refund Bookkeeping</h6>
                    <button class="btn btn-warning btn-sm" onclick="updateBookingStatus(${booking.id}, 'refunded', event, 'payment')">
                        <i class="fas fa-undo"></i> Mark Refunded
                    </button>
                </div>
            </div>
            ` : ''}
        `;

        const modal = new bootstrap.Modal(document.getElementById('bookingModal'));
        modal.show();
    }

    // Utility functions
    function showLoading(elementId, show) {
        document.getElementById(elementId).style.display = show ? 'block' : 'none';
    }

    function formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('en-US', { 
            weekday: 'long', 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric' 
        });
    }

    function formatTime(timeString) {
        const time = new Date(`2000-01-01T${timeString}`);
        return time.toLocaleTimeString('en-US', { 
            hour: 'numeric', 
            minute: '2-digit',
            hour12: true 
        });
    }

    function getStatusBadgeColor(status) {
        const colors = {
            'pending': 'warning',
            'approved': 'success',
            'confirmed': 'info',
            'cancelled': 'danger',
            'completed': 'primary',
            'refunded': 'secondary'
        };
        return colors[status] || 'secondary';
    }

    function strtotime(timeString) {
        return new Date(`2000-01-01T${timeString}`).getTime();
    }

    // Navigation functions
    function previousMonth() {
        currentMonth--;
        if (currentMonth < 1) {
            currentMonth = 12;
            currentYear--;
        }
        loadCalendar();
        hideGrid();
    }

    function nextMonth() {
        currentMonth++;
        if (currentMonth > 12) {
            currentMonth = 1;
            currentYear++;
        }
        loadCalendar();
        hideGrid();
    }

    function hideGrid() {
        document.getElementById('calendarGridContainer').style.display = 'none';
    }

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
  </script>
</body>
</html>