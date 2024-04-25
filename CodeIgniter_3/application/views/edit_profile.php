<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Profile - VividSpace</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Inline styles are used here for brevity */
        body {
            padding-top: 50px;
        }
        .container {
            max-width: 600px;
            margin: auto;
        }
        .logo {
            display: block;
            margin: 0 auto 30px;
            text-align: center;
        }
        .logo h1 {
            color: #333;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .btn-primary {
            width: 48%;
            display: inline-block;
        }
        .btn-secondary {
            width: 48%;
            display: inline-block;
            float: right;
        }
    </style>
</head>
<body>
    <div class="container">
    <h2>Edit Profile</h2>
    <?php echo form_open_multipart('profile/update'); ?>
        <div class="form-group">
            <label for="first_name">First Name:</label>
            <input type="text" class="form-control" name="first_name" value="<?= isset($profile['first_name']) ? $profile['first_name'] : ''; ?>">
        </div>
        <div class="form-group">
            <label for="last_name">Last Name:</label>
            <input type="text" class="form-control" name="last_name" value="<?= isset($profile['last_name']) ? $profile['last_name'] : ''; ?>">
        </div>
        <div class="form-group">
            <label for="bio">Bio:</label>
            <textarea class="form-control" name="bio"><?= isset($profile['bio']) ? $profile['bio'] : ''; ?></textarea>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" class="form-control" name="email" value="<?= isset($profile['email']) ? $profile['email'] : ''; ?>" required>
        </div>
        <div class="form-group">
            <label for="profile_image">Profile Picture:</label>
            <input type="file" name="profile_image">
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-primary">Update Profile</button>
            <a href="<?= site_url('profile'); ?>" class="btn btn-secondary">Cancel</a>
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
    <?php echo form_close(); ?>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>
