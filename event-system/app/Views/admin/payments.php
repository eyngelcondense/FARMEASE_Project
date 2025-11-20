<?= $this->extend('admin/layout') ?>

<?php $title = "Payments - San Isidro Labrador Resort"; ?>

<?= $this->section('content') ?>
    <div class="page-header-card">
        <h1>Payments</h1>
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
            <label for="statusFilter" class="form-label">Status:</label>
            <select class="form-select" id="statusFilter">
                <option value="">All Payments</option>
                <option value="paid">Paid</option>
                <option value="pending">Pending</option>
                <option value="refunded">Refunded</option>
            </select>
        </div>

        <div class="search-box-payments flex-grow-1">
            <input type="text" id="searchInput" class="form-control" placeholder="Search payments...">
        </div>
    </div>

    <div class="table-card">
        <table class="table table-striped table-bordered" id="paymentsTable" style="width:100%">
            <thead>
                <tr>
                    <th>Payment ID</th>
                    <th>Client</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr data-status="paid" data-date="2025-05-25">
                    <td>001</td>
                    <td>Apple Templa</td>
                    <td>25 May 2025</td>
                    <td><span class="badge bg-success">Paid</span></td>
                    <td>Php 15,000</td>
                </tr>
                <tr data-status="pending" data-date="2025-06-12">
                    <td>002</td>
                    <td>Earlsin Combenido</td>
                    <td>12 June 2025</td>
                    <td><span class="badge bg-warning text-dark">Pending</span></td>
                    <td>Php 10,000</td>
                </tr>
                <tr data-status="refunded" data-date="2025-01-13">
                    <td>003</td>
                    <td>Jean Iwayan</td>
                    <td>13 January 2025</td>
                    <td><span class="badge bg-danger">Refunded</span></td>
                    <td>Php 5,000</td>
                </tr>
                <tr data-status="paid" data-date="2025-01-21">
                    <td>004</td>
                    <td>Ryan Magnaye</td>
                    <td>21 January 2025</td>
                    <td><span class="badge bg-success">Paid</span></td>
                    <td>Php 20,000</td>
                </tr>
                <tr data-status="pending" data-date="2025-02-13">
                    <td>005</td>
                    <td>Gilbert Bumanglag</td>
                    <td>13 February 2025</td>
                    <td><span class="badge bg-warning text-dark">Pending</span></td>
                    <td>Php 20,000</td>
                </tr>
                <tr data-status="pending" data-date="2025-03-25">
                    <td>006</td>
                    <td>Johnmoreen Rol</td>
                    <td>25 March 2025</td>
                    <td><span class="badge bg-warning text-dark">Pending</span></td>
                    <td>Php 10,000</td>
                </tr>
            </tbody>
        </table>
    </div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    // Initialize DataTable
    var table = $('#paymentsTable').DataTable({
        "order": [[2, "desc"]], // sort by date descending
        "columnDefs": [
            { "orderable": false, "targets": [3,4] } // status and amount not sortable
        ]
    });

    // Custom filtering
    $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
        var status = $('#statusFilter').val();
        var dateFilter = $('#dateFilter').val();
        var row = $('#paymentsTable tbody tr').eq(dataIndex);
        var rowStatus = row.data('status');
        var rowDate = new Date(row.data('date'));
        var today = new Date();

        // Status filter
        if (status && rowStatus !== status) return false;

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
    $('#statusFilter, #dateFilter').on('change', function() {
        table.draw();
    });

    // Search input
    $('#searchInput').on('keyup', function() {
        table.search(this.value).draw();
    });

    // Row click alert (replace later with modal)
    $('#paymentsTable tbody').on('click', 'tr', function() {
        var paymentId = $(this).find('td:eq(0)').text();
        var clientName = $(this).find('td:eq(1)').text();
        var amount = $(this).find('td:eq(4)').text();
        var status = $(this).data('status');

        alert(`Payment Details:\n\nPayment ID: ${paymentId}\nClient: ${clientName}\nAmount: ${amount}\nStatus: ${status.toUpperCase()}`);
    });
});
</script>
<?= $this->endSection() ?>
