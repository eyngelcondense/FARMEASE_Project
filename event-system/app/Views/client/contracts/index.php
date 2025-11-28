<?php
$title = "My Contracts | San Isidro Labrador Resort and Leisure Farm";
?>
<?= view('client/header', ['title' => $title, 'user' => $user, 'client' => $client]) ?>

<style>
    .contracts-section {
        background: linear-gradient(135deg, #f8f6f3 0%, #ffffff 100%);
        border-radius: 12px;
        padding: 30px;
        margin: 20px 0;
        border: 1px solid #e8e3da;
    }
    
    .contracts-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        padding-bottom: 20px;
        border-bottom: 2px solid #e8e3da;
    }
    
    .contracts-header h2 {
        color: #3b2a18;
        font-weight: bold;
        margin: 0;
        font-family: 'Times New Roman', Times, serif;
    }
    
    .contract-card {
        background: white;
        border-radius: 10px;
        padding: 25px;
        margin-bottom: 20px;
        border: 2px solid #e8e3da;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .contract-card:hover {
        border-color: #7c6a43;
        box-shadow: 0 8px 25px rgba(124, 106, 67, 0.15);
        transform: translateY(-3px);
    }
    
    .contract-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 5px;
        height: 100%;
        background: linear-gradient(180deg, #7c6a43, #3b2a18);
        opacity: 0.8;
    }
    
    .contract-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 1px solid #e8e3da;
    }
    
    .contract-title {
        flex: 1;
    }
    
    .contract-title h5 {
        color: #3b2a18;
        font-weight: bold;
        margin-bottom: 5px;
        font-size: 1.2rem;
    }
    
    .contract-ref {
        color: #7c6a43;
        font-size: 0.9rem;
        font-weight: 500;
    }
    
    .contract-status {
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: bold;
        text-transform: uppercase;
        white-space: nowrap;
        margin-left: 15px;
    }
    
    .status-draft { background: #e2e3e5; color: #383d41; }
    .status-sent { background: #d1edff; color: #0c5460; }
    .status-signed { background: #d4edda; color: #155724; }
    .status-expired { background: #fff3cd; color: #856404; }
    .status-cancelled { background: #f8d7da; color: #721c24; }
    
    .contract-details {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 20px;
    }
    
    .detail-item {
        margin-bottom: 12px;
    }
    
    .detail-label {
        font-weight: bold;
        color: #7c6a43;
        font-size: 13px;
        display: block;
        margin-bottom: 4px;
    }
    
    .detail-value {
        color: #3b2a18;
        font-size: 14px;
        font-weight: 500;
    }
    
    .contract-expiry {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 12px 15px;
        margin: 15px 0;
        border-left: 4px solid #ffc107;
    }
    
    .expiry-text {
        color: #856404;
        font-size: 13px;
        font-weight: 500;
        margin: 0;
    }
    
    .contract-actions {
        display: flex;
        gap: 10px;
        justify-content: flex-end;
        margin-top: 20px;
        padding-top: 20px;
        border-top: 1px solid #e8e3da;
    }
    
    .btn-contract {
        padding: 8px 16px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 500;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.3s ease;
        border: 1px solid;
    }
    
    .btn-view {
        background: #17a2b8;
        color: white;
        border-color: #17a2b8;
    }
    
    .btn-view:hover {
        background: #138496;
        border-color: #117a8b;
        color: white;
        text-decoration: none;
    }
    
    .btn-download {
        background: #6c757d;
        color: white;
        border-color: #6c757d;
    }
    
    .btn-download:hover {
        background: #5a6268;
        border-color: #545b62;
        color: white;
        text-decoration: none;
    }
    
    .btn-sign {
        background: #28a745;
        color: white;
        border-color: #28a745;
    }
    
    .btn-sign:hover {
        background: #218838;
        border-color: #1e7e34;
        color: white;
        text-decoration: none;
        transform: translateY(-1px);
    }
    
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #666;
    }
    
    .empty-state i {
        font-size: 64px;
        margin-bottom: 20px;
        color: #c19a6b;
        opacity: 0.7;
    }
    
    .empty-state h4 {
        color: #7c6a43;
        font-family: 'Times New Roman', Times, serif;
        margin-bottom: 10px;
    }
    
    .empty-state p {
        color: #8b7d6a;
        font-size: 1rem;
        max-width: 400px;
        margin: 0 auto 20px;
        line-height: 1.6;
    }
    
    .tabs {
        display: flex;
        margin-bottom: 25px;
        border-bottom: 2px solid #e8e3da;
        flex-wrap: wrap;
        gap: 5px;
    }
    
    .tab {
        padding: 12px 24px;
        cursor: pointer;
        border-bottom: 3px solid transparent;
        transition: all 0.3s;
        font-weight: bold;
        white-space: nowrap;
        color: #6c757d;
    }
    
    .tab.active {
        border-bottom-color: #7c6a43;
        color: #7c6a43;
    }
    
    .tab:hover {
        color: #7c6a43;
    }
    
    .tab-content {
        display: none;
    }
    
    .tab-content.active {
        display: block;
    }
    
    .alert {
        border-radius: 10px;
        border: none;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        border-left: 4px solid;
        margin-bottom: 25px;
    }
    
    .alert-success {
        background: linear-gradient(135deg, #d4edda, #c3e6cb);
        color: #155724;
        border-left-color: #28a745;
    }
    
    .alert-danger {
        background: linear-gradient(135deg, #f8d7da, #f1b0b7);
        color: #721c24;
        border-left-color: #dc3545;
    }
    
    /* Buttons */
    .btn-brown {
        background-color: #5f493a;
        color: #fff;
        border-color: #7a4b2a;
        padding: 10px 20px;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .btn-brown:hover {
        background-color: #935d3a;
        color: #fff;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(122, 75, 42, 0.3);
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .contracts-section {
            padding: 20px;
            margin: 15px 0;
        }
        
        .contracts-header {
            flex-direction: column;
            gap: 15px;
            align-items: flex-start;
        }
        
        .contract-header {
            flex-direction: column;
            gap: 10px;
        }
        
        .contract-status {
            margin-left: 0;
            align-self: flex-start;
        }
        
        .contract-details {
            grid-template-columns: 1fr;
            gap: 15px;
        }
        
        .contract-actions {
            flex-direction: column;
            gap: 8px;
        }
        
        .btn-contract {
            justify-content: center;
            text-align: center;
        }
        
        .tabs {
            overflow-x: auto;
            flex-wrap: nowrap;
        }
    }
</style>

<div class="container">
    <div class="contracts-section">
        <div class="contracts-header">
            <h2>My Contracts</h2>
            <div>
                <a href="<?= site_url('booking') ?>" class="btn btn-brown">
                    <i class="fas fa-plus"></i> New Booking
                </a>
            </div>
        </div>
        
        <div class="tabs">
            <div class="tab active" onclick="switchContractTab('all')">All Contracts</div>
            <div class="tab" onclick="switchContractTab('sent')">Awaiting Signature</div>
            <div class="tab" onclick="switchContractTab('signed')">Signed Contracts</div>
            <div class="tab" onclick="switchContractTab('draft')">Drafts</div>
        </div>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <i class="icon fas fa-check"></i> <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <i class="icon fas fa-ban"></i> <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <!-- All Contracts Tab -->
        <div id="all-contracts-tab" class="tab-content active">
            <?php if (empty($contracts)): ?>
                <div class="empty-state">
                    <i class="fas fa-file-contract"></i>
                    <h4>No Contracts Found</h4>
                    <p>You don't have any contracts yet. Contracts will appear here once your bookings are approved.</p>
                    <a href="<?= site_url('booking') ?>" class="btn btn-brown">Make a Booking</a>
                </div>
            <?php else: ?>
                <?php foreach ($contracts as $contract): ?>
                    <div class="contract-card" data-status="<?= $contract['status'] ?>">
                        <div class="contract-header">
                            <div class="contract-title">
                                <h5><?= esc($contract['title']) ?></h5>
                                <div class="contract-ref">Contract #: <?= esc($contract['contract_number']) ?></div>
                            </div>
                            <span class="contract-status status-<?= $contract['status'] ?>">
                                <i class="fas fa-<?= getStatusIcon($contract['status']) ?>"></i>
                                <?= ucfirst($contract['status']) ?>
                            </span>
                        </div>
                        
                        <div class="contract-details">
                            <div class="detail-item">
                                <span class="detail-label">Booking Reference</span>
                                <span class="detail-value"><?= esc($contract['booking_reference']) ?></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Event Date</span>
                                <span class="detail-value"><?= date('F j, Y', strtotime($contract['event_date'])) ?></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Event Type</span>
                                <span class="detail-value"><?= esc($contract['event_type']) ?></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Created</span>
                                <span class="detail-value"><?= date('M j, Y', strtotime($contract['created_at'])) ?></span>
                            </div>
                        </div>
                        
                        <?php if ($contract['status'] == 'sent' && !empty($contract['expires_at']) && $contract['expires_at'] != '0000-00-00 00:00:00'): ?>
                            <div class="contract-expiry">
                                <p class="expiry-text">
                                    <i class="fas fa-clock"></i> 
                                    This contract expires on <?= date('F j, Y g:i A', strtotime($contract['expires_at'])) ?>
                                </p>
                            </div>
                        <?php endif; ?>
                        
                        <div class="contract-actions">
                            <a href="<?= base_url('client/contracts/view/' . $contract['id']) ?>" 
                               class="btn-contract btn-view">
                                <i class="fas fa-eye"></i> View Details
                            </a>
                            
                            <a href="<?= base_url('client/contracts/download/' . $contract['id']) ?>" 
                               class="btn-contract btn-download">
                                <i class="fas fa-download"></i> Download PDF
                            </a>

                            <?php if ($contract['status'] == 'sent'): ?>
                                <button type="button" class="btn-contract btn-sign sign-contract-btn"
                                        data-contract-id="<?= $contract['id'] ?>"
                                        data-contract-title="<?= esc($contract['title']) ?>">
                                    <i class="fas fa-signature"></i> Sign Contract
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Filtered tabs will be handled by JavaScript -->
        <div id="sent-contracts-tab" class="tab-content"></div>
        <div id="signed-contracts-tab" class="tab-content"></div>
        <div id="draft-contracts-tab" class="tab-content"></div>
    </div>
</div>

<!-- Quick Sign Modal (Same as before) -->
<div class="modal fade" id="quickSignModal" tabindex="-1" role="dialog" aria-labelledby="quickSignModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="quickSignModalLabel">Sign Contract</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>You are about to sign: <strong id="signContractTitle"></strong></p>
                <p class="text-muted">By signing this contract, you agree to all terms and conditions outlined in the document.</p>
                
                <div class="form-group">
                    <label for="signatureType">Signature Method</label>
                    <select class="form-control" id="signatureType">
                        <option value="draw">Draw Signature</option>
                        <option value="type">Type Signature</option>
                    </select>
                </div>

                <div id="drawSignatureSection">
                    <label>Draw Your Signature</label>
                    <div class="signature-pad-container border rounded p-2 mb-2" style="background: white;">
                        <canvas id="signaturePad" width="400" height="200" style="width: 100%; height: 200px; cursor: crosshair;"></canvas>
                    </div>
                    <div class="text-center mb-3">
                        <button type="button" class="btn btn-outline-secondary btn-sm" id="clearSignature">
                            <i class="fas fa-eraser"></i> Clear
                        </button>
                    </div>
                </div>

                <div id="typeSignatureSection" style="display: none;">
                    <div class="form-group">
                        <label for="typedSignature">Type Your Full Name</label>
                        <input type="text" class="form-control" id="typedSignature" 
                               placeholder="Enter your full name as signature">
                    </div>
                </div>

                <div class="form-check mb-3">
                    <input type="checkbox" class="form-check-input" id="agreeTerms" required>
                    <label class="form-check-label" for="agreeTerms">
                        I have read and agree to all terms and conditions of this contract
                    </label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="submitSignature">
                    <i class="fas fa-signature"></i> Sign Contract
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
<script>
// Status icons mapping
function getStatusIcon(status) {
    const icons = {
        'draft': 'edit',
        'sent': 'paper-plane',
        'signed': 'check-circle',
        'expired': 'exclamation-triangle',
        'cancelled': 'times-circle'
    };
    return icons[status] || 'file-contract';
}

// Tab switching
function switchContractTab(tabName) {
    // Hide all tab contents
    document.querySelectorAll('.tab-content').forEach(tab => {
        tab.classList.remove('active');
    });
    
    // Remove active class from all tabs
    document.querySelectorAll('.tab').forEach(tab => {
        tab.classList.remove('active');
    });
    
    // Show selected tab content
    document.getElementById(tabName + '-contracts-tab').classList.add('active');
    
    // Add active class to clicked tab
    event.target.classList.add('active');
    
    // Filter contracts for specific tabs
    filterContracts(tabName);
}

// Filter contracts based on tab
function filterContracts(filter) {
    const allContracts = document.querySelectorAll('.contract-card');
    const targetTab = document.getElementById(filter + '-contracts-tab');
    
    if (filter === 'all') return; // Show all contracts
    
    // Clear the target tab
    targetTab.innerHTML = '';
    
    // Filter and move contracts
    allContracts.forEach(contract => {
        if (contract.dataset.status === filter) {
            targetTab.appendChild(contract.cloneNode(true));
        }
    });
    
    // Add empty state if no contracts
    if (targetTab.children.length === 0) {
        targetTab.innerHTML = `
            <div class="empty-state">
                <i class="fas fa-file-contract"></i>
                <h4>No ${filter} Contracts</h4>
                <p>You don't have any ${filter} contracts at the moment.</p>
            </div>
        `;
    }
}

// Signature functionality (same as before)
let signaturePad = null;
let currentContractId = null;

$(document).ready(function() {
    // Initialize signature pad when modal is shown
    $('#quickSignModal').on('shown.bs.modal', function() {
        const canvas = document.getElementById('signaturePad');
        if (signaturePad) {
            signaturePad.clear();
        } else {
            signaturePad = new SignaturePad(canvas, {
                backgroundColor: 'rgb(255, 255, 255)',
                penColor: 'rgb(0, 0, 0)',
                minWidth: 1,
                maxWidth: 3
            });
        }
    });

    // Clear signature
    $('#clearSignature').on('click', function() {
        if (signaturePad) {
            signaturePad.clear();
        }
    });

    // Signature type toggle
    $('#signatureType').on('change', function() {
        const type = $(this).val();
        if (type === 'draw') {
            $('#drawSignatureSection').show();
            $('#typeSignatureSection').hide();
        } else {
            $('#drawSignatureSection').hide();
            $('#typeSignatureSection').show();
        }
    });

    // Quick sign button
    $(document).on('click', '.sign-contract-btn', function() {
        currentContractId = $(this).data('contract-id');
        const contractTitle = $(this).data('contract-title');
        
        $('#signContractTitle').text(contractTitle);
        $('#quickSignModal').modal('show');
        
        // Reset form
        $('#signatureType').val('draw');
        $('#drawSignatureSection').show();
        $('#typeSignatureSection').hide();
        $('#typedSignature').val('');
        $('#agreeTerms').prop('checked', false);
        
        if (signaturePad) {
            signaturePad.clear();
        }
    });

    // Submit signature
    $('#submitSignature').on('click', function() {
        if (!$('#agreeTerms').prop('checked')) {
            alert('Please agree to the terms and conditions before signing.');
            return;
        }

        const signatureType = $('#signatureType').val();
        let signatureData = '';

        if (signatureType === 'draw') {
            if (signaturePad.isEmpty()) {
                alert('Please provide your signature.');
                return;
            }
            signatureData = signaturePad.toDataURL();
        } else {
            const typedName = $('#typedSignature').val().trim();
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
            url: '<?= base_url('client/contracts/sign') ?>/' + currentContractId,
            type: 'POST',
            data: {
                signature_data: signatureData,
                signature_type: signatureType
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#quickSignModal').modal('hide');
                    showSuccessAlert('Contract signed successfully!');
                    setTimeout(function() {
                        location.reload();
                    }, 1500);
                } else {
                    alert('Error: ' + response.message);
                    submitBtn.prop('disabled', false).html('<i class="fas fa-signature"></i> Sign Contract');
                }
            },
            error: function() {
                alert('Error signing contract. Please try again.');
                submitBtn.prop('disabled', false).html('<i class="fas fa-signature"></i> Sign Contract');
            }
        });
    });

    function showSuccessAlert(message) {
        const alertHtml = `
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <i class="icon fas fa-check"></i> ${message}
            </div>
        `;
        document.querySelector('.contracts-section').insertAdjacentHTML('afterbegin', alertHtml);
    }
});
</script>

<?= view('client/footer') ?>