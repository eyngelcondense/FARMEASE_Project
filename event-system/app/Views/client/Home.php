<?php
 $title = "Welcome | San Isidro Labrador Resort and Leisure Farm";
 include ('header.php');
?>



  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f8f6f3;
      color: #3b2a18;
      overflow-x: hidden;
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

    /* About Section Button */
    .hidden-content {
      animation: fadeIn 0.5s ease-in;
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(-10px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .btn:hover {
      background-color: #a07d54 !important;
      transition: background-color 0.3s ease;
    }

  </style>


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
    <a href="<?= site_url('packages') ?>" 
      class="btn btn-dark <?= (service('uri')->getSegment(1) == 'packages') ? 'active' : '' ?>">
      View Packages
    </a>
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
        
        <!-- Visible Content -->
        <div class="visible-content">
          <p class="mt-3">
            A serene haven nestled amidst nature's beauty — perfect for those seeking relaxation, celebration, and meaningful experiences. Whether you're planning a wedding, family outing, or corporate retreat, our resort offers a unique blend of elegance and tranquility.
          </p>
          <p>
            Set against the backdrop of lush greenery and open skies, San Isidro Labrador provides an idyllic escape from the hustle and bustle of city life. Our beautifully landscaped grounds feature charming event spaces, from our elegant chandelier-lit pavilions to our picturesque outdoor gardens, each thoughtfully designed to create unforgettable moments.
          </p>
        </div>

        <!-- Hidden Content -->
        <div class="hidden-content" id="moreContent" style="display: none;">
          <p>
            We pride ourselves on delivering exceptional service and attention to detail, ensuring every event is tailored to your vision. Our versatile venues can accommodate intimate gatherings and grand celebrations alike, with modern amenities seamlessly integrated into our natural setting. From romantic weddings under the stars to productive business meetings in our exclusive café spaces, we provide the perfect atmosphere for every occasion.
          </p>
          <p>
            Experience the warmth of countryside hospitality combined with refined sophistication. At San Isidro Labrador, we don't just host events — we create memories that last a lifetime. Discover why countless guests have chosen us as their destination for life's most precious moments.
          </p>
        </div>

        <!-- Toggle Button -->
        <button class="btn mt-3" id="toggleBtn" onclick="toggleContent()" 
                style="background-color: #b8956a; color: white; padding: 10px 30px; border: none; border-radius: 5px;">
          Read More
        </button>
      </div>
      
      <div class="col-lg-6 mt-4 mt-lg-0">
        <img src="images/pic 2.jpg" alt="Resort" class="img-fluid rounded">
      </div>
    </div>
  </div>
</section>

 <!-- JavaScript for Toggle -->
<script>
function toggleContent() {
  const moreContent = document.getElementById('moreContent');
  const btn = document.getElementById('toggleBtn');
  
  if (moreContent.style.display === 'none') {
    moreContent.style.display = 'block';
    btn.textContent = 'See Less';
  } else {
    moreContent.style.display = 'none';
    btn.textContent = 'Read More';
    // Optional: Scroll back to the section
    document.querySelector('.about-section').scrollIntoView({ behavior: 'smooth', block: 'start' });
  }
}
</script>

  <!-- Booking Section -->
  <section class="booking-section py-4">
    <div class="container d-flex justify-content-between align-items-center flex-wrap">
      <div>
        <h3 class="fw-bold mb-1">Book your preferred date in advance</h3>
        <p class="mb-0 text-muted"><strong>"At San Isidro, Where Nature Meets Grandeur"</strong></p>
      </div>
      <a href="<?= site_url('contact') ?>" 
        class="btn btn-book-now <?= (service('uri')->getSegment(1) == 'contact') ? 'active' : '' ?>">
        BOOK NOW
      </a>
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

  <?php
    include ('footer.php');
  ?>

