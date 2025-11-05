<?php
 $title = "Booking | San Isidro Labrador Resort and Leisure Farm";
 include ('header.php');
?>

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
          Please don’t hesitate to contact us using the provided contact information.
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

      <!-- RIGHT COLUMN: INQUIRY FORM -->
      <div class="col-md-6 inquiry-form">
        <h2><strong>Inquire Now!</strong></h2>
        <form method="POST" action="submit_inquiry.php">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">First name</label>
              <input type="text" class="form-control" name="fname" placeholder="Enter first name" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Last name</label>
              <input type="text" class="form-control" name="lname" placeholder="Enter last name" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Email address</label>
              <input type="email" class="form-control" name="email" placeholder="Enter email" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Contact Number</label>
              <input type="tel" class="form-control" name="contact" placeholder="09XX-XXX-XXXX" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Type of Event</label>
              <input type="text" class="form-control" name="event_type" placeholder="e.g., Birthday, Wedding">
            </div>
            <div class="col-md-6">
              <label class="form-label">Event Date</label>
              <input type="date" class="form-control" name="event_date">
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
                <option>Playground</option>
                <option>Day Tour</option>
                <option>Overnight Stay</option>
                <option>Exclusive Private Event</option>
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

 <?php
    include ('footer.php');
  ?>

  <!-- Bootstrap JS -->
  <script src="bootstrap5/js/bootstrap.bundle.min.js"></script>


