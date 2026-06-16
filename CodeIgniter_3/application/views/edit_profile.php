<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Profile - VividSpace</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/app.css'); ?>">
    <style>
        .container { padding: 1rem; }
        .form-container {
            max-width: 600px;
            margin: auto;
            padding: 2rem;
            background-color: #fff;
            border-radius: 0.5rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.1);
        }
        .form-group { margin-bottom: 15px; }
        .button-container {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
        .updatebtn {
            background-color: #003789;
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php $this->load->view('partials/header'); ?>
        <div class="form-container">    
            <form action="<?= site_url('profile/update'); ?>" method="post" enctype="multipart/form-data" novalidate>
                <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
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
                    <label>Profile Picture</label>
                    <?php if (!empty($profile['profile_image'])): ?>
                        <img id="profile-preview" src="<?= base_url(ltrim(htmlspecialchars($profile['profile_image']), '/')); ?>"
                             alt="Profile picture" class="profile-pic d-block mb-2">
                    <?php else: ?>
                        <img id="profile-preview" src="<?= base_url('Images/default_profile_pic.png'); ?>"
                             alt="Profile picture" class="profile-pic d-block mb-2">
                    <?php endif; ?>
                    <input type="file" name="profile_image" id="profile_image"
                           accept="image/jpeg,image/png,image/gif"
                           onchange="previewProfile(this);">
                </div>
                <div class="button-container">
                    <button type="submit" class="btn updatebtn">Update Profile</button>
                    <a href="<?= site_url('profile'); ?>" class="btn btn-secondary">Cancel</a>
                    <a href="<?= site_url('profile/reset_password'); ?>" class="btn btn-link">Reset Password</a>
                </div>
                <?php if($this->session->flashdata('success')): ?>
                    <div class="alert alert-success mt-3">
                        <?= $this->session->flashdata('success'); ?>
                    </div>
                <?php endif; ?>
                <?php if($this->session->flashdata('error')): ?>
                    <div class="alert alert-danger mt-3">
                        <?= $this->session->flashdata('error'); ?>
                    </div>
                <?php endif; ?>
            </form>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function previewProfile(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('profile-preview').src = e.target.result;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        $(document).ready(function() {
            $('#email').on('keyup', function() {
                validateEmail($(this).val());
            });
        });

        function validateEmail(email) {
            var emailError = $('#email-error');
            var updateProfileBtn = $('button[type="submit"]');
            
            var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            
            if (!emailRegex.test(email)) {
                emailError.text('Please enter a valid email address.');
                updateProfileBtn.prop('disabled', true);
            } else {
                emailError.text('');
                updateProfileBtn.prop('disabled', false);
            }
        }
    </script>
</body>
</html>
