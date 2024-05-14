<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login - VividSpace</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.13.1/underscore-min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/backbone.js/1.4.0/backbone-min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Custom CSS to center the form */
        .container {
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        /* Adjusting form width */
        form {
            width: 500px;
            margin: 40px auto 0;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.1); /* Added box-shadow for elevation */
            background-color: #fff; /* Added background color */
        }
        .btn-dark {
            border-radius: 8px;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <h1 class="text-center">VividSpace</h1>
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="alert alert-danger" id="error-msg" style="display: none;"></div>
            <form id="login-form">
                <h3>Login</h3>
                <div class="form-group">
                    <input type="text" class="form-control" id="username" placeholder="Username" required>
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" id="password" placeholder="Password" required>
                </div>
                <button type="submit" class="btn btn-primary">Login</button>
                <p>Don’t have an account? <a href="<?= site_url('signup'); ?>">Sign Up</a> here</p>
            </form>
        </div>
    </div>
</div>

<script>
    var Login = Backbone.Model.extend({
        urlRoot: '<?php echo base_url()?>index.php/login', // Replace with your API URL
        defaults: {
            username: '',
            password: ''
        }
    });

    var LoginForm = Backbone.View.extend({
        el: '#login-form',
        events: {
            'submit': 'submitForm'
        },
        submitForm: function (e) {
            e.preventDefault();
            var formData = {
                username: this.$('#username').val(),
                password: this.$('#password').val()
            };
            var user = new Login(formData);
            user.save(null, {
                success: function (model, response) {
                    console.log('Login successful');
                    if (response.result === 'success') {
                        window.location.href = 'profile';
                    } else {
                        $('#error-msg').text('Invalid username or password').show();
                    }
                },
                error: function (model, response) {
                    console.error('Error occurred during login');
                    $('#error-msg').text('Error occurred during login').show();
                }
            });
        }
    });

    $(document).ready(function () {
        new LoginForm();
    });
</script>
</body>
</html>
