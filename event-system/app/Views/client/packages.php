<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Packages - San Isidro Labrador Resort and Leisure Farm</title>
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

    /* Top Divider */
    .top-divider {
      text-align: center;
      padding: 30px 0 20px;
      background-color: #f8f6f3;
    }

    .top-divider img {
      height: 60px;
    }

    /* Packages Hero Section */
    .packages-hero {
      background-color: #7a6a58;
      border-radius: 50px;
      padding: 60px 40px;
      margin: 0 auto;
      max-width: 95%;
      text-align: center;
      color: white;
      position: relative;
      margin-bottom: 40px;
    }

    .packages-hero h1 {
      font-family: 'Times New Roman', Times, serif;
      font-size: 3.5rem;
      font-weight: 400;
      margin-bottom: 20px;
    }

    .packages-hero p {
      font-size: 1.1rem;
      font-weight: 300;
      margin-bottom: 0;
    }

    /* Package Cards Section */
    .packages-section {
      padding: 40px 0 60px;
      background-color: #f8f6f3;
    }

    .package-card {
      background-color: #e8e2d8;
      border-radius: 0;
      overflow: hidden;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      height: 100%;
      display: flex;
      flex-direction: column;
    }

    .package-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    }

    .package-card img {
      width: 100%;
      height: 280px;
      object-fit: cover;
    }

    .package-card-body {
      padding: 30px 25px;
      flex-grow: 1;
      display: flex;
      flex-direction: column;
    }

    .package-card-body h5 {
      font-size: 1.3rem;
      font-weight: 600;
      color: #3b2a18;
      margin-bottom: 10px;
    }

    .package-card-body p {
      font-size: 0.95rem;
      color: #5a4a3a;
      margin-bottom: 20px;
      flex-grow: 1;
    }

    .btn-inquiry {
      background-color: #3b2a18;
      color: white;
      border: none;
      padding: 12px 30px;
      border-radius: 5px;
      font-weight: 500;
      transition: background-color 0.3s ease;
      width: 100%;
    }

    .btn-inquiry:hover {
      background-color: #2a1f12;
      color: white;
    }

    /* Bottom Divider */
    .bottom-divider {
      text-align: center;
      padding: 40px 0;
      background-color: #f8f6f3;
    }

    .bottom-divider img {
      height: 60px;
    }

    /* Description Section */
    .description-section {
      background-color: #ffffff;
      padding: 60px 0 40px;
    }

    .description-section p {
      font-size: 1rem;
      line-height: 1.8;
      color: #3b2a18;
      margin-bottom: 20px;
      text-align: justify;
    }

    /* Images Section */
    .images-section {
      background-color: #ffffff;
      padding: 20px 0 60px;
    }

    .image-container {
      display: flex;
      gap: 20px;
      margin-bottom: 40px;
    }

    .image-container img {
      width: 100%;
      height: auto;
      object-fit: cover;
      border-radius: 0;
    }

    .image-left {
      flex: 1;
    }

    .image-right {
      flex: 1;
    }

    /* Logo Section */
    .logo-section {
      background-color: #ffffff;
      padding: 10px 0 20px;
      text-align: center;
    }

    .logo-section img {
      max-width: 200px;
      height: 50px;
    }

    /* Package Inclusions Section */
    .inclusions-section {
      background-color: #f8f6f3;
      padding: 60px 0 80px;
    }

    .inclusions-title {
      font-family: 'Times New Roman', Times, serif;
      font-size: 2.5rem;
      font-weight: 700;
      text-align: left;
      margin-bottom: 40px;
      color: #3b2a18;
    }

    .package-image-wrapper {
      cursor: pointer;
      transition: transform 0.3s ease;
    }

    .package-image-wrapper:hover {
      transform: scale(1.05);
    }

    .package-img {
      width: 100%;
      height: auto;
      border-radius: 10px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }

    /* Modal Styles */
    .modal-overlay {
      display: none;
      position: fixed;
      z-index: 9999;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.9);
      justify-content: center;
      align-items: center;
    }

    .modal-overlay.active {
      display: flex;
    }

    .modal-content-wrapper {
      position: relative;
      max-width: 90%;
      max-height: 90%;
      text-align: center;
    }

    .modal-image {
      max-width: 100%;
      max-height: 85vh;
      object-fit: contain;
      border-radius: 10px;
    }

    .modal-close {
      position: absolute;
      top: 20px;
      right: 40px;
      color: #fff;
      font-size: 50px;
      font-weight: bold;
      cursor: pointer;
      z-index: 10000;
    }

    .modal-close:hover {
      color: #c19a6b;
    }

    .modal-caption {
      color: #fff;
      font-size: 1.2rem;
      margin-top: 15px;
      font-weight: 500;
    }

    /* Decorative Bars Section */
    .decorative-bars {
      background-color: #ffffff;
      padding: 0;
      margin: 0;
    }

    .bar {
      width: 100%;
      height: 50px;
      margin: 0;
    }

    .bar-light {
      background-color: #e8e3db;
    }

    .bar-dark {
      background-color: #a89b88;
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
    @media (max-width: 768px) {
      .packages-hero {
        padding: 40px 30px;
        border-radius: 30px;
      }

      .packages-hero h1 {
        font-size: 2.5rem;
      }

      .packages-hero p {
        font-size: 1rem;
      }

      .package-card img {
        height: 220px;
      }

      .image-container {
        flex-direction: column;
        gap: 15px;
      }

      .description-section {
        padding: 40px 0 30px;
      }

      .description-section p {
        font-size: 0.95rem;
      }

      .logo-section img {
        max-width: 150px;
      }

      .inclusions-title {
        font-size: 2rem;
        text-align: center;
      }

      .package-image-wrapper {
        margin-bottom: 20px;
      }

      .header-container {
        padding: 0 20px;
        flex-direction: column;
        text-align: center;
      }

      .header-logo {
        flex-direction: column;
        gap: 10px;
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
          <li class="nav-item"><a class="nav-link active" href="#">PACKAGES</a></li>
          <li class="nav-item"><a class="nav-link" href="#">VIDEOS/GALLERIES</a></li>
          <li class="nav-item"><a class="nav-link" href="#">TESTIMONIALS</a></li>
          <li class="nav-item"><a class="nav-link" href="#">CONTACT</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Top Divider -->
  <div class="top-divider">
    <img src="decor.png" alt="Decorative Divider">
  </div>

  <!-- Packages Hero Section -->
  <section class="packages-hero">
    <h1>Packages</h1>
    <p>San Isidro Labrador Resort and Leisure Farm is gearing up to be the premiere location for your once-in-a-lifetime event</p>
  </section>

  <!-- Package Cards -->
  <section class="packages-section">
    <div class="container">
      <div class="row g-4">
        
        <!-- Package Card 1 -->
        <div class="col-lg-4 col-md-6">
          <div class="package-card">
            <img src="san isidroweas.jpg" alt="Package 1">
            <div class="package-card-body">
              <h5>Package sample</h5>
              <p>Explanation</p>
              <button class="btn-inquiry">Make an inquiry</button>
            </div>
          </div>
        </div>

        <!-- Package Card 2 -->
        <div class="col-lg-4 col-md-6">
          <div class="package-card">
            <img src="wed_eventspic.jpg" alt="Package 2">
            <div class="package-card-body">
              <h5>Package sample</h5>
              <p>Explanation</p>
              <button class="btn-inquiry">Make an inquiry</button>
            </div>
          </div>
        </div>

        <!-- Package Card 3 -->
        <div class="col-lg-4 col-md-6">
          <div class="package-card">
            <img src="priv_gathetingspic.jpg" alt="Package 3">
            <div class="package-card-body">
              <h5>Package sample</h5>
              <p>Explanation</p>
              <button class="btn-inquiry">Make an inquiry</button>
            </div>
          </div>
        </div>

      </div>
    </div>
  </section>

  <!-- Bottom Divider -->
  <div class="bottom-divider">
    <img src="decor.png" alt="Decorative Divider">
  </div>

  <!-- Description Section -->
  <section class="description-section">
    <div class="container">
      <div class="row">
        <div class="col-12">
          <p>
            Body text for your whole article or post. We'll put in some lorem ipsum to show how a filled-out page might look:
          </p>
          <p>
            Excepteur efficient emerging, minim veniam anim aute carefully curated Ginza conversation exquisite perfect nostrud nisi intricate Content. Qui international first-class nulla ut. Punctual adipisicing, essential lovely queen tempor eiusmod irure. Exclusive izakaya charming Scandinavian impeccable aute quality of life soft power pariatur Melbourne occaecat discerning. Qui wardrobe aliquip, et Porter destination Toto remarkable officia Helsinki excepteur Basset hound. Zürich sleepy perfect consectetur.
          </p>
          <p>
            Exquisite sophisticated iconic cutting-edge laborum deserunt Addis Ababa esse bureaux cupidatat id minim. Sharp classic the best commodo nostrud delightful. Conversation aute Rochester id. Qui sunt remarkable deserunt intricate airport handsome K-pop excepteur classic esse Asia-Pacific laboris.
          </p>
        </div>
      </div>
    </div>
  </section>

  <!-- Images Section -->
  <section class="images-section">
    <div class="container">
      <div class="image-container">
        <div class="image-left">
          <img src="pink1.jpg" alt="Package Image 1">
        </div>
        <div class="image-right">
          <img src="pink2.jpg" alt="Package Image 2">
        </div>
      </div>
    </div>
  </section>

  <!-- Logo Section -->
  <section class="logo-section">
    <div class="container">
      <img src="decor.png" alt="Decorative Divider">
    </div>
  </section>

  <!-- Package Inclusions Section -->
  <section class="inclusions-section">
    <div class="container">
      <h2 class="inclusions-title">Package Inclusions</h2>
      
      <div class="row g-4 justify-content-center">
        
        <!-- Café Package -->
        <div class="col-lg-2 col-md-4 col-6">
          <div class="package-image-wrapper">
            <img src="cafe.jpg" alt="Café Package" class="package-img" onclick="openModal('cafe.jpg', 'Café 2nd Floor Venue')">
          </div>
        </div>

        <!-- Playground Package -->
        <div class="col-lg-2 col-md-4 col-6">
          <div class="package-image-wrapper">
            <img src="playground.jpg" alt="Playground Package" class="package-img" onclick="openModal('playground.jpg', 'Playground')">
          </div>
        </div>

        <!-- Venue Package -->
        <div class="col-lg-2 col-md-4 col-6">
          <div class="package-image-wrapper">
            <img src="venue.jpg" alt="Venue Package" class="package-img" onclick="openModal('venue.jpg', 'Venue')">
          </div>
        </div>

        <!-- Prep Package -->
        <div class="col-lg-2 col-md-4 col-6">
          <div class="package-image-wrapper">
            <img src="prep.jpg" alt="Prep & Photoshoot Package" class="package-img" onclick="openModal('prep.jpg', 'Prep & Photoshoot')">
          </div>
        </div>

        <!-- Meeting Package -->
        <div class="col-lg-2 col-md-4 col-6">
          <div class="package-image-wrapper">
            <img src="meeting.jpg" alt="Meeting Package" class="package-img" onclick="openModal('meeting.jpg', 'Meeting Room')">
          </div>
        </div>

      </div>
    </div>
  </section>

  <!-- Decorative Bars Section -->
  <section class="decorative-bars">
    <div class="bar bar-light"></div>
    <div class="bar bar-dark"></div>
    <div class="bar bar-light"></div>
    <div class="bar bar-dark"></div>
    <div class="bar bar-light"></div>
    <div class="bar bar-dark"></div>
  </section>

  <!-- Modal for Image Popup -->
  <div id="imageModal" class="modal-overlay" onclick="closeModal()">
    <span class="modal-close">&times;</span>
    <div class="modal-content-wrapper">
      <img id="modalImage" class="modal-image" src="" alt="">
      <div id="modalCaption" class="modal-caption"></div>
    </div>
  </div>

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
    function openModal(imgSrc, caption) {
      var modal = document.getElementById('imageModal');
      var modalImg = document.getElementById('modalImage');
      var modalCaption = document.getElementById('modalCaption');
      
      modal.classList.add('active');
      modalImg.src = imgSrc;
      modalCaption.textContent = caption;
    }

    function closeModal() {
      var modal = document.getElementById('imageModal');
      modal.classList.remove('active');
    }

    // Close modal with Escape key
    document.addEventListener('keydown', function(event) {
      if (event.key === 'Escape') {
        closeModal();
      }
    });
  </script>
</body>
</html>