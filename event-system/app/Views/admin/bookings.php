<?= $this->extend('admin/layout') ?>

<?php $title = "Bookings - San Isidro Labrador Resort"; ?>

<?= $this->section('content') ?>
    <div class="page-header-card">
        <h1>Bookings</h1>
    </div>

    <div class="filter-section d-flex flex-wrap align-items-center gap-3 mb-3">
        <div class="filter-item">
            <label for="dateFilter" class="form-label">Date:</label>
            <select class="form-select" id="dateFilter">
                <option value="">All Time</option>
                <option value="today">Today</option>
                <option value="week">This Week</option>
                <option value="month">This Month</option>
            </select>
        </div>

        <div class="filter-item">
            <label for="venueFilter" class="form-label">Venue:</label>
            <select class="form-select" id="venueFilter">
                <option value="">All Venues</option>
                <option value="enclosed">Enclosed Venue</option>
                <option value="open">Open Venue</option>
                <option value="playground">Playground</option>
                <option value="cafe">Cafe 2nd Floor</option>
            </select>
        </div>

        <div class="search-box-bookings flex-grow-1">
            <input type="text" id="searchInput" class="form-control" placeholder="Search bookings...">
        </div>

        <button class="btn btn-outline-brown" onclick="viewCalendar()">
            <i class="fas fa-calendar-alt"></i> View Calendar
        </button>
    </div>

    <div class="table-card">
        <table class="table table-striped table-bordered" id="bookingsTable" style="width:100%">
            <thead>
                <tr>
                    <th>Booking ID</th>
                    <th>Client</th>
                    <th>Venue</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr data-venue="enclosed" data-date="2025-05-25">
                    <td>001</td>
                    <td>Apple Templa</td>
                    <td>Enclosed Venue</td>
                    <td>25 May 2025</td>
                    <td>
                        <button class="btn btn-success btn-sm" onclick="approveBooking(1)">Approve</button>
                        <button class="btn btn-danger btn-sm" onclick="rejectBooking(1)">Reject</button>
                    </td>
                </tr>
                <tr data-venue="open" data-date="2025-06-12">
                    <td>002</td>
                    <td>Earlsin Combenido</td>
                    <td>Open Venue</td>
                    <td>12 June 2025</td>
                    <td>
                        <button class="btn btn-success btn-sm" onclick="approveBooking(2)">Approve</button>
                        <button class="btn btn-danger btn-sm" onclick="rejectBooking(2)">Reject</button>
                    </td>
                </tr>
                <tr data-venue="playground" data-date="2025-01-13">
                    <td>003</td>
                    <td>Jean Iwayan</td>
                    <td>Playground</td>
                    <td>13 January 2025</td>
                    <td>
                        <button class="btn btn-success btn-sm" onclick="approveBooking(3)">Approve</button>
                        <button class="btn btn-danger btn-sm" onclick="rejectBooking(3)">Reject</button>
                    </td>
                </tr>
                <tr data-venue="cafe" data-date="2025-01-21">
                    <td>004</td>
                    <td>Ryan Magnaye</td>
                    <td>Cafe 2nd Floor</td>
                    <td>21 January 2025</td>
                    <td>
                        <button class="btn btn-success btn-sm" onclick="approveBooking(4)">Approve</button>
                        <button class="btn btn-danger btn-sm" onclick="rejectBooking(4)">Reject</button>
                    </td>
                </tr>
                <tr data-venue="cafe" data-date="2025-02-13">
                    <td>005</td>
                    <td>Gilbert Bumanglag</td>
                    <td>Cafe 2nd Floor</td>
                    <td>13 February 2025</td>
                    <td>
                        <button class="btn btn-success btn-sm" onclick="approveBooking(5)">Approve</button>
                        <button class="btn btn-danger btn-sm" onclick="rejectBooking(5)">Reject</button>
                    </td>
                </tr>
                <tr data-venue="open" data-date="2025-03-25">
                    <td>006</td>
                    <td>Johnmoreen Rol</td>
                    <td>Open Venue</td>
                    <td>25 March 2025</td>
                    <td>
                        <button class="btn btn-success btn-sm" onclick="approveBooking(6)">Approve</button>
                        <button class="btn btn-danger btn-sm" onclick="rejectBooking(6)">Reject</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    // Initialize DataTable
    var table = $('#bookingsTable').DataTable({
        "order": [[3, "desc"]], // sort by date descending
        "columnDefs": [
            { "orderable": false, "targets": 4 } // actions not sortable
        ]
    });

    // Custom filtering
    $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
        var venueFilter = $('#venueFilter').val();
        var dateFilter = $('#dateFilter').val();
        var row = $('#bookingsTable tbody tr').eq(dataIndex);
        var rowVenue = row.data('venue');
        var rowDate = new Date(row.data('date'));
        var today = new Date();

        // Venue filter
        if (venueFilter && rowVenue !== venueFilter) return false;

        // Date filter
        if (dateFilter) {
            if (dateFilter === 'today' && rowDate.toDateString() !== today.toDateString()) return false;
            if (dateFilter === 'week') {
                var weekAgo = new Date(today.getTime() - 7*24*60*60*1000);
                if (rowDate < weekAgo || rowDate > today) return false;
            }
            if (dateFilter === 'month') {
                if (rowDate.getMonth() !== today.getMonth() || rowDate.getFullYear() !== today.getFullYear()) return false;
            }
        }
        return true;
    });

    // Trigger filters on change
    $('#venueFilter, #dateFilter').on('change', function() {
        table.draw();
    });

    // Search input
    $('#searchInput').on('keyup', function() {
        table.search(this.value).draw();
    });

});

// Approve / Reject functions
function approveBooking(id) {
    if (confirm(`Approve booking #${String(id).padStart(3,'0')}?`)) {
        alert(`Booking #${String(id).padStart(3,'0')} approved!`);
    }
}

function rejectBooking(id) {
    const reason = prompt(`Reject booking #${String(id).padStart(3,'0')}?\nReason:`);
    if (reason) {
        alert(`Booking #${String(id).padStart(3,'0')} rejected.\nReason: ${reason}`);
    }
}

function viewCalendar() {
    alert('This will open the calendar of events.');
}
</script>
<?= $this->endSection() ?>
