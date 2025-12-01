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

    /* Package Sections */
    .package-section {
      padding: 40px 0 60px;
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

    /* Venue Cards - Centered Layout */
    .venue-cards-container {
      display: flex;
      justify-content: center;
      flex-wrap: wrap;
      gap: 2rem;
      max-width: 1200px;
      margin: 0 auto;
    }

    .venue-card {
      background-color: #e8e2d8;
      border-radius: 0;
      overflow: hidden;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      height: 100%;
      display: flex;
      flex-direction: column;
      width: 100%;
      max-width: 380px;
      min-width: 320px;
      flex: 0 1 380px;
    }

    .venue-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    }

    .venue-card img {
      width: 100%;
      height: 280px;
      object-fit: cover;
      cursor: pointer;
      transition: opacity 0.3s ease;
    }

    .venue-card img:hover {
      opacity: 0.9;
    }

    .venue-card-body {
      padding: 30px 25px;
      flex-grow: 1;
      display: flex;
      flex-direction: column;
    }

    .venue-card-body h5 {
      font-size: 1.3rem;
      font-weight: 600;
      color: #3b2a18;
      margin-bottom: 10px;
    }

    .venue-card-body p {
      font-size: 0.95rem;
      color: #5a4a3a;
      margin-bottom: 20px;
      flex-grow: 1;
    }

    /* View More button styles */
    .btn-view-more {
      background-color: #3b2a18;
      color: white;
      border: none;
      padding: 12px 30px;
      border-radius: 5px;
      font-weight: 500;
      transition: background-color 0.3s ease;
      width: 100%;
      text-decoration: none;
      display: block;
      text-align: center;
      cursor: pointer;
    }

    .btn-view-more:hover {
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

    /* Package Inclusions Section */
    .inclusions-section {
      background-color: #f8f6f3;
      padding: 60px 0 80px;
      text-align: center;
    }

    .inclusions-title {
      text-align: center;
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

    /* No Packages Message */
    .no-packages {
      text-align: center;
      padding: 60px 20px;
      color: #7a6a58;
      font-size: 1.1rem;
    }

    .no-packages i {
      font-size: 3rem;
      margin-bottom: 20px;
      display: block;
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

      .venue-card {
        min-width: 280px;
        max-width: 400px;
      }

      .venue-cards-container {
        gap: 1.5rem;
        padding: 0 15px;
      }

      .description-section {
        padding: 40px 0 30px;
      }

      .description-section p {
        font-size: 0.95rem;
      }

      .inclusions-title {
        font-size: 2rem;
        text-align: center;
      }

      .package-image-wrapper {
        margin-bottom: 20px;
      }

      .section-title {
        font-size: 2rem;
      }
    }

    @media (max-width: 576px) {
      .venue-card {
        min-width: 250px;
        max-width: 100%;
      }
      
      .venue-cards-container {
        gap: 1rem;
      }
    }

    button:focus,
    .modal-close:focus {
      outline: none;
      box-shadow: 0 0 0 3px rgba(122, 106, 88, 0.4);
    }
  </style>

  <!-- Top Divider -->
  <div class="top-divider">
    <img src="<?= base_url('images/divider.png') ?>" alt="Decorative Divider">
  </div>

  <!-- Packages Hero Section -->
  <section class="packages-hero">
    <h1>Packages</h1>
    <p>San Isidro Labrador Resort and Leisure Farm is gearing up to be the premiere location for your once-in-a-lifetime event</p>
  </section>

    <!-- Package Inclusions Section -->
<section class="inclusions-section">
  <div class="container">
    <h2 class="inclusions-title" >Package Inclusions</h2>

    <!-- ROW 1: 3 IMAGES -->
    <div class="row g-4 justify-content-center mb-3">

      <!-- Café Package -->
      <div class="col-lg-3 col-md-4 col-6">
        <div class="package-image-wrapper">
          <img src="<?= base_url('images/cafe.jpeg') ?>" class="package-img"
               onclick="openModal('<?= base_url('images/cafe.jpeg') ?>', 'Café 2nd Floor Venue')">
        </div>
      </div>

      <!-- Playground Package -->
      <div class="col-lg-3 col-md-4 col-6">
        <div class="package-image-wrapper">
          <img src="<?= base_url('images/playground.jpeg') ?>" class="package-img"
               onclick="openModal('<?= base_url('images/playground.jpeg') ?>', 'Playground')">
        </div>
      </div>

      <!-- Venue Package -->
      <div class="col-lg-3 col-md-4 col-6">
        <div class="package-image-wrapper">
          <img src="<?= base_url('images/venue.jpeg') ?>" class="package-img"
               onclick="openModal('<?= base_url('images/venue.jpeg') ?>', 'Venue')">
        </div>
      </div>

    </div>

    <!-- ROW 2: 2 IMAGES CENTERED -->
    <div class="row g-4 justify-content-center">

      <!-- Prep Package -->
      <div class="col-lg-3 col-md-4 col-6">
        <div class="package-image-wrapper">
          <img src="<?= base_url('images/prep.jpeg') ?>" class="package-img"
               onclick="openModal('<?= base_url('images/prep.jpeg') ?>', 'Prep & Photoshoot')">
        </div>
      </div>

      <!-- Meeting Package -->
      <div class="col-lg-3 col-md-4 col-6">
        <div class="package-image-wrapper">
          <img src="<?= base_url('images/meeting.jpeg') ?>" class="package-img"
               onclick="openModal('<?= base_url('images/meeting.jpeg') ?>', 'Meeting Room')">
        </div>
      </div>

    </div>

  </div>
</section>

  <!-- Package Sections will be dynamically loaded here -->
  <div id="packageSections"></div>

  <!-- Bottom Divider -->
  <div class="bottom-divider">
    <img src="<?= base_url('images/divider.png') ?>" alt="Decorative Divider">
  </div>

  <!-- Description Section -->
  <section class="description-section py-5">
    <div class="container">
      <div class="row">
        <div class="col-12">
          <p>
            At <strong>San Isidro Labrador Resort and Leisure Farm</strong>, we believe that the most beautiful celebrations begin with a setting that inspires. Surrounded by nature's calm and kissed by golden sunsets, our resort provides the ideal canvas for your most unforgettable moments.
          </p>
          <p>
            Each of our <strong>packages</strong> is crafted to bring together elegance, comfort, and personalized service. From breathtaking outdoor ceremonies and charming indoor receptions to relaxing leisure stays, we offer a complete experience designed to match your vision and personality.
          </p>
          <p>
            Whether you dream of a fairy-tale wedding, a joyful reunion, or a serene corporate retreat, our team ensures that every detail is handled with care — so you can focus on what truly matters: celebrating, connecting, and creating memories that last a lifetime.
          </p>
          <p>
            Discover how <strong>San Isidro Labrador Resort and Leisure Farm</strong> can turn your special moments into timeless stories. Because here, every celebration is more than a day — it's an experience to remember.
          </p>
        </div>
      </div>
    </div>
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
    // Global variables
    let allPackages = [];

    // Fetch packages and venues from API
    async function loadPackagesData() {
      try {
        console.log('Loading packages data...');
        const response = await fetch('<?= site_url('api/packages/getPackagesWithVenues') ?>');
        const data = await response.json();
        
        console.log('API Response:', data);
        
        if (data.success) {
          allPackages = data.data;
          console.log('Packages loaded:', allPackages);
          renderPackageSections();
        } else {
          showError('Failed to load packages data: ' + (data.message || 'Unknown error'));
        }
      } catch (error) {
        console.error('Error loading packages:', error);
        showError('Failed to load packages. Please try again later. Error: ' + error.message);
      }
    }

    // Render package sections
    function renderPackageSections() {
      const container = document.getElementById('packageSections');
      container.innerHTML = '';

      if (allPackages.length === 0) {
        container.innerHTML = `
          <div class="no-packages">
            <i class="fas fa-box-open"></i>
            <h3>No Packages Available</h3>
            <p>No packages found in the system.</p>
          </div>
        `;
        return;
      }

      allPackages.forEach((pkg, index) => {
        if (!pkg.venues || pkg.venues.length === 0) return;

        const section = document.createElement('section');
        section.className = 'package-section';
        
        section.innerHTML = `
          <div class="section-divider">
            <img src="<?= base_url('images/divider.png') ?>" alt="Decorative Divider">
          </div>
          <h2 class="section-title">${pkg.name}</h2>
          <div class="container">
            <div class="venue-cards-container">
              ${pkg.venues.map(venue => `
                <div class="venue-card">
                  <img src="${venue.image_url || '<?= base_url('images/placeholder.jpg') ?>'}" 
                       alt="${venue.name}" 
                       onerror="this.src='<?= base_url('images/placeholder.jpg') ?>'"
                       onclick="goToGallery(${venue.id})">
                  <div class="venue-card-body">
                    <h5>${venue.name}</h5>
                    <p>${venue.description || 'No description available.'}</p>
                    <button class="btn-view-more" onclick="goToGallery(${venue.id})">
                      View Gallery
                    </button>
                  </div>
                </div>
              `).join('')}
            </div>
          </div>
        `;
        container.appendChild(section);
      });
    }

    // Function to redirect to gallery page
    function goToGallery(venueId) {
      window.location.href = '<?= site_url('gallery') ?>?venue=' + venueId;
    }

    // Show error message
    function showError(message) {
      const container = document.getElementById('packageSections');
      container.innerHTML = `
        <div class="no-packages">
          <i class="fas fa-exclamation-triangle"></i>
          <h3>Error Loading Packages</h3>
          <p>${message}</p>
          <button onclick="loadPackagesData()" class="btn-view-more" style="margin-top: 20px; width: auto; display: inline-block;">
            <i class="fas fa-redo"></i> Try Again
          </button>
        </div>
      `;
    }

    // Modal functions for the inclusions section
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

    // Load packages data when page loads
    document.addEventListener('DOMContentLoaded', loadPackagesData);
</script>