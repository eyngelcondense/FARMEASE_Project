<?php
// You can include server-side logic here if needed later (e.g., form handling)
// Example:
// if ($_SERVER["REQUEST_METHOD"] == "POST") { ... }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Booking & Contact | San Isidro Labrador Resort</title>
  <!-- Bootstrap CSS Link -->
  <link rel="stylesheet" href="bootstrap5/css/bootstrap.min.css">
  <!-- Font Awesome 6 -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.6.0/css/all.min.css" crossorigin="anonymous">

  <style>
    .header-bar {
      background-color: #b2a187;
      padding: 10px 0;
      text-align: center;
      font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
    }
    .header-bar img { height: 80px; }
    .navbar {
      background-color: #ffffff;
      border-top: 1px solid #ddd;
      border-bottom: 1px solid #ddd;
    }
    .navbar-nav .nav-link {
      color: #3b2a18;
      font-weight: 500;
      margin: 0 12px;
      transition: color 0.3s;
    }
    .navbar-nav .nav-link:hover { color: #c19a6b; }
    body {
      font-family: "Times New Roman", serif;
      background-color: #f8f6f3;
      color: #3b2a18;
    }
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
    footer {
      background-color:#b2a187;
      color: #fff;
      text-align: center;
      padding: 20px;
      font-size: 14px;
    }
    footer a {
      color: #f5e3c6;
      text-decoration: none;
    }
    footer a:hover { text-decoration: underline; }
    .social-icons i {
      color: #fff;
      margin: 0 8px;
      font-size: 18px;
    }
    .social-icons i:hover { color: #e3cfa3; }
  </style>
</head>
<body>

  <!-- Header -->
  <div class="header-bar">
    <img src="LOGO NG SAN ISIDRO.png" alt="San Isidro Labrador Logo">
    <h5 class="mt-2 fw-semibold">San Isidro Labrador Resort and Leisure Farm</h5>
  </div>

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg">
    <div class="container">
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item"><a class="nav-link active" href="Home.php">HOME</a></li>
          <li class="nav-item"><a class="nav-link" href="packages.php">PACKAGES</a></li>
          <li class="nav-item"><a class="nav-link" href="gallery.php">VIDEOS/GALLERIES</a></li>
          <li class="nav-item"><a class="nav-link" href="testimonials.php">TESTIMONIALS</a></li>
          <li class="nav-item"><a class="nav-link" href="booking.php">CONTACT</a></li>
        </ul>
      </div>
    </div>
  </nav>

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
    <img src="divider.png" alt="Divider" class="d-block mx-auto" style="width:100px;"> 
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

        <img src="san isidroweas.jpg" alt="Resort Image">
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

  <!-- Footer -->
  <footer>
    <div class="social-icons mb-2">
      <a href="https://www.facebook.com/sanisidrofarmresort" target="_blank" title="Facebook"><i class="fa-brands fa-facebook"></i></a>
      <a href="https://www.tiktok.com/@sanisidrofarmph" target="_blank" title="TikTok"><i class="fa-brands fa-tiktok"></i></a>
      <a href="https://www.instagram.com/sanisidrofarmph" target="_blank" title="Instagram"><i class="fa-brands fa-instagram"></i></a>
    </div>
    © 2025 San Isidro Labrador Resort and Leisure Farm. All rights reserved.<br>
    Website developed by <strong>Farmease</strong>.
  </footer>

  <!-- Bootstrap JS -->
  <script src="bootstrap5/js/bootstrap.bundle.min.js"></script>

</body>
</html>
