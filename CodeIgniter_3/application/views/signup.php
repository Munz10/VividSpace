<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Signup - VividSpace</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Roboto', sans-serif; /* Set default font */
            background-color: #f8f9fa; /* Light background color */
        }
        .container {
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        form {
            width: 500px;
            margin: 40px auto 0;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.3); /* Added box-shadow for elevation */
            background-color: #fff;
        }
        h1 {
            font-family: 'Montserrat', sans-serif; 
            font-weight: bold; 
            color: #333333; 
            text-align: center;
            margin-bottom: 30px; 
        }
        .form-group label {
            font-weight: bold; /* Make labels bold */
            color: #555555; /* Darker label color */
        }
        .btn-dark {
            border-radius: 8px;
            width: 70%; 
            display: block; 
            margin: 20px auto 0; 
        }
        .mt-3 {
            text-align: center; /* Center align login link */
        }
        .mt-3 a {
            color: #007bff; /* Blue link color */
            text-decoration: none; /* Remove underline */
        }
        .mt-3 a:hover {
            text-decoration: underline; /* Add underline on hover */
        }
        .required::after {
            content: '*';
            color: red;
            margin-left: 5px;
        }
    </style>
</head>
<body>
    <div class="container  mt-5">
        <h1>VividSpace</h1>
        <form method="post" id="signupForm">
            <div class="form-group">
                <label for="username">Username<span class="required"></span></label>
                <input type="text" class="form-control" name="username" id="username" placeholder="Username" required>
            </div>
            <div class="form-group">
                <label for="first_name">First Name</label>
                <input type="text" class="form-control" name="first_name" id="first_name" placeholder="First Name">
            </div>
            <div class="form-group">
                <label for="last_name">Last Name</label>
                <input type="text" class="form-control" name="last_name" id="last_name" placeholder="Last Name">
            </div>
            <div class="form-group">
                <label for="email">Email<span class="required"></span></label>
                <input type="email" class="form-control" name="email" id="email" placeholder="Email" required>
            </div>
            <div class="form-group">
                <label for="password">Password<span class="required"></span></label>
                <input type="password" class="form-control" name="password" id="password" placeholder="Password" required>
            </div>
            <button type="submit" class="btn btn-dark">Signup</button>
            <p class="mt-3"> Have an account? <a href="<?= site_url('login'); ?>" > Login</a></p>
        </form>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/backbone.js/1.2.3/backbone-min.js"></script>
    <script>
        var UserModel = Backbone.Model.extend({
            urlRoot: '<?= site_url('signup/index_post'); ?>'
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
                }
            });
        });
    </script>
</body>
</html>
