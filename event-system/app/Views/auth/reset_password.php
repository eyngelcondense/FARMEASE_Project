<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="forgot-container">
    <div class="forgot-card">
        <img src="images/LOGO NG SAN ISIDRO.png" alt="San Isidro Labrador Logo" class="logo">
        <div style="font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif; color:#7c6a43;">SAN ISIDRO LABRADOR</div>
        <small>RESORT AND LEISURE FARM</small>
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