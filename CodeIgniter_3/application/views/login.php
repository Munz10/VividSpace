<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - VividSpace</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f8f9fa;
        }
        form {
            width: 500px;
            margin: 40px auto 0;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.3);
            background-color: #fff;
        }
        h1 {
            font-family: 'Montserrat', sans-serif; 
            font-weight: bold; 
            color: #333333; 
            text-align: center;
            margin-bottom: 30px; 
        }
        label {
            font-weight: bold;
            color: #555555;
        }
        .btn-primary {
            border-radius: 8px;
            width: 70%; 
            display: block; 
            margin: 20px auto 0; 
        }
        .alert-danger {
            border-radius: 5px;
            display: none;
        }
        .chnage-link{
            text-align: center;
            padding: 15px;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <h1>VividSpace</h1>
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="alert alert-danger" id="error-msg" style="display: none;"></div>
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
                <p class="chnage-link">Don't have an account? <a href="signup">Sign Up</a> here</p>
                <p class="chnage-link text-muted"><small>Having login issues? <a href="fix_accounts">Fix Account</a> | <a href="test_account">Test Login</a></small></p>
            </form>
        </div>
    </div>
</div>

<!-- Include CSRF AJAX Helper -->
<script src="<?= base_url('assets/js/csrf-ajax.js'); ?>"></script>

<script>
$(document).ready(function() {
    $('#login-form').on('submit', function(e) {
        e.preventDefault();
        
        var username = $('#username').val();
        var password = $('#password').val();
        var submitBtn = $(this).find('button[type="submit"]');
        var errorMsg = $('#error-msg');
        
        // Show loading state
        submitBtn.prop('disabled', true).text('Logging in...');
        errorMsg.hide();
        
        // Use CSRF-protected POST
        csrfPost(
            '<?= site_url("login/process"); ?>',
            {
                username: username,
                password: password
            },
            function(response) {
                if (response.status === 'success') {
                    errorMsg.removeClass('alert-danger').addClass('alert-success')
                            .text(response.message + ' Redirecting...').show();
                    
                    // Redirect to profile
                    setTimeout(function() {
                        window.location.href = response.redirect;
                    }, 500);
                } else {
                    // Show error
                    errorMsg.removeClass('alert-success').addClass('alert-danger')
                            .text(response.message).show();
                    
                    // Re-enable button
                    submitBtn.prop('disabled', false).text('Login');
                    
                    // Clear password
                    $('#password').val('').focus();
                }
            },
            function(error) {
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
