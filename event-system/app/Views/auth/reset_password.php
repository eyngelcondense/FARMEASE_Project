<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="icon" type="image/png" href="images/LOGO NG SAN ISIDRO.png"/>
</head>
<style>
    body {
        background: linear-gradient(to bottom right, #f7f4ef, #e5dfd2);
        font-family: "Cambria", "Times New Roman", serif;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
    }

    .forgot-container {
        width: 100%;
        max-width: 420px;
        padding: 20px;
    }

    .forgot-card {
        background: #ffffff;
        border-radius: 15px;
        padding: 35px 30px;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.10);
        text-align: center;
        animation: fadeIn 0.4s ease;
    }

    .logo {
        width: 80px;
        height: 80px;
        object-fit: contain;
        margin-bottom: 8px;
    }

    h1 {
        font-size: 28px;
        margin-bottom: 5px;
        font-weight: bold;
        color: #5c4a23;
    }

    .subtitle {
        font-size: 14px;
        color: #7a7a7a;
        margin-bottom: 25px;
    }

    .input-group-text {
        background: #f2f2f2;
        border-right: none;
    }

    .form-control {
        border-left: none;
        border-radius: 0 5px 5px 0 !important;
    }

    .input-group {
        border: 1px solid #ddd;
        border-radius: 5px;
        overflow: hidden;
    }

    .btn-reset {
        width: 100%;
        padding: 12px;
        background: #7c6a43;
        border: none;
        border-radius: 6px;
        color: white;
        font-size: 16px;
        cursor: pointer;
        margin-top: 10px;
        transition: 0.2s ease;
    }

    .btn-reset:hover {
        background: #5e4e30;
    }

    .back-link {
        display: block;
        margin-top: 18px;
        font-size: 14px;
        color: #7c6a43;
        text-decoration: none;
    }

    .back-link:hover {
        text-decoration: underline;
    }

    .alert {
        text-align: left;
        font-size: 14px;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<body>
    <div class="forgot-container">
        <div class="forgot-card">
            <img src="images/LOGO NG SAN ISIDRO.png" alt="San Isidro Labrador Logo" class="logo">
            <div style="color:#7c6a43; font-size: 18px;">SAN ISIDRO LABRADOR</div>
            <small style="letter-spacing: 1px; color:#7a7a7a;">RESORT AND LEISURE FARM</small>

            <h1>Reset Password</h1>
            <p class="subtitle">Enter your new password below.</p>

            <?php if (session()->has('error')): ?>
                <div class="alert alert-danger"><?= session('error') ?></div>
            <?php endif; ?>

            <form method="POST" action="<?= site_url('reset-password') ?>">
                <?= csrf_field() ?>
                <input type="hidden" name="token" value="<?= $token ?>">
                <input type="hidden" name="email" value="<?= $email ?>">

                <div class="mb-3">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input name="password" type="password" class="form-control" placeholder="New Password" required minlength="8">
                    </div>
                </div>

                <div class="mb-3">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input name="confirm_password" type="password" class="form-control" placeholder="Confirm New Password" required minlength="8">
                    </div>
                </div>

                <button type="submit" class="btn-reset">Reset Password</button>
            </form>

            <a href="<?= site_url('login') ?>" class="back-link">Back to Login</a>
        </div>
    </div>
</body>
</html>