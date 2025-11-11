<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Successful</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: #f8f9fa;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        .brand-text {
            font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
            color: #7c6a43;
            font-weight: bold;
        }
        .btn-primary {
            background: #7c6a43;
            border: none;
        }
        .btn-primary:hover {
            background: #6a5a38;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card p-4">
                    <div class="text-center mb-4">
                        <img src="/images/LOGO NG SAN ISIDRO.png" alt="Logo" width="80" class="mb-3">
                        <div class="brand-text h4">SAN ISIDRO LABRADOR</div>
                        <small class="text-muted">RESORT AND LEISURE FARM</small>
                    </div>

                    <div class="text-center mb-4">
                        <i class="bi bi-envelope-check text-success" style="font-size: 3rem;"></i>
                        <h2 class="mt-3">Check Your Email</h2>
                    </div>

                    <?php if (session()->has('message')): ?>
                        <div class="alert alert-success">
                            <?= session('message') ?>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-success">
                            <strong>Success!</strong> We've sent an activation link to your email address.
                        </div>
                    <?php endif; ?>

                    <div class="alert alert-info">
                        <h6><i class="bi bi-info-circle"></i> What's Next?</h6>
                        <ul class="mb-0 mt-2">
                            <li>Check your email inbox</li>
                            <li>Click the activation link</li>
                            <li>Login to your account</li>
                        </ul>
                    </div>

                    <div class="text-center mt-4">
                        <a href="<?= site_url('login') ?>" class="btn btn-primary btn-lg">
                            <i class="bi bi-box-arrow-in-right"></i> Continue to Login
                        </a>
                    </div>

                    <!-- <div class="text-center mt-3">
                        <small class="text-muted">
                            Didn't receive the email? 
                            <a href="<?= site_url('resend-activation') ?>" class="text-decoration-none">Resend activation link</a>
                        </small>
                    </div> -->
                </div>
            </div>
        </div>
    </div>
</body>
</html>