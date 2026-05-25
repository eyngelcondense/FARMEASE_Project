<?php $current_page = $current_page ?? 'studios'; ?>
<?= $this->extend('admin/layout') ?>

<?php $title = "Studio Management - San Isidro Labrador Resort"; ?>

<?= $this->section('content') ?>
<style>
    .filter-section {
        background-color: #f5f0eb;
        padding: 15px 20px;
        border-radius: 10px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.08);
    }

    .table-card {
        background-color: #fff7f0;
        border-radius: 12px;
        padding: 15px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.08);
        margin-bottom: 20px;
    }

    .table th {
        background-color: #f0e6dc;
        color: #5c3a21;
    }

    .section-title {
        color: #5c3a21;
        border-bottom: 2px solid #7c6a43;
        padding-bottom: 10px;
        margin-bottom: 15px;
    }

    .badge.bg-success { background-color: #3a5c39 !important; color: white !important; }
    .badge.bg-danger { background-color: #8c2e0b !important; color: white !important; }
    .badge.bg-warning { background-color: #b58a4a !important; color: white !important; }
    .badge.bg-info { background-color: #4a6b8a !important; color: white !important; }
    .badge.bg-primary { background-color: #5c3a21 !important; color: white !important; }

    .badge-available { background-color: #3a5c39; color: white; }
    .badge-busy { background-color: #b58a4a; color: white; }

    .btn-action {
        padding: 4px 8px;
        font-size: 12px;
        margin: 2px;
        transition: all 0.2s ease;
    }
    
    .btn-outline-primary {
        color: #5c3a21;
        border-color: #5c3a21;
    }
    
    .btn-outline-primary:hover {
        background-color: #5c3a21;
        color: white;
    }

    .studio-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        margin-bottom: 20px;
    }

    .stat-card {
        background: linear-gradient(135deg, #5c3a21, #7c6a43);
        color: white;
        padding: 20px;
        border-radius: 10px;
        text-align: center;
    }

    .stat-number {
        font-size: 2rem;
        font-weight: bold;
    }

    .stat-label {
        font-size: 0.9rem;
        opacity: 0.9;
    }

    .studio-card {
        border-left: 4px solid #5c3a21;
        background: #fff7f0;
        padding: 15px;
        margin-bottom: 15px;
        border-radius: 5px;
        transition: all 0.3s ease;
    }

    .studio-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .studio-name {
        font-size: 1.2rem;
        font-weight: bold;
        color: #5c3a21;
        margin-bottom: 5px;
    }

    .studio-details {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 10px;
    }

    .studio-info {
        flex: 1;
    }

    .studio-info-item {
        margin: 3px 0;
        font-size: 0.9rem;
    }

    .studio-actions {
        display: flex;
        gap: 5px;
    }

    .pricing-info {
        background: #f0e6dc;
        padding: 10px;
        border-radius: 5px;
        margin-top: 10px;
    }

    .calendar-preview {
        background: white;
        border-radius: 10px;
        padding: 15px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .calendar-day {
        display: inline-block;
        width: 30px;
        height: 30px;
        line-height: 30px;
        text-align: center;
        margin: 2px;
        border-radius: 50%;
        font-size: 12px;
        cursor: pointer;
    }

    .calendar-day.booked {
        background-color: #b58a4a;
        color: white;
    }

    .calendar-day.available {
        background-color: #3a5c39;
        color: white;
    }

    .calendar-day.today {
        border: 2px solid #5c3a21;
        font-weight: bold;
    }
</style>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="section-title mb-0">Studio Management</h2>
        <div>
            <button class="btn btn-primary" onclick="addStudio()">
                <i class="fas fa-plus"></i> Add Studio
            </button>
            <button class="btn btn-outline-primary" onclick="viewCalendar()">
                <i class="fas fa-calendar-alt"></i> Calendar View
            </button>
        </div>
    </div>

    <!-- Studio Statistics -->
    <div class="studio-stats">
        <div class="stat-card">
            <div class="stat-number" id="totalStudios">-</div>
            <div class="stat-label">Total Studios</div>
        </div>
        <div class="stat-card">
            <div class="stat-number" id="totalBookings">-</div>
            <div class="stat-label">Total Bookings</div>
        </div>
        <div class="stat-card">
            <div class="stat-number" id="todayBookings">-</div>
            <div class="stat-label">Today's Bookings</div>
        </div>
        <div class="stat-card">
            <div class="stat-number" id="totalRevenue">-</div>
            <div class="stat-label">Total Revenue</div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="filter-section">
        <div class="row">
            <div class="col-md-3">
                <label>Search Studio:</label>
                <input type="text" id="searchStudio" class="form-control" placeholder="Name or Location">
            </div>
            <div class="col-md-2">
                <label>Capacity:</label>
                <select id="capacityFilter" class="form-select">
                    <option value="">All Capacities</option>
                    <option value="10">10+ people</option>
                    <option value="20">20+ people</option>
                    <option value="50">50+ people</option>
                    <option value="100">100+ people</option>
                </select>
            </div>
            <div class="col-md-2">
                <label>Status:</label>
                <select id="statusFilter" class="form-select">
                    <option value="">All Status</option>
                    <option value="available">Available</option>
                    <option value="busy">Busy</option>
                </select>
            </div>
            <div class="col-md-2">
                <label>Max Price:</label>
                <input type="number" id="priceFilter" class="form-control" placeholder="Max hourly rate">
            </div>
            <div class="col-md-3">
                <label>&nbsp;</label>
                <div class="btn-group w-100">
                    <button class="btn btn-outline-primary" onclick="applyFilters()">
                        <i class="fas fa-filter"></i> Apply
                    </button>
                    <button class="btn btn-outline-secondary" onclick="resetFilters()">
                        <i class="fas fa-undo"></i> Reset
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- View Toggle -->
    <div class="mb-3">
        <div class="btn-group" role="group">
            <button type="button" class="btn btn-outline-primary active" onclick="showGridView()">
                <i class="fas fa-th"></i> Grid View
            </button>
            <button type="button" class="btn btn-outline-primary" onclick="showTableView()">
                <i class="fas fa-list"></i> Table View
            </button>
        </div>
    </div>

    <!-- Studios Grid View -->
    <div id="studiosGrid" class="row">
        <!-- Studio cards will be loaded here -->
    </div>

    <!-- Studios Table View -->
    <div id="studiosTable" class="table-card" style="display: none;">
        <div class="table-responsive">
            <table id="studioTable" class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Location</th>
                        <th>Capacity</th>
                        <th>Hourly Rate</th>
                        <th>Status</th>
                        <th>Bookings</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="studioTableBody">
                    <!-- Studio data will be loaded here -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add/Edit Studio Modal -->
<div class="modal fade" id="studioModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="studioModalTitle">Add Studio</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="studioForm">
                    <input type="hidden" id="studioId">
                    <div class="mb-3">
                        <label for="studioName" class="form-label">Studio Name *</label>
                        <input type="text" class="form-control" id="studioName" required>
                    </div>
                    <div class="mb-3">
                        <label for="studioLocation" class="form-label">Location</label>
                        <input type="text" class="form-control" id="studioLocation">
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="studioCapacity" class="form-label">Capacity</label>
                                <input type="number" class="form-control" id="studioCapacity" min="1">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="studioCost" class="form-label">Hourly Rate (₱)</label>
                                <input type="number" class="form-control" id="studioCost" min="0" step="0.01">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="studioUser" class="form-label">Manager</label>
                        <select class="form-select" id="studioUser">
                            <option value="">Select Manager (Optional)</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="saveStudio()">Save Studio</button>
            </div>
        </div>
    </div>
</div>

<!-- Studio Details Modal -->
<div class="modal fade" id="studioDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="studioDetailsTitle">Studio Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="studioDetailsContent">
                    <!-- Studio details will be loaded here -->
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const studioApiBase = '<?= site_url('studio-management/api') ?>'.replace(/^https?:\/\/[^/]+/i, '');

$(document).ready(function() {
    loadStudios();
    loadStudioStats();
});

// Load studio data
function loadStudios() {
    $.ajax({
        url: `${studioApiBase}/studio/list`,
        method: 'GET',
        success: function(response) {
            renderStudioGrid(response);
            renderStudioTable(response);
        },
        error: function() {
            showNotification('Error loading studio data', 'error');
        }
    });
}

// Load studio statistics
function loadStudioStats() {
    $.ajax({
        url: `${studioApiBase}/stats`,
        method: 'GET',
        success: function(response) {
            const totalStudios = Number(response.total_studios || 0);
            const totalBookings = Number(response.total_bookings || 0);
            const todayBookings = Number(response.today_bookings || 0);
            const totalRevenue = Number(response.total_revenue || 0);

            $('#totalStudios').text(totalStudios);
            $('#totalBookings').text(totalBookings);
            $('#todayBookings').text(todayBookings);
            $('#totalRevenue').text('₱' + totalRevenue.toFixed(2));
        },
        error: function() {
            $('#totalStudios').text(0);
            $('#totalBookings').text(0);
            $('#todayBookings').text(0);
            $('#totalRevenue').text('₱0.00');
            showNotification('Error loading studio statistics', 'error');
        }
    });
}

// Render studio grid view
function renderStudioGrid(studios) {
    let grid = $('#studiosGrid');
    grid.empty();
    
    studios.forEach(function(studio) {
        let statusBadge = getStatusBadge(studio.availability_status);
        
        grid.append(`
            <div class="col-md-6 col-lg-4 mb-3">
                <div class="studio-card">
                    <div class="studio-name">${studio.name}</div>
                    <div class="studio-info">
                        <div class="studio-info-item">
                            <i class="fas fa-map-marker-alt"></i> ${studio.location || 'No location specified'}
                        </div>
                        <div class="studio-info-item">
                            <i class="fas fa-users"></i> Capacity: ${studio.capacity || 'N/A'}
                        </div>
                        <div class="studio-info-item">
                            <i class="fas fa-clock"></i> ₱${parseFloat(studio.cost || 0).toFixed(2)}/hour
                        </div>
                        <div class="studio-info-item">
                            <i class="fas fa-calendar-check"></i> ${studio.booking_count || 0} bookings
                        </div>
                    </div>
                    <div class="studio-details">
                        <div class="studio-info">
                            ${statusBadge}
                        </div>
                        <div class="studio-actions">
                            <button class="btn btn-sm btn-outline-primary btn-action" onclick="viewStudioDetails(${studio.id})">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-info btn-action" onclick="editStudio(${studio.id})">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-success btn-action" onclick="viewBookings(${studio.id})">
                                <i class="fas fa-calendar"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `);
    });
}

// Render studio table view
function renderStudioTable(studios) {
    let tbody = $('#studioTableBody');
    tbody.empty();
    
    studios.forEach(function(studio) {
        let statusBadge = getStatusBadge(studio.availability_status);
        
        tbody.append(`
            <tr>
                <td>${studio.id}</td>
                <td>${studio.name}</td>
                <td>${studio.location || 'N/A'}</td>
                <td>${studio.capacity || 'N/A'}</td>
                <td>₱${parseFloat(studio.cost || 0).toFixed(2)}</td>
                <td>${statusBadge}</td>
                <td>
                    <span class="badge bg-info">${studio.booking_count || 0}</span>
                </td>
                <td>
                    <button class="btn btn-sm btn-outline-primary btn-action" onclick="viewStudioDetails(${studio.id})">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-info btn-action" onclick="editStudio(${studio.id})">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-success btn-action" onclick="viewBookings(${studio.id})">
                        <i class="fas fa-calendar"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-danger btn-action" onclick="deleteStudio(${studio.id})">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `);
    });
}

// Helper functions
function getStatusBadge(status) {
    const badges = {
        'available': '<span class="badge badge-available">Available</span>',
        'busy': '<span class="badge badge-busy">Busy</span>'
    };
    return badges[status] || '<span class="badge bg-secondary">Unknown</span>';
}

// View toggle functions
function showGridView() {
    $('#studiosGrid').show();
    $('#studiosTable').hide();
    $('.btn-group button').removeClass('active');
    $('.btn-group button:first').addClass('active');
}

function showTableView() {
    $('#studiosGrid').hide();
    $('#studiosTable').show();
    $('.btn-group button').removeClass('active');
    $('.btn-group button:last').addClass('active');
}

// Modal functions
function addStudio() {
    $('#studioModalTitle').text('Add Studio');
    $('#studioForm')[0].reset();
    $('#studioId').val('');
    new bootstrap.Modal(document.getElementById('studioModal')).show();
}

function editStudio(id) {
    $.ajax({
        url: `${studioApiBase}/studio/${id}`,
        method: 'GET',
        success: function(studio) {
            $('#studioModalTitle').text('Edit Studio');
            $('#studioId').val(studio.id);
            $('#studioName').val(studio.name);
            $('#studioLocation').val(studio.location || '');
            $('#studioCapacity').val(studio.capacity || '');
            $('#studioCost').val(studio.cost || '');
            $('#studioUser').val(studio.user_id || '');
            new bootstrap.Modal(document.getElementById('studioModal')).show();
        }
    });
}

function saveStudio() {
    const studioId = $('#studioId').val();
    const studioData = {
        name: $('#studioName').val(),
        location: $('#studioLocation').val(),
        capacity: $('#studioCapacity').val(),
        cost: $('#studioCost').val(),
        user_id: $('#studioUser').val()
    };

    const url = studioId ? `${studioApiBase}/studio/${studioId}` : `${studioApiBase}/studio`;
    const method = studioId ? 'PUT' : 'POST';

    $.ajax({
        url: url,
        method: method,
        data: JSON.stringify(studioData),
        contentType: 'application/json',
        success: function() {
            bootstrap.Modal.getInstance(document.getElementById('studioModal')).hide();
            loadStudios();
            loadStudioStats();
            showNotification('Studio saved successfully', 'success');
        },
        error: function() {
            showNotification('Error saving studio', 'error');
        }
    });
}

function viewStudioDetails(id) {
    $.ajax({
        url: `${studioApiBase}/studio/${id}`,
        method: 'GET',
        success: function(studio) {
            let content = `
                <div class="row">
                    <div class="col-md-6">
                        <h6>Studio Information</h6>
                        <p><strong>Name:</strong> ${studio.name}</p>
                        <p><strong>Location:</strong> ${studio.location || 'N/A'}</p>
                        <p><strong>Capacity:</strong> ${studio.capacity || 'N/A'} people</p>
                        <p><strong>Hourly Rate:</strong> ₱${parseFloat(studio.cost || 0).toFixed(2)}</p>
                    </div>
                    <div class="col-md-6">
                        <h6>Booking Information</h6>
                        <p><strong>Total Bookings:</strong> ${studio.booking_count || 0}</p>
                        <p><strong>Status:</strong> ${getStatusBadge(studio.availability_status)}</p>
                    </div>
                </div>
                <div class="mt-3">
                    <h6>Upcoming Bookings</h6>
                    <div id="upcomingBookings">
                        Loading...
                    </div>
                </div>
            `;
            
            $('#studioDetailsTitle').text(studio.name);
            $('#studioDetailsContent').html(content);
            
            // Load upcoming bookings
            loadUpcomingBookings(id);
            
            new bootstrap.Modal(document.getElementById('studioDetailsModal')).show();
        }
    });
}

function loadUpcomingBookings(studioId) {
    $.ajax({
        url: `${studioApiBase}/booking/studio/${studioId}`,
        method: 'GET',
        success: function(bookings) {
            let container = $('#upcomingBookings');
            
            if (bookings.length === 0) {
                container.html('<p class="text-muted">No upcoming bookings</p>');
                return;
            }
            
            let bookingsHtml = bookings.slice(0, 5).map(function(booking) {
                return `
                    <div class="alert alert-info">
                        <strong>${booking.booking_reference}</strong><br>
                        ${booking.event_type} - ${formatDate(booking.event_date)}<br>
                        ${booking.start_time} - ${booking.end_time}<br>
                        <small>Client: ${booking.client_name}</small>
                    </div>
                `;
            }).join('');
            
            container.html(bookingsHtml);
        }
    });
}

function viewBookings(id) {
    // This could open a detailed bookings view for the studio
    window.location.href = `/studio-management/studio/${id}/bookings`;
}

function deleteStudio(id) {
    if (confirm('Are you sure you want to delete this studio? This action cannot be undone.')) {
        $.ajax({
            url: `${studioApiBase}/studio/${id}`,
            method: 'DELETE',
            success: function() {
                loadStudios();
                loadStudioStats();
                showNotification('Studio deleted successfully', 'success');
            },
            error: function() {
                showNotification('Error deleting studio', 'error');
            }
        });
    }
}

function viewCalendar() {
    // This could open a calendar view showing all studio bookings
    window.location.href = '/admin/calendar';
}

function applyFilters() {
    // Implement filter logic
    loadStudios();
}

function resetFilters() {
    $('#searchStudio').val('');
    $('#capacityFilter').val('');
    $('#statusFilter').val('');
    $('#priceFilter').val('');
    loadStudios();
}

function formatDate(dateString) {
    const options = { year: 'numeric', month: 'short', day: 'numeric' };
    return new Date(dateString).toLocaleDateString(undefined, options);
}

function showNotification(message, type) {
    console.log(`${type}: ${message}`);
}
</script>
<?= $this->endSection() ?>
