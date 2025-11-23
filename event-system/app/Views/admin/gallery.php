<?php $current_page = $current_page ?? 'gallery'; ?>
<?= $this->extend('admin/layout') ?>

<?= $this->section('title') ?>
    <?= $title ?? 'Venue Gallery - San Isidro Labrador Resort' ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Venue Gallery</h1>
    </div>

    <!-- Upload Section -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Upload Venue Images</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Select Venue</label>
                    <select class="form-control" id="venueSelect" required>
                        <option value="">Choose a venue...</option>
                        <?php foreach ($venues as $venue): ?>
                            <option value="<?= $venue['id'] ?>"><?= esc($venue['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="upload-area border-dashed rounded p-4 text-center mb-3" 
                 id="uploadArea" 
                 style="border: 2px dashed #dee2e6; background: #f8f9fa; cursor: pointer;"
                 onclick="document.getElementById('fileInput').click()">
                <div class="upload-icon mb-2">
                    <i class="fas fa-cloud-upload-alt fa-2x text-muted"></i>
                </div>
                <p class="upload-text mb-1">Click or drag images to upload</p>
                <p class="upload-subtext text-muted small mb-0">Supports JPG, PNG, WEBP, GIF (Max: 5MB per image)</p>
                <input type="file" id="fileInput" class="d-none" multiple accept="image/*">
            </div>

            <div id="selectedFiles" class="d-none">
                <h6>Selected Files:</h6>
                <div id="fileList" class="d-flex flex-wrap gap-2 mb-3"></div>
            </div>

            <button class="btn btn-primary" onclick="uploadImages()" id="uploadBtn" disabled>
                <i class="fas fa-upload me-1"></i> Upload Images
            </button>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <label class="form-label">Filter by Venue</label>
                    <select class="form-control" id="venueFilter" onchange="filterGallery()">
                        <option value="all">All Venues</option>
                        <?php foreach ($venues as $venue): ?>
                            <option value="<?= $venue['id'] ?>"><?= esc($venue['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Search</label>
                    <input type="text" class="form-control" id="searchInput" placeholder="Search images...">
                </div>
            </div>
        </div>
    </div>

    <!-- Gallery Grid -->
    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Venue Images</h6>
        </div>
        <div class="card-body">
            <div id="galleryGrid">
                <?= $this->include('admin/gallery_items') ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>

<script>
let selectedFiles = [];

// Initialize when page loads
document.addEventListener('DOMContentLoaded', function() {
    // File input event listener
    document.getElementById('fileInput').addEventListener('change', handleFileSelect);
    
    // Drag and drop functionality
    const uploadArea = document.getElementById('uploadArea');
    
    ['dragover', 'dragenter'].forEach(event => {
        uploadArea.addEventListener(event, (e) => {
            e.preventDefault();
            uploadArea.style.borderColor = '#007bff';
            uploadArea.style.background = '#e7f3ff';
        });
    });

    ['dragleave', 'dragend'].forEach(event => {
        uploadArea.addEventListener(event, (e) => {
            e.preventDefault();
            uploadArea.style.borderColor = '#dee2e6';
            uploadArea.style.background = '#f8f9fa';
        });
    });

    uploadArea.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadArea.style.borderColor = '#dee2e6';
        uploadArea.style.background = '#f8f9fa';
        const files = e.dataTransfer.files;
        handleFiles(files);
    });

    // Search functionality
    document.getElementById('searchInput').addEventListener('input', searchGallery);
    
    // Venue select change
    document.getElementById('venueSelect').addEventListener('change', updateUploadButton);
});

// File handling
function handleFileSelect(event) {
    const files = event.target.files;
    handleFiles(files);
}

function handleFiles(files) {
    if (files.length > 0) {
        selectedFiles = Array.from(files);
        displaySelectedFiles();
        updateUploadButton();
    }
}

function displaySelectedFiles() {
    const fileList = document.getElementById('fileList');
    const selectedFilesDiv = document.getElementById('selectedFiles');
    
    fileList.innerHTML = '';
    
    selectedFiles.forEach((file, index) => {
        // Validate file type and size
        if (!validateFile(file)) {
            return;
        }
        
        const fileItem = document.createElement('div');
        fileItem.className = 'file-item bg-light rounded px-3 py-2 d-flex align-items-center mb-2';
        fileItem.innerHTML = `
            <span class="file-name me-2">${file.name}</span>
            <span class="file-size text-muted me-2">(${(file.size / 1024 / 1024).toFixed(2)} MB)</span>
            <button type="button" class="btn-remove btn btn-sm btn-outline-danger" onclick="removeFile(${index})">
                <i class="fas fa-times"></i>
            </button>
        `;
        fileList.appendChild(fileItem);
    });
    
    if (selectedFiles.length > 0) {
        selectedFilesDiv.classList.remove('d-none');
    } else {
        selectedFilesDiv.classList.add('d-none');
    }
}

function validateFile(file) {
    const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp', 'image/gif'];
    const maxSize = 5 * 1024 * 1024; // 5MB
    
    if (!validTypes.includes(file.type)) {
        showAlert('error', `File "${file.name}" is not a supported image type.`);
        return false;
    }
    
    if (file.size > maxSize) {
        showAlert('error', `File "${file.name}" exceeds 5MB size limit.`);
        return false;
    }
    
    return true;
}

function removeFile(index) {
    selectedFiles.splice(index, 1);
    displaySelectedFiles();
    updateUploadButton();
}

function updateUploadButton() {
    const venueSelect = document.getElementById('venueSelect');
    const uploadBtn = document.getElementById('uploadBtn');
    uploadBtn.disabled = !(venueSelect.value && selectedFiles.length > 0);
}

// AJAX Functions
function uploadImages() {
    const venueId = document.getElementById('venueSelect').value;
    
    if (!venueId) {
        showAlert('error', 'Please select a venue first.');
        return;
    }
    
    if (selectedFiles.length === 0) {
        showAlert('error', 'Please select at least one image to upload.');
        return;
    }

    const formData = new FormData();
    
    formData.append('venue_id', venueId);
    selectedFiles.forEach(file => {
        formData.append('images[]', file);
    });

    const uploadBtn = document.getElementById('uploadBtn');
    const originalText = uploadBtn.innerHTML;
    uploadBtn.disabled = true;
    uploadBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Uploading...';

    fetch('<?= site_url('admin/gallery/upload') ?>', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            showAlert('success', data.message);
            // Reset form
            selectedFiles = [];
            document.getElementById('fileInput').value = '';
            document.getElementById('selectedFiles').classList.add('d-none');
            document.getElementById('venueSelect').value = '';
            
            // Update gallery
            if (data.data) {
                document.getElementById('galleryGrid').innerHTML = data.data;
            } else {
                refreshGallery();
            }
        } else {
            showAlert('error', data.message || 'Upload failed. Please try again.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('error', 'Upload failed. Please try again.');
    })
    .finally(() => {
        uploadBtn.disabled = false;
        uploadBtn.innerHTML = originalText;
    });
}

function toggleImageStatus(id) {
    fetch('<?= site_url('admin/gallery/toggle/') ?>' + id, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('success', data.message);
            if (data.data) {
                document.getElementById('galleryGrid').innerHTML = data.data;
            }
        } else {
            showAlert('error', data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('error', 'Failed to update image status');
    });
}

function deleteImage(id) {
    if (confirm('Are you sure you want to delete this image? This action cannot be undone.')) {
        fetch('<?= site_url('admin/gallery/delete/') ?>' + id, {
            method: 'DELETE',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('success', data.message);
                if (data.data) {
                    document.getElementById('galleryGrid').innerHTML = data.data;
                }
            } else {
                showAlert('error', data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('error', 'Failed to delete image');
        });
    }
}

function filterGallery() {
    const venueId = document.getElementById('venueFilter').value;
    const cards = document.querySelectorAll('.gallery-card');
    
    cards.forEach(card => {
        const cardVenueId = card.getAttribute('data-venue-id');
        if (venueId === 'all' || cardVenueId === venueId) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
}

function searchGallery() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const cards = document.querySelectorAll('.gallery-card');
    
    cards.forEach(card => {
        const venueName = card.getAttribute('data-venue-name').toLowerCase();
        if (venueName.includes(searchTerm)) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
}

function showAlert(type, message) {
    const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert ${alertClass} alert-dismissible fade show`;
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    const container = document.querySelector('.container-fluid');
    container.insertBefore(alertDiv, container.firstChild);
    
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 5000);
}

// Refresh gallery
function refreshGallery() {
    fetch('<?= site_url('admin/gallery/getGallery') ?>', {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.data) {
            document.getElementById('galleryGrid').innerHTML = data.data;
        }
    })
    .catch(error => {
        console.error('Error refreshing gallery:', error);
    });
}
</script>

<style>
.upload-area:hover {
    border-color: #007bff !important;
    background: #e7f3ff !important;
}

.file-item {
    border: 1px solid #dee2e6;
}

.gallery-card {
    transition: transform 0.2s ease;
}

.gallery-card:hover {
    transform: translateY(-2px);
}

.card-img-top {
    height: 200px;
    object-fit: cover;
}
</style>
<?= $this->endSection() ?>