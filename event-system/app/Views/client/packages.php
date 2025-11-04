<?php
 $title = "Packages | San Isidro Labrador Resort and Leisure Farm";
 include ('header.php');
?>

  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f8f6f3;
      color: #3b2a18;
      overflow-x: hidden;
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

    button:focus,
    .modal-close:focus {
      outline: none;
      box-shadow: 0 0 0 3px rgba(122, 106, 88, 0.4); /* subtle brown glow */
    }

  </style>


  <!-- Top Divider -->
  <div class="top-divider">
    <img src="images/divider.png" alt="Decorative Divider">
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
            <img src="images/san isidroweas.jpg" alt="Package 1">
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
            <img src="images/wed_eventspic.jpg" alt="Package 2">
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
            <img src="images/priv_gathetingspic.jpg" alt="Package 3">
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
    <img src="images/divider.png" alt="Decorative Divider">
  </div>

 <!-- Description Section -->
<section class="description-section py-5">
  <div class="container">
    <div class="row">
      <div class="col-12">
        <p>
          At <strong>San Isidro Labrador Resort and Leisure Farm</strong>, we believe that the most beautiful celebrations begin with a setting that inspires. Surrounded by nature’s calm and kissed by golden sunsets, our resort provides the ideal canvas for your most unforgettable moments.
        </p>
        <p>
          Each of our <strong>packages</strong> is crafted to bring together elegance, comfort, and personalized service. From breathtaking outdoor ceremonies and charming indoor receptions to relaxing leisure stays, we offer a complete experience designed to match your vision and personality.
        </p>
        <p>
          Whether you dream of a fairy-tale wedding, a joyful reunion, or a serene corporate retreat, our team ensures that every detail is handled with care — so you can focus on what truly matters: celebrating, connecting, and creating memories that last a lifetime.
        </p>
        <p>
          Discover how <strong>San Isidro Labrador Resort and Leisure Farm</strong> can turn your special moments into timeless stories. Because here, every celebration is more than a day — it’s an experience to remember.
        </p>
      </div>
    </div>
  </div>
</section>



  <!-- Logo Section -->
  <section class="logo-section">
    <div class="container">
      <img src="images/divider.png" alt="Decorative Divider">
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
            <img src="images/cafe.jpeg" alt="Café Package" class="package-img" onclick="openModal('images/cafe.jpeg', 'Café 2nd Floor Venue')">
          </div>
        </div>

        <!-- Playground Package -->
        <div class="col-lg-2 col-md-4 col-6">
          <div class="package-image-wrapper">
            <img src="images/playground.jpeg" alt="Playground Package" class="package-img" onclick="openModal('images/playground.jpeg', 'Playground')">
          </div>
        </div>

        <!-- Venue Package -->
        <div class="col-lg-2 col-md-4 col-6">
          <div class="package-image-wrapper">
            <img src="images/venue.jpeg" alt="Venue Package" class="package-img" onclick="openModal('images/venue.jpeg', 'Venue')">
          </div>
        </div>

        <!-- Prep Package -->
        <div class="col-lg-2 col-md-4 col-6">
          <div class="package-image-wrapper">
            <img src="images/prep.jpeg" alt="Prep & Photoshoot Package" class="package-img" onclick="openModal('images/prep.jpeg', 'Prep & Photoshoot')">
          </div>
        </div>

        <!-- Meeting Package -->
        <div class="col-lg-2 col-md-4 col-6">
          <div class="package-image-wrapper">
            <img src="images/meeting.jpeg" alt="Meeting Package" class="package-img" onclick="openModal('images/meeting.jpeg', 'Meeting Room')">
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

  <?php
    include ('footer.php');
  ?>

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
