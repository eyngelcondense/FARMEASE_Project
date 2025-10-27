<?php
// You can use this later to manage user sessions or dynamic data
// session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>San Isidro Labrador Resort and Leisure Farm</title>

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="bootstrap5/css/bootstrap.min.css">

  <!-- Google Font -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.6.0/css/all.min.css" crossorigin="anonymous">

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
      padding: 10px 0;
      text-align: center;
      font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
    }

    .header-bar img {
      height: 80px;
    }

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

    .navbar-nav .nav-link:hover {
      color: #c19a6b;
    }

    /* Hero Section */
    .hero {
      position: relative;
      height: 70vh;
      background: url('images/san isidroweas.jpg') center/cover no-repeat;
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

    .hero-text h1 {
      font-size: 2.5rem;
      font-weight: 600;
    }

    .hero-text p {
      font-size: 1.2rem;
      font-weight: 400;
    }

    /* Feature Cards */
    .feature-section {
      background-color: #fff;
      padding: 60px 0;
    }

    .feature-card img {
      border-radius: 10px;
      height: 250px;
      object-fit: cover;
    }

    .feature-card h5 {
      margin-top: 12px;
      font-weight: 600;
      text-align: center;
      color: #3b2a18;
    }

    /* About Section */
    .about-section {
      background-color: #e9e3db;
      padding: 60px 0;
    }

    .about-section img {
      border-radius: 10px;
      width: 100%;
      height: 350px;
      object-fit: cover;
    }

    .about-section h2 {
      font-weight: 600;
      color: #3b2a18;
    }

    .about-section p {
      color: #4d3b28;
    }

    .btn-custom {
      background-color: #c19a6b;
      color: white;
      border: none;
      padding: 10px 25px;
      border-radius: 5px;
    }

    .btn-custom:hover {
      background-color: #b38850;
      color: white;
    }

    /* Package View Section */
    .packageview-section {
      background-color: #7a6a58;
      color: white;
      padding: 60px 0;
      text-align: center;
      font-family: 'Times New Roman', Times, serif;
    }

    /* Special Section */
    .special-section {
      background-color: #f7f3ef;
      padding: 60px 0;
    }

    .special-section h2 {
      font-weight: 600;
      color: #3b2a18;
    }

    /* Booking Section */
    .booking-section {
      background-color: #8f8a83;
      color: #000;
      font-family: 'Times New Roman', Times, serif;
    }

    .booking-section .btn {
      background-color: #f5e3c6;
      color: #3b2a18;
      border: none;
      font-weight: 600;
      border-radius: 5px;
      transition: all 0.3s ease;
    }

    .booking-section .btn:hover {
      background-color: #e3cfa3;
    }

    .booking-section h3 {
      font-size: 1.5rem;
    }

    /* Events */
    .events-section {
      padding: 60px 0;
      background-color: #fff;
    }

    .events-section h3 {
      text-align: center;
      margin-bottom: 40px;
      font-weight: 600;
      color: #3b2a18;
    }

    .event-gallery img {
      border-radius: 10px;
      width: 100%;
      height: 230px;
      object-fit: cover;
      transition: transform 0.3s;
    }

    .event-gallery img:hover {
      transform: scale(1.05);
    }

    /* Footer */
    footer {
      background-color: #b2a187;
      color: white;
      padding: 25px 0;
      text-align: center;
      font-size: 0.95rem;
    }

    footer a {
      color: #f5e3c6;
      text-decoration: none;
      margin: 0 5px;
    }

    footer a:hover {
      text-decoration: underline;
    }
  </style>
</head>

<body>

  <!-- Header -->
  <div class="header-bar">
    <img src="images/LOGO NG SAN ISIDRO.png" alt="San Isidro Labrador Logo">
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
          <li class="nav-item">
            <a class="nav-link active" href="<?= site_url('logout') ?>">HOME</a>
          </li>
          <li class="nav-item"><a class="nav-link" href="packages.php">PACKAGES</a></li>
          <li class="nav-item"><a class="nav-link" href="events.php">EVENTS</a></li>
          <li class="nav-item"><a class="nav-link" href="testimonials.php">TESTIMONIALS</a></li>
          <li class="nav-item"><a class="nav-link" href="booking.php">BOOKING</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Hero Section -->
  <section class="hero">
    <div class="hero-text">
      <h3 style="font-family: 'Times New Roman', Times, serif;">
        Embrace the enchanting ambiance of San Isidro with its harmonious and calming breeze.
      </h3>
      <p style="font-family: 'Times New Roman', Times, serif;">
        San Isidro Labrador Resort and Leisure Farm
      </p>
    </div>
  </section>

  <!-- Feature Cards -->
  <section class="feature-section">
    <div class="container">
      <div class="row g-4">
        <div class="col-md-4">
          <div class="feature-card">
            <img src="images/san isidroweas.jpg">
            <h5>Package Lounge</h5>
          </div>
        </div>
        <div class="col-md-4">
          <div class="feature-card">
            <img src="images/wed_eventspic.jpg" alt="">
            <h5>Wedding Events</h5>
          </div>
        </div>
        <div class="col-md-4">
          <div class="feature-card">
            <img src="images/priv_gathetingspic.jpg" alt="">
            <h5>Private Gatherings</h5>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- View Packages Section -->
  <section class="packageview-section">
    <h2>We Plan Your Special Day</h2>
    <p>The perfect place to have your events with the people that matter</p>
    <button class="btn btn-dark">View Packages</button>
  </section>

  <!-- Special Section -->
  <section class="special-section">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-6 mb-4 mb-lg-0">
          <img src="images/pic 1.jpg" alt="Resort Image" class="img-fluid rounded">
        </div>

        <div class="col-lg-6">
          <h2 class="mb-3" style="font-family: 'Times New Roman', serif; font-weight: bold;">
            What Makes Us Special
          </h2>
          <div class="d-flex align-items-center mb-3">
            <img src="images/divider.png" alt="Divider" style="height: 30px;">
          </div>

          <div class="accordion" id="specialAccordion">
            <div class="accordion-item border-0 border-bottom">
              <h2 class="accordion-header" id="headingOne">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                  data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                  What Makes Us Special
                </button>
              </h2>
              <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#specialAccordion">
                <div class="accordion-body">
                  Our resort offers lush greenery, serene surroundings, and elegant event spaces designed for unforgettable celebrations.
                </div>
              </div>
            </div>

            <div class="accordion-item border-0 border-bottom">
              <h2 class="accordion-header" id="headingTwo">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                  data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                  Virtual Discussion
                </button>
              </h2>
              <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#specialAccordion">
                <div class="accordion-body">
                  We provide convenient virtual meetings for event planning and inquiries.
                </div>
              </div>
            </div>

            <div class="accordion-item border-0 border-bottom">
              <h2 class="accordion-header" id="headingThree">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                  data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                  Nearby Churches
                </button>
              </h2>
              <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#specialAccordion">
                <div class="accordion-body">
                  Several beautiful churches nearby make your wedding day more convenient and special.
                </div>
              </div>
            </div>

            <div class="accordion-item border-0 border-bottom">
              <h2 class="accordion-header" id="headingFour">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                  data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                  Menu Best Seller
                </button>
              </h2>
              <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#specialAccordion">
                <div class="accordion-body">
                  Experience our top-rated dishes prepared with love and fresh ingredients.
                </div>
              </div>
            </div>

            <div class="accordion-item border-0 border-bottom">
              <h2 class="accordion-header" id="headingFive">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" 
                  data-bs-target="#collapseFive" aria-expanded="true" aria-controls="collapseFive">
                  Ocular Visits
                </button>
              </h2>
              <div id="collapseFive" class="accordion-collapse collapse show" aria-labelledby="headingFive" data-bs-parent="#specialAccordion">
                <div class="accordion-body">
                  We are open Mondays to Sundays from 11am – 5pm for ocular visits and guided tours.
                </div>
              </div>
            </div>
          </div>

          <a href="#" class="btn btn-custom mt-4">Contact Us Today!</a>
        </div>
      </div>
    </div>
  </section>

  <!-- About Section -->
  <section class="about-section">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-6">
          <h2 class="fw-bold" style="font-family: 'Times New Roman', serif;">
            San Isidro Labrador Resort and Leisure Farm
          </h2>
          <img src="images/divider.png" alt="Divider" style="width: 80px; height: auto; display: block; margin-top: 5px;">
          <p class="mt-3">
            A serene haven nestled amidst nature’s beauty — perfect for those seeking relaxation, celebration, and meaningful experiences. Whether you’re planning a wedding, family outing, or corporate retreat, our resort offers a unique blend of elegance and tranquility.
          </p>
          <a href="#" class="btn btn-custom">Read More</a>
        </div>
        <div class="col-lg-6 mt-4 mt-lg-0">
          <img src="images/pic 2.jpg" alt="Resort" class="img-fluid rounded">
        </div>
      </div>
    </div>
  </section>

  <!-- Booking Section -->
  <section class="booking-section py-4">
    <div class="container d-flex justify-content-between align-items-center flex-wrap">
      <div>
        <h3 class="fw-bold mb-1">Book your preferred date in advance</h3>
        <p class="mb-0 text-muted">"Tagline nila here"</p>
      </div>
      <a href="#" class="btn btn-book-now mt-3 mt-sm-0">BOOK NOW</a>
    </div>
  </section>

  <!-- Events Section -->
  <section class="events-section">
    <div class="container">
      <h3>Recent Events</h3>
      <div class="row g-3 event-gallery">
        <div class="col-md-4 col-sm-6"><img src="images/pic 3.jpg" alt=""></div>
        <div class="col-md-4 col-sm-6"><img src="images/pic 4.jpg" alt=""></div>
        <div class="col-md-4 col-sm-6"><img src="images/pic 5.jpg" alt=""></div>
        <div class="col-md-4 col-sm-6"><img src="images/pic 6.jpg" alt=""></div>
        <div class="col-md-4 col-sm-6"><img src="images/pic 7.jpg" alt=""></div>
        <div class="col-md-4 col-sm-6"><img src="images/pic 8.jpg" alt=""></div>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer>
    <p>© 2025 San Isidro Labrador Resort and Leisure Farm. All rights reserved.</p>
    <div>
      <a href="https://www.facebook.com/sanisidrofarmresort"><i class="fa-brands fa-facebook"></i> Facebook</a> | 
      <a href="https://www.instagram.com/sanisidrofarmph"><i class="fa-brands fa-instagram"></i> Instagram</a> | 
      <a href="#"><i class="fa-solid fa-phone"></i> Contact Us</a>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
