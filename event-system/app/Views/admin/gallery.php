<?php
    $current_page = isset($current_page) ? $current_page : 'gallery';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gallery - San Isidro Labrador Resort</title>
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

    /* Page Header Card */
    .page-header-card {
      background-color: white;
      border-radius: 10px;
      padding: 22px 25px;
      margin-bottom: 22px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.04);
    }

    .page-header-card h1 {
      font-size: 20px;
      font-weight: 600;
      color: #3b2a18;
      margin: 0;
    }

    /* Upload Section */
    .upload-section {
      display: flex;
      gap: 20px;
      margin-bottom: 30px;
      flex-wrap: wrap;
    }

    .upload-area {
      flex: 1;
      min-width: 300px;
      background-color: #faf8f5;
      border: 2px dashed #d4cfc5;
      border-radius: 10px;
      padding: 50px 30px;
      text-align: center;
      cursor: pointer;
      transition: all 0.3s;
    }

    .upload-area:hover {
      border-color: #8b7d6b;
      background-color: #f5f3f0;
    }

    .upload-area.dragover {
      border-color: #8b7d6b;
      background-color: #e8e3db;
    }

    .upload-icon {
      width: 80px;
      height: 80px;
      margin: 0 auto 20px;
      background-color: white;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }

    .upload-icon i {
      font-size: 32px;
      color: #8b7d6b;
    }

    .upload-text {
      font-size: 15px;
      font-weight: 500;
      color: #3b2a18;
      margin: 0;
    }

    .upload-input {
      display: none;
    }

    .filter-upload-group {
      display: flex;
      flex-direction: column;
      gap: 15px;
      min-width: 250px;
    }

    .category-filter {
      display: flex;
      flex-direction: column;
      gap: 8px;
    }

    .filter-label {
      font-size: 13px;
      font-weight: 500;
      color: #3b2a18;
    }

    .filter-dropdown {
      padding: 10px 35px 10px 15px;
      border: 1px solid #d4cfc5;
      border-radius: 8px;
      background-color: white;
      font-size: 13px;
      color: #3b2a18;
      cursor: pointer;
      appearance: none;
      background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%233b2a18' d='M6 8L2 4h8z'/%3E%3C/svg%3E");
      background-repeat: no-repeat;
      background-position: right 12px center;
      transition: all 0.3s;
    }

    .filter-dropdown:focus {
      outline: none;
      border-color: #8b7d6b;
    }

    .upload-btn {
      padding: 12px 24px;
      background-color: #3b2a18;
      color: white;
      border: none;
      border-radius: 8px;
      font-size: 14px;
      font-weight: 500;
      cursor: pointer;
      transition: all 0.3s;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
    }

    .upload-btn:hover {
      background-color: #2a1f12;
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    /* Gallery Grid */
    .gallery-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
      gap: 20px;
    }

    .gallery-card {
      background-color: white;
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 2px 6px rgba(0,0,0,0.04);
      transition: all 0.3s;
      position: relative;
    }

    .gallery-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .gallery-image {
      width: 100%;
      height: 200px;
      object-fit: cover;
      display: block;
    }

    .gallery-info {
      padding: 15px;
    }

    .gallery-title {
      font-size: 14px;
      font-weight: 500;
      color: #3b2a18;
      margin: 0 0 10px 0;
    }

    .gallery-footer {
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    /* Category Badges */
    .category-badge {
      display: inline-block;
      padding: 5px 12px;
      border-radius: 6px;
      font-size: 11px;
      font-weight: 500;
    }

    .badge-corporate {
      background-color: #8b7d6b;
      color: white;
    }

    .badge-birthday {
      background-color: #7a9cc6;
      color: white;
    }

    .badge-wedding {
      background-color: #d4a5a5;
      color: white;
    }

    /* Action Icons */
    .action-icons {
      display: flex;
      gap: 8px;
      opacity: 0;
      transition: opacity 0.3s;
    }

    .gallery-card:hover .action-icons {
      opacity: 1;
    }

    .icon-action {
      width: 32px;
      height: 32px;
      border-radius: 6px;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      transition: all 0.3s;
      border: none;
    }

    .icon-edit {
      background-color: #f5f3f0;
      color: #3b2a18;
    }

    .icon-edit:hover {
      background-color: #3b2a18;
      color: white;
    }

    .icon-delete {
      background-color: #f8d7da;
      color: #721c24;
    }

    .icon-delete:hover {
      background-color: #d9534f;
      color: white;
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
      .gallery-grid {
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
      }
    }

    @media (max-width: 768px) {
      .upload-section {
        flex-direction: column;
      }
      .gallery-grid {
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>
<body>

  <?= $this->include('admin/sidebar') ?>

  <!-- Main Content Area -->
  <div class="main-layout">
    <!-- Page Header Card -->
    <div class="page-header-card">
      <h1>Gallery</h1>
    </div>

    <!-- Upload Section -->
    <div class="upload-section">
      <div class="upload-area" id="uploadArea" onclick="document.getElementById('fileInput').click()">
        <div class="upload-icon">
          <i class="fas fa-cloud-upload-alt"></i>
        </div>
        <p class="upload-text">Click or Drag to Upload Images</p>
        <input type="file" id="fileInput" class="upload-input" multiple accept="image/*" onchange="handleFileSelect(event)">
      </div>

      <div class="filter-upload-group">
        <div class="category-filter">
          <label class="filter-label">Category</label>
          <select class="filter-dropdown" id="categoryFilter" onchange="filterGallery()">
            <option value="all">All Categories</option>
            <option value="corporate">Corporate</option>
            <option value="birthday">Birthdays</option>
            <option value="wedding">Weddings</option>
          </select>
        </div>
        <button class="upload-btn" onclick="uploadImages()">
          <i class="fas fa-upload"></i>
          UPLOAD
        </button>
      </div>
    </div>

    <!-- Gallery Grid -->
    <div class="gallery-grid" id="galleryGrid">
      <!-- Gallery Item 1 -->
      <div class="gallery-card" data-category="corporate">
        <img src="room2.jpg" alt="Corporate Presentation" class="gallery-image">
        <div class="gallery-info">
          <h3 class="gallery-title">Corporate Presenta...</h3>
          <div class="gallery-footer">
            <span class="category-badge badge-corporate">Corporate</span>
            <div class="action-icons">
              <button class="icon-action icon-edit" onclick="editImage(1)">
                <i class="fas fa-edit"></i>
              </button>
              <button class="icon-action icon-delete" onclick="deleteImage(1)">
                <i class="fas fa-trash"></i>
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Gallery Item 2 -->
      <div class="gallery-card" data-category="birthday">
        <img src="bday.jpg" alt="Birthday Party" class="gallery-image">
        <div class="gallery-info">
          <h3 class="gallery-title">Birthday Party</h3>
          <div class="gallery-footer">
            <span class="category-badge badge-birthday">Birthdays</span>
            <div class="action-icons">
              <button class="icon-action icon-edit" onclick="editImage(2)">
                <i class="fas fa-edit"></i>
              </button>
              <button class="icon-action icon-delete" onclick="deleteImage(2)">
                <i class="fas fa-trash"></i>
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Gallery Item 3 -->
      <div class="gallery-card" data-category="wedding">
        <img src="weddings.jpg" alt="Wedding Ceremony" class="gallery-image">
        <div class="gallery-info">
          <h3 class="gallery-title">Wedding Ceremony</h3>
          <div class="gallery-footer">
            <span class="category-badge badge-wedding">Weddings</span>
            <div class="action-icons">
              <button class="icon-action icon-edit" onclick="editImage(3)">
                <i class="fas fa-edit"></i>
              </button>
              <button class="icon-action icon-delete" onclick="deleteImage(3)">
                <i class="fas fa-trash"></i>
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Gallery Item 4 -->
      <div class="gallery-card" data-category="corporate">
        <img src="stairs.jpg" alt="Corporate Agreement" class="gallery-image">
        <div class="gallery-info">
          <h3 class="gallery-title">Corporate Agreement</h3>
          <div class="gallery-footer">
            <span class="category-badge badge-corporate">Corporate</span>
            <div class="action-icons">
              <button class="icon-action icon-edit" onclick="editImage(4)">
                <i class="fas fa-edit"></i>
              </button>
              <button class="icon-action icon-delete" onclick="deleteImage(4)">
                <i class="fas fa-trash"></i>
              </button>
            </div>
          </div>
        </div>
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

    // Drag and Drop functionality
    const uploadArea = document.getElementById('uploadArea');

    uploadArea.addEventListener('dragover', (e) => {
      e.preventDefault();
      uploadArea.classList.add('dragover');
    });

    uploadArea.addEventListener('dragleave', () => {
      uploadArea.classList.remove('dragover');
    });

    uploadArea.addEventListener('drop', (e) => {
      e.preventDefault();
      uploadArea.classList.remove('dragover');
      const files = e.dataTransfer.files;
      handleFiles(files);
    });

    // Handle file select
    function handleFileSelect(event) {
      const files = event.target.files;
      handleFiles(files);
    }

    // Handle files
    function handleFiles(files) {
      if (files.length > 0) {
        alert(`${files.length} file(s) selected for upload.\n\nYour backend developer will implement:\n- Image preview\n- File validation (size, type)\n- Upload progress bar\n- Store in database with category`);
      }
    }

    // Upload images
    function uploadImages() {
      const fileInput = document.getElementById('fileInput');
      const category = document.getElementById('categoryFilter').value;
      
      if (fileInput.files.length === 0) {
        alert('Please select images to upload first!');
        return;
      }

      alert(`Uploading ${fileInput.files.length} image(s) to category: ${category}\n\nYour backend developer will implement:\n- Upload to server\n- Save file paths in database\n- Create thumbnails\n- Refresh gallery grid`);
      
      // Reset file input
      fileInput.value = '';
    }

    // Filter gallery
    function filterGallery() {
      const filter = document.getElementById('categoryFilter').value;
      const cards = document.querySelectorAll('.gallery-card');

      cards.forEach(card => {
        const category = card.getAttribute('data-category');
        if (filter === 'all' || category === filter) {
          card.style.display = 'block';
        } else {
          card.style.display = 'none';
        }
      });
    }

    // Edit image
    function editImage(id) {
      alert(`Edit Image ID: ${id}\n\nYour backend developer will implement:\n- Open edit modal\n- Update image title\n- Change category\n- Replace image`);
    }

    // Delete image
    function deleteImage(id) {
      if (confirm('Are you sure you want to delete this image?')) {
        alert(`Delete Image ID: ${id}\n\nYour backend developer will implement:\n- Remove from database\n- Delete file from server\n- Remove from gallery grid`);
      }
    }

    // Add animation to gallery cards on load
    window.addEventListener('load', () => {
      const cards = document.querySelectorAll('.gallery-card');
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