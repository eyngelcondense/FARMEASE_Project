<?php $current_page = $current_page ?? 'gallery'; ?>

<?= $this->extend('admin/layout') ?>

<?= $this->section('title') ?>
    <?= $title ?? 'Gallery - San Isidro Labrador Resort' ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="container-fluid">

    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div class="page-header-card">
            <h1>Gallery</h1>
        </div>
    </div>

    <!-- Upload Section -->
    <div class="upload-section mb-4">
        <div class="upload-area" id="uploadArea" onclick="document.getElementById('fileInput').click()">
            <div class="upload-icon">
                <i class="fas fa-cloud-upload-alt"></i>
            </div>
            <p class="upload-text">Click or Drag to Upload Images</p>
            <input type="file" id="fileInput" class="upload-input" multiple accept="image/*" onchange="handleFileSelect(event)">
        </div>

        <div class="filter-upload-group mt-3">
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
                <i class="fas fa-upload"></i> UPLOAD
            </button>
        </div>
    </div>

    <!-- Gallery Grid -->
    <div class="gallery-grid" id="galleryGrid">
        <!-- Your gallery items remain the same -->
        <?= $this->include('admin/gallery_items') ?>
    </div>

</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>

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
<?= $this->endSection() ?>