<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Request Form | San Isidro Labrador</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            padding: 20px;
        }
        .debug-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .alert {
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="debug-container">
        <h2 class="text-center mb-4">Data Request Form - Debug Mode</h2>
        
        <form id="debugForm" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Full Name *</label>
                <input type="text" class="form-control" name="fullName" value="Test User" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Email *</label>
                <input type="email" class="form-control" name="email" value="test@example.com" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Registered Email *</label>
                <input type="email" class="form-control" name="registeredEmail" value="test@example.com" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Phone *</label>
                <input type="tel" class="form-control" name="phone" value="09123456789" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Request Type *</label>
                <select class="form-select" name="requestType" required>
                    <option value="booking_history" selected>Booking History</option>
                </select>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Details *</label>
                <textarea class="form-control" name="details" required>Test request details</textarea>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Consent *</label>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="consent" checked required>
                    <label class="form-check-label">I agree to the terms</label>
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary w-100">Submit Debug Request</button>
        </form>
        
        <div id="debugResult" class="mt-4"></div>
    </div>

    <script>
        document.getElementById('debugForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const resultDiv = document.getElementById('debugResult');
            
            try {
                resultDiv.innerHTML = '<div class="alert alert-info">Sending request...</div>';
                
                const response = await fetch('<?= site_url('data-request/submit') ?>', {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.text();
                
                // Try to parse as JSON first
                try {
                    const jsonResult = JSON.parse(result);
                    resultDiv.innerHTML = `<div class="alert alert-success">
                        <strong>Response (JSON):</strong><br>
                        <pre>${JSON.stringify(jsonResult, null, 2)}</pre>
                    </div>`;
                } catch (e) {
                    // If not JSON, show raw response
                    resultDiv.innerHTML = `<div class="alert alert-warning">
                        <strong>Raw Response:</strong><br>
                        <pre>${result}</pre>
                    </div>`;
                }
                
            } catch (error) {
                resultDiv.innerHTML = `<div class="alert alert-danger">
                    <strong>Error:</strong><br>
                    ${error.message}
                </div>`;
            }
        });
    </script>
</body>
</html>