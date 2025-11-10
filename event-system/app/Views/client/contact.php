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
    <img src="images/divider.png" alt="Divider" class="d-block mx-auto" style="width:100px;"> 
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

        <img src="images/san isidroweas.jpg" alt="Resort Image">
      </div>

      <!-- RIGHT COLUMN: INQUIRY FORM & CALENDAR -->
      <div class="col-md-6">
        <h2><strong>Book Now!</strong></h2>
        
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

        <!-- INQUIRY FORM -->
        <div class="inquiry-form">
          <form method="POST" action="submit_inquiry.php">
            <div class="row g-3">
              
              
              <div class="col-md-6 mt-4">
                <label class="form-label">Type of Event</label>
                <input type="text" class="form-control" name="event_type" placeholder="e.g., Birthday, Wedding">
              </div>
              <div class="col-md-6 mt-4">
                <label class="form-label">Event Date</label>
                <input type="date" class="form-control" name="event_date" id="event_date" required>
              </div>

              <div class="col-md-6">
                <label class="form-label">Number of Expected Guests</label>
                <input type="number" class="form-control" name="guests" placeholder="e.g., 50">
              </div>
              <div class="col-md-6">
                <label class="form-label">Your Preferred Time</label>
                <input type="text" class="form-control" name="time" placeholder="e.g., 7:00 AM - 12:00 NN">
              </div>
              <div class="col-12">
                <label class="form-label">Select Package</label>
                <select class="form-select" name="package">
                  <option selected disabled>Select Package</option>
                  <option>Cafe 2nd Floor Venue</option>
                  <option>Playground</option>
                  <option>Enclosed Venue</option>
                  <option>Prep & Photoshoot</option>
                  <option>Cafe Meetings</option>
                </select>
              </div>
              <div class="col-12">
                <label class="form-label">Category</label>
                <select class="form-select" name="category" id="category" disabled>
                  <option selected disabled>Select a package first</option>
                </select>
              </div>

              <div class="col-12">
                <label class="form-label">Your message</label>
                <textarea class="form-control" name="message" rows="3" placeholder="Enter your question or message"></textarea>
              </div>
            </div>
            <button type="submit" class="btn btn-submit mt-4">Submit</button>
          </form>
        </div>

      </div>
    </div>
  </div>

  <script>
document.addEventListener("DOMContentLoaded", function() {

  const packageSelect = document.querySelector("select[name='package']");
  const categorySelect = document.getElementById("category");

  packageSelect.addEventListener("change", function() {
    const selected = this.value;
    
    // Reset category dropdown
    categorySelect.innerHTML = "";
    categorySelect.disabled = false;

    let options = [];

    if (selected === "Prep & Photoshoot" || selected === "Cafe Meeting (2nd Floor)") {
      options = ["Basic", "Premium"];
    }

    else if (selected === "Playground") {
      options = ["Exclusive", "Non-Exclusive"];
    }

    else if (selected === "Cafe Meetings") {
      options = ["Exclusive"];
    }

    else {
      // Disable if no category needed
      categorySelect.disabled = true;
      categorySelect.innerHTML = '<option disabled selected>No category required</option>';
      return;
    }

    // Populate category dropdown
    options.forEach(opt => {
      const optionElement = document.createElement("option");
      optionElement.textContent = opt;
      optionElement.value = opt;
      categorySelect.appendChild(optionElement);
    });
  });
});
</script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const dateInput = document.getElementById("event_date");
    let bookedDates = [];
    let currentDate = new Date();

    // Calendar functions
    const monthNames = [
        'January', 'February', 'March', 'April', 'May', 'June',
        'July', 'August', 'September', 'October', 'November', 'December'
    ];

    function renderCalendar() {
        const year = currentDate.getFullYear();
        const month = currentDate.getMonth();
        
        // Update month/year display
        document.getElementById('calendar-month-year').textContent = 
            `${monthNames[month]} ${year}`;

        // Get first day of month and number of days
        const firstDay = new Date(year, month, 1).getDay();
        const daysInMonth = new Date(year, month + 1, 0).getDate();
        
        // Get today's date for comparison
        const today = new Date();
        const isCurrentMonth = today.getMonth() === month && today.getFullYear() === year;
        const todayDate = today.getDate();

        // Clear existing calendar days
        const calendarDays = document.getElementById('calendar-days');
        if (!calendarDays) return;
        
        calendarDays.innerHTML = '';

        // Add empty cells for days before the first day of month
        for (let i = 0; i < firstDay; i++) {
            const emptyDiv = document.createElement('div');
            emptyDiv.className = 'calendar-day empty';
            calendarDays.appendChild(emptyDiv);
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
            }

            // Check if it's today
            if (isCurrentMonth && day === todayDate) {
                dayDiv.classList.add('today');
            }

            calendarDays.appendChild(dayDiv);
        }
    }

    // Initialize calendar first
    renderCalendar();

    // Fetch booked dates
    fetch("get_booked_dates.php")
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(dates => {
            // Ensure dates is an array
            bookedDates = Array.isArray(dates) ? dates : [];
            renderCalendar(); // Re-render with booked dates
            
            // Disable manually via input event
            dateInput.addEventListener("change", function() {
                const selected = this.value;
                if (bookedDates.includes(selected)) {
                    this.value = "";
                    alert("Sorry, this date is already booked. Please choose another date.");
                }
            });

            // Disable visually using min/max/step validation
            dateInput.addEventListener("input", () => {
                if (bookedDates.includes(dateInput.value)) {
                    dateInput.setCustomValidity("This date is already booked.");
                } else {
                    dateInput.setCustomValidity("");
                }
            });
        })
        .catch(error => {
            console.error('Error fetching booked dates:', error);
            // Continue with empty booked dates
            bookedDates = [];
            renderCalendar(); // Render anyway even if fetch fails
        });

    // Previous month button
    const prevBtn = document.getElementById('prev-month');
    if (prevBtn) {
        prevBtn.addEventListener('click', function() {
            currentDate.setMonth(currentDate.getMonth() - 1);
            renderCalendar();
        });
    }

    // Next month button
    const nextBtn = document.getElementById('next-month');
    if (nextBtn) {
        nextBtn.addEventListener('click', function() {
            currentDate.setMonth(currentDate.getMonth() + 1);
            renderCalendar();
        });
    }
});
</script>


 <?php
    include ('footer.php');
  ?>

  <!-- Bootstrap JS -->
  <script src="bootstrap5/js/bootstrap.bundle.min.js"></script>