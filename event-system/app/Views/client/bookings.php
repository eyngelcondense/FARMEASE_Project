<?php
 $title = "Booking | San Isidro Labrador Resort and Leisure Farm";
?>
<?= view('client/header', ['title' => $title, 'user' => $user, 'client' => $client]) ?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

  <style>
    .section-title {
      text-align: center;
      font-weight: bold;
      font-size: 26px;
      text-transform: uppercase;
      margin-top: 40px;
      margin-bottom: 30px;
    }
    .title-decoration {
      width: 80px;
      height: 3px;
      background-color: #8f8a83;
      margin: 10px auto 30px;
    }
    .contact-section { padding: 20px 0 60px; }
    .contact-info h3 {
      font-size: 18px;
      font-weight: bold;
      margin-top: 25px;
      text-transform: uppercase;
    }
    .contact-info p { font-size: 15px; line-height: 1.7; }
    .contact-info img {
      width: 100%;
      border-radius: 8px;
      margin-top: 10px;
    }
    .map-container iframe {
      border: none;
      width: 100%;
      height: 250px;
      border-radius: 8px;
      margin-top: 10px;
    }
    .inquiry-form h3 {
      font-size: 18px;
      font-weight: bold;
      margin-bottom: 20px;
    }
    form input, form textarea, form select {
      font-size: 14px;
      border-radius: 0;
      border: 1px solid #ccc;
    }
    .btn-submit {
      transition: 0.3s;
      background-color: #7c6a43;
      color: #fff;
      border-radius: 10px;
      width: 100%;
      padding: 10px;
      margin-top: 20px;
      border: none;
    }
    .btn-submit:hover {
      background-color: #3b2a18;
      color: #fff;
    }
    .social-icons i:hover { color: #e3cfa3; }

    /* Calendar Styles */
    .calendar-container {
      background: linear-gradient(135deg, #f8f6f3 0%, #ffffff 100%);
      border-radius: 12px;
      box-shadow: 0 4px 15px rgba(124, 106, 67, 0.15);
      padding: 25px;
      margin-top: 20px;
      border: 1px solid #e8e3da;
    }
    .calendar-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
      padding-bottom: 15px;
      border-bottom: 2px solid #7c6a43;
    }
    .calendar-header h3 {
      font-size: 20px;
      font-weight: bold;
      margin: 0;
      color: #3b2a18;
      letter-spacing: 0.5px;
    }
    .calendar-nav {
      display: flex;
      gap: 8px;
    }
    .calendar-nav button {
      background: #7c6a43;
      color: white;
      border: none;
      padding: 8px 14px;
      border-radius: 8px;
      cursor: pointer;
      transition: all 0.3s;
      font-weight: bold;
      box-shadow: 0 2px 5px rgba(124, 106, 67, 0.2);
    }
    .calendar-nav button:hover {
      background: #3b2a18;
      transform: translateY(-2px);
      box-shadow: 0 4px 8px rgba(124, 106, 67, 0.3);
    }
    .calendar-grid {
      display: grid;
      grid-template-columns: repeat(7, 1fr);
      gap: 8px;
      margin-bottom: 10px;
    }
    .calendar-day-header {
      text-align: center;
      font-weight: bold;
      font-size: 12px;
      color: #7c6a43;
      padding: 8px 0;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }
    .calendar-days-grid {
      display: grid;
      grid-template-columns: repeat(7, 1fr);
      gap: 6px;
    }
    .calendar-day {
      aspect-ratio: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 8px;
      font-size: 13px;
      font-weight: 500;
      cursor: default;
      position: relative;
      min-height: 38px;
      max-height: 38px;
      transition: all 0.3s ease;
    }
    .calendar-day.selected {
      background: linear-gradient(135deg, #7c6a43 0%, #5a4a3a 100%) !important;
      color: white !important;
      font-weight: bold;
      border: 2px solid #3b2a18 !important;
      box-shadow: 0 3px 10px rgba(124, 106, 67, 0.4) !important;
    }
    .calendar-day.booked {
      background: linear-gradient(135deg, #5a4a3a 0%, #3b2a18 100%);
      color: white;
      font-weight: bold;
      box-shadow: 0 3px 8px rgba(59, 42, 24, 0.4);
    }
    .calendar-day.available {
      background: white;
      border: 2px solid #e8e3da;
      color: #3b2a18;
    }
    .calendar-day.available:hover {
      border-color: #7c6a43;
      background: #f8f6f3;
      transform: scale(1.05);
      box-shadow: 0 2px 8px rgba(124, 106, 67, 0.2);
    }
    .calendar-day.today {
      border: 3px solid #7c6a43;
      font-weight: bold;
      background: linear-gradient(135deg, #fff9e6 0%, #ffffff 100%);
      box-shadow: 0 3px 10px rgba(124, 106, 67, 0.3);
    }
    .calendar-day.empty {
      background: transparent;
      border: none;
    }
    .calendar-legend {
      display: flex;
      justify-content: center;
      gap: 25px;
      margin-top: 20px;
      padding-top: 20px;
      border-top: 2px solid #e8e3da;
      font-size: 13px;
    }
    .legend-item {
      display: flex;
      align-items: center;
      gap: 10px;
      font-weight: 500;
      color: #3b2a18;
    }
    .legend-box {
      width: 24px;
      height: 24px;
      border-radius: 6px;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .legend-box.booked {
      background: linear-gradient(135deg, #5a4a3a 0%, #3b2a18 100%);
    }
    .legend-box.available {
      background: white;
      border: 2px solid #e8e3da;
    }
    .legend-box.today {
      background: linear-gradient(135deg, #fff9e6 0%, #ffffff 100%);
      border: 3px solid #7c6a43;
    }
    .time-slot {
        padding: 8px 12px;
        margin: 5px;
        border: 2px solid #e8e3da;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s;
        background: white;
    }
    .time-slot:hover {
        border-color: #7c6a43;
        background: #f8f6f3;
    }
    .time-slot.selected {
        background: #7c6a43;
        color: white;
        border-color: #3b2a18;
    }
    .time-slot.unavailable {
        background: #f8d7da;
        color: #721c24;
        border-color: #f5c6cb;
        cursor: not-allowed;
    }
    .venue-option {
        padding: 10px;
        border: 2px solid #e8e3da;
        border-radius: 8px;
        margin-bottom: 10px;
        cursor: pointer;
        transition: all 0.3s;
    }
    .venue-option:hover {
        border-color: #7c6a43;
    }
    .venue-option.selected {
        background: #f8f6f3;
        border-color: #7c6a43;
    }
    .loading-spinner {
        display: none;
        text-align: center;
        padding: 10px;
    }
    .time-slot {
        padding: 8px 12px;
        margin: 5px;
        border: 2px solid #e8e3da;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s;
        background: white;
        display: inline-block;
    }
    .time-slot:hover {
        border-color: #7c6a43;
        background: #f8f6f3;
    }
    .time-slot.selected {
        background: #7c6a43;
        color: white;
        border-color: #3b2a18;
    }
    .time-slot.unavailable {
        background: #f8d7da;
        color: #721c24;
        border-color: #f5c6cb;
        cursor: not-allowed;
    }
    .loading-spinner {
        display: none;
        text-align: center;
        padding: 10px;
    }
    
    /* Package Carousel Styles */
    .package-carousel {
        background: linear-gradient(135deg, #f8f6f3 0%, #ffffff 100%);
        border-radius: 12px;
        padding: 25px;
        margin: 30px 0;
        border: 1px solid #e8e3da;
    }
    .package-card {
        background: white;
        border-radius: 10px;
        padding: 20px;
        margin: 10px;
        border: 2px solid #e8e3da;
        transition: all 0.3s ease;
        height: 100%;
    }
    .package-card:hover {
        border-color: #7c6a43;
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(124, 106, 67, 0.15);
    }
    .package-card.selected {
        border-color: #7c6a43;
        background: #f8f6f3;
    }
    .package-name {
        font-size: 18px;
        font-weight: bold;
        color: #3b2a18;
        margin-bottom: 10px;
    }
    .package-price {
        font-size: 24px;
        font-weight: bold;
        color: #7c6a43;
        margin-bottom: 10px;
    }
    .package-details {
        font-size: 14px;
        color: #666;
        margin-bottom: 15px;
    }
    .package-details li {
        margin-bottom: 5px;
    }
    .package-venues {
        margin-top: 15px;
        padding-top: 15px;
        border-top: 1px solid #e8e3da;
    }
    .venue-tag {
        display: inline-block;
        background: #7c6a43;
        color: white;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 12px;
        margin: 2px;
    }
    .carousel-controls {
        display: flex;
        justify-content: center;
        gap: 10px;
        margin-top: 20px;
    }
    .carousel-btn {
        background: #7c6a43;
        color: white;
        border: none;
        padding: 10px 15px;
        border-radius: 5px;
        cursor: pointer;
        transition: all 0.3s;
    }
    .carousel-btn:hover {
        background: #3b2a18;
    }
    .carousel-btn:disabled {
        background: #ccc;
        cursor: not-allowed;
    }
    .time-slots-container {
    max-height: 200px;
    overflow-y: auto;
    border: 1px solid #e8e3da;
    border-radius: 8px;
    padding: 10px;
    background: white;
}
.time-slot {
    padding: 8px 12px;
    margin: 5px 0;
    border: 2px solid #e8e3da;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s;
    background: white;
    display: block;
    width: 100%;
    text-align: center;
}
.time-slot:hover {
    border-color: #7c6a43;
    background: #f8f6f3;
}
.time-slot.selected {
    background: #7c6a43;
    color: white;
    border-color: #3b2a18;
}
.time-slot.unavailable {
    background: #f8d7da;
    color: #721c24;
    border-color: #f5c6cb;
    cursor: not-allowed;
}
.duration-info {
    font-size: 12px;
    color: #666;
    margin-top: 2px;
}
.addon-card {
    transition: all 0.3s ease;
    background: white;
}

.addon-card:hover {
    border-color: #7c6a43 !important;
    box-shadow: 0 2px 8px rgba(124, 106, 67, 0.1);
}

.addon-checkbox:checked ~ label {
    color: #7c6a43;
    font-weight: bold;
}

.addon-card .form-check-input:checked {
    background-color: #7c6a43;
    border-color: #7c6a43;
}

.addon-quantity .input-group {
    width: 120px;
}
  </style>


<!-- Google Maps Embed -->
<div class="map-container mt-4">
    <iframe 
        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3861.737084666372!2d120.73654697499172!3d14.019296486657689!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33bd808d6f540739%3A0x6578b16532152cb5!2sSan%20Isidro%20Labrador%20Resort%20and%20Leisure%20Farm!5e0!3m2!1sen!2sph!4v1729323644107!5m2!1sen!2sph"
        allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
    </iframe>
</div>

<!-- Contact & Inquiry Section -->
<div class="container contact-section">
    <h2 class="section-title">Get in Touch With Us</h2>
    <img src="/images/divider.png" alt="Divider" class="d-block mx-auto" style="width:100px;"> 
    <br>

    <div class="row align-items-start">
        <!-- LEFT COLUMN -->
        <div class="col-md-6 contact-info">
            <h2><strong>San Isidro Labrador Resort and Leisure Farm</strong></h2>
            <p>
                Please don't hesitate to contact us using the provided contact information.
                Our team of dedicated professionals is readily available to offer personalized
                assistance and address any inquiries you may have.
            </p>
            <p>
                To expedite the process, you can also use the inquiry form on our website
                to provide us with specific details about your event. We will get back to you
                as soon as possible to discuss your requirements and assist you in planning
                the perfect celebration.
            </p>

            <h3>Contact Us</h3>
            <p><strong>San Isidro Labrador Resort and Leisure Farm</strong><br>
            <i class="fa-solid fa-location-dot"></i> Address: Bolboc, Tuy, Batangas, Philippines 4214</p>

            <h3>Contact Details</h3>
            <p>
                <i class="fa-solid fa-phone"></i> Phone: +63 967 007 1971<br>
                <i class="fa-solid fa-envelope"></i> Email: sanisidroresort@gmail.com
            </p>

            <img src="/images/san isidroweas.jpg" alt="Resort Image">
        </div>

        <!-- RIGHT COLUMN: INQUIRY FORM & CALENDAR -->
        <div class="col-md-6">
            <h2><strong>Book Now!</strong></h2>
            
            <!-- Success/Error Messages -->
            <?php if (session()->has('message')): ?>
                <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: "<?= esc(session('message')) ?>",
                    timer: 2500,
                    showConfirmButton: false,
                });
                </script>
            <?php endif; ?>
                

            <!-- BOOKING CALENDAR -->
            <div class="calendar-container">
                <div class="mb-3">
                    <h3 style="font-size: 18px; font-weight: bold; margin-bottom: 5px;">Availability Calendar</h3>
                    <p style="font-size: 13px; color: #666; margin: 0;">Dark brown dates are already booked</p>
                </div>
                
                <div class="calendar-header">
                    <h3 id="calendar-month-year">November 2025</h3>
                    <div class="calendar-nav">
                        <button type="button" id="prev-month">◄</button>
                        <button type="button" id="next-month">►</button>
                    </div>
                </div>

                <div class="calendar-grid">
                    <div class="calendar-day-header">Sun</div>
                    <div class="calendar-day-header">Mon</div>
                    <div class="calendar-day-header">Tue</div>
                    <div class="calendar-day-header">Wed</div>
                    <div class="calendar-day-header">Thu</div>
                    <div class="calendar-day-header">Fri</div>
                    <div class="calendar-day-header">Sat</div>
                </div>
                <div class="calendar-days-grid" id="calendar-days"></div>

                <div class="calendar-legend">
                    <div class="legend-item">
                        <div class="legend-box booked"></div>
                        <span>Booked</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-box available"></div>
                        <span>Available</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-box today"></div>
                        <span>Today</span>
                    </div>
                </div>
            </div>

            <!-- PACKAGE CAROUSEL -->
            <div class="package-carousel">
                <h3 class="text-center mb-4" style="color: #3b2a18; font-weight: bold;">Available Packages</h3>
                <div class="row" id="package-carousel">
                    <?php foreach ($packages as $index => $package): ?>
                        <div class="col-md-4">
                            <div class="package-card <?= $index === 0 ? 'selected' : '' ?>" 
                                data-package-id="<?= $package['id'] ?>"
                                onclick="selectPackage(this, <?= $package['id'] ?>)">
                                <div class="package-name"><?= esc($package['name']) ?></div>
                                <div class="package-price">₱<?= number_format($package['base_price'], 2) ?></div>
                                <div class="package-details">
                                    <ul style="padding-left: 15px; margin-bottom: 0;">
                                        <li><strong><?= $package['base_hours'] ?> hours</strong> base duration</li>
                                        <li><strong>₱<?= number_format($package['overtime_rate'], 2) ?>/hour</strong> overtime rate</li>
                                        <li>Up to <strong><?= $package['max_capacity'] ?> guests</strong> capacity</li>
                                    </ul>
                                </div>
                                <?php if (!empty($package['venue_names'])): ?>
                                    <div class="package-venues">
                                        <strong>Includes:</strong><br>
                                        <?php 
                                        $venueNames = explode(',', $package['venue_names']);
                                        foreach ($venueNames as $venueName): 
                                        ?>
                                            <span class="venue-tag"><?= esc(trim($venueName)) ?></span>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                                <div class="text-center mt-3">
                                    <small class="text-muted">Click to select this package</small>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <?php if (count($packages) > 3): ?>
                <div class="carousel-controls">
                    <button class="carousel-btn" onclick="scrollCarousel(-1)">◄ Previous</button>
                    <button class="carousel-btn" onclick="scrollCarousel(1)">Next ►</button>
                </div>
                <?php endif; ?>
            </div>

            <!-- BOOKING FORM -->
            <div class="inquiry-form mt-4">
                <form method="POST" action="<?= site_url('booking/submit') ?>" id="booking-form">
                    <?= csrf_field() ?>
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Type of Event *</label>
                            <input type="text" class="form-control" name="event_type" 
                                value="<?= old('event_type') ?>"
                                placeholder="e.g., Birthday, Wedding, Corporate Event" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Event Date *</label>
                            <input type="date" class="form-control" name="event_date" 
                                id="event_date" value="<?= old('event_date') ?>" 
                                min="<?= date('Y-m-d') ?>" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Duration (hours) *</label>
                            <select class="form-control" name="duration_hours" id="duration_hours" required>
                                <option value="">Select Duration</option>
                                <option value="4" <?= old('duration_hours') == '4' ? 'selected' : '' ?>>4 hours (ends by 11 PM)</option>
                                <option value="6" <?= old('duration_hours') == '6' ? 'selected' : '' ?>>6 hours (must start by 5 PM)</option>
                                <option value="8" <?= old('duration_hours') == '8' ? 'selected' : '' ?>>8 hours (must start by 3 PM)</option>
                                <option value="10" <?= old('duration_hours') == '10' ? 'selected' : '' ?>>10 hours (must start by 1 PM)</option>
                                <option value="12" <?= old('duration_hours') == '12' ? 'selected' : '' ?>>12 hours (must start by 11 AM)</option>
                            </select>
                            <small class="form-text text-muted">All events must conclude by 11 PM</small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Start Time *</label>
                            <div id="time-slots-container">
                                <div class="loading-spinner" id="time-loading">
                                    <small class="text-muted">Loading available time slots...</small>
                                </div>
                                <div class="time-slots-container" id="time-slots">
                                    <!-- Time slots will be loaded here -->
                                </div>
                                <input type="hidden" name="start_time" id="start_time" value="<?= old('start_time') ?>" required>
                            </div>
                            <div id="time-slots-info"></div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Number of Guests *</label>
                            <input type="number" class="form-control" name="total_guests" 
                                id="total_guests" value="<?= old('total_guests') ?>"
                                placeholder="e.g., 50" min="1" required>
                            <div id="capacity-info" class="form-text">
                                <small>Maximum capacity: <span id="max-capacity">--</span> guests</small>
                            </div>
                            <div id="capacity-warning" class="text-danger small mt-1" style="display: none;">
                                <i class="fas fa-exclamation-triangle"></i> 
                                <span id="warning-message"></span>
                            </div>
                        </div>
                        <!-- Addons Section -->
                        <div class="col-12 mt-4">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h5 class="card-title mb-0">Additional Services (Optional)</h5>
                                    <small class="text-muted">Enhance your event with these additional services</small>
                                </div>
                                <div class="card-body">
                                    <div id="addons-container">
                                        <div class="row" id="addons-list">
                                            <div class="col-12 text-center">
                                                <div class="spinner-border text-primary" role="status">
                                                    <span class="visually-hidden">Loading addons...</span>
                                                </div>
                                                <p class="mt-2">Loading additional services...</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="addons-summary" class="mt-3 p-3 bg-light rounded" style="display: none;">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <strong>Selected Addons:</strong>
                                                <span id="selected-addons-count">0</span> items
                                            </div>
                                            <div>
                                                <strong>Addons Total: ₱<span id="addons-total">0.00</span></strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Hidden package ID field -->
                        <input type="hidden" name="package_id" id="package_id" value="<?= old('package_id', $packages[0]['id'] ?? '') ?>" required>

                        <div class="col-12">
                            <label class="form-label">Additional Notes (Optional)</label>
                            <textarea class="form-control" name="special_requests" rows="3" 
                                placeholder="Any special requirements or additional information..."><?= old('special_requests') ?></textarea>
                        </div>
                    </div>
                    
                    <div class="alert alert-info mt-3">
                        <strong>Note:</strong> When you book a package, <strong>all venues included in that package</strong> will be reserved for your event date and time.
                    </div>
                    
                    <button type="submit" class="btn btn-submit" id="submit-btn">Submit Booking Request</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    console.log('DOM loaded, initializing booking system...');

    // Initialize all DOM elements with null checks
    const initializeElement = (id) => document.getElementById(id);
    
    const elements = {
        eventDate: initializeElement('event_date'),
        durationHours: initializeElement('duration_hours'),
        packageIdInput: initializeElement('package_id'),
        timeSlotsContainer: initializeElement('time-slots'),
        startTimeInput: initializeElement('start_time'),
        timeLoading: initializeElement('time-loading'),
        guestInput: initializeElement('total_guests'),
        bookingForm: initializeElement('booking-form'),
        categorySelect: initializeElement('category'),
        calendarMonthYear: initializeElement('calendar-month-year'),
        calendarDays: initializeElement('calendar-days'),
        addonsList: initializeElement('addons-list'),
        addonsSummary: initializeElement('addons-summary'),
        selectedAddonsCount: initializeElement('selected-addons-count'),
        addonsTotal: initializeElement('addons-total'),
        capacityWarning: initializeElement('capacity-warning'),
        warningMessage: initializeElement('warning-message'),
        maxCapacity: initializeElement('max-capacity'),
        timeSlotsInfo: initializeElement('time-slots-info')
    };

    // Only proceed if essential elements exist
    if (!elements.eventDate || !elements.durationHours || !elements.packageIdInput) {
        console.error('Essential booking elements not found');
        return;
    }

    // State variables
    let selectedTimeSlot = null;
    let selectedPackageId = elements.packageIdInput.value;
    let currentPackageCapacity = 0;
    let bookedDates = [];
    let currentDate = new Date();
    let selectedAddons = {};
    let addonsTotalAmount = 0;

    // Calendar functions
    const monthNames = [
        'January', 'February', 'March', 'April', 'May', 'June',
        'July', 'August', 'September', 'October', 'November', 'December'
    ];

    function renderCalendar() {
        if (!elements.calendarMonthYear || !elements.calendarDays) return;

        const year = currentDate.getFullYear();
        const month = currentDate.getMonth();
        
        // Update month/year display
        elements.calendarMonthYear.textContent = `${monthNames[month]} ${year}`;

        // Get first day of month and number of days
        const firstDay = new Date(year, month, 1).getDay();
        const daysInMonth = new Date(year, month + 1, 0).getDate();
        
        // Get today's date for comparison
        const today = new Date();
        const isCurrentMonth = today.getMonth() === month && today.getFullYear() === year;
        const todayDate = today.getDate();

        // Clear existing calendar days
        elements.calendarDays.innerHTML = '';

        // Add empty cells for days before the first day of month
        for (let i = 0; i < firstDay; i++) {
            const emptyDiv = document.createElement('div');
            emptyDiv.className = 'calendar-day empty';
            elements.calendarDays.appendChild(emptyDiv);
        }

        // Add days of the month
        for (let day = 1; day <= daysInMonth; day++) {
            const dayDiv = document.createElement('div');
            dayDiv.className = 'calendar-day';
            dayDiv.textContent = day;

            // Format date as YYYY-MM-DD
            const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
            
            // Check if date is booked
            if (bookedDates.includes(dateStr)) {
                dayDiv.classList.add('booked');
            } else {
                dayDiv.classList.add('available');
                // Make available dates clickable
                dayDiv.style.cursor = 'pointer';
                dayDiv.addEventListener('click', function() {
                    if (elements.eventDate) {
                        elements.eventDate.value = dateStr;
                    }
                    // Remove highlight from previously selected date
                    document.querySelectorAll('.calendar-day.selected').forEach(el => {
                        el.classList.remove('selected');
                    });
                    // Add highlight to selected date
                    dayDiv.classList.add('selected');
                    updateTimeSlots();
                });
            }

            // Check if it's today
            if (isCurrentMonth && day === todayDate) {
                dayDiv.classList.add('today');
            }

            elements.calendarDays.appendChild(dayDiv);
        }
    }

    // Fetch booked dates from your backend
    function fetchBookedDates() {
        fetch("<?= site_url('booking/booked-dates') ?>")
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(dates => {
                bookedDates = Array.isArray(dates) ? dates : [];
                renderCalendar();
                
                // Add event listener for date input validation
                if (elements.eventDate) {
                    elements.eventDate.addEventListener("change", function() {
                        const selected = this.value;
                        if (bookedDates.includes(selected)) {
                            this.value = "";
                            alert("Sorry, this date is already booked. Please choose another date.");
                        }
                    });
                }
            })
            .catch(error => {
                console.error('Error fetching booked dates:', error);
                bookedDates = [];
                renderCalendar();
            });
    }

    // Calendar navigation
    const prevMonthBtn = document.getElementById('prev-month');
    const nextMonthBtn = document.getElementById('next-month');

    if (prevMonthBtn) {
        prevMonthBtn.addEventListener('click', function() {
            currentDate.setMonth(currentDate.getMonth() - 1);
            renderCalendar();
        });
    }

    if (nextMonthBtn) {
        nextMonthBtn.addEventListener('click', function() {
            currentDate.setMonth(currentDate.getMonth() + 1);
            renderCalendar();
        });
    }

    // Package selection function
    function selectPackage(element, packageId) {
        // Remove selected class from all packages
        document.querySelectorAll('.package-card').forEach(card => {
            card.classList.remove('selected');
        });
        
        // Add selected class to clicked package
        element.classList.add('selected');
        
        // Update hidden input
        selectedPackageId = packageId;
        if (elements.packageIdInput) {
            elements.packageIdInput.value = packageId;
        }
        
        // Update capacity information
        updateCapacityInfo(packageId);
        
        // Update time slots if date and duration are already selected
        updateTimeSlots();
    }

    // Update capacity info when package is selected
    function updateCapacityInfo(packageId) {
        if (!packageId) return;
        
        // Find the selected package from our data
        const packages = <?= json_encode($packages) ?>;
        const package = packages.find(p => p.id == packageId);
        
        if (package) {
            currentPackageCapacity = package.max_capacity;
            if (elements.maxCapacity) {
                elements.maxCapacity.textContent = package.max_capacity;
            }
            
            // Validate current guest count if any
            if (elements.guestInput && elements.guestInput.value) {
                validateGuestCount(elements.guestInput.value);
            }
        }
    }

    // Validate guest count in real-time
    function validateGuestCount(guestCount) {
        if (!elements.guestInput || !elements.capacityWarning || !elements.warningMessage) return;
        
        if (guestCount > currentPackageCapacity) {
            elements.capacityWarning.style.display = 'block';
            elements.warningMessage.textContent = `Exceeds maximum capacity of ${currentPackageCapacity} guests`;
            elements.guestInput.classList.add('is-invalid');
            elements.guestInput.classList.remove('is-valid');
        } else if (guestCount > 0) {
            elements.capacityWarning.style.display = 'none';
            elements.guestInput.classList.add('is-valid');
            elements.guestInput.classList.remove('is-invalid');
        } else {
            elements.capacityWarning.style.display = 'none';
            elements.guestInput.classList.remove('is-valid', 'is-invalid');
        }
    }

    // Time slots management
    function getLatestStartTime(duration) {
        const hours = parseInt(duration);
        let latestHour = 23 - hours; // 11 PM minus duration
        
        // Convert to 12-hour format
        let displayHour = latestHour > 12 ? latestHour - 12 : latestHour;
        let period = latestHour >= 12 ? 'PM' : 'AM';
        
        // Handle special cases
        if (latestHour === 0) {
            displayHour = 12;
            period = 'AM';
        } else if (latestHour === 12) {
            displayHour = 12;
            period = 'PM';
        }
        
        return displayHour + ':00 ' + period;
    }

    function updateTimeSlots() {
        const date = elements.eventDate ? elements.eventDate.value : '';
        const duration = elements.durationHours ? elements.durationHours.value : '';
        const packageId = selectedPackageId;

        if (!date || !duration || !packageId) {
            if (elements.timeSlotsContainer) {
                elements.timeSlotsContainer.innerHTML = '<small class="text-muted">Please select date, duration, and package first.</small>';
            }
            return;
        }

        if (elements.timeLoading) elements.timeLoading.style.display = 'block';
        if (elements.timeSlotsContainer) elements.timeSlotsContainer.innerHTML = '';

        fetch(`<?= site_url('booking/available-time-slots') ?>?date=${date}&package_id=${packageId}&duration=${duration}`)
            .then(response => response.json())
            .then(slots => {
                if (elements.timeLoading) elements.timeLoading.style.display = 'none';
                
                if (!elements.timeSlotsContainer) return;
                
                if (!slots || slots.length === 0) {
                    let message = 'No available time slots for the selected date and duration.';
                    if (parseInt(duration) >= 6) {
                        message += ' For longer durations, try selecting an earlier start time.';
                    }
                    elements.timeSlotsContainer.innerHTML = `<div class="alert alert-warning">${message}</div>`;
                    return;
                }

                slots.forEach(slot => {
                    const timeSlot = document.createElement('div');
                    timeSlot.className = 'time-slot';
                    timeSlot.textContent = slot.display;
                    timeSlot.dataset.start = slot.start;
                    
                    timeSlot.addEventListener('click', function() {
                        // Remove selected class from all slots
                        document.querySelectorAll('.time-slot').forEach(s => {
                            s.classList.remove('selected');
                        });
                        
                        // Add selected class to current slot
                        this.classList.add('selected');
                        selectedTimeSlot = slot.start;
                        if (elements.startTimeInput) {
                            elements.startTimeInput.value = slot.start;
                        }
                    });

                    elements.timeSlotsContainer.appendChild(timeSlot);
                });
            })
            .catch(error => {
                console.error('Error loading time slots:', error);
                if (elements.timeLoading) elements.timeLoading.style.display = 'none';
                if (elements.timeSlotsContainer) {
                    elements.timeSlotsContainer.innerHTML = '<div class="alert alert-danger">Error loading time slots. Please try again.</div>';
                }
            });
    }

    // Addons management
    function loadAddons() {
        console.log('Loading addons...');
        
        fetch('<?= site_url('booking/get-addons') ?>')
            .then(response => {
                console.log('Response status:', response.status);
                if (!response.ok) {
                    throw new Error('Failed to load addons: ' + response.status);
                }
                return response.json();
            })
            .then(addons => {
                console.log('Addons loaded:', addons);
                displayAddons(addons);
            })
            .catch(error => {
                console.error('Error loading addons:', error);
                displayAddonsError();
            });
    }

    function displayAddons(addons) {
        if (!elements.addonsList) return;
        
        if (!addons || addons.length === 0) {
            elements.addonsList.innerHTML = `
                <div class="col-12 text-center text-muted">
                    <i class="fas fa-box-open fa-2x mb-2"></i>
                    <p>No additional services available at the moment.</p>
                </div>
            `;
            return;
        }

        elements.addonsList.innerHTML = addons.map(addon => {
            const addonId = addon.id;
            const addonName = addon.name || 'Unnamed Service';
            const addonPrice = parseFloat(addon.price) || 0;
            const addonDescription = addon.description || 'No description available';
            const addonType = addon.type || 'general';
            
            return `
                <div class="col-md-6 mb-3">
                    <div class="addon-card border rounded p-3 h-100">
                        <div class="form-check mb-2">
                            <input class="form-check-input addon-checkbox" type="checkbox" 
                                id="addon_${addonId}" value="${addonId}">
                            <label class="form-check-label w-100" for="addon_${addonId}">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1 me-3">
                                        <strong class="d-block mb-1">${addonName}</strong>
                                        <small class="text-muted d-block">${addonDescription}</small>
                                        <small class="text-capitalize text-info">${addonType.replace('_', ' ')}</small>
                                    </div>
                                    <div class="text-end flex-shrink-0">
                                        <div class="fw-bold text-success">₱${addonPrice.toFixed(2)}</div>
                                    </div>
                                </div>
                            </label>
                        </div>
                        <div class="addon-quantity" id="quantity_${addonId}" style="display: none;">
                            <label class="form-label small mb-1">Quantity:</label>
                            <div class="input-group input-group-sm" style="width: 140px;">
                                <button class="btn btn-outline-secondary" type="button" onclick="updateAddonQuantity(${addonId}, -1)">-</button>
                                <input type="number" class="form-control text-center" 
                                    id="qty_${addonId}" name="addons[${addonId}]" 
                                    value="0" min="0" max="100">
                                <button class="btn btn-outline-secondary" type="button" onclick="updateAddonQuantity(${addonId}, 1)">+</button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }).join('');

        // Add event listeners to checkboxes
        addons.forEach(addon => {
            const checkbox = document.getElementById(`addon_${addon.id}`);
            if (checkbox) {
                checkbox.addEventListener('change', function() {
                    toggleAddon(addon.id, addon.name, parseFloat(addon.price));
                });
            }
            
            // Add event listener to quantity input
            const quantityInput = document.getElementById(`qty_${addon.id}`);
            if (quantityInput) {
                quantityInput.addEventListener('change', function() {
                    updateAddonTotal(addon.id);
                });
            }
        });
    }

    function displayAddonsError() {
        if (!elements.addonsList) return;
        
        elements.addonsList.innerHTML = `
            <div class="col-12 text-center text-danger">
                <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                <p>Failed to load additional services.</p>
                <button class="btn btn-sm btn-outline-primary" onclick="loadAddons()">
                    <i class="fas fa-redo"></i> Try Again
                </button>
            </div>
        `;
    }

    // Addon functions
    function toggleAddon(addonId, addonName, addonPrice) {
        const checkbox = document.getElementById(`addon_${addonId}`);
        const quantityDiv = document.getElementById(`quantity_${addonId}`);
        const quantityInput = document.getElementById(`qty_${addonId}`);
        
        if (checkbox.checked) {
            quantityDiv.style.display = 'block';
            selectedAddons[addonId] = {
                name: addonName,
                price: addonPrice,
                quantity: 1
            };
            quantityInput.value = 1;
            console.log('Addon selected:', addonId, addonName);
        } else {
            quantityDiv.style.display = 'none';
            delete selectedAddons[addonId];
            quantityInput.value = 0;
            console.log('Addon removed:', addonId);
        }
        
        updateAddonsSummary();
    }

    function updateAddonQuantity(addonId, change) {
        const input = document.getElementById(`qty_${addonId}`);
        let newQuantity = parseInt(input.value) + change;
        
        if (newQuantity < 0) newQuantity = 0;
        if (newQuantity > 100) newQuantity = 100;
        
        input.value = newQuantity;
        
        if (newQuantity === 0) {
            // If quantity becomes 0, uncheck the checkbox
            const checkbox = document.getElementById(`addon_${addonId}`);
            if (checkbox) {
                checkbox.checked = false;
                const quantityDiv = document.getElementById(`quantity_${addonId}`);
                if (quantityDiv) {
                    quantityDiv.style.display = 'none';
                }
                delete selectedAddons[addonId];
            }
        } else if (selectedAddons[addonId]) {
            selectedAddons[addonId].quantity = newQuantity;
        } else {
            // If adding quantity but addon not selected yet, select it
            const checkbox = document.getElementById(`addon_${addonId}`);
            if (checkbox && !checkbox.checked) {
                checkbox.checked = true;
                const quantityDiv = document.getElementById(`quantity_${addonId}`);
                if (quantityDiv) {
                    quantityDiv.style.display = 'block';
                }
                // We need the addon name and price - we'll get it from the displayed data
                const addonName = checkbox.parentElement.querySelector('strong').textContent;
                const addonPriceText = checkbox.parentElement.querySelector('.text-success').textContent;
                const addonPrice = parseFloat(addonPriceText.replace('₱', ''));
                
                selectedAddons[addonId] = {
                    name: addonName,
                    price: addonPrice,
                    quantity: newQuantity
                };
            }
        }
        
        updateAddonsSummary();
    }

    function updateAddonTotal(addonId) {
        const input = document.getElementById(`qty_${addonId}`);
        if (!input) return;
        
        const quantity = parseInt(input.value) || 0;
        
        if (quantity === 0) {
            // If quantity is 0, uncheck and remove
            const checkbox = document.getElementById(`addon_${addonId}`);
            if (checkbox) {
                checkbox.checked = false;
                const quantityDiv = document.getElementById(`quantity_${addonId}`);
                if (quantityDiv) {
                    quantityDiv.style.display = 'none';
                }
            }
            delete selectedAddons[addonId];
        } else if (selectedAddons[addonId]) {
            selectedAddons[addonId].quantity = quantity;
        } else {
            // If setting quantity but addon not selected, select it
            const checkbox = document.getElementById(`addon_${addonId}`);
            if (checkbox && !checkbox.checked) {
                checkbox.checked = true;
                const quantityDiv = document.getElementById(`quantity_${addonId}`);
                if (quantityDiv) {
                    quantityDiv.style.display = 'block';
                }
                const addonName = checkbox.parentElement.querySelector('strong').textContent;
                const addonPriceText = checkbox.parentElement.querySelector('.text-success').textContent;
                const addonPrice = parseFloat(addonPriceText.replace('₱', ''));
                
                selectedAddons[addonId] = {
                    name: addonName,
                    price: addonPrice,
                    quantity: quantity
                };
            }
        }
        
        updateAddonsSummary();
    }

    function updateAddonsSummary() {
        if (!elements.addonsSummary || !elements.selectedAddonsCount || !elements.addonsTotal) return;
        
        const selectedCount = Object.keys(selectedAddons).length;
        addonsTotalAmount = Object.values(selectedAddons).reduce((total, addon) => {
            return total + (addon.price * addon.quantity);
        }, 0);
        
        if (selectedCount > 0 && addonsTotalAmount > 0) {
            elements.addonsSummary.style.display = 'block';
            elements.selectedAddonsCount.textContent = selectedCount;
            elements.addonsTotal.textContent = addonsTotalAmount.toFixed(2);
        } else {
            elements.addonsSummary.style.display = 'none';
        }
    }

    // Clean up addons before form submission
    function cleanupAddonsBeforeSubmit() {
        // Remove all existing addon inputs first
        const existingAddonInputs = document.querySelectorAll('input[name^="addons["]');
        existingAddonInputs.forEach(input => {
            input.remove();
        });
        
        // Only add inputs for selected addons with quantity > 0
        Object.keys(selectedAddons).forEach(addonId => {
            const addon = selectedAddons[addonId];
            if (addon.quantity > 0) {
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = `addons[${addonId}]`;
                hiddenInput.value = addon.quantity;
                if (elements.bookingForm) {
                    elements.bookingForm.appendChild(hiddenInput);
                }
            }
        });
        
        console.log('Addons being submitted:', selectedAddons);
    }

    // Carousel scrolling function
    function scrollCarousel(direction) {
        const carousel = document.getElementById('package-carousel');
        if (carousel) {
            const scrollAmount = 300;
            carousel.scrollLeft += direction * scrollAmount;
        }
    }

    // Event listeners
    if (elements.durationHours) {
        elements.durationHours.addEventListener('change', function() {
            const duration = this.value;
            if (elements.timeSlotsInfo) {
                if (duration) {
                    const latestStart = getLatestStartTime(duration);
                    elements.timeSlotsInfo.innerHTML = `<small class="text-info"><i class="fas fa-clock"></i> For ${duration}-hour events, latest start time is ${latestStart}</small>`;
                } else {
                    elements.timeSlotsInfo.innerHTML = '';
                }
            }
            updateTimeSlots();
        });
    }

    if (elements.eventDate) {
        elements.eventDate.addEventListener('change', updateTimeSlots);
    }

    if (elements.guestInput) {
        elements.guestInput.addEventListener('input', function() {
            validateGuestCount(this.value);
        });
    }

    if (elements.bookingForm) {
        elements.bookingForm.addEventListener('submit', function(e) {
            const guestCount = elements.guestInput ? parseInt(elements.guestInput.value) : 0;
            
            if (!selectedTimeSlot) {
                e.preventDefault();
                alert('Please select a time slot before submitting.');
                return;
            }
            
            if (selectedPackageId && guestCount > currentPackageCapacity) {
                e.preventDefault();
                alert(`Number of guests (${guestCount}) exceeds the maximum capacity of ${currentPackageCapacity} for the selected package. Please reduce the number of guests or select a different package.`);
                return;
            }
            
            // Clean up addons before submission
            cleanupAddonsBeforeSubmit();
        });
    }

    // Initialize everything
    function initializeBookingSystem() {
        // Initialize calendar
        renderCalendar();
        fetchBookedDates();
        
        // Load addons
        loadAddons();
        
        // Set initial capacity info
        if (selectedPackageId) {
            updateCapacityInfo(selectedPackageId);
        }
        
        // Validate initial guest count if any
        if (elements.guestInput && elements.guestInput.value) {
            validateGuestCount(elements.guestInput.value);
        }

        // Initialize with old values if any
        <?php if (old('package_id')): ?>
            const oldPackageCard = document.querySelector(`.package-card[data-package-id="<?= old('package_id') ?>"]`);
            if (oldPackageCard) {
                selectPackage(oldPackageCard, <?= old('package_id') ?>);
            }
        <?php endif; ?>
    }

    // Expose functions to global scope
    window.selectPackage = selectPackage;
    window.scrollCarousel = scrollCarousel;
    window.loadAddons = loadAddons;
    window.toggleAddon = toggleAddon;
    window.updateAddonQuantity = updateAddonQuantity;
    window.updateAddonTotal = updateAddonTotal;

    // Start the system
    initializeBookingSystem();
});
</script>

<?php include ('footer.php'); ?>

