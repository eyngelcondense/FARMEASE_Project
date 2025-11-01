<?php
           
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Testimonials - San Isidro Labrador Resort and Leisure Farm</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
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
      padding: 5px 0;
    }

    .header-container {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 50px;
    }

    .header-logo {
      display: flex;
      align-items: center;
      gap: 15px;
    }

    .header-logo img {
      height: 70px;
    }

    .header-logo-text {
      text-align: left;
      font-family: 'Times New Roman', Times, serif;
    }

    .header-logo-text h5 {
      margin: 0;
      font-size: 1rem;
      color: #8b6f47;
      letter-spacing: 2px;
    }

    .header-logo-text p {
      margin: 0;
      font-size: 0.7rem;
      color: #8b6f47;
    }

    .navbar {
      background-color: #ffffff;
      border-top: 1px solid #ddd;
      border-bottom: 1px solid #ddd;
      padding: 0;
    }

    .navbar-nav {
      margin: 0 auto;
    }

    .navbar-nav .nav-link {
      color: #3b2a18;
      font-weight: 600;
      margin: 0 20px;
      padding: 15px 0;
      transition: color 0.3s;
      font-size: 0.95rem;
    }

    .navbar-nav .nav-link:hover,
    .navbar-nav .nav-link.active {
      color: #c19a6b;
    }

    /* Testimonials Header Section */
    .testimonials-header {
      background-color: #f8f6f3;
      padding: 60px 0 40px;
      text-align: center;
    }

    .testimonials-header h1 {
      font-family: 'Times New Roman', Times, serif;
      font-size: 3rem;
      font-weight: 700;
      color: #3b2a18;
      margin-bottom: 20px;
    }

    .testimonials-header .divider {
      height: 50px;
      margin: 0 auto 20px;
    }

    .testimonials-header p {
      font-size: 1.1rem;
      color: #5a4a3a;
      max-width: 700px;
      margin: 0 auto;
    }

    /* Gray Banner Section */
    .testimonials-banner {
      background-color: #7a6a58;
      color: white;
      padding: 30px 0;
      text-align: center;
      margin-bottom: 60px;
    }

    .testimonials-banner h3 {
      font-family: 'Times New Roman', Times, serif;
      font-size: 1.8rem;
      font-weight: 600;
      margin: 0;
    }

    /* Testimonial Cards Section */
    .testimonials-section {
      background-color: #f8f6f3;
      padding: 0 0 80px;
    }

    .testimonial-card {
      background-color: white;
      border: 2px solid #3b2a18;
      border-radius: 20px;
      padding: 40px 30px 30px;
      text-align: center;
      height: 100%;
      position: relative;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .testimonial-card:hover {
      transform: translateY(-10px);
      box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    }

    .testimonial-avatar {
      width: 100px;
      height: 100px;
      border-radius: 50%;
      border: 4px solid #3b2a18;
      object-fit: cover;
      position: absolute;
      top: -50px;
      left: 50%;
      transform: translateX(-50%);
      background-color: white;
    }

    .testimonial-content {
      margin-top: 60px;
      min-height: 200px;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }

    .testimonial-text {
      font-size: 0.95rem;
      line-height: 1.7;
      color: #3b2a18;
      font-style: italic;
      margin-bottom: 20px;
      text-align: left;
    }

    .testimonial-author {
      margin-top: auto;
      padding-top: 20px;
      border-top: 1px solid #e8e3db;
    }

    .testimonial-name {
      font-weight: 700;
      color: #3b2a18;
      font-size: 1rem;
      margin-bottom: 5px;
    }

    .testimonial-event {
      font-size: 0.85rem;
      color: #7a6a58;
      font-style: italic;
    }

    /* Book Button Section */
    .book-section {
      background-color: #f8f6f3;
      padding: 60px 0 80px;
      text-align: center;
    }

    .btn-book {
      background-color: #3b2a18;
      color: white;
      border: none;
      padding: 15px 50px;
      font-size: 1.1rem;
      font-weight: 600;
      border-radius: 5px;
      transition: all 0.3s ease;
    }

    .btn-book:hover {
      background-color: #2a1f12;
      color: white;
      transform: scale(1.05);
    }

    /* Feedback Section */
    .feedback-section {
      background-color: #f8f6f3;
      padding: 40px 0 80px;
    }

    .feedback-title {
      font-family: 'Poppins', sans-serif;
      font-size: 1.8rem;
      font-weight: 600;
      color: #3b2a18;
      text-align: left;
      margin-bottom: 20px;
    }

    .feedback-form {
      max-width: px;
    }

    .feedback-textarea {
      background-color: #e8e3db;
      border: 1px solid #b2a187;
      border-radius: 5px;
      padding: 15px;
      font-size: 0.95rem;
      color: #3b2a18;
      resize: vertical;
      margin-bottom: 20px;
    }

    .feedback-textarea:focus {
      background-color: #e8e3db;
      border-color: #7a6a58;
      box-shadow: none;
      outline: none;
    }

    .feedback-textarea::placeholder {
      color: #8b7d6b;
    }

    .btn-submit {
      background-color: #3b2a18;
      color: white;
      border: none;
      padding: 12px 40px;
      font-size: 1rem;
      font-weight: 600;
      border-radius: 5px;
      transition: all 0.3s ease;
    }

    .btn-submit:hover {
      background-color: #2a1f12;
      color: white;
    }

    /* Footer */
    footer {
      background-color: #f8f6f3;
      padding: 30px 0 0;
      text-align: center;
      font-size: 0.95rem;
      color: #3b2a18;
    }

    .footer-content {
      padding: 20px 0;
    }

    .footer-content p {
      margin: 5px 0;
      color: #3b2a18;
    }

    .footer-icons {
      display: flex;
      justify-content: center;
      gap: 20px;
      margin: 20px 0;
    }

    .footer-icons a {
      color: #7a6a58;
      font-size: 1.8rem;
      transition: color 0.3s;
    }

    .footer-icons a:hover {
      color: #3b2a18;
    }

    .footer-bottom {
      background-color: #b2a187;
      padding: 12px 0;
      color: #3b2a18;
      font-size: 0.9rem;
      margin-top: 20px;
    }

    .footer-bottom a {
      color: #3b2a18;
      text-decoration: none;
    }

    .footer-bottom a:hover {
      text-decoration: underline;
    }

    /* Responsive */
    @media (max-width: 992px) {
      .header-container {
        padding: 0 20px;
        flex-direction: column;
        text-align: center;
      }

      .header-logo {
        flex-direction: column;
        gap: 10px;
      }

      .testimonials-header h1 {
        font-size: 2.5rem;
      }

      .testimonial-card {
        margin-bottom: 60px;
      }
    }

    @media (max-width: 768px) {
      .testimonials-header h1 {
        font-size: 2rem;
      }

      .testimonials-banner h3 {
        font-size: 1.3rem;
        padding: 0 20px;
      }
    }
  </style>
</head>
<body>

  <!-- Header -->
  <div class="header-bar">
    <div class="header-container">
      <div class="header-logo">
        <img src="LOGO NG SAN ISIDRO.png" alt="San Isidro Labrador Logo">
        <div class="header-logo-text">
          <h5>SAN ISIDRO LABRADOR</h5>
          <p>RESORT AND LEISURE FARM</p>
        </div>
      </div>
    </div>
  </div>

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item"><a class="nav-link" href="#">HOME</a></li>
          <li class="nav-item"><a class="nav-link" href="#">PACKAGES</a></li>
          <li class="nav-item"><a class="nav-link" href="#">VIDEOS/GALLERIES</a></li>
          <li class="nav-item"><a class="nav-link active" href="#">TESTIMONIALS</a></li>
          <li class="nav-item"><a class="nav-link" href="#">CONTACT</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Testimonials Header -->
  <section class="testimonials-header">
    <h1>Client Testimonials</h1>
    <img src="decor.png" alt="Divider" class="divider">
    <p>San Isidro Labrador Resort and Leisure Farm is gearing up to be the premiere location for your once-in-a-lifetime event</p>
  </section>

  <!-- Banner Section -->
  <section class="testimonials-banner">
    <div class="container">
      <h3>Hear what our clients have to say about our venue.</h3>
    </div>
  </section>

  <!-- Testimonials Cards -->
  <section class="testimonials-section">
    <div class="container">
      <div class="row g-5">
        
        <!-- Testimonial 1 -->
        <div class="col-lg-4 col-md-6">
          <div class="testimonial-card">
            <img src="angel.jpg" alt="Angel Cortino" class="testimonial-avatar">
            <div class="testimonial-content">
              <div class="testimonial-text">
                “Superbb. Bongga apakaangas kinilig yung mga bisita sa place. Recommendable sha ya, see you next event yah.”
              </div>
              <div class="testimonial-author">
                <div class="testimonial-name">Angel Cortino</div>
                <div class="testimonial-event">Wedding</div>
              </div>
            </div>
          </div>
        </div>

        <!-- Testimonial 2 -->
        <div class="col-lg-4 col-md-6">
          <div class="testimonial-card">
            <img src="ryan.jpg" alt="Ryan Magnaye" class="testimonial-avatar">
            <div class="testimonial-content">
              <div class="testimonial-text">
                "Okay yung place. Pwede na."
              </div>
              <div class="testimonial-author">
                <div class="testimonial-name">Ryan Magnaye</div>
                <div class="testimonial-event">Birthday</div>
              </div>
            </div>
          </div>
        </div>

        <!-- Testimonial 3 -->
        <div class="col-lg-4 col-md-6">
          <div class="testimonial-card">
            <img src="apple.jpg" alt="Apple Templa" class="testimonial-avatar">
            <div class="testimonial-content">
              <div class="testimonial-text">
                “Waaaahhhhh grabe diko alam sasabihin ko pero super ganda ng placeeeee irerecommend ko talaga to sa lahat friends ko. Very affordable at ang babait ng staff.”
              </div>
              <div class="testimonial-author">
                <div class="testimonial-name">Apple Templa</div>
                <div class="testimonial-event">Wedding</div>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </section>

  <!-- Book Button Section -->
  <section class="book-section">
    <button class="btn btn-book">Book Your Event Today</button>
  </section>

  <!-- Feedback Section -->
  <section class="feedback-section">
    <div class="container">
      <h2 class="feedback-title">Your Feedback</h2>
      <form id="feedbackForm" method="post">
        <div class="feedback-form">
          <textarea 
            class="form-control feedback-textarea" 
            placeholder="Enter your feedback or message here..." 
            rows="6"
            name="feedback"
            required
          ></textarea>
          <button type="submit" class="btn btn-submit">Submit</button>
        </div>
      </form>
    </div>
  </section>

  <!-- Footer -->
  <footer>
    <div class="footer-content">
      <p>© 2025 San Isidro Labrador Resort and Leisure Farm. All rights reserved.</p>
      <p>Website developed by kunganomanteamname</p>
      
      <div class="footer-icons">
        <a href="https://www.facebook.com/sanisidrofarmresort" target="_blank">
          <i class="fa-brands fa-facebook"></i>
        </a>
        <a href="https://www.youtube.com/@sanisidrolabrador" target="_blank">
          <i class="fa-brands fa-youtube"></i>
        </a>
        <a href="https://www.instagram.com/sanisidrofarmph" target="_blank">
          <i class="fa-brands fa-instagram"></i>
        </a>
      </div>
    </div>
    
    <div class="footer-bottom">
      Phone: 0912 345 6789 | Email: <a href="mailto:sanisidro@gmail.com">sanisidro@gmail.com</a>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Feedback Form Submission
    document.getElementById('feedbackForm').addEventListener('submit', function(e) {
      e.preventDefault();
      
      const feedback = this.querySelector('[name="feedback"]').value;
      
      if(feedback.trim() === '') {
        alert('Please enter your feedback before submitting.');
        return;
      }
      
    
      alert('Thank you for your feedback! We appreciate your input.');
      
      // Clear the form
      this.reset();
      
  
      /*
      fetch('submit_feedback.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'feedback=' + encodeURIComponent(feedback)
      })
      .then(response => response.json())
      .then(data => {
        alert(data.message);
        this.reset();
      })
      .catch(error => {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
      });
      */
    });
  </script>
</body>
</html>