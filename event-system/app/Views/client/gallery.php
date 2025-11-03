<?php
           
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gallery - San Isidro Labrador Resort and Leisure Farm</title>
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

    /* Gallery Hero Section */
    .gallery-hero {
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

    .gallery-hero h1 {
      font-family: 'Times New Roman', Times, serif;
      font-size: 3.5rem;
      font-weight: 400;
      margin-bottom: 20px;
    }

    .gallery-hero p {
      font-size: 1.1rem;
      font-weight: 300;
      margin-bottom: 0;
    }

    /* Filter Buttons */
    .filter-section {
      text-align: center;
      padding: 30px 0;
      background-color: #f8f6f3;
    }

    .filter-btn {
      background-color: #3b2a18;
      color: white;
      border: none;
      padding: 10px 25px;
      margin: 0 8px;
      border-radius: 25px;
      font-weight: 500;
      font-size: 0.95rem;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .filter-btn:hover,
    .filter-btn.active {
      background-color: #c19a6b;
      transform: translateY(-2px);
    }

    /* Gallery Sections */
    .gallery-section {
      padding: 40px 0;
      background-color: #f8f6f3;
    }

    .section-divider {
      text-align: center;
      padding: 20px 0;
    }

    .section-divider img {
      height: 50px;
    }

    .section-title {
      font-family: 'Times New Roman', Times, serif;
      font-size: 2.5rem;
      font-weight: 400;
      text-align: center;
      margin: 30px 0 40px;
      color: #3b2a18;
    }

    /* Gallery Grid */
    .gallery-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 20px;
      margin-bottom: 60px;
    }

    .gallery-item {
      position: relative;
      overflow: hidden;
      border-radius: 0;
      cursor: pointer;
      aspect-ratio: 4/3;
      border: 8px solid #3b2a18;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .gallery-item img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform 0.4s ease;
    }

    .gallery-item:hover img {
      transform: scale(1.1);
    }

    .gallery-item-overlay {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(59, 42, 24, 0.7);
      display: flex;
      align-items: center;
      justify-content: center;
      opacity: 0;
      transition: opacity 0.3s ease;
    }

    .gallery-item:hover .gallery-item-overlay {
      opacity: 1;
    }

    .gallery-item-title {
      color: white;
      font-size: 1.3rem;
      font-weight: 500;
      text-align: center;
      padding: 20px;
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
      background-color: rgba(0, 0, 0, 0.95);
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

    .modal-nav {
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
      color: white;
      font-size: 40px;
      cursor: pointer;
      padding: 20px;
      user-select: none;
      transition: color 0.3s ease;
    }

    .modal-nav:hover {
      color: #c19a6b;
    }

    .modal-prev {
      left: 20px;
    }

    .modal-next {
      right: 20px;
    }

    .modal-caption {
      color: #fff;
      font-size: 1.2rem;
      margin-top: 15px;
      font-weight: 500;
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
      .gallery-hero {
        padding: 40px 30px;
        border-radius: 30px;
      }

      .gallery-hero h1 {
        font-size: 2.5rem;
      }

      .gallery-hero p {
        font-size: 1rem;
      }

      .section-title {
        font-size: 2rem;
      }

      .filter-btn {
        margin: 5px;
        padding: 8px 20px;
        font-size: 0.85rem;
      }

      .gallery-grid {
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 15px;
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

      .modal-nav {
        font-size: 30px;
        padding: 10px;
      }

      .modal-close {
        font-size: 40px;
        right: 20px;
      }
    }

    /* Hide sections based on filter */
    .gallery-section.hidden {
      display: none;
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
          <li class="nav-item"><a class="nav-link active" href="#">VIDEOS/GALLERIES</a></li>
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

  <!-- Gallery Hero Section -->
  <section class="gallery-hero">
    <h1>Gallery</h1>
    <p>San Isidro Labrador Resort and Leisure Farm is gearing up to be the premiere location for your once-in a lifetime event</p>
  </section>

  <!-- Filter Buttons -->
  <section class="filter-section">
    <button class="filter-btn active" onclick="filterGallery('all')">All</button>
    <button class="filter-btn" onclick="filterGallery('weddings')">Weddings</button>
    <button class="filter-btn" onclick="filterGallery('birthdays')">Birthdays</button>
    <button class="filter-btn" onclick="filterGallery('corporate')">Corporate</button>
  </section>

  <!-- Garden Cafe Section -->
  <section class="gallery-section" data-category="all">
    <div class="section-divider">
      <img src="decor.png" alt="Decorative Divider">
    </div>
    <h2 class="section-title">Garden Cafe</h2>
    <div class="container">
      <div class="gallery-grid">
        <div class="gallery-item" onclick="openModal(0)">
          <img src="cafe1.jpg" alt="Garden Cafe 1">
          <div class="gallery-item-overlay">
            <div class="gallery-item-title">Garden Cafe</div>
          </div>
        </div>
        <div class="gallery-item" onclick="openModal(1)">
          <img src="cafe2.jpg" alt="Garden Cafe 2">
          <div class="gallery-item-overlay">
            <div class="gallery-item-title">Garden Cafe</div>
          </div>
        </div>
        <div class="gallery-item" onclick="openModal(2)">
          <img src="cafe3.jpg" alt="Garden Cafe 3">
          <div class="gallery-item-overlay">
            <div class="gallery-item-title">Garden Cafe</div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Rooms Section -->
  <section class="gallery-section" data-category="all">
    <div class="section-divider">
      <img src="decor.png" alt="Decorative Divider">
    </div>
    <h2 class="section-title">Rooms</h2>
    <div class="container">
      <div class="gallery-grid">
        <div class="gallery-item" onclick="openModal(3)">
          <img src="room1.jpg" alt="Room 1">
          <div class="gallery-item-overlay">
            <div class="gallery-item-title">Rooms</div>
          </div>
        </div>
        <div class="gallery-item" onclick="openModal(4)">
          <img src="room2.jpg" alt="Room 2">
          <div class="gallery-item-overlay">
            <div class="gallery-item-title">Rooms</div>
          </div>
        </div>
        <div class="gallery-item" onclick="openModal(5)">
          <img src="room3.jpg" alt="Room 3">
          <div class="gallery-item-overlay">
            <div class="gallery-item-title">Rooms</div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Garden Venue Section -->
  <section class="gallery-section" data-category="all">
    <div class="section-divider">
      <img src="decor.png" alt="Decorative Divider">
    </div>
    <h2 class="section-title">Garden Venue</h2>
    <div class="container">
      <div class="gallery-grid">
        <div class="gallery-item" onclick="openModal(6)">
          <img src="garden1.jpg" alt="Garden Venue 1">
          <div class="gallery-item-overlay">
            <div class="gallery-item-title">Garden Venue</div>
          </div>
        </div>
        <div class="gallery-item" onclick="openModal(7)">
          <img src="garden2.jpg" alt="Garden Venue 2">
          <div class="gallery-item-overlay">
            <div class="gallery-item-title">Garden Venue</div>
          </div>
        </div>
        <div class="gallery-item" onclick="openModal(8)">
          <img src="garden3.jpg" alt="Garden Venue 3">
          <div class="gallery-item-overlay">
            <div class="gallery-item-title">Garden Venue</div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Modal for Image Popup -->
  <div id="imageModal" class="modal-overlay" onclick="closeModalOnBackground(event)">
    <span class="modal-close" onclick="closeModal()">&times;</span>
    <span class="modal-nav modal-prev" onclick="previousImage(event)">&#10094;</span>
    <span class="modal-nav modal-next" onclick="nextImage(event)">&#10095;</span>
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
    // Gallery images array
    const galleryImages = [
      { src: 'cafe1.jpg', caption: 'Garden Cafe' },
      { src: 'cafe2.jpg', caption: 'Garden Cafe' },
      { src: 'cafe3.jpg', caption: 'Garden Cafe' },
      { src: 'room1.jpg', caption: 'Rooms' },
      { src: 'room2.jpg', caption: 'Rooms' },
      { src: 'room3.jpg', caption: 'Rooms' },
      { src: 'garden1.jpg', caption: 'Garden Venue' },
      { src: 'garden2.jpg', caption: 'Garden Venue' },
      { src: 'garden3.jpg', caption: 'Garden Venue' }
    ];

    let currentImageIndex = 0;

    function openModal(index) {
      currentImageIndex = index;
      const modal = document.getElementById('imageModal');
      const modalImg = document.getElementById('modalImage');
      const modalCaption = document.getElementById('modalCaption');
      
      modal.classList.add('active');
      modalImg.src = galleryImages[index].src;
      modalCaption.textContent = galleryImages[index].caption;
      document.body.style.overflow = 'hidden';
    }

    function closeModal() {
      const modal = document.getElementById('imageModal');
      modal.classList.remove('active');
      document.body.style.overflow = 'auto';
    }

    function closeModalOnBackground(event) {
      if (event.target.id === 'imageModal') {
        closeModal();
      }
    }

    function nextImage(event) {
      event.stopPropagation();
      currentImageIndex = (currentImageIndex + 1) % galleryImages.length;
      const modalImg = document.getElementById('modalImage');
      const modalCaption = document.getElementById('modalCaption');
      modalImg.src = galleryImages[currentImageIndex].src;
      modalCaption.textContent = galleryImages[currentImageIndex].caption;
    }

    function previousImage(event) {
      event.stopPropagation();
      currentImageIndex = (currentImageIndex - 1 + galleryImages.length) % galleryImages.length;
      const modalImg = document.getElementById('modalImage');
      const modalCaption = document.getElementById('modalCaption');
      modalImg.src = galleryImages[currentImageIndex].src;
      modalCaption.textContent = galleryImages[currentImageIndex].caption;
    }

    // Filter functionality
    function filterGallery(category) {
      const sections = document.querySelectorAll('.gallery-section');
      const buttons = document.querySelectorAll('.filter-btn');
      
      // Update active button
      buttons.forEach(btn => btn.classList.remove('active'));
      event.target.classList.add('active');
      
      // Show/hide sections based on category
      if (category === 'all') {
        sections.forEach(section => section.classList.remove('hidden'));
      } else {
        sections.forEach(section => {
          if (section.dataset.category === category || section.dataset.category === 'all') {
            section.classList.remove('hidden');
          } else {
            section.classList.add('hidden');
          }
        });
      }
    }

    // Close modal with Escape key
    document.addEventListener('keydown', function(event) {
      if (event.key === 'Escape') {
        closeModal();
      } else if (event.key === 'ArrowRight') {
        if (document.getElementById('imageModal').classList.contains('active')) {
          nextImage(event);
        }
      } else if (event.key === 'ArrowLeft') {
        if (document.getElementById('imageModal').classList.contains('active')) {
          previousImage(event);
        }
      }
    });
  </script>
</body>
</html>