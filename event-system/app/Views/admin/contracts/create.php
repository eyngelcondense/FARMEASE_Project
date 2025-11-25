<?php
// app/Views/admin/contracts/create.php
?>

<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Create New Contract</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin') ?>">Home</a></li>
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/contracts') ?>">Contracts</a></li>
                        <li class="breadcrumb-item active">Create Contract</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Contract Details</h3>
                        </div>

                        <form action="<?= base_url('admin/contracts/store') ?>" method="post">
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

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="booking_id">Select Booking *</label>
                                            <select class="form-control select2" id="booking_id" name="booking_id" required>
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
                                    <textarea class="form-control" id="content" name="content" rows="15" 
                                              placeholder="Enter the main contract content here..." required><?= old('content') ?></textarea>
                                    <small class="form-text text-muted">
                                        You can use the following placeholders: {client_name}, {event_date}, {venue_name}, {package_name}, {total_amount}
                                    </small>
                                </div>

                                <div class="form-group">
                                    <label for="terms_conditions">Terms & Conditions *</label>
                                    <textarea class="form-control" id="terms_conditions" name="terms_conditions" rows="8" 
                                              placeholder="Enter terms and conditions..." required><?= old('terms_conditions') ?></textarea>
                                </div>

                                <!-- Contract Template -->
                                <div class="card card-secondary">
                                    <div class="card-header">
                                        <h3 class="card-title">Contract Template</h3>
                                        <div class="card-tools">
                                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <p>Quick templates for common contract types:</p>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-default template-btn" data-template="wedding">
                                                Wedding Event
                                            </button>
                                            <button type="button" class="btn btn-default template-btn" data-template="corporate">
                                                Corporate Event
                                            </button>
                                            <button type="button" class="btn btn-default template-btn" data-template="birthday">
                                                Birthday Party
                                            </button>
                                            <button type="button" class="btn btn-default template-btn" data-template="generic">
                                                Generic Event
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Create Contract
                                </button>
                                <a href="<?= base_url('admin/contracts') ?>" class="btn btn-default">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?= $this->section('scripts') ?>
<script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
<script>
$(document).ready(function() {
    // Initialize CKEditor
    CKEDITOR.replace('content', {
        height: 400
    });
    
    CKEDITOR.replace('terms_conditions', {
        height: 200
    });

    // Initialize Select2
    $('.select2').select2();

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
        } else {
            $('#bookingDetails').hide();
        }
    });

    // Template buttons
    $('.template-btn').on('click', function() {
        const template = $(this).data('template');
        let content = '';
        let terms = '';

        switch(template) {
            case 'wedding':
                content = `This Agreement is made and entered into on {event_date} between San Isidro Labrador Resort (hereinafter referred to as "Service Provider") and {client_name} (hereinafter referred to as "Client").

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
- Cancellation less than 30 days: No refund`;
                
                terms = `1. Client is responsible for any damages to venue property
2. Service Provider is not liable for force majeure events
3. Event must conclude by agreed end time
4. Additional overtime charges apply beyond contracted hours
5. Client must provide final guest count 7 days before event`;
                break;

            case 'corporate':
                content = `CORPORATE EVENT AGREEMENT

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
- Teardown: 1 hour after event conclusion`;
                
                terms = `1. Client shall provide certificate of insurance
2. Venue Provider reserves right to inspect all materials
3. No smoking in venue premises
4. Client responsible for attendee conduct
5. Additional security may be required for large events`;
                break;

            case 'birthday':
                content = `BIRTHDAY PARTY CONTRACT

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
- Basic party decorations`;
                
                terms = `1. Outside food and beverages subject to corkage fee
2. Event must comply with venue noise regulations
3. Client responsible for guest behavior
4. Security deposit may be required
5. Setup and teardown times must be adhered to`;
                break;

            case 'generic':
                content = `EVENT SERVICE AGREEMENT

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
- Make timely payments as scheduled`;
                
                terms = `1. Force Majeure: Neither party liable for events beyond reasonable control
2. Indemnification: Client agrees to indemnify Service Provider for damages caused by Client or guests
3. Governing Law: This Agreement shall be governed by the laws of the Philippines
4. Entire Agreement: This document constitutes the entire agreement between parties`;
                break;
        }

        // Set CKEditor content
        if (CKEDITOR.instances.content) {
            CKEDITOR.instances.content.setData(content);
        }
        
        if (CKEDITOR.instances.terms_conditions) {
            CKEDITOR.instances.terms_conditions.setData(terms);
        }
    });

    // Form validation
    $('form').on('submit', function() {
        const bookingId = $('#booking_id').val();
        const title = $('#title').val();
        
        if (!bookingId || !title) {
            alert('Please fill in all required fields.');
            return false;
        }
        
        return true;
    });
});
</script>
<?= $this->endSection() ?>

<?= $this->endSection() ?>