<?php
$title = "Contract - " . $contract['title'] . " | San Isidro Labrador Resort and Leisure Farm";
?>
<?= view('client/header', ['title' => $title, 'user' => $user, 'client' => $client]) ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><?= $contract['title'] ?></h3>
                    <div class="card-tools">
                        <a href="<?= base_url('client/contracts/download/' . $contract['id']) ?>" 
                           class="btn btn-secondary btn-sm">
                            <i class="fas fa-download"></i> Download PDF
                        </a>
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Contract Status Alert -->
                    <?php if ($contract['status'] == 'sent'): ?>
                        <div class="alert alert-info">
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                <div>
                                    <h5 class="mb-1"><i class="fas fa-file-signature"></i> Contract Sent for Signature</h5>
                                    <p class="mb-1">Please review and sign this contract.</p>
                                    <?php if (!empty($contract['expires_at']) && $contract['expires_at'] != '0000-00-00 00:00:00'): ?>
                                        <p class="mb-0"><strong>Expires on:</strong> <?= date('F j, Y g:i A', strtotime($contract['expires_at'])) ?></p>
                                    <?php else: ?>
                                        <p class="mb-0"><strong>No expiration date set.</strong></p>
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <button type="button" class="btn btn-success btn-lg sign-contract-main"
                                            data-contract-id="<?= $contract['id'] ?>">
                                        <i class="fas fa-signature"></i> Sign Contract Now
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php elseif ($contract['status'] == 'signed'): ?>
                        <div class="alert alert-success">
                            <h5 class="alert-heading mb-1"><i class="fas fa-check-circle"></i> Contract Signed</h5>
                            <p class="mb-0">You signed this contract on 
                                <strong><?= date('F j, Y g:i A', strtotime($contract['signature_date'])) ?></strong>
                            </p>
                        </div>
                    <?php elseif ($contract['status'] == 'expired'): ?>
                        <div class="alert alert-warning">
                            <h5 class="alert-heading mb-1"><i class="fas fa-exclamation-triangle"></i> Contract Expired</h5>
                            <p class="mb-0">This contract has expired. Please contact the resort for a new contract.</p>
                        </div>
                    <?php endif; ?>

                    <!-- Contract Details -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="info-box bg-light">
                                <span class="info-box-icon"><i class="fas fa-file-contract"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Contract Number</span>
                                    <span class="info-box-number"><?= $contract['contract_number'] ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-box bg-light">
                                <span class="info-box-icon"><i class="fas fa-calendar-alt"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Event Date</span>
                                    <span class="info-box-number"><?= date('F j, Y', strtotime($contract['event_date'])) ?></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contract Content -->
                    <div class="contract-content-section mb-4">
                        <div class="contract-header text-center mb-4 p-4 bg-light rounded">
                            <h2 class="mb-2"><?= $contract['title'] ?></h2>
                            <p class="text-muted mb-0">Contract Number: <?= $contract['contract_number'] ?></p>
                            <p class="text-muted">Date Created: <?= date('F j, Y', strtotime($contract['created_at'])) ?></p>
                        </div>

                        <div class="contract-meta mb-4 p-3 bg-light rounded">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5>Client Information</h5>
                                    <p class="mb-1"><strong>Name:</strong> <?= esc($contract['client_name'] ?? 'Not specified') ?></p>
                                    <p class="mb-1"><strong>Email:</strong> <?= esc($contract['client_email'] ?? $client['email'] ?? 'Not specified') ?></p>
                                    <?php if (!empty($contract['client_phone'])): ?>
                                        <p class="mb-1"><strong>Phone:</strong> <?= esc($contract['client_phone']) ?></p>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-6">
                                    <h5>Event Details</h5>
                                    <p class="mb-1"><strong>Booking Reference:</strong> <?= esc($contract['booking_reference'] ?? 'Not specified') ?></p>
                                    <p class="mb-1"><strong>Event Date:</strong> <?= !empty($contract['event_date']) ? date('F j, Y', strtotime($contract['event_date'])) : 'Not specified' ?></p>
                                    <p class="mb-1"><strong>Event Type:</strong> <?= esc($contract['event_type'] ?? 'Not specified') ?></p>
                                    <?php if (!empty($contract['venue_name'])): ?>
                                        <p class="mb-1"><strong>Venue:</strong> <?= esc($contract['venue_name']) ?></p>
                                    <?php endif; ?>
                                    <?php if (!empty($contract['package_name'])): ?>
                                        <p class="mb-1"><strong>Package:</strong> <?= esc($contract['package_name']) ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="contract-content mb-4 p-3">
                            <?= nl2br(htmlspecialchars($contract['content'])) ?>
                        </div>

                        <div class="terms-conditions mb-4 p-3 bg-warning bg-opacity-10 rounded">
                            <h5 class="text-warning"><i class="fas fa-exclamation-triangle"></i> Terms & Conditions</h5>
                            <div class="terms-content">
                                <?= nl2br(htmlspecialchars($contract['terms_conditions'])) ?>
                            </div>
                        </div>

                        <!-- Signature Section -->
                        <div class="signature-section mt-5 p-4 border rounded">
                            <div class="row">
                                <div class="col-md-6 text-center">
                                    <div class="signature-area mb-3" style="min-height: 100px;">
                                        <?php if ($contract['status'] == 'signed' && $contract['signature_data']): ?>
                                            <?php if (strpos($contract['signature_data'], 'data:image') === 0): ?>
                                                <img src="<?= $contract['signature_data'] ?>" 
                                                     alt="Client Signature" 
                                                     style="max-width: 300px; max-height: 100px; border: 1px solid #ddd;">
                                            <?php else: ?>
                                                <div class="typed-signature" 
                                                     style="font-family: 'Dancing Script', cursive; font-size: 24px; color: #333;">
                                                    <?= $contract['signature_data'] ?>
                                                </div>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <div class="signature-placeholder text-muted" 
                                                 style="border: 1px dashed #ccc; padding: 20px; background: #f9f9f9;">
                                                <i class="fas fa-signature fa-2x mb-2"></i><br>
                                                Signature Required
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="signature-line" style="border-top: 1px solid #000; width: 300px; margin: 0 auto;"></div>
                                    <p class="mb-0 mt-2"><strong>Client Signature</strong></p>
                                    <p class="text-muted"><?= $contract['client_name'] ?></p>
                                    <?php if ($contract['signature_date']): ?>
                                        <p class="text-success">
                                            <small>Signed on: <?= date('F j, Y g:i A', strtotime($contract['signature_date'])) ?></small>
                                        </p>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-6 text-center">
                                    <div class="signature-area mb-3" style="min-height: 100px;">
                                        <div class="resort-signature text-muted">
                                            <img src="<?= base_url('assets/img/resort-logo.png') ?>" 
                                                 alt="San Isidro Labrador Resort" 
                                                 style="max-height: 60px; margin-bottom: 10px;"><br>
                                            <strong>San Isidro Labrador Resort</strong>
                                        </div>
                                    </div>
                                    <div class="signature-line" style="border-top: 1px solid #000; width: 300px; margin: 0 auto;"></div>
                                    <p class="mb-0 mt-2"><strong>Authorized Representative</strong></p>
                                    <p class="text-muted">San Isidro Labrador Resort</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="row mt-4">
                        <div class="col-12 text-center">
                            <?php if ($contract['status'] == 'sent'): ?>
                                <button type="button" class="btn btn-success btn-lg sign-contract-main"
                                        data-contract-id="<?= $contract['id'] ?>">
                                    <i class="fas fa-signature"></i> Sign Contract Now
                                </button>
                                <a href="<?= base_url('client/contracts/download/' . $contract['id']) ?>" 
                                   class="btn btn-outline-primary btn-lg ml-2">
                                    <i class="fas fa-download"></i> Download PDF
                                </a>
                            <?php else: ?>
                                <a href="<?= base_url('client/contracts/download/' . $contract['id']) ?>" 
                                   class="btn btn-primary btn-lg">
                                    <i class="fas fa-download"></i> Download Signed Contract
                                </a>
                                <a href="<?= base_url('client/contracts') ?>" 
                                   class="btn btn-outline-secondary btn-lg ml-2">
                                    <i class="fas fa-arrow-left"></i> Back to Contracts
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Sign Contract Modal -->
<div class="modal fade" id="signContractModal" tabindex="-1" role="dialog" aria-labelledby="signContractModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="signContractModalLabel">Sign Contract</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <h6><i class="fas fa-info-circle"></i> Important</h6>
                    <p class="mb-0">By signing this contract, you agree to all terms and conditions. This is a legally binding document.</p>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="signatureMethod">Signature Method</label>
                            <select class="form-control" id="signatureMethod">
                                <option value="draw">Draw Signature</option>
                                <option value="type">Type Signature</option>
                                <option value="upload">Upload Signature Image</option>
                            </select>
                        </div>

                        <div id="drawSignatureSection">
                            <label>Draw Your Signature</label>
                            <div class="signature-pad-container border rounded p-2 mb-2" style="background: white;">
                                <canvas id="signatureCanvas" width="400" height="200" style="width: 100%; height: 200px; cursor: crosshair;"></canvas>
                            </div>
                            <div class="text-center mb-3">
                                <button type="button" class="btn btn-outline-secondary btn-sm" id="clearCanvas">
                                    <i class="fas fa-eraser"></i> Clear Signature
                                </button>
                            </div>
                        </div>

                        <div id="typeSignatureSection" style="display: none;">
                            <div class="form-group">
                                <label for="typedName">Type Your Full Name</label>
                                <input type="text" class="form-control" id="typedName" 
                                       placeholder="Enter your full name as signature"
                                       value="<?= $contract['client_name'] ?>">
                            </div>
                        </div>

                        <div id="uploadSignatureSection" style="display: none;">
                            <div class="form-group">
                                <label for="signatureFile">Upload Signature Image (PNG/JPG)</label>
                                <input type="file" class="form-control" id="signatureFile" accept="image/*">
                            </div>
                            <div class="mb-2 text-center">
                                <img id="signaturePreview" src="" alt="Signature Preview" style="max-width:100%; max-height:150px; display:none; border:1px solid #ddd;" />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="agreement-section border rounded p-3 bg-light">
                            <h6>Contract Agreement</h6>
                            <p><strong>Contract:</strong> <?= $contract['title'] ?></p>
                            <p><strong>Contract #:</strong> <?= $contract['contract_number'] ?></p>
                            <p><strong>Event Date:</strong> <?= date('F j, Y', strtotime($contract['event_date'])) ?></p>
                            <hr>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="agreeAllTerms" required>
                                <label class="form-check-label" for="agreeAllTerms">
                                    I hereby agree to all terms and conditions stated in this contract
                                </label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="confirmIdentity" required>
                                <label class="form-check-label" for="confirmIdentity">
                                    I confirm that I am <?= $contract['client_name'] ?> and have the authority to sign this contract
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="finalizeSignature">
                    <i class="fas fa-signature"></i> Sign and Submit
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
<script>
$(document).ready(function() {
    let signaturePad = null;
    const contractId = <?= $contract['id'] ?>;
    // Note: bootstrap JS is loaded after this script in the footer, so create modal instances when needed

    // Initialize signature pad
    function initializeSignaturePad() {
        const canvas = document.getElementById('signatureCanvas');
        if (signaturePad) {
            signaturePad.clear();
        } else {
            signaturePad = new SignaturePad(canvas, {
                backgroundColor: 'rgb(255, 255, 255)',
                penColor: 'rgb(0, 0, 0)',
                minWidth: 1,
                maxWidth: 3,
                throttle: 16
            });
        }
    }

    // Clear signature
    $('#clearCanvas').on('click', function() {
        if (signaturePad) {
            signaturePad.clear();
        }
    });

    // Signature method toggle
    $('#signatureMethod').on('change', function() {
        const method = $(this).val();
        if (method === 'draw') {
            $('#drawSignatureSection').show();
            $('#typeSignatureSection').hide();
            $('#uploadSignatureSection').hide();
            initializeSignaturePad();
        } else {
            if (method === 'type') {
                $('#drawSignatureSection').hide();
                $('#typeSignatureSection').show();
                $('#uploadSignatureSection').hide();
            } else if (method === 'upload') {
                $('#drawSignatureSection').hide();
                $('#typeSignatureSection').hide();
                $('#uploadSignatureSection').show();
            }
        }
    });

    // Preview uploaded signature image (attach once)
    $('#signatureFile').on('change', function(e) {
        const input = this;
        const preview = document.getElementById('signaturePreview');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(evt) {
                preview.src = evt.target.result;
                preview.style.display = 'block';
            }
            reader.readAsDataURL(input.files[0]);
        } else {
            preview.src = '';
            preview.style.display = 'none';
        }
    });

    // Sign contract buttons
    $('.sign-contract-main').on('click', function() {
        const el = document.getElementById('signContractModal');
        if (el && typeof bootstrap !== 'undefined') {
            const modalInst = bootstrap.Modal.getOrCreateInstance(el);
            modalInst.show();
        }
        $('#signatureMethod').val('draw');
        $('#drawSignatureSection').show();
        $('#typeSignatureSection').hide();
        $('#typedName').val('<?= $contract['client_name'] ?>');
        $('#agreeAllTerms').prop('checked', false);
        $('#confirmIdentity').prop('checked', false);
        
        setTimeout(initializeSignaturePad, 300);
    });

    // Finalize signature
    $('#finalizeSignature').on('click', function() {
        // Validate agreement
        if (!$('#agreeAllTerms').prop('checked') || !$('#confirmIdentity').prop('checked')) {
            alert('Please check all agreement boxes before signing.');
            return;
        }

        const signatureMethod = $('#signatureMethod').val();
        let signatureData = '';

        // If upload selected, handle file separately
        if (signatureMethod === 'upload') {
            const fileInput = document.getElementById('signatureFile');
            if (!fileInput || !fileInput.files || fileInput.files.length === 0) {
                alert('Please choose an image file to upload as your signature.');
                return;
            }
            const file = fileInput.files[0];

            // Prepare FormData and send file
            const formData = new FormData();
            formData.append('signature_file', file);
            formData.append('signature_type', 'upload');
            formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

            // Disable button
            const submitBtn = $(this);
            submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Signing...');

            $.ajax({
                url: '<?= base_url('client/contracts/sign') ?>/' + contractId,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                    const el = document.getElementById('signContractModal');
                    if (el && typeof bootstrap !== 'undefined') {
                        const inst = bootstrap.Modal.getInstance(el);
                        if (inst) inst.hide();
                    }
                        showSuccessAlert('Contract signed successfully! Refreshing...');
                        setTimeout(function() { location.reload(); }, 1500);
                    } else {
                        alert('Error: ' + response.message);
                        submitBtn.prop('disabled', false).html('<i class="fas fa-signature"></i> Sign and Submit');
                    }
                },
                error: function(xhr) {
                    const msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'An error occurred. Please try again.';
                    alert('Error: ' + msg);
                    submitBtn.prop('disabled', false).html('<i class="fas fa-signature"></i> Sign and Submit');
                }
            });


            return; // upload flow handled
        }

        if (signatureMethod === 'draw') {
            if (signaturePad.isEmpty()) {
                alert('Please provide your signature by drawing in the box.');
                return;
            }
            signatureData = signaturePad.toDataURL();
        } else {
            const typedName = $('#typedName').val().trim();
            if (!typedName) {
                alert('Please enter your full name.');
                return;
            }
            signatureData = typedName;
        }

        // Show loading state
        const submitBtn = $(this);
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Signing...');

        $.ajax({
            url: '<?= base_url('client/contracts/sign') ?>/' + contractId,
            type: 'POST',
            data: {
                signature_data: signatureData,
                signature_type: signatureMethod,
                '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    const el = document.getElementById('signContractModal');
                    if (el && typeof bootstrap !== 'undefined') {
                        const inst = bootstrap.Modal.getInstance(el);
                        if (inst) inst.hide();
                    }
                    showSuccessAlert('Contract signed successfully! Refreshing...');
                    setTimeout(function() {
                        location.reload();
                    }, 1500);
                } else {
                    alert('Error: ' + response.message);
                    submitBtn.prop('disabled', false).html('<i class="fas fa-signature"></i> Sign and Submit');
                }
            },
            error: function(xhr) {
                const msg = (xhr.responseJSON && xhr.responseJSON.message)
                    ? xhr.responseJSON.message
                    : 'An error occurred. Please try again.';
                alert('Error: ' + msg);
                submitBtn.prop('disabled', false).html('<i class="fas fa-signature"></i> Sign and Submit');
            }
        });
    });

    function showSuccessAlert(message) {
        const alertHtml = `
            <div class="alert alert-success alert-dismissible fade show">
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                <i class="icon fas fa-check"></i> ${message}
            </div>
        `;
        $('.card-body').prepend(alertHtml);
    }

    // Auto-adjust canvas size
    $(window).on('resize', function() {
        if (signaturePad) {
            const canvas = document.getElementById('signatureCanvas');
            const ratio = Math.max(window.devicePixelRatio || 1, 1);
            canvas.width = canvas.offsetWidth * ratio;
            canvas.height = canvas.offsetHeight * ratio;
            canvas.getContext("2d").scale(ratio, ratio);
            signaturePad.clear();
        }
    });
});
</script>
<style>
.contract-content-section {
    font-family: 'Times New Roman', Times, serif;
    line-height: 1.6;
}

.contract-header h2 {
    color: #2c3e50;
    font-weight: bold;
}

.contract-content {
    text-align: justify;
    white-space: pre-line;
}

.signature-area {
    min-height: 120px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.typed-signature {
    font-family: 'Dancing Script', cursive;
    font-size: 28px !important;
    color: #2c3e50;
}

.signature-placeholder {
    border: 2px dashed #dee2e6;
    border-radius: 5px;
    color: #6c757d;
}

.signature-pad-container {
    background: white !important;
    cursor: crosshair;
}

.info-box {
    border: 1px solid #dee2e6;
    border-radius: 0.25rem;
}

.info-box-icon {
    background: rgba(0, 0, 0, 0.1);
}
</style>

<?= view('client/footer') ?>