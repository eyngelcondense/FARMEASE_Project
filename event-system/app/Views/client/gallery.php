<?php
 $title = "Gallery | San Isidro Labrador Resort and Leisure Farm";
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
    /* Grid layout for venue images */
    .venue-grid {
  display: flex;
  flex-wrap: wrap;
  justify-content: center; /* center the row items */
  gap: 20px;
  margin-bottom: 40px;
}

.venue-grid-item {
  background-color: #fff;
  border: 2px solid #3b2a18;
  border-radius: 15px;
  box-shadow: 0 4px 8px rgba(0,0,0,0.1);
  cursor: pointer;
  overflow: hidden;
  display: flex;
  align-items: center;
  justify-content: center;
  width: 250px; /* card width */
  height: 250px; /* card height */
  position: relative;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.venue-grid-item:hover {
  transform: translateY(-5px);
  box-shadow: 0 8px 16px rgba(0,0,0,0.2);
}

.venue-grid-item img {
  max-width: 90%;
  max-height: 90%;
  object-fit: contain;
  display: block;
  margin: auto;
  transition: transform 0.3s ease;
}

.venue-grid-item:hover img {
  transform: scale(1.05);
}

.venue-grid-item::after {
  content: attr(data-name);
  position: absolute;
  bottom: 0;
  width: 100%;
  text-align: center;
  background: rgba(59, 42, 24, 0.7);
  color: #fff;
  font-weight: 500;
  padding: 10px 0;
  opacity: 0;
  transition: opacity 0.3s ease;
}

.venue-grid-item:hover::after {
  opacity: 1;
}



  /* Modal styles remain same as before */
  .modal-overlay { display: none; position: fixed; z-index: 9999; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.95); justify-content: center; align-items: center; }
  .modal-overlay.active { display: flex; }
  .modal-content-wrapper { position: relative; max-width: 90%; max-height: 90%; text-align: center; }
  .modal-image { max-width: 100%; max-height: 85vh; object-fit: contain; border-radius: 10px; }
  .modal-close { position: absolute; top: 20px; right: 40px; color: #fff; font-size: 50px; font-weight: bold; cursor: pointer; }
  .modal-caption { color: #fff; font-size: 1.2rem; margin-top: 15px; font-weight: 500; }

    /* No Images Message */
    .no-images {
      text-align: center;
      padding: 60px 20px;
      color: #7a6a58;
      font-size: 1.1rem;
    }

    .no-images i {
      font-size: 3rem;
      margin-bottom: 20px;
      display: block;
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

    /* Hide sections based on filter */
    .gallery-section.hidden {
      display: none;
    }}

  </style>


  <!-- Top Divider -->
  <div class="top-divider">
    <img src="images/divider.png" alt="Decorative Divider">
  </div>

  <!-- Gallery Hero Section -->
  <section class="gallery-hero">
    <h1>Gallery</h1>
    <p>San Isidro Labrador Resort and Leisure Farm is gearing up to be the premiere location for your once-in a lifetime event</p>
  </section>

  <!-- Filter Buttons -->
  <section class="filter-section">
    <button class="filter-btn active" onclick="filterGallery('all')">All</button>
  </section>

  <!-- Venue Sections will be dynamically loaded here -->
  <div id="venueSections"></div>

  <!-- Modal for Image Popup -->
  <div id="imageModal" class="modal-overlay" onclick="closeModalOnBackground(event)">
    <span class="modal-close" onclick="closeModal()">&times;</span>
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
    let allVenues = [];
    let currentImageIndex = 0;
    let currentVenueImages = [];

    // Get URL parameter function
    function getUrlParameter(name) {
        name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
        var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
        var results = regex.exec(location.search);
        return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
    }

    // Fetch venues and images from API
    async function loadGalleryData() {
      try {
        console.log('Loading gallery data...');
        const response = await fetch('<?= site_url('api/gallery/getVenueImages') ?>');
        const data = await response.json();
        
        console.log('API Response:', data);
        
        if (data.success) {
          allVenues = data.data;
          console.log('Venues loaded:', allVenues);
          renderVenueSections();
          autoFilterGallery(); // Auto-filter after loading data
        } else {
          showError('Failed to load gallery data: ' + (data.message || 'Unknown error'));
        }
      } catch (error) {
        console.error('Error loading gallery:', error);
        showError('Failed to load gallery. Please try again later. Error: ' + error.message);
      }
    }

    // Render venue sections
    function renderVenueSections() {
      const container = document.getElementById('venueSections');
      const filterSection = document.querySelector('.filter-section');

      if (!container || !filterSection) return;

      container.innerHTML = ''; // Clear old content

      if (!allVenues || allVenues.length === 0) {
        container.innerHTML = `<div class="no-images"><h3>No Images Available</h3></div>`;
        return;
      }

      // Build filter buttons
      let filterButtonsHTML = `<button class="filter-btn active" onclick="filterVenue('all', this)">All</button>`;
      allVenues.forEach(venue => {
        const key = venue.name.toLowerCase().replace(/\s+/g, '-');
        filterButtonsHTML += `<button class="filter-btn" data-venue-id="${venue.id}" onclick="filterVenue('${key}', this)">${venue.name}</button>`;
      });
      filterSection.innerHTML = filterButtonsHTML;

      // Build venue sections
      allVenues.forEach((venue, venueIndex) => {
        if (!venue.images || venue.images.length === 0) return;

        const key = venue.name.toLowerCase().replace(/\s+/g, '-');
        const section = document.createElement('section');
        section.className = 'gallery-section';
        section.dataset.category = key;
        section.dataset.venueId = venue.id; // Add venue ID to section

        section.innerHTML = `
          <div class="section-divider">
            <img src="images/divider.png" alt="Divider">
          </div>
          <h2 class="section-title">${venue.name}</h2>
          <div class="venue-grid">
            ${venue.images.map((img, i) => `
              <div class="venue-grid-item" data-name="${venue.name}" onclick="openModal(${venueIndex}, ${i})">
                <img src="${img.path}" alt="${venue.name}">
              </div>
            `).join('')}
          </div>
        `;

        container.appendChild(section);
      });
    }

    // Auto-filter gallery when venue parameter is present
    function autoFilterGallery() {
      const venueId = getUrlParameter('venue');
      console.log('URL venue parameter:', venueId);
      
      if (venueId) {
        // Find the venue by ID
        const venue = allVenues.find(v => v.id == venueId);
        console.log('Found venue:', venue);
        
        if (venue) {
          // Get the category key from the venue name
          const category = venue.name.toLowerCase().replace(/\s+/g, '-');
          console.log('Filtering by category:', category);
          
          // Find the corresponding filter button and click it
          const filterButtons = document.querySelectorAll('.filter-btn');
          let foundButton = null;
          
          filterButtons.forEach(button => {
            if (button.textContent.trim() === venue.name) {
              foundButton = button;
            }
          });
          
          if (foundButton) {
            console.log('Clicking filter button for:', venue.name);
            foundButton.click();
            
            // Scroll to the section after a short delay to ensure it's visible
            setTimeout(() => {
              const targetSection = document.querySelector(`[data-venue-id="${venueId}"]`);
              if (targetSection) {
                targetSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
              }
            }, 300);
          }
        }
      }
    }

    function filterVenue(category, button) {
    // Get all elements
    const sections = document.querySelectorAll('.gallery-section');
    const buttons = document.querySelectorAll('.filter-btn');
    
    // Reset all buttons
    buttons.forEach(btn => btn.classList.remove('active'));
    
    // Activate clicked button
    if (button) button.classList.add('active');
    
    // Filter sections - using multiple methods for compatibility
    sections.forEach(section => {
        const sectionCategory = section.dataset.category;
        const shouldShow = category === 'all' || sectionCategory === category;
        
        // Method 1: CSS class
        if (shouldShow) {
            section.classList.remove('hidden');
        } else {
            section.classList.add('hidden');
        }
        
        // Method 2: Direct display property (backup)
        section.style.display = shouldShow ? 'block' : 'none';
        
        // Method 3: Visibility (additional backup)
        section.style.visibility = shouldShow ? 'visible' : 'hidden';
        section.style.opacity = shouldShow ? '1' : '0';
        section.style.height = shouldShow ? 'auto' : '0';
        section.style.overflow = shouldShow ? 'visible' : 'hidden';
    });
}

    function openModal(venueIndex, imageIndex) {
      const venue = allVenues[venueIndex];
      currentVenueImages = venue.images;
      currentImageIndex = imageIndex;

      const modal = document.getElementById('imageModal');
      const modalImg = document.getElementById('modalImage');
      const modalCaption = document.getElementById('modalCaption');

      modal.classList.add('active');
      modalImg.src = currentVenueImages[currentImageIndex].path;
      modalCaption.textContent = venue.name;
      document.body.style.overflow = 'hidden';
    }

    function closeModal() {
      const modal = document.getElementById('imageModal');
      modal.classList.remove('active');
      document.body.style.overflow = 'auto';
    }

    function closeModalOnBackground(event) {
      if (event.target.id === 'imageModal') closeModal();
    }

    function nextImage() {
      currentImageIndex = (currentImageIndex + 1) % currentVenueImages.length;
      document.getElementById('modalImage').src = currentVenueImages[currentImageIndex].path;
      document.getElementById('modalCaption').textContent = allVenues.find(venue =>
        venue.images.some(img => img.id === currentVenueImages[currentImageIndex].id)
      )?.name || 'Venue Image';
    }

    function previousImage() {
      currentImageIndex = (currentImageIndex - 1 + currentVenueImages.length) % currentVenueImages.length;
      document.getElementById('modalImage').src = currentVenueImages[currentImageIndex].path;
      document.getElementById('modalCaption').textContent = allVenues.find(venue =>
        venue.images.some(img => img.id === currentVenueImages[currentImageIndex].id)
      )?.name || 'Venue Image';
    }

    document.addEventListener('keydown', function(event) {
      if (!document.getElementById('imageModal').classList.contains('active')) return;

      if (event.key === 'Escape') closeModal();
      else if (event.key === 'ArrowRight') nextImage();
      else if (event.key === 'ArrowLeft') previousImage();
    });

    // Load gallery data when page loads
    document.addEventListener('DOMContentLoaded', loadGalleryData);
</script>