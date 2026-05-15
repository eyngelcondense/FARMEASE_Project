<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>

<style>
    /* Color Variables */
    :root {
        --primary: #5c3a21;
        --primary-light: #7a4b2a;
        --primary-dark: #4a2f1a;
        --secondary: #8b7355;
        --success: #3a5c39;
        --danger: #8c2e0b;
        --warning: #b58a4a;
        --info: #4a6b8a;
        --light: #f0e6dc;
        --dark: #2c1a0d;
        --beige: #f5f0eb;
        --light-beige: #fff7f0;
    }

    /* Page Header */
    .content-header h1 {
        color: var(--primary);
        font-weight: 700;
    }

    /* Card Styling */
    .card {
        border: 1px solid var(--light);
        border-radius: 10px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.08);
        margin-bottom: 20px;
    }

    .card-header {
        background-color: var(--beige);
        border-bottom: 1px solid var(--light);
        padding: 15px 20px;
    }

    .card-title {
        color: var(--primary);
        font-weight: 600;
        margin: 0;
    }

    /* Buttons */
    .btn-primary {
        background-color: var(--primary);
        border-color: var(--primary);
    }

    .btn-primary:hover {
        background-color: var(--primary-dark);
        border-color: var(--primary-dark);
    }

    .btn-outline-primary {
        color: var(--primary);
        border-color: var(--primary);
    }

    .btn-outline-primary:hover {
        background-color: var(--primary);
        border-color: var(--primary);
    }

    .btn-info {
        background-color: var(--info);
        border-color: var(--info);
    }

    .btn-success {
        background-color: var(--success);
        border-color: var(--success);
    }

    .btn-warning {
        background-color: var(--warning);
        border-color: var(--warning);
        color: #fff;
    }

    .btn-danger {
        background-color: var(--danger);
        border-color: var(--danger);
    }

    .btn-secondary {
        background-color: var(--secondary);
        border-color: var(--secondary);
    }

    /* Form Controls */
    .form-control:focus, 
    .custom-select:focus,
    .form-control:focus,
    .custom-select:focus {
        border-color: var(--primary-light);
        box-shadow: 0 0 0 0.2rem rgba(92, 58, 33, 0.25);
    }

    .custom-file-label::after {
        background-color: var(--primary);
        color: #fff;
        border-color: var(--primary);
    }

    /* Template Buttons */
    .template-btn {
        margin-right: 5px;
        margin-bottom: 5px;
    }

    .template-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    /* Alert Styling */
    .alert {
        border: none;
        border-left: 4px solid transparent;
    }

    .alert-danger {
        background-color: #f8e2e2;
        border-left-color: var(--danger);
        color: var(--danger);
    }

    /* Code Styling */
    code {
        background-color: #f5f5f5;
        padding: 2px 4px;
        border-radius: 3px;
        color: var(--danger);
    }

    /* Contract Textarea */
    .contract-textarea {
        font-family: 'Courier New', monospace;
        line-height: 1.6;
        min-height: 150px;
    }

    /* Card Footer */
    .card-footer {
        background-color: var(--beige);
        border-top: 1px solid var(--light);
        padding: 15px 20px;
    }

    /* Formatting Help Section */
    .card-info {
        border-color: var(--info);
    }

    .card-info > .card-header {
        background-color: rgba(74, 107, 138, 0.1);
        border-bottom-color: var(--info);
    }
</style>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Create New Contract</h1>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h3 class="card-title m-0">Contract Details</h3>
                            
                            </div>
                        </div>

                        <form action="<?= base_url('admin/contracts/store') ?>" method="post" id="contractForm">
                            <?= csrf_field() ?>
                            
                            <div class="card-body">
                                <?php if (session()->getFlashdata('errors')): ?>
                                    <div class="alert alert-danger">
                                        <ul class="mb-0">
                                            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                                <li><?= $error ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                <?php endif; ?>

                                <?php if (session()->getFlashdata('error')): ?>
                                    <div class="alert alert-danger">
                                        <?= session()->getFlashdata('error') ?>
                                    </div>
                                <?php endif; ?>

                                <!-- Contract Template Section at Top -->
                                <div class="card card-secondary mb-4">
                                    <div class="card-header">
                                        <h3 class="card-title"><i class="fas fa-magic mr-2"></i>Quick Templates</h3>
                                    </div>
                                    <div class="card-body">
                                        <p class="mb-3">Select a template to pre-fill the contract content:</p>
                                        <div class="btn-group flex-wrap">
                                            <button type="button" class="btn btn-outline-primary template-btn mb-2" data-template="wedding">
                                                <i class="fas fa-ring"></i> Wedding Event
                                            </button>
                                            <button type="button" class="btn btn-outline-primary template-btn mb-2" data-template="corporate">
                                                <i class="fas fa-briefcase"></i> Corporate Event
                                            </button>
                                            <button type="button" class="btn btn-outline-primary template-btn mb-2" data-template="birthday">
                                                <i class="fas fa-birthday-cake"></i> Birthday Party
                                            </button>
                                            <button type="button" class="btn btn-outline-primary template-btn mb-2" data-template="generic">
                                                <i class="fas fa-file-contract"></i> Generic Event
                                            </button>
                                        </div>
                                        <div class="mt-2">
                                            <small class="text-muted">
                                                <i class="fas fa-info-circle"></i> Templates will populate the content below.
                                            </small>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="booking_id">Select Booking *</label>
                                            <select class="form-control" id="booking_id" name="booking_id" required>
                                                <option value="">Select a booking</option>
                                                <?php foreach ($bookings as $booking): ?>
                                                    <option value="<?= $booking['id'] ?>" 
                                                            data-client="<?= $booking['client_name'] ?>"
                                                            data-event-date="<?= $booking['event_date'] ?>"
                                                            data-event-type="<?= $booking['event_type'] ?>"
                                                            data-venue="<?= $booking['venue_name'] ?>"
                                                            data-package="<?= $booking['package_name'] ?>"
                                                            data-amount="<?= number_format($booking['total_amount'], 2) ?>">
                                                        <?= $booking['booking_reference'] ?> - 
                                                        <?= $booking['client_name'] ?> - 
                                                        <?= date('M j, Y', strtotime($booking['event_date'])) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                            <small class="form-text text-muted">Only approved bookings without existing contracts are shown</small>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="title">Contract Title *</label>
                                            <input type="text" class="form-control" id="title" name="title" 
                                                   placeholder="e.g., Event Service Agreement" required
                                                   value="<?= old('title') ?>">
                                        </div>
                                    </div>
                                </div>

                                <!-- Booking Details Preview -->
                                <div class="row mb-3" id="bookingDetails" style="display: none;">
                                    <div class="col-12">
                                        <div class="card card-info">
                                            <div class="card-header">
                                                <h3 class="card-title">Booking Details</h3>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <strong>Client:</strong>
                                                        <span id="previewClient"></span>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <strong>Event Date:</strong>
                                                        <span id="previewEventDate"></span>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <strong>Event Type:</strong>
                                                        <span id="previewEventType"></span>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <strong>Venue:</strong>
                                                        <span id="previewVenue"></span>
                                                    </div>
                                                </div>
                                                <div class="row mt-2">
                                                    <div class="col-md-3">
                                                        <strong>Package:</strong>
                                                        <span id="previewPackage"></span>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <strong>Total Amount:</strong>
                                                        <span id="previewAmount" class="text-success"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="content">Contract Content *</label>
                                    <textarea class="form-control contract-textarea" id="content" name="content" rows="15" 
                                              placeholder="Enter the main contract content here..." required><?= old('content') ?></textarea>
                                    <small class="form-text text-muted">
                                        You can use the following placeholders: {client_name}, {event_date}, {venue_name}, {package_name}, {total_amount}
                                    </small>
                                </div>

                                <div class="form-group">
                                    <label for="terms_conditions">Terms & Conditions *</label>
                                    <textarea class="form-control contract-textarea" id="terms_conditions" name="terms_conditions" rows="8" 
                                              placeholder="Enter terms and conditions..." required><?= old('terms_conditions') ?></textarea>
                                </div>

                                <!-- Formatting Help -->
                                <div class="card card-info mt-4">
                                    <div class="card-header">
                                        <h3 class="card-title"><i class="fas fa-question-circle mr-2"></i>Formatting Tips</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <strong>Basic Formatting:</strong>
                                                <ul class="mb-0">
                                                    <li>Use empty lines to separate paragraphs</li>
                                                    <li>Use <code>-</code> for bullet points</li>
                                                    <li>Use <code>1.</code>, <code>2.</code> for numbered lists</li>
                                                </ul>
                                            </div>
                                            <div class="col-md-6">
                                                <strong>Available Placeholders:</strong>
                                                <ul class="mb-0">
                                                    <li><code>{client_name}</code> - Client's full name</li>
                                                    <li><code>{event_date}</code> - Event date</li>
                                                    <li><code>{venue_name}</code> - Venue name</li>
                                                    <li><code>{package_name}</code> - Package name</li>
                                                    <li><code>{total_amount}</code> - Total amount</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer d-flex justify-content-between">
                                <div>
                                    <button type="submit" class="btn btn-primary" id="submitBtn">
                                        <i class="fas fa-save"></i> Create Contract
                                    </button>
                                    <button type="button" class="btn btn-outline-danger" id="cancelBtn">
                                    <i class="fas fa-times"></i> Cancel
                                </button>
                                </div>
                                
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?= $this->section('scripts') ?>
<!-- SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    // Define templates
    const templates = {
        'wedding': {
            'content': `This Agreement is made and entered into on {event_date} between San Isidro Labrador Resort (hereinafter referred to as "Service Provider") and {client_name} (hereinafter referred to as "Client").

ARTICLE 1: SERVICES PROVIDED
The Service Provider agrees to provide wedding venue and package services at {venue_name} including but not limited to:
- Use of designated venue space
- Basic sound system setup
- Standard tables and chairs
- Basic decoration as per package

ARTICLE 2: PAYMENT TERMS
The total contract amount is ₱{total_amount}. Payment schedule:
- 50% upon signing this contract
- 50% 30 days before the event

ARTICLE 3: CANCELLATION POLICY
- Cancellation 90+ days before: 80% refund
- Cancellation 60-89 days before: 50% refund
- Cancellation 30-59 days before: 25% refund
- Cancellation less than 30 days: No refund`,
            'terms': `1. Client is responsible for any damages to venue property
2. Service Provider is not liable for force majeure events
3. Event must conclude by agreed end time
4. Additional overtime charges apply beyond contracted hours
5. Client must provide final guest count 7 days before event`
        },
        'corporate': {
            'content': `CORPORATE EVENT AGREEMENT

This Corporate Event Agreement (the "Agreement") is made effective as of {event_date} by and between San Isidro Labrador Resort ("Venue Provider") and {client_name} ("Client").

SCOPE OF SERVICES
Venue Provider shall provide the following services for Client's corporate event:
- Exclusive use of {venue_name}
- Audio-visual equipment as specified
- Conference-style seating arrangement
- Basic catering services as per {package_name}

COMPENSATION
Total Contract Value: ₱{total_amount}
Payment Terms: Net 15 days from invoice date

EVENT SCHEDULE
- Setup: 2 hours before event start
- Event: As per booked schedule
- Teardown: 1 hour after event conclusion`,
            'terms': `1. Client shall provide certificate of insurance
2. Venue Provider reserves right to inspect all materials
3. No smoking in venue premises
4. Client responsible for attendee conduct
5. Additional security may be required for large events`
        },
        'birthday': {
            'content': `BIRTHDAY PARTY CONTRACT

Dear {client_name},

Thank you for choosing San Isidro Labrador Resort for your birthday celebration on {event_date}. This contract outlines the terms of our agreement for your event at {venue_name}.

PACKAGE DETAILS:
- Selected Package: {package_name}
- Venue: {venue_name}
- Total Contract Amount: ₱{total_amount}

SERVICES INCLUDED:
- Venue rental for specified hours
- Basic sound system
- Standard tables and chairs setup
- Basic party decorations`,
            'terms': `1. Outside food and beverages subject to corkage fee
2. Event must comply with venue noise regulations
3. Client responsible for guest behavior
4. Security deposit may be required
5. Setup and teardown times must be adhered to`
        },
        'generic': {
            'content': `EVENT SERVICE AGREEMENT

This Event Service Agreement (the "Agreement") is entered into on {event_date} between San Isidro Labrador Resort ("Service Provider") and {client_name} ("Client").

SERVICES
Service Provider agrees to provide event services including venue rental at {venue_name} and package services as detailed in {package_name}.

FINANCIAL TERMS
Total Agreement Value: ₱{total_amount}

RESPONSIBILITIES
Service Provider Responsibilities:
- Provide venue in clean and working condition
- Supply agreed upon equipment and services
- Ensure staff availability during event

Client Responsibilities:
- Provide accurate guest count
- Adhere to venue rules and regulations
- Make timely payments as scheduled`,
            'terms': `1. Force Majeure: Neither party liable for events beyond reasonable control
2. Indemnification: Client agrees to indemnify Service Provider for damages caused by Client or guests
3. Governing Law: This Agreement shall be governed by the laws of the Philippines
4. Entire Agreement: This document constitutes the entire agreement between parties`
        }
    };

    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });

    // Booking selection change
    $('#booking_id').on('change', function() {
        const selectedOption = $(this).find('option:selected');
        
        if (selectedOption.val()) {
            $('#previewClient').text(selectedOption.data('client'));
            $('#previewEventDate').text(selectedOption.data('event-date'));
            $('#previewEventType').text(selectedOption.data('event-type'));
            $('#previewVenue').text(selectedOption.data('venue'));
            $('#previewPackage').text(selectedOption.data('package'));
            $('#previewAmount').text('₱' + selectedOption.data('amount'));
            $('#bookingDetails').show();
            
            Toast.fire({
                icon: 'info',
                title: 'Booking selected'
            });
        } else {
            $('#bookingDetails').hide();
        }
    });

    const preselectedBookingId = <?= json_encode($selectedBookingId ?? null) ?>;
    if (preselectedBookingId) {
        $('#booking_id').val(String(preselectedBookingId)).trigger('change');
    }

    // Template buttons with SweetAlert
    $('.template-btn').on('click', function() {
        const templateType = $(this).data('template');
        const template = templates[templateType];
        
        if (template) {
            Swal.fire({
                title: 'Apply Template?',
                text: `This will load the ${templateType} template and replace any existing content.`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: 'var(--primary)',
                cancelButtonColor: 'var(--secondary)',
                confirmButtonText: 'Yes, apply template!',
                cancelButtonText: 'Cancel',
                customClass: {
                    confirmButton: 'swal2-confirm',
                    cancelButton: 'swal2-cancel',
                    popup: 'swal2-popup swal2-modal swal2-show',
                    title: 'swal2-title',
                    htmlContainer: 'swal2-html-container',
                    icon: 'swal2-icon swal2-question',
                    actions: 'swal2-actions',
                    confirmButton: 'swal2-confirm',
                    cancelButton: 'swal2-cancel'
                },
                buttonsStyling: true,
                showClass: {
                    popup: 'swal2-show',
                    backdrop: 'swal2-backdrop-show',
                    icon: 'swal2-icon-show'
                },
                hideClass: {
                    popup: 'swal2-hide',
                    backdrop: 'swal2-backdrop-hide',
                    icon: 'swal2-icon-hide'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Auto-fill title based on template
                    const titleMap = {
                        'wedding': 'Wedding Event Service Agreement',
                        'corporate': 'Corporate Event Agreement',
                        'birthday': 'Birthday Party Contract',
                        'generic': 'Event Service Agreement'
                    };
                    
                    $('#title').val(titleMap[templateType] || 'Event Contract');
                    
                    // Set content directly to textareas
                    $('#content').val(template.content);
                    $('#terms_conditions').val(template.terms);
                    
                    Toast.fire({
                        icon: 'success',
                        title: `${templateType.charAt(0).toUpperCase() + templateType.slice(1)} template applied!`
                    });
                }
            });
        }
    });

    // Cancel button with confirmation
    $('#cancelBtn').on('click', function() {
        Swal.fire({
            title: 'Are you sure?',
            text: 'Any unsaved changes will be lost!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: 'var(--primary)',
            cancelButtonColor: 'var(--secondary)',
            confirmButtonText: 'Yes, cancel!',
            cancelButtonText: 'Continue editing',
            customClass: {
                confirmButton: 'swal2-confirm',
                cancelButton: 'swal2-cancel',
                popup: 'swal2-popup swal2-modal swal2-show',
                title: 'swal2-title',
                htmlContainer: 'swal2-html-container',
                icon: 'swal2-icon swal2-warning',
                actions: 'swal2-actions',
                confirmButton: 'swal2-confirm',
                cancelButton: 'swal2-cancel'
            },
            buttonsStyling: true,
            showClass: {
                popup: 'swal2-show',
                backdrop: 'swal2-backdrop-show',
                icon: 'swal2-icon-show'
            },
            hideClass: {
                popup: 'swal2-hide',
                backdrop: 'swal2-backdrop-hide',
                icon: 'swal2-icon-hide'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '<?= base_url('admin/contracts') ?>';
            }
        });
    });

    // Form submission with validation and confirmation
    $('#contractForm').on('submit', function(e) {
        e.preventDefault();
        
        const bookingId = $('#booking_id').val();
        const title = $('#title').val();
        const content = $('#content').val();
        const terms = $('#terms_conditions').val();
        
        // Validation
        if (!bookingId || !title || !content || !terms) {
            Swal.fire({
                title: 'Missing Information!',
                text: 'Please fill in all required fields before submitting.',
                icon: 'error',
                confirmButtonColor: 'var(--primary)',
                customClass: {
                    confirmButton: 'swal2-confirm',
                    popup: 'swal2-popup swal2-modal swal2-show',
                    title: 'swal2-title',
                    htmlContainer: 'swal2-html-container',
                    icon: 'swal2-icon swal2-error',
                    actions: 'swal2-actions'
                },
                buttonsStyling: true
            });
            return false;
        }
        
        // Show confirmation dialog
        Swal.fire({
            title: 'Create Contract?',
            html: `
                <div class="text-left">
                    <p><strong>Title:</strong> ${title}</p>
                    <p><strong>Booking:</strong> ${$('#booking_id option:selected').text()}</p>
                    <p class="text-muted">This will create a new contract and notify the client.</p>
                </div>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: 'var(--primary)',
            cancelButtonColor: 'var(--secondary)',
            confirmButtonText: 'Yes, create contract!',
            cancelButtonText: 'Review again',
            customClass: {
                confirmButton: 'swal2-confirm',
                cancelButton: 'swal2-cancel',
                popup: 'swal2-popup swal2-modal swal2-show',
                title: 'swal2-title',
                htmlContainer: 'swal2-html-container',
                icon: 'swal2-icon swal2-question',
                actions: 'swal2-actions'
            },
            buttonsStyling: true,
            showClass: {
                popup: 'swal2-show',
                backdrop: 'swal2-backdrop-show',
                icon: 'swal2-icon-show'
            },
            hideClass: {
                popup: 'swal2-hide',
                backdrop: 'swal2-backdrop-hide',
                icon: 'swal2-icon-hide'
            },
            preConfirm: () => {
                // Submit the form
                $('#contractForm').off('submit').submit();
            }
        });
    });

    // Show helpful tips when page loads
    setTimeout(() => {
        Toast.fire({
            icon: 'info',
            title: 'Select a template to get started!'
        });
    }, 1000);

    // Improve textarea appearance
    $('.contract-textarea').css({
        'font-family': 'monospace',
        'font-size': '14px',
        'line-height': '1.4'
    });
});
</script>

<style>
.contract-textarea {
    font-family: 'Courier New', monospace;
    font-size: 14px;
    line-height: 1.4;
    white-space: pre-wrap;
}

.formatting-help {
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 4px;
    padding: 10px;
    margin-top: 5px;
    font-size: 12px;
}
</style>


<?= $this->endSection() ?>

<?= $this->endSection() ?>