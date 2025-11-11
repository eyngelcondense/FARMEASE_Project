<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Settings - San Isidro Labrador</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        .profile-container {
            max-width: 600px;
            margin: 0 auto;
        }
        .profile-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            padding: 30px;
        }
        .profile-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .profile-pic-container {
            position: relative;
            display: inline-block;
        }
        .profile-pic {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 5px solid #7c6a43;
        }
        .profile-upload-btn {
            position: absolute;
            bottom: 10px;
            right: 10px;
            background: #7c6a43;
            color: white;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }
        .brand-name {
            font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
            color: #7c6a43;
            font-size: 1.5rem;
            font-weight: bold;
            margin-top: 15px;
        }
        .form-label {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 8px;
        }
        .form-control {
            border-radius: 8px;
            padding: 12px 15px;
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
        }
        .form-control:focus {
            border-color: #7c6a43;
            box-shadow: 0 0 0 0.2rem rgba(124, 106, 67, 0.25);
        }
        .btn-primary {
            background: #7c6a43;
            border: none;
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background: #6a5a38;
            transform: translateY(-2px);
        }
        .alert {
            border-radius: 10px;
            border: none;
        }
    </style>
</head>
<body>
    <div class="profile-container">
        <div class="profile-card">
            <!-- Header -->
            <div class="profile-header">
                <div class="profile-pic-container">
                    <?php if (!empty($client['profile_pic'])): ?>
                            <img src="/uploads/profile_pics/<?= esc($client['profile_pic']) ?>" 
                                 class="rounded-circle border" 
                                 width="120" 
                                 height="120" 
                                 style="object-fit: cover; border: 3px solid #7c6a43 !important;">
                        <?php else: ?>
                            <img src="https://ui-avatars.com/api/?name=<?= urlencode($user->username ?? 'User') ?>&background=7c6a43&color=fff&size=120" 
                                 class="rounded-circle border" 
                                 width="120" 
                                 height="120" 
                                 style="object-fit: cover; border: 3px solid #7c6a43 !important;">
                        <?php endif; ?>
                </div>
                <div class="brand-name">SAN ISIDRO LABRADOR</div>
                <small class="text-muted">RESORT AND LEISURE FARM</small>
                <h2 class="mt-3">Profile Settings</h2>
            </div>

            <!-- Messages -->
            <?php if (session()->has('message')): ?>
                <div class="alert alert-success">
                    <i class="bi bi-check-circle-fill"></i>
                    <?= session('message') ?>
                </div>
            <?php endif; ?>

            <?php if (session()->has('errors')): ?>
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-circle-fill"></i>
                    <strong>Please fix the following errors:</strong>
                    <ul class="mb-0 mt-2">
                        <?php foreach (session('errors') as $error): ?>
                            <li><?= $error ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <!-- Profile Form -->
            <form action="<?= site_url('profile-update') ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>

                <!-- Profile Picture Upload -->
                <div class="mb-4">
                    <label class="form-label">Profile Picture</label>
                    <input type="file" name="profile_pic" class="form-control" accept="image/*">
                    <small class="text-muted">Max size: 2MB | Formats: JPG, PNG, GIF</small>
                </div>

                <!-- Email (Read-only) -->
                <div class="mb-3">
                    <label class="form-label">Email Address</label>
                    <input type="email" class="form-control" value="<?= esc($client['email'] ?? $user->username) ?>" readonly>
                    <small class="text-muted">Email cannot be changed</small>
                </div>

                <!-- Full Name (Read-only if from user table) -->
                <div class="mb-3">
                    <label class="form-label">Full Name</label>
                    <input type="text" class="form-control" value="<?= esc($client['fullname'] ?? $user->username) ?>" readonly>
                </div>

                <!-- Phone -->
                <div class="mb-3">
                    <label class="form-label">Phone Number</label>
                    <input type="text" name="phone" class="form-control" 
                           value="<?= esc($client['phone'] ?? '') ?>" 
                           placeholder="+63 912 345 6789" required>
                </div>

                <!-- Address -->
                <div class="mb-4">
                    <label class="form-label">Address</label>
                    <textarea name="address" class="form-control" rows="4" 
                              placeholder="Enter your complete address" required><?= esc($client['address'] ?? '') ?></textarea>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-check-lg"></i> Save Changes
                </button>
            </form>

            <!-- Navigation -->
            <div class="text-center mt-4">
                <a href="<?= site_url('/home') ?>" class="text-decoration-none">
                    <i class="bi bi-arrow-left"></i> Back to Home
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Preview image before upload
        document.addEventListener('DOMContentLoaded', function() {
            const fileInput = document.querySelector('input[type="file"]');
            const profilePic = document.querySelector('.profile-pic');
            
            fileInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        profilePic.src = e.target.result;
                    }
                    reader.readAsDataURL(file);
                }
            });
        });
    </script>
</body>
</html>