<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete Account - VividSpace</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/app.css'); ?>">
    <style>
        .container { max-width: 480px; padding-top: 3rem; }
        .danger-box { border: 2px solid #dc3545; border-radius: 8px; padding: 2rem; background: #fff5f5; }
    </style>
</head>
<body>
<div class="container">
    <?php $this->load->view('partials/header'); ?>

    <div class="danger-box mt-4">
        <h4 class="text-danger">Delete Your Account</h4>
        <p class="text-muted">This will permanently delete your account, posts, comments, likes, follows, and all other data. This action <strong>cannot be undone</strong>.</p>

        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($this->session->flashdata('error')); ?></div>
        <?php endif; ?>

        <form method="post" action="<?= site_url('profile/delete_account'); ?>" onsubmit="return confirm('Are you absolutely sure? This cannot be undone.');">
            <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
            <div class="form-group">
                <label for="password">Confirm your password</label>
                <input type="password" id="password" name="password" class="form-control" required autofocus>
            </div>
            <button type="submit" class="btn btn-danger btn-block">Delete My Account</button>
            <a href="<?= site_url('profile/index'); ?>" class="btn btn-secondary btn-block mt-2">Cancel</a>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>
</html>
