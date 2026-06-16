<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password - VividSpace</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/app.css'); ?>">
    <style>
        h2 { margin-top: 1rem; text-align: center; }
        .container { padding: 1rem; }
        form {
            max-width: 600px;
            margin: auto;
            padding: 2rem;
            background-color: #fff;
            border-radius: 0.5rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.1);
        }
        .resetbtn {
            background-color: #003789;
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php $this->load->view('partials/header'); ?>
        <h2>Reset Password</h2>
        <form action="<?= site_url('profile/reset_password'); ?>" method="post">
            <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">

            <?php if ($this->session->flashdata('error')): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($this->session->flashdata('error')); ?></div>
            <?php endif; ?>
            <?php if ($this->session->flashdata('success')): ?>
                <div class="alert alert-success"><?= htmlspecialchars($this->session->flashdata('success')); ?></div>
            <?php endif; ?>

            <div class="form-group">
                <label for="old_password">Current Password</label>
                <input type="password" class="form-control" id="old_password" name="old_password" required>
            </div>
            <div class="form-group">
                <label for="new_password">New Password</label>
                <input type="password" class="form-control" id="new_password" name="new_password" minlength="6" required>
                <small class="text-muted">At least 6 characters. Must be different from your current password.</small>
            </div>
            <div class="form-group">
                <label for="confirm_new_password">Confirm New Password</label>
                <input type="password" class="form-control" id="confirm_new_password" name="confirm_new_password" minlength="6" required>
            </div>
            <button type="submit" class="btn resetbtn">Reset Password</button>
            <a href="<?= site_url('profile/edit'); ?>" class="btn btn-link">Cancel</a>
        </form>
    </div>
</body>
</html>
