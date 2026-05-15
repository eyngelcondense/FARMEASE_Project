<?php
$current_page = $current_page ?? 'assign-booking';
$page_title = $title ?? 'Assign Staff - San Isidro Labrador Resort';
$bookings = $bookings ?? [];
$staffs = $staffs ?? [];
$booking = $booking ?? null;
$availabilityMatrix = $availabilityMatrix ?? [];
$selectedBookingId = $selectedBookingId ?? 0;
$availableStaffCount = $availableStaffCount ?? 0;
$totalStaffCount = $totalStaffCount ?? 0;
?>
<?= $this->extend('staff/layout_sidebar') ?>
<?= $this->section('content') ?>

<header class="top-header">
    <div class="welcome-section">
        <div class="admin-avatar">
            <i class="fas fa-user-plus"></i>
        </div>
        <div class="welcome-text">
            <h2>Assign Staff to Booking</h2>
            <p>Choose a booking, then allocate the staff members who are available on that date.</p>
        </div>
    </div>
    <div class="header-actions">
        <a href="<?= site_url('staff/assignments') ?>" class="icon-btn" title="View assignments">
            <i class="fas fa-clipboard-list"></i>
        </a>
    </div>
</header>

<div class="dashboard-content">
    <div class="page-header">
        <h1 class="page-title">Booking Assignment</h1>
        <div class="gold-line"></div>
        <p class="page-subtitle">Pick an open booking and assign one or more available staff members.</p>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('warning')): ?>
        <div class="alert alert-warning"><?= esc(session()->getFlashdata('warning')) ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach ((array) session()->getFlashdata('errors') as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="stats-row">
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-calendar-check"></i></div>
            <div class="stat-info"><h3>Open bookings</h3><p><?= count($bookings) ?></p></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-user-clock"></i></div>
            <div class="stat-info"><h3>Available staff</h3><p><?= $availableStaffCount ?></p></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-users"></i></div>
            <div class="stat-info"><h3>Total staff</h3><p><?= $totalStaffCount ?></p></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-tasks"></i></div>
            <div class="stat-info"><h3>Selected booking</h3><p><?= $selectedBookingId ? 'Ready' : 'None' ?></p></div>
        </div>
    </div>

    <?php if (empty($bookings)): ?>
        <div class="empty-state card p-5 text-center">
            <div class="empty-icon"><i class="fas fa-calendar-times"></i></div>
            <div class="empty-title">No bookings ready for assignment</div>
            <div class="empty-sub">There are no confirmed or approved bookings without staff yet.</div>
        </div>
    <?php else: ?>
        <div class="row g-4">
            <div class="col-lg-5">
                <div class="card h-100">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="fas fa-calendar-alt text-primary"></i> Select Booking</h5>
                    </div>
                    <div class="card-body">
                        <label for="booking_id" class="form-label fw-semibold">Booking</label>
                        <select id="booking_id" name="booking_id" class="form-select mb-3">
                            <?php foreach ($bookings as $row): ?>
                                <option value="<?= (int) $row['id'] ?>" <?= (int) $row['id'] === (int) $selectedBookingId ? 'selected' : '' ?>>
                                    <?= esc($row['booking_reference']) ?> - <?= esc($row['event_type']) ?> (<?= date('M d, Y', strtotime($row['event_date'])) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>

                        <div id="booking-summary" class="border rounded-3 p-3 bg-light mb-3"></div>

                        <div class="small text-muted">
                            Only staff marked available for the selected booking date can be assigned.
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="card h-100">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-user-friends text-success"></i> Available Staff</h5>
                        <span class="badge bg-success" id="available-staff-badge"><?= $availableStaffCount ?> available</span>
                    </div>
                    <div class="card-body">
                        <form action="<?= site_url('staff/assignToBooking') ?>" method="POST" id="assignment-form">
                            <?= csrf_field() ?>
                            <input type="hidden" name="booking_id" id="booking_id_input" value="<?= (int) $selectedBookingId ?>">

                            <div class="mb-3">
                                <label for="staff_ids" class="form-label fw-semibold">Staff Members</label>
                                <select id="staff_ids" name="staff_ids[]" class="form-select" multiple size="10" required>
                                    <?php foreach ($staffs as $staff): ?>
                                        <option value="<?= (int) $staff['id'] ?>" data-staff-id="<?= (int) $staff['id'] ?>" data-base-label="<?= esc($staff['name']) ?> - <?= esc(ucwords(str_replace('_', ' ', $staff['role']))) ?>" <?= ! empty($staff['is_available']) ? '' : 'disabled' ?> <?= ! empty($staff['is_available']) ? 'selected' : '' ?>>
                                            <?= esc($staff['name']) ?> - <?= ucwords(str_replace('_', ' ', $staff['role'])) ?><?= ! empty($staff['is_available']) ? ' (available)' : ' (unavailable)' ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="form-text">Hold Cmd/Ctrl to choose more than one staff member.</div>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="role" class="form-label fw-semibold">Assignment Role</label>
                                    <select id="role" name="role" class="form-select">
                                        <option value="event_coordinator">Event Coordinator</option>
                                        <option value="front_desk">Front Desk</option>
                                        <option value="customer_service">Customer Service</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="notes" class="form-label fw-semibold">Notes</label>
                                    <input id="notes" name="notes" class="form-control" placeholder="Optional assignment notes">
                                </div>
                            </div>

                            <div class="d-flex gap-2 mt-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-check"></i> Assign Selected Staff
                                </button>
                                <a href="<?= site_url('staff/assignments') ?>" class="btn btn-outline-secondary">
                                    View Assignments
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php if (! empty($bookings)): ?>
    <script>
        const bookingAvailability = <?= json_encode($availabilityMatrix, JSON_UNESCAPED_SLASHES) ?>;
        const bookingData = <?= json_encode($bookings, JSON_UNESCAPED_SLASHES) ?>;
        const staffSelect = document.getElementById('staff_ids');
        const bookingSelect = document.getElementById('booking_id');
        const bookingInput = document.getElementById('booking_id_input');
        const bookingSummary = document.getElementById('booking-summary');
        const availableBadge = document.getElementById('available-staff-badge');

        function renderBookingSummary(booking) {
            if (!booking) {
                bookingSummary.innerHTML = '<div class="text-muted">No booking selected.</div>';
                return;
            }

            bookingSummary.innerHTML = `
                <div class="fw-semibold mb-2">${booking.booking_reference} - ${booking.event_type}</div>
                <div class="small text-muted mb-1"><i class="fas fa-user"></i> ${booking.client_fullname || 'Client'}</div>
                <div class="small text-muted mb-1"><i class="fas fa-map-marker-alt"></i> ${booking.venue_name || 'Venue'}</div>
                <div class="small text-muted mb-1"><i class="fas fa-calendar"></i> ${booking.event_date}</div>
                <div class="small text-muted"><i class="fas fa-clock"></i> ${booking.start_time} - ${booking.end_time}</div>
            `;
        }

        function updateStaffAvailability() {
            const bookingId = parseInt(bookingSelect.value, 10);
            const booking = bookingData.find(item => parseInt(item.id, 10) === bookingId);
            const availableIds = bookingAvailability[bookingId] || [];
            let availableCount = 0;

            bookingInput.value = bookingId;
            renderBookingSummary(booking);

            Array.from(staffSelect.options).forEach(option => {
                const staffId = parseInt(option.dataset.staffId || option.value, 10);
                const baseLabel = option.dataset.baseLabel || option.text.replace(' (available)', '').replace(' (unavailable)', '');
                const isAvailable = availableIds.includes(staffId);

                option.disabled = !isAvailable;
                option.text = baseLabel + (isAvailable ? ' (available)' : ' (unavailable)');

                if (!isAvailable) {
                    option.selected = false;
                } else {
                    availableCount++;
                }
            });

            availableBadge.textContent = `${availableCount} available`;

            const selectedOptions = Array.from(staffSelect.selectedOptions).filter(option => !option.disabled);
            if (!selectedOptions.length) {
                const firstAvailable = Array.from(staffSelect.options).find(option => !option.disabled);
                if (firstAvailable) {
                    firstAvailable.selected = true;
                }
            }
        }

        bookingSelect.addEventListener('change', updateStaffAvailability);
        updateStaffAvailability();
    </script>
<?php endif; ?>

<?= $this->endSection() ?>