<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Signup - VividSpace</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/app.css'); ?>">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
        .form-group label {
            font-weight: bold;
            color: #555555;
        }
        .btn-dark {
            border-radius: 8px;
            width: 70%;
            display: block;
            margin: 20px auto 0;
        }
        .mt-3 {
            text-align: center;
        }
        .mt-3 a {
            color: #007bff;
            text-decoration: none;
        }
        .mt-3 a:hover {
            text-decoration: underline;
        }
        .required::after {
            content: '*';
            color: red;
            margin-left: 5px;
        }
        #errormsg {
            color: #ff0000; /* Red text color */
            border-radius: 5px;
            margin-bottom: 15px;
        }
        .field-hint {
            font-size: 0.85rem;
            color: #6c757d;
            margin-top: 4px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <img src="<?= base_url('Images/vividSpace_Intro.png'); ?>" alt="VividSpace" class="auth-logo">
        <form method="post" id="signupForm" name="signupform">
            <div class="errormsg" id="errormsg"></div>
            <div class="form-group">
                <label for="username">Username<span class="required"></span></label>
                <input type="text" class="form-control" name="username" id="username" placeholder="Username" onkeyup="checkusername(); checkinputs();" required />
                <div class="field-hint">4-50 characters. Letters, numbers, and underscores only.</div>
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
                <input type="email" class="form-control" name="email" id="email" placeholder="Email" onkeyup="checkemail(); checkinputs();" required />
                <div class="field-hint">We'll never share your email.</div>
            </div>
            <div class="form-group">
                <label for="password">Password<span class="required"></span></label>
                <input type="password" class="form-control" name="password" id="password" placeholder="Password" onkeyup="checkinputs();" required>
                <div class="field-hint">At least 6 characters.</div>
            </div>
            <button type="submit" id="createUser" class="btn btn-dark" disabled="disabled">Signup</button>
            <p class="mt-3"> Have an account? <a href="<?= site_url('login'); ?>">Login</a></p>
        </form>
    </div>

    <script src="<?= base_url('assets/js/csrf-ajax.js'); ?>"></script>
    <script>
        function setError(msg) {
            document.getElementById("errormsg").textContent = msg;
            document.getElementById('createUser').disabled = !!msg;
        }

        function checkinputs() {
            var u = document.forms["signupform"]["username"].value;
            var e = document.forms["signupform"]["email"].value;
            var p = document.forms["signupform"]["password"].value;
            var hasError = document.getElementById("errormsg").textContent !== "";
            var usernameOk = u.length >= 4 && u.length <= 50;
            var passwordOk = p.length >= 6;
            document.getElementById('createUser').disabled = !(usernameOk && e && passwordOk && !hasError);
        }

        function checkemail() {
            var email = $('#email').val();
            var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (!emailRegex.test(email)) {
                setError("Please enter a valid e-mail address");
                return;
            }
            setError("");

            csrfPost(
                '<?= site_url('signup/check_email'); ?>',
                { email: email },
                function (data) {
                    if (data.exists) {
                        setError("Email already in use");
                    } else {
                        setError("");
                        checkinputs();
                    }
                }
            );
        }

        function checkusername() {
            var username = $('#username').val().toLowerCase();
            var regex = /^[a-zA-Z0-9_]+$/;

            if (!regex.test(username)) {
                setError("Username can only contain letters, numbers, and underscores");
                return;
            }
            setError("");

            csrfPost(
                '<?= site_url('signup/check_user'); ?>',
                { username: username },
                function (data) {
                    if (data.exists) {
                        setError("Username Already Exists!");
                    } else {
                        setError("");
                        checkinputs();
                    }
                }
            );
        }

        $(document).ready(function () {
            $('#createUser').click(function (event) {
                event.preventDefault();
                userSignup();
            });
        });

        function userSignup() {
            csrfPost(
                '<?= site_url('signup/create'); ?>',
                {
                    username:   $("#username").val().toLowerCase(),
                    email:      $("#email").val(),
                    first_name: $("#first_name").val(),
                    last_name:  $("#last_name").val(),
                    password:   $("#password").val()
                },
                function (response) {
                    if (response.success) {
                        window.location.href = response.redirect_url;
                    } else {
                        setError(response.error || 'Failed to create user');
                    }
                },
                function () {
                    setError('Network error. Please try again.');
                }
            );
        }
    </script>
</body>
</html>