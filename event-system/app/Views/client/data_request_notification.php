<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>New Data Request Submission</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #7c6a43; color: white; padding: 20px; text-align: center; }
        .content { background: #f4f4f4; padding: 20px; }
        .field { margin-bottom: 10px; }
        .label { font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>New Data Request Submission</h1>
        </div>
        <div class="content">
            <p>A new data request has been submitted through the website.</p>
            
            <div class="field">
                <span class="label">Full Name:</span>
                <span><?= esc($full_name) ?></span>
            </div>
            
            <div class="field">
                <span class="label">Email:</span>
                <span><?= esc($email) ?></span>
            </div>
            
            <div class="field">
                <span class="label">Registered Email:</span>
                <span><?= esc($registered_email) ?></span>
            </div>
            
            <div class="field">
                <span class="label">Phone:</span>
                <span><?= esc($phone) ?></span>
            </div>
            
            <div class="field">
                <span class="label">Request Type:</span>
                <span><?= esc(ucfirst(str_replace('_', ' ', $request_type))) ?></span>
            </div>
            
            <div class="field">
                <span class="label">Booking Reference:</span>
                <span><?= esc($booking_reference ?? 'N/A') ?></span>
            </div>
            
            <div class="field">
                <span class="label">Details:</span>
                <p><?= nl2br(esc($details)) ?></p>
            </div>
            
            <div class="field">
                <span class="label">Submitted At:</span>
                <span><?= date('F j, Y g:i A') ?></span>
            </div>
            
            <p>Please log in to the admin panel to process this request.</p>
        </div>
    </div>
</body>
</html>