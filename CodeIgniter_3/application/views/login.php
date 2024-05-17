<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - VividSpace</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.13.1/underscore-min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/backbone.js/1.4.0/backbone-min.js"></script>
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
                    <input type="text" class="form-control" id="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" required>
                </div>
                <button type="submit" class="btn btn-primary">Login</button>
                <p class="chnage-link">Don’t have an account? <a href="signup">Sign Up</a> here</p>
            </form>
        </div>
    </div>
</div>

<script>
    var Login = Backbone.Model.extend({
        urlRoot: 'login',
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
                    $('#error-msg').text('Invalid username or password').show();
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
