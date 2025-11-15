<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Request Form | San Isidro Labrador</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="images/LOGO NG SAN ISIDRO.png"/>

    <style>
        .header-bar {
            height: 10px;
            background-color: #b2a187;
        }

        body {
            background-color: #fff;
            font-family: 'Poppins', sans-serif;
            color: #4b4b4b;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .form-container {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 15px;
        }

        .form-card {
            max-width: 650px;
            width: 100%;
            text-align: center;
        }

        .form-card img {
            width: 100px;
            margin-bottom: 10px;
        }

        .brand-name {
            font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
            color: #7c6a43;
            font-size: 1.2rem;
            font-weight: 600;
        }

        h1 {
            font-weight: 700;
            color: #3e3e3e;
            margin-bottom: 5px;
            font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
        }

        p.subtitle {
            color: #6b6b6b;
            font-size: 0.9rem;
            margin-bottom: 30px;
        }

        .info-box {
            background-color: #f4f2ed;
            border-left: 4px solid #7c6a43;
            padding: 15px;
            margin-bottom: 30px;
            border-radius: 8px;
            text-align: left;
        }

        .info-box h5 {
            color: #7c6a43;
            margin-bottom: 8px;
            font-size: 1rem;
            font-weight: 600;
        }

        .info-box p {
            font-size: 0.85rem;
            color: #555;
            line-height: 1.6;
            margin-bottom: 0;
        }

        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #3e3e3e;
            font-size: 0.9rem;
        }

        .required {
            color: #dc3545;
        }

        .form-control {
            border: 2px solid #b9a782;
            border-radius: 10px;
            padding: 10px 15px;
            font-size: 0.95rem;
        }

        .form-control:focus {
            border-color: #7c6a43;
            box-shadow: none;
        }

        .form-select {
            border: 2px solid #b9a782;
            border-radius: 10px;
            padding: 10px 15px;
            font-size: 0.95rem;
        }

        .form-select:focus {
            border-color: #7c6a43;
            box-shadow: none;
        }

        textarea.form-control {
            resize: vertical;
            min-height: 100px;
        }

        .file-upload-wrapper {
            position: relative;
            width: 100%;
        }

        .file-upload-input {
            display: none;
        }

        .file-upload-label {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 30px 20px;
            border: 2px dashed #b9a782;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            background: #fafaf8;
        }

        .file-upload-label:hover {
            background: #f4f2ed;
            border-color: #7c6a43;
        }

        .file-upload-label i {
            font-size: 2rem;
            color: #7c6a43;
            margin-right: 15px;
        }

        .file-upload-text {
            color: #4b4b4b;
            font-size: 0.95rem;
        }

        .file-info {
            margin-top: 10px;
            font-size: 0.8rem;
            color: #6b6b6b;
            text-align: center;
        }

        .file-name {
            margin-top: 10px;
            padding: 10px 15px;
            background: #d4edda;
            border: 1px solid #c3e6cb;
            border-radius: 8px;
            font-size: 0.85rem;
            color: #155724;
            display: none;
        }

        .checkbox-wrapper {
            display: flex;
            align-items: flex-start;
            margin-bottom: 20px;
            text-align: left;
        }

        .checkbox-wrapper input[type="checkbox"] {
            margin-right: 10px;
            margin-top: 3px;
            width: 18px;
            height: 18px;
            cursor: pointer;
            border: 2px solid #b9a782;
        }

        .checkbox-wrapper label {
            font-size: 0.85rem;
            color: #4b4b4b;
            line-height: 1.6;
            cursor: pointer;
            font-weight: normal;
        }

        .btn-submit {
            background-color: #7c6a43;
            color: #fff;
            border-radius: 10px;
            width: 100%;
            padding: 12px;
            margin-top: 20px;
            border: none;
            font-weight: 600;
            font-size: 1rem;
        }

        .btn-submit:hover {
            background-color: #6a5938;
            transform: scale(1.02);
            transition: transform 0.5s;
        }

        .btn-submit:disabled {
            background-color: #cccccc;
            cursor: not-allowed;
            transform: none;
        }

        .back-btn {
            position: absolute;
            top: 20px;
            left: 25px;
            background-color: #7c6a43;
            color: #fff;
            border: none;
            border-radius: 50px;
            padding: 10px 18px;
            font-size: 1rem;
            font-weight: 600;
            text-decoration: none;
            box-shadow: 0 2px 6px rgba(0,0,0,0.2);
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 6px;
            z-index: 1000;
        }

        .back-btn:hover {
            background-color: #6a5938;
            transform: translateX(-3px);
            color: #fff;
        }

        footer {
            padding: 15px 0;
            font-size: 0.85rem;
            background-color: #b2a187;
            text-align: center;
            color: #fff;
        }

        .alert {
            margin-top: 15px;
            border-radius: 10px;
        }

        @media (max-width: 576px) {
            .back-btn {
                top: 10px;
                left: 10px;
                font-size: 0.9rem;
                padding: 8px 15px;
            }

            .form-card {
                padding: 0 10px;
            }

            .form-card img {
                width: 80px;
            }
        }
    </style>
</head>

<body>
    <div class="header-bar"></div>

    <div class="form-container">
        <a href="index.php" class="back-btn">
            <i class="fa-solid fa-chevron-left"></i>
        </a>

        <div class="form-card">
            <img src="images/LOGO NG SAN ISIDRO.png" alt="San Isidro Labrador Logo">
            <div class="brand-name">SAN ISIDRO LABRADOR</div>
            <small style="display: block; margin-bottom: 20px;">RESORT AND LEISURE FARM</small>
            
            <h1>Data Request Form</h1>
            <p class="subtitle">Request access to your booking history and personal data</p>

            <div class="info-box">
                <h5><i class="bi bi-info-circle"></i> About This Request</h5>
                <p>This form allows you to request access to your booking history and personal data stored in our system. Please provide accurate information and upload a valid ID for verification purposes. We will process your request within 5-7 business days.</p>
            </div>

            <form id="dataRequestForm" method="POST" enctype="multipart/form-data">
                <!-- Personal Information -->
                <div class="form-group">
                    <label for="fullName">Full Name <span class="required">*</span></label>
                    <input type="text" class="form-control" id="fullName" name="fullName" placeholder="Juan Dela Cruz" required>
                </div>

                <div class="form-group">
                    <label for="email">Email Address <span class="required">*</span></label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="juan.delacruz@example.com" required>
                </div>

                <div class="form-group">
                    <label for="phone">Contact Number <span class="required">*</span></label>
                    <input type="tel" class="form-control" id="phone" name="phone" placeholder="+63 912 345 6789" required>
                </div>

                <!-- Request Type -->
                <div class="form-group">
                    <label for="requestType">Type of Request <span class="required">*</span></label>
                    <select class="form-select" id="requestType" name="requestType" required>
                        <option value="">-- Select Request Type --</option>
                        <option value="booking_history">Booking History</option>
                        <option value="personal_data">Personal Data Access</option>
                        <option value="data_correction">Data Correction</option>
                        <option value="data_deletion">Data Deletion Request</option>
                        <option value="other">Other</option>
                    </select>
                </div>

                <!-- Additional Details -->
                <div class="form-group">
                    <label for="details">Request Details <span class="required">*</span></label>
                    <textarea class="form-control" id="details" name="details" placeholder="Please provide specific details about your request (e.g., date range for booking history, specific information you need, etc.)" required></textarea>
                </div>

                <!-- Booking Reference (Optional) -->
                <div class="form-group">
                    <label for="bookingRef">Booking Reference Number <small>(if applicable)</small></label>
                    <input type="text" class="form-control" id="bookingRef" name="bookingRef" placeholder="e.g., BK-2024-12345">
                </div>

                <!-- File Upload for Valid ID -->
                <div class="form-group">
                    <label for="validId">Upload Valid ID <span class="required">*</span></label>
                    <div class="file-upload-wrapper">
                        <input type="file" class="file-upload-input" id="validId" name="validId" accept="image/*,.pdf" required>
                        <label for="validId" class="file-upload-label">
                            <i class="bi bi-cloud-upload"></i>
                            <div>
                                <div class="file-upload-text" id="fileText">Click to upload valid ID</div>
                            </div>
                        </label>
                        <div class="file-name" id="fileName"></div>
                        <div class="file-info">
                            Accepted formats: JPG, PNG, PDF (Max size: 5MB)<br>
                            Valid IDs: Driver's License, Passport, National ID, PhilHealth ID, etc.
                        </div>
                    </div>
                </div>

                <!-- Consent Checkbox -->
                <div class="checkbox-wrapper">
                    <input type="checkbox" id="consent" name="consent" required>
                    <label for="consent">
                        I hereby confirm that the information provided above is accurate and true. I understand that this request will be processed according to the Data Privacy Act of 2012 (RA 10173) and the resort's privacy policy. I consent to the verification of my identity using the uploaded document. <span class="required">*</span>
                    </label>
                </div>

                <!-- Alert Box -->
                <div id="alertBox"></div>

                <!-- Submit Button -->
                <button type="submit" class="btn-submit" id="submitBtn">Submit Request</button>
            </form>
        </div>
    </div>

    <footer>
        © 2025 San Isidro Labrador Resort and Leisure Farm. All rights reserved.
    </footer>

    <script>
        // File upload handling
        const fileInput = document.getElementById('validId');
        const fileText = document.getElementById('fileText');
        const fileName = document.getElementById('fileName');

        fileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // Check file size (5MB limit)
                if (file.size > 5 * 1024 * 1024) {
                    showAlert('File size exceeds 5MB. Please upload a smaller file.', 'danger');
                    fileInput.value = '';
                    return;
                }

                fileText.textContent = file.name;
                fileName.textContent = `Selected: ${file.name} (${(file.size / 1024).toFixed(2)} KB)`;
                fileName.style.display = 'block';
            }
        });

        // Form submission handling
        document.getElementById('dataRequestForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const fullName = document.getElementById('fullName').value.trim();
            const email = document.getElementById('email').value.trim();
            const phone = document.getElementById('phone').value.trim();
            const requestType = document.getElementById('requestType').value;
            const details = document.getElementById('details').value.trim();
            const consent = document.getElementById('consent').checked;
            const validId = document.getElementById('validId').files[0];

            const alertBox = document.getElementById('alertBox');
            alertBox.innerHTML = '';

            // Email validation pattern
            const emailPattern = /^[^ ]+@[^ ]+\.[a-z]{2,3}$/;

            // Validation
            if (!fullName || !email || !phone || !requestType || !details || !validId) {
                showAlert('Please fill in all required fields and upload your valid ID.', 'danger');
                return;
            }

            if (!emailPattern.test(email)) {
                showAlert('Please enter a valid email address.', 'warning');
                return;
            }

            if (!consent) {
                showAlert('You must agree to the consent statement to proceed.', 'warning');
                return;
            }

            const submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled = true;
            submitBtn.textContent = 'Submitting...';

            // Create FormData for file upload
            const formData = new FormData(this);

            // Simulate form submission (replace with actual backend submission)
            setTimeout(() => {
                showAlert('Your data request has been submitted successfully! We will process your request within 5-7 business days and contact you via email.', 'success');
                
                // Reset form after successful submission
                setTimeout(() => {
                    this.reset();
                    fileName.style.display = 'none';
                    fileText.textContent = 'Click to upload valid ID';
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Submit Request';
                }, 2000);

                // In production, send to your PHP backend:
                // fetch('process-data-request.php', {
                //     method: 'POST',
                //     body: formData
                // })
                // .then(response => response.json())
                // .then(data => {
                //     if (data.success) {
                //         showAlert('Request submitted successfully!', 'success');
                //     } else {
                //         showAlert('Error: ' + data.message, 'danger');
                //     }
                // })
                // .catch(error => {
                //     showAlert('Error submitting request. Please try again.', 'danger');
                // });

            }, 2000);
        });

        function showAlert(message, type) {
            const alertBox = document.getElementById('alertBox');
            alertBox.innerHTML = `
                <div class="alert alert-${type}" role="alert">
                    ${message}
                </div>
            `;
        }
    </script>
</body>
</html>