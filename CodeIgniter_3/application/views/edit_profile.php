<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Profile - VividSpace</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f8f9fa;
        }
        .container {
            padding: 1rem;
        }
        .header-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
        }
        .logo-and-home {
            display: flex;
            align-items: center;
        }
        .logo-and-home h1 {
            margin-right: 10px;
            margin-bottom: 0;
        }
        .form-container {
            max-width: 600px;
            margin: auto;
            padding: 2rem;
            background-color: #fff;
            border-radius: 0.5rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.1);
        }
        .profile-icon {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #333;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .button-container {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
        h1 {
            font-family: 'Montserrat', sans-serif; 
            font-weight: bold; 
            color: #333333; 
            text-align: center;
            margin-bottom: 30px; 
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header-section">
            <div class="logo-and-home">
                <!-- Logo -->
                <h1>VividSpace</h1>
                <!-- Home button -->
                <a href="<?= site_url('profile/feed'); ?>" class="btn btn-primary ml-2">Home</a>
            </div>
            <div class="col-6 col-md-8 d-flex justify-content-end">
                <!-- User Icon -->
                <a href="<?= site_url('profile'); ?>">
                    <img src="<?= base_url('Images/user_icon.jpg'); ?>" class="profile-icon" alt="Profile">
                </a>
            </div>
        </div>
        <div class="form-container">    
            <form action="<?= site_url('profile/update'); ?>" method="post" enctype="multipart/form-data" novalidate>
                <div class="form-group">
                    <label for="first_name">First Name</label>
                    <input type="text" class="form-control" name="first_name" value="<?= $profile['first_name'] ?? ''; ?>">
                </div>
                <div class="form-group">
                    <label for="last_name">Last Name</label>
                    <input type="text" class="form-control" name="last_name" value="<?= $profile['last_name'] ?? ''; ?>">
                </div>
                <div class="form-group">
                    <label for="bio">Bio</label>
                    <textarea class="form-control" name="bio"><?= $profile['bio'] ?? ''; ?></textarea>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" name="email" id="email" value="<?= $profile['email'] ?? ''; ?>" required>
                    <div id="email-error" class="text-danger"></div>
                </div>
                <div class="form-group">
                    <label for="profile_image">Profile Picture:</label>
                    <input type="file" name="profile_image">
                </div>
                <div class="button-container">
                    <button type="submit" class="btn btn-primary">Update Profile</button>
                    <a href="<?= site_url('profile'); ?>" class="btn btn-secondary">Cancel</a>
                    <a href="<?= site_url('profile/reset_password'); ?>" class="btn btn-link">Reset Password</a>
                </div>
                <?php if($this->session->flashdata('success')): ?>
                    <div class="alert alert-success">
                        <?= $this->session->flashdata('success'); ?>
                    </div>
                <?php endif; ?>

                <?php if($this->session->flashdata('error')): ?>
                    <div class="alert alert-danger">
                        <?= $this->session->flashdata('error'); ?>
                    </div>
                <?php endif; ?>
            </form>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#email').on('keyup', function() {
                validateEmail($(this).val());
            });
        });

        function validateEmail(email) {
            var emailError = $('#email-error');
            var updateProfileBtn = $('button[type="submit"]'); // Select the "Update Profile" button
            
            // Regular expression for email validation
            var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            
            if (!emailRegex.test(email)) {
                emailError.text('Please enter a valid email address.');
                updateProfileBtn.prop('disabled', true); // Disable "Update Profile" button
            } else {
                emailError.text('');
                updateProfileBtn.prop('disabled', false); // Enable "Update Profile" button
            }
        }
    </script>
</body>
</html>
