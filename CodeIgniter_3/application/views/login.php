<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - VividSpace</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/app.css'); ?>">
    <style>
        label { font-weight: bold; color: #555; }
        .btn-primary { border-radius: 8px; width: 70%; display: block; margin: 20px auto 0; }
        .chnage-link { text-align: center; padding: 15px 0 0; }
    </style>
</head>
<body class="auth-page">

<div class="auth-card">
    <div class="auth-brand">
        <img src="<?= base_url('Images/vividSpace_Intro.png'); ?>" alt="VividSpace" class="auth-logo">
    </div>
    <div class="auth-form-body">
        <div class="alert alert-danger" id="error-msg" style="display:none;"></div>
        <form id="login-form">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" class="form-control" id="username" placeholder="Enter your username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" class="form-control" id="password" placeholder="Enter your password" required>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
            <p class="chnage-link">Don't have an account? <a href="<?= site_url('signup'); ?>">Sign Up</a> here</p>
        </form>
    </div>
</div>

<script src="<?= base_url('assets/js/csrf-ajax.js'); ?>"></script>
<script>
$(document).ready(function() {
    $('#login-form').on('submit', function(e) {
        e.preventDefault();
        var username  = $('#username').val();
        var password  = $('#password').val();
        var submitBtn = $(this).find('button[type="submit"]');
        var errorMsg  = $('#error-msg');

        submitBtn.prop('disabled', true).text('Logging in...');
        errorMsg.hide();

        csrfPost(
            '<?= site_url("login/process"); ?>',
            { username: username, password: password },
            function(response) {
                if (response.status === 'success') {
                    errorMsg.removeClass('alert-danger').addClass('alert-success')
                            .text(response.message + ' Redirecting...').show();
                    setTimeout(function() { window.location.href = response.redirect; }, 500);
                } else {
                    errorMsg.removeClass('alert-success').addClass('alert-danger')
                            .text(response.message).show();
                    submitBtn.prop('disabled', false).text('Login');
                    $('#password').val('').focus();
                }
            },
            function() {
                errorMsg.removeClass('alert-success').addClass('alert-danger')
                        .text('An error occurred. Please try again.').show();
                submitBtn.prop('disabled', false).text('Login');
            }
        );
    });
});
</script>
</body>
</html>
