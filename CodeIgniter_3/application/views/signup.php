<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Signup - VividSpace</title>
    <!-- Include Bootstrap CSS from CDN -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <form method="post">
            <h2>Signup</h2>
            <div class="form-group">
                <input type="text" class="form-control" name="username" placeholder="Username" required>
                <?= form_error('username'); ?> 
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="first_name" placeholder="First Name" required>
                <?= form_error('first_name'); ?> 
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="last_name" placeholder="Last Name" required>
                <?= form_error('last_name'); ?> 
            </div>
            <div class="form-group">
                <input type="email" class="form-control" name="email" placeholder="Email" required>
                <?= form_error('email'); ?> 
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="password" placeholder="Password" required>
                <?= form_error('password'); ?> 
            </div>
            <button type="submit" class="btn btn-dark">Signup</button>
        </form>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.8.3/underscore-min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/backbone.js/1.2.3/backbone-min.js"></script>
    <script>
        var UserModel = Backbone.Model.extend({
            urlRoot: 'http://localhost/CW/VividSpace/CodeIgniter_3/index.php/signup'
        });

        $('#signupForm').submit(function(event) {
            event.preventDefault();

            var userData = {
                username: $('input[name="username"]').val(),
                first_name: $('input[name="first_name"]').val(),
                last_name: $('input[name="last_name"]').val(),
                email: $('input[name="email"]').val(),
                password: $('input[name="password"]').val()
            };

            var newUser = new UserModel(userData);

            newUser.save(null, {
                success: function(model, response) {
                    console.log('Signup successful:', response);
                    // Redirect to login page
                    window.location.href = 'login.php';
                },
                error: function(model, response) {
                    console.error('Signup error:', response);
                    // Handle signup error, display error messages, etc.
                }
            });
        });
    </script>
</body>
</html>
