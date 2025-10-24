<?php
// Start a session (useful for login, user data, etc.)
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Welcome | San Isidro Labrador Resort and Leisure Farm</title>
  <link rel="stylesheet" href="bootstrap5/css/bootstrap.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&family=Playfair+Display:wght@600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.6.0/css/all.min.css">

  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f8f6f3;
      color: #3b2a18;
      overflow-x: hidden;
    }

    /* Header */
    .header-bar {
      background-color: #b2a187;
      padding: 20px 0;
      text-align: center;
      position: relative;
    }
    .header-bar img {
      height: 80px;
    }
    .header-bar h5 {
      font-family: 'Playfair Display', serif;
      font-weight: 600;
      margin-top: 10px;
    }

    /* Profile Icon */
    .profile-btn {
      position: absolute;
      top: 25px;
      right: 25px;
      background-color: white;
      border-radius: 50%;
      border: 2px solid #c19a6b;
      padding: 6px;
      cursor: pointer;
      transition: transform 0.3s;
    }

    .profile-btn:hover {
      transform: scale(1.1);
    }

    .profile-btn img {
      width: 40px;
      height: 40px;
      border-radius: 50%;
    }

    /* Overlay for sidebar */
    .overlay {
      position: fixed;
      top: 0; left: 0;
      width: 100%; height: 100%;
      background-color: rgba(0, 0, 0, 0.4);
      opacity: 0;
      visibility: hidden;
      transition: 0.3s ease;
      z-index: 998;
    }
    .overlay.active {
      opacity: 1;
      visibility: visible;
    }

    /* Sidebar */
    .profile-sidebar {
      position: fixed;
      top: 0;
      right: 0;
      width: 320px;
      height: 100%;
      background: #fff;
      box-shadow: -2px 0 10px rgba(0,0,0,0.3);
      padding: 30px 20px;
      transform: translateX(100%);
      transition: 0.3s ease;
      z-index: 999;
    }
    .profile-sidebar.active {
      transform: translateX(0);
    }

    .profile-sidebar h3 {
      font-family: 'Playfair Display', serif;
      margin-bottom: 20px;
      color: #4a3c2f;
    }

    .close-btn {
      position: absolute;
      top: 10px;
      right: 15px;
      font-size: 24px;
      border: none;
      background: none;
      cursor: pointer;
      color: #333;
    }

    .btn-custom {
      background-color: #c19a6b;
      color: white;
      border: none;
    }
    .btn-custom:hover {
      background-color: #a07f50;
    }

    /* Hero Section */
    .hero {
      position: relative;
      height: 70vh;
      background: url('san isidroweas.jpg') center/cover no-repeat;
      display: flex;
      justify-content: center;
      align-items: center;
      text-align: center;
      color: white;
    }

    .hero::after {
      content: "";
      position: absolute;
      top: 0; left: 0;
      width: 100%; height: 100%;
      background: rgba(0, 0, 0, 0.45);
    }

    .hero-text {
      position: relative;
      z-index: 2;
    }

    .btn-login {
      background-color: #c19a6b;
      color: white;
      border: none;
      padding: 12px 28px;
      font-weight: 600;
      border-radius: 30px;
      margin-top: 25px;
      text-decoration: none;
      transition: all 0.3s;
    }
    .btn-login:hover {
      background-color: #b38755;
      color: white;
      transform: scale(1.05);
    }

    /* About Section */
    .about {
      padding: 70px 0;
      background-color: #fff;
    }
    .about h2 {
      font-family: 'Playfair Display', serif;
      font-weight: bold;
      margin-bottom: 20px;
    }
    .about p {
      color: #4d3b28;
      max-width: 750px;
      margin: auto;
    }
    .about img {
      border-radius: 10px;
      width: 100%;
      height: 350px;
      object-fit: cover;
    }

    /* Highlights Section */
    .highlights {
      padding: 60px 0;
      background-color: #f3eee9;
    }
    .highlight-box {
      background: white;
      border-radius: 10px;
      padding: 30px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      transition: transform 0.3s;
    }
    .highlight-box:hover {
      transform: translateY(-8px);
    }
    .highlight-box i {
      font-size: 2rem;
      color: #c19a6b;
      margin-bottom: 15px;
    }

    /* Gallery */
    .gallery {
      background-color: #fff;
      padding: 60px 0;
    }
    .gallery img {
      border-radius: 10px;
      width: 100%;
      height: 220px;
      object-fit: cover;
      transition: transform 0.3s;
    }
    .gallery img:hover {
      transform: scale(1.05);
    }

    /* Testimonials */
    .testimonials {
      background-color: #f7f3ef;
      padding: 60px 0;
      text-align: center;
    }
    .testimonials h2 {
      font-family: 'Playfair Display', serif;
      font-weight: bold;
    }
    .testimonial-box {
      background-color: white;
      border-radius: 10px;
      padding: 25px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      margin: 20px;
    }
    .testimonial-box p {
      font-style: italic;
      color: #5c4834;
    }

    /* Call to Action */
    .cta {
      background: url('pool_night.jpg') center/cover no-repeat;
      color: white;
      text-align: center;
      padding: 50px 0;
      position: relative;
    }
    .cta::after {
      content: "";
      position: absolute;
      inset: 0;
      background: rgba(0, 0, 0, 0.6);
    }
    .cta-content {
      position: relative;
      z-index: 2;
    }
    .cta-content h2 {
      font-family: 'Playfair Display', serif;
      font-weight: 600;
      font-size: 2.5rem;
    }

    .map-container iframe {
      border: none;
      width: 100%;
      height: 250px;
      border-radius: 8px;
      margin-top: 10px;
    }

    /* Footer */
    footer {
      background-color: #b2a187;
      color: white;
      text-align: center;
      padding: 10px;
      font-size: 0.9rem;
    }
    footer a {
      color: #f5e3c6;
      text-decoration: none;
      margin: 0 6px;
    }
    footer a:hover {
      color: white;
    }
  </style>
</head>

<body>
  <!-- Header -->
  <div class="header-bar">
    <img src="LOGO NG SAN ISIDRO.png" alt="Logo">
    <h5>San Isidro Labrador Resort and Leisure Farm</h5>

    <!-- Profile Button -->
    <div class="profile-btn d-flex align-items-center justify-content-center" onclick="toggleSidebar()">
      <i class="fa-solid fa-user me-2" style="font-size: 20px; color: #c19a6b;"></i>
    </div>

    <!-- Overlay -->
    <div class="overlay" id="overlay" onclick="toggleSidebar()"></div>

    <!-- Sidebar -->
    <div class="profile-sidebar" id="sidebar">
      <button class="close-btn" onclick="toggleSidebar()">&times;</button>
      <h3>Welcome!</h3>
      <p>Access your account or create one to explore our resort’s full features, including event bookings and packages.</p>
      <a href="Login.php" class="btn btn-custom w-100 mb-3">Login</a>
      <a href="Signup.php" class="btn btn-outline-dark w-100">Sign Up</a>
      <hr class="my-4">
      <p style="font-size: 14px;">Need help? <a href="#" style="color:#c19a6b;">Contact Support</a></p>
    </div>
  </div>

  <!-- Hero Section -->
  <section class="hero">
    <div class="hero-text">
      <h3 style="font-family: 'Times New Roman', Times, serif;">Embrace the enchanting ambiance of San Isidro with its harmonious and calming breeze.</h3>
      <p style="font-family: 'Times New Roman', Times, serif;">San Isidro Labrador Resort and Leisure Farm</p>
    </div>
  </section>    

  <!-- Highlights -->
  <section class="highlights text-center">
    <div class="container">
      <h2 class="mb-5">What We Offer</h2>
      <div class="row g-4">
        <div class="col-md-4">
          <div class="highlight-box">
            <i class="fas fa-spa"></i>
            <h5>Relax & Rejuvenate</h5>
            <p>Unwind in our tranquil spaces surrounded by nature’s beauty.</p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="highlight-box">
            <i class="fas fa-ring"></i>
            <h5>Celebrate Love</h5>
            <p>Make every occasion special — from weddings to family gatherings.</p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="highlight-box">
            <i class="fas fa-leaf"></i>
            <h5>Farm Experience</h5>
            <p>Reconnect with the land and enjoy the simplicity of rural life.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Gallery -->
  <section class="gallery">
    <div class="container">
      <h2 class="text-center mb-4">A Glimpse of Paradise</h2>
      <div class="row g-3">
        <div class="col-md-4"><img src="pic 2.jpg" class="img-fluid"></div>
        <div class="col-md-4"><img src="pic 7.jpg" class="img-fluid"></div>
        <div class="col-md-4"><img src="pic 9.jpg" class="img-fluid"></div>
      </div>
    </div>
  </section>

  <!-- Testimonials -->
  <section class="testimonials">
    <div class="container">
      <h2>What Our Guests Say</h2>
      <div class="row justify-content-center">
        <div class="col-md-4">
          <div class="testimonial-box">
            <p>“The place is magical! Perfect for weddings and quiet retreats.”</p>
            <strong>- Apple T.</strong>
          </div>
        </div>
        <div class="col-md-4">
          <div class="testimonial-box">
            <p>“A hidden gem in Batangas! The scenery was breathtaking.”</p>
            <strong>- Earlsin C.</strong>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Contact -->
  <section class="contact-section" style="background-color: #fff; padding: 60px 0;">
    <div class="container text-center" style="font-family: 'Times New Roman', Times, serif;">
      <h2><strong>Get in Touch With Us</strong></h2>
      <img src="divider.png" alt="Divider" class="d-block mx-auto" style="width:100px;"> 
      <br>
      <div class="map-container mt-4">
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3861.737084666372!2d120.73654697499172!3d14.019296486657689!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33bd808d6f540739%3A0x6578b16532152cb5!2sSan%20Isidro%20Labrador%20Resort%20and%20Leisure%20Farm!5e0!3m2!1sen!2sph!4v1729323644107!5m2!1sen!2sph" allowfullscreen loading="lazy"></iframe>
      </div>
      <div class="mt-4">
        <h3>Contact Us</h3>
        <p><strong>San Isidro Labrador Resort and Leisure Farm</strong><br>
        <i class="fa-solid fa-location-dot"></i> Bolboc, Tuy, Batangas, Philippines 4214</p>
        <h3>Contact Details</h3>
        <p><i class="fa-solid fa-phone"></i> +63 967 007 1971<br>
        <i class="fa-solid fa-envelope"></i> sanisidroresort@gmail.com</p>
      </div>
    </div>
  </section>

  <!-- Call to Action -->
  <section class="cta">
    <div class="cta-content">
      <h2>Ready to Discover More?</h2>
      <p>Login now to access our full website — view our event packages, amenities, and booking options.</p>
      <a href="Login.php" class="btn-login">Login to Explore</a>
    </div>
  </section>

  <!-- Footer -->
  <footer style="font-family: 'Times New Roman', Times, serif;">
    <div class="social-icons mb-2">
      <a href="https://www.facebook.com/sanisidrofarmresort" target="_blank"><i class="fa-brands fa-facebook"></i></a>
      <a href="https://www.tiktok.com/@sanisidrofarmph" target="_blank"><i class="fa-brands fa-tiktok"></i></a>
      <a href="https://www.instagram.com/sanisidrofarmph" target="_blank"><i class="fa-brands fa-instagram"></i></a>
    </div>
    © 2025 San Isidro Labrador Resort and Leisure Farm. All rights reserved.<br>
    Website developed by <strong>Farmease</strong>.
  </footer>

  <script>
    function toggleSidebar() {
      document.getElementById("sidebar").classList.toggle("active");
      document.getElementById("overlay").classList.toggle("active");
    }
  </script>

  <script src="bootstrap5/js/bootstrap.bundle.min.js"></script>
</body>
</html>
