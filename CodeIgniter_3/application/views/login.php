<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - VividSpace</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2>Login</h2>
            <?php if(isset($error)): ?>
                <div class="alert alert-danger"><?= $error; ?></div>
            <?php endif; ?>
            <?= form_open('login/process'); ?>
                <div class="form-group">
                    <input type="text" class="form-control" name="username" placeholder="Username" required>
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" name="password" placeholder="Password" required>
                </div>
                <button type="submit" class="btn btn-primary">Login</button>
            <?= form_close(); ?>
            <p>Don’t have an account? <a href="<?= site_url('signup'); ?>">Sign Up</a> here</p>
        </div>
    </div>
</div>
<script src="<?= base_url('assets/js/bootstrap.min.js'); ?>"></script>
</body>
</html>
