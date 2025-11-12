<?php
    $current_page = isset($current_page) ? $current_page : 'feedback';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Feedback/Testimonials - San Isidro Labrador Resort</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.6.0/css/all.min.css">
  
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f5f3f0;
      color: #3b2a18;
      overflow-x: hidden;
    }

    /* Sidebar */
    .sidebar {
      position: fixed;
      left: 0;
      top: 0;
      width: 220px;
      height: 100vh;
      background-color: #8b7d6b;
      color: white;
      overflow-y: auto;
      z-index: 1000;
      padding-bottom: 20px;
    }

    .sidebar-header {
      padding: 20px 15px;
      border-bottom: 1px solid rgba(255,255,255,0.1);
    }

    .sidebar-logo {
      display: flex;
      align-items: center;
      gap: 12px;
      margin-bottom: 15px;
    }

    .sidebar-logo-icon {
      width: 40px;
      height: 40px;
      background-color: white;
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
      overflow: hidden;
    }

    .sidebar-logo-icon img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .sidebar-logo-icon i {
      color: #8b7d6b;
      font-size: 20px;
    }

    .sidebar-title {
      font-size: 12px;
      font-weight: 600;
      line-height: 1.3;
      color: white;
    }

    .quick-add-btn {
      display: flex;
      align-items: center;
      gap: 10px;
      background-color: rgba(255,255,255,0.15);
      border: none;
      color: white;
      padding: 10px 12px;
      border-radius: 8px;
      width: 100%;
      font-size: 13px;
      font-weight: 500;
      transition: all 0.3s;
      margin-top: 15px;
      cursor: pointer;
    }

    .quick-add-btn:hover {
      background-color: rgba(255,255,255,0.25);
    }

    .quick-add-btn-icon {
      width: 28px;
      height: 28px;
      background-color: white;
      color: #8b7d6b;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 14px;
      flex-shrink: 0;
    }

    .quick-add-text {
      text-align: left;
      flex: 1;
    }

    .quick-add-text-title {
      font-weight: 600;
      font-size: 13px;
    }

    .quick-add-text-sub {
      font-size: 10px;
      opacity: 0.8;
    }

    .nav-section {
      padding: 15px 12px 10px;
    }

    .nav-section-title {
      font-size: 10px;
      font-weight: 600;
      text-transform: uppercase;
      color: rgba(255,255,255,0.5);
      margin-bottom: 8px;
      padding: 0 8px;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .nav-menu {
      list-style: none;
    }

    .nav-item {
      margin-bottom: 3px;
    }

    .nav-link {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 10px 12px;
      color: rgba(255,255,255,0.85);
      text-decoration: none;
      border-radius: 6px;
      font-size: 13px;
      font-weight: 400;
      transition: all 0.3s;
      position: relative;
    }

    .nav-link:hover {
      background-color: rgba(255,255,255,0.1);
      color: white;
      transform: translateX(3px);
    }

    .nav-link.active {
      background-color: #6d5d4d;
      color: white;
      font-weight: 600;
      box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    }

    .nav-link.active::before {
      content: '';
      position: absolute;
      left: 0;
      top: 50%;
      transform: translateY(-50%);
      width: 4px;
      height: 70%;
      background-color: white;
      border-radius: 0 4px 4px 0;
    }

    .nav-link.active i {
      color: white;
      transform: scale(1.1);
    }

    .nav-link i {
      font-size: 16px;
      width: 18px;
      text-align: center;
      transition: transform 0.3s;
    }

    /* Main Layout Container */
    .main-layout {
      margin-left: 220px;
      min-height: 100vh;
      padding: 30px 35px;
      background-color: #f5f3f0;
    }

    /* Page Header */
    .page-header {
      margin-bottom: 12px;
    }

    .page-header h1 {
      font-size: 20px;
      font-weight: 600;
      color: #3b2a18;
      margin: 0 0 8px 0;
    }

    .page-subtitle {
      font-size: 14px;
      font-weight: 400;
      color: #8b7d6b;
      margin: 0 0 30px 0;
    }

    /* Testimonials Grid */
    .testimonials-grid {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 20px;
      margin-bottom: 25px;
    }

    .testimonial-card {
      background-color: #e8dcc8;
      border-radius: 10px;
      padding: 30px 25px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.04);
      transition: all 0.3s;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      min-height: 200px;
      position: relative;
      cursor: pointer;
    }

    .testimonial-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .testimonial-quote {
      font-size: 15px;
      font-style: italic;
      color: #3b2a18;
      line-height: 1.6;
      margin-bottom: 20px;
      flex: 1;
    }

    .testimonial-author {
      font-size: 14px;
      font-weight: 600;
      color: #3b2a18;
      margin: 0;
    }

    /* Action Buttons (Hidden by default, shown on hover) */
    .testimonial-actions {
      position: absolute;
      top: 15px;
      right: 15px;
      display: flex;
      gap: 8px;
      opacity: 0;
      transition: opacity 0.3s;
    }

    .testimonial-card:hover .testimonial-actions {
      opacity: 1;
    }

    .action-btn {
      width: 32px;
      height: 32px;
      border-radius: 6px;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      transition: all 0.3s;
      border: none;
      font-size: 13px;
    }

    .btn-edit-testimonial {
      background-color: rgba(255,255,255,0.9);
      color: #3b2a18;
    }

    .btn-edit-testimonial:hover {
      background-color: #3b2a18;
      color: white;
    }

    .btn-delete-testimonial {
      background-color: rgba(217,83,79,0.9);
      color: white;
    }

    .btn-delete-testimonial:hover {
      background-color: #c9302c;
    }

    /* Add Testimonial Button */
    .add-testimonial-section {
      display: flex;
      justify-content: flex-end;
      margin-bottom: 25px;
    }

    .add-testimonial-btn {
      display: inline-flex;
      align-items: center;
      gap: 10px;
      background-color: #3b2a18;
      border: none;
      color: white;
      padding: 12px 20px;
      border-radius: 8px;
      font-size: 14px;
      font-weight: 500;
      transition: all 0.3s;
      cursor: pointer;
    }

    .add-testimonial-btn:hover {
      background-color: #2a1f12;
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    .add-testimonial-btn i {
      font-size: 16px;
    }

    /* Mobile Menu Toggle */
    .mobile-menu-toggle {
      position: fixed;
      top: 20px;
      left: 20px;
      z-index: 1001;
      background: #8b7d6b;
      color: white;
      border: none;
      width: 40px;
      height: 40px;
      border-radius: 8px;
      display: none;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      font-size: 18px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.2);
    }

    /* Responsive */
    @media (max-width: 992px) {
      .sidebar {
        transform: translateX(-100%);
        transition: transform 0.3s;
      }
      .sidebar.active {
        transform: translateX(0);
      }
      .main-layout {
        margin-left: 0;
        padding: 20px 15px;
      }
      .mobile-menu-toggle {
        display: flex;
      }
      .testimonials-grid {
        grid-template-columns: 1fr;
      }
    }

    @media (max-width: 768px) {
      .testimonial-card {
        padding: 25px 20px;
      }
    }
  </style>
</head>
<body>

  <?= $this->include('admin/sidebar') ?>

  <!-- Main Content Area -->
  <div class="main-layout">
    <!-- Page Header -->
    <div class="page-header">
      <h1>Feedback/Testimonials</h1>
      <p class="page-subtitle">See what clients say about their experience</p>
    </div>

    <!-- Add Testimonial Button -->
    <div class="add-testimonial-section">
      <button class="add-testimonial-btn" onclick="addTestimonial()">
        <i class="fas fa-plus"></i>
        Add Testimonial
      </button>
    </div>

    <!-- Testimonials Grid -->
    <div class="testimonials-grid">
      <!-- Testimonial 1 -->
      <div class="testimonial-card">
        <div class="testimonial-actions">
          <button class="action-btn btn-edit-testimonial" onclick="editTestimonial(1)">
            <i class="fas fa-edit"></i>
          </button>
          <button class="action-btn btn-delete-testimonial" onclick="deleteTestimonial(1)">
            <i class="fas fa-trash"></i>
          </button>
        </div>
        <p class="testimonial-quote">"The place is magical! Perfect for weddings and quiet retreats."</p>
        <p class="testimonial-author">Apple Template, October 10, 2025</p>
      </div>

      <!-- Testimonial 2 -->
      <div class="testimonial-card">
        <div class="testimonial-actions">
          <button class="action-btn btn-edit-testimonial" onclick="editTestimonial(2)">
            <i class="fas fa-edit"></i>
          </button>
          <button class="action-btn btn-delete-testimonial" onclick="deleteTestimonial(2)">
            <i class="fas fa-trash"></i>
          </button>
        </div>
        <p class="testimonial-quote">"A hidden gem in Batangas! The scenery was breathtaking."</p>
        <p class="testimonial-author">Earlsin Comenudo, November 1, 2025</p>
      </div>

      <!-- Testimonial 3 -->
      <div class="testimonial-card">
        <div class="testimonial-actions">
          <button class="action-btn btn-edit-testimonial" onclick="editTestimonial(3)">
            <i class="fas fa-edit"></i>
          </button>
          <button class="action-btn btn-delete-testimonial" onclick="deleteTestimonial(3)">
            <i class="fas fa-trash"></i>
          </button>
        </div>
        <p class="testimonial-quote">"Ganda yarn!?"</p>
        <p class="testimonial-author">Lady Jean, December 25, 2025</p>
      </div>

      <!-- Testimonial 4 -->
      <div class="testimonial-card">
        <div class="testimonial-actions">
          <button class="action-btn btn-edit-testimonial" onclick="editTestimonial(4)">
            <i class="fas fa-edit"></i>
          </button>
          <button class="action-btn btn-delete-testimonial" onclick="deleteTestimonial(4)">
            <i class="fas fa-trash"></i>
          </button>
        </div>
        <p class="testimonial-quote">"Perfect!"</p>
        <p class="testimonial-author">Eyngelcondense, January 13, 2025</p>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  
  <script>
    // Toggle Sidebar for Mobile
    function toggleSidebar() {
      document.getElementById('sidebar').classList.toggle('active');
    }

    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', (e) => {
      const sidebar = document.getElementById('sidebar');
      const toggle = document.querySelector('.mobile-menu-toggle');
      
      if (window.innerWidth <= 992) {
        if (!sidebar.contains(e.target) && !toggle.contains(e.target)) {
          sidebar.classList.remove('active');
        }
      }
    });

    // Add testimonial
    function addTestimonial() {
      alert('Add New Testimonial\n\nYour backend developer will implement:\n- Open modal/form\n- Input fields: Client name, Date, Quote\n- Save to database\n- Refresh testimonials grid');
    }

    // Edit testimonial
    function editTestimonial(id) {
      event.stopPropagation();
      alert(`Edit Testimonial ID: ${id}\n\nYour backend developer will implement:\n- Open edit modal\n- Pre-fill form with existing data\n- Update database\n- Refresh display`);
    }

    // Delete testimonial
    function deleteTestimonial(id) {
      event.stopPropagation();
      if (confirm('Are you sure you want to delete this testimonial?')) {
        alert(`Delete Testimonial ID: ${id}\n\nYour backend developer will implement:\n- Remove from database\n- Remove card from grid\n- Show success message`);
      }
    }

    // Add animation to testimonial cards on load
    window.addEventListener('load', () => {
      const cards = document.querySelectorAll('.testimonial-card');
      cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'all 0.5s ease';
        
        setTimeout(() => {
          card.style.opacity = '1';
          card.style.transform = 'translateY(0)';
        }, 100 + (index * 100));
      });
    });
  </script>
</body>
</html>