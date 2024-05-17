<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Signup - VividSpace</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.8.3/underscore-min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/backbone.js/1.2.3/backbone-min.js"></script>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f8f9fa;
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
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1>VividSpace</h1>
        <form method="post" id="signupForm" name="signupform">
            <div class="errormsg" id="errormsg"></div>
            <div class="form-group">
                <label for="username">Username<span class="required"></span></label>
                <input type="text" class="form-control" name="username" id="username" placeholder="Username" onkeyup="checkusername(); checkinputs();" required />
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
                <input type="email" class="form-control" name="email" id="email" placeholder="Email" onkeyup="checkinputs(); validateemail();" required />
            </div>
            <div class="form-group">
                <label for="password">Password<span class="required"></span></label>
                <input type="password" class="form-control" name="password" id="password" placeholder="Password" required>
            </div>
            <button type="submit" id="createUser" class="btn btn-dark" disabled="disabled">Signup</button>
            <p class="mt-3"> Have an account? <a href="<?= site_url('login'); ?>">Login</a></p>
        </form>
    </div>

    <script>
        function checkinputs() {
            if (document.forms["signupform"]["username"].value != "" && document.forms["signupform"]["email"].value != "" && document.forms["signupform"]["password"].value != "" && document.getElementById("errormsg").innerHTML == "") {
                document.getElementById('createUser').disabled = false;
            } else {
                document.getElementById('createUser').disabled = true;
            }
        }

        function validateemail() {
            var x = document.forms["signupform"]["email"].value;
            var atposition = x.indexOf("@");
            var dotposition = x.lastIndexOf(".");
            if (atposition < 1 || dotposition < atposition + 2 || dotposition + 2 >= x.length) {
                document.getElementById("errormsg").innerHTML = "Please enter a valid e-mail address";
                document.getElementById('createUser').disabled = true;
            } else {
                document.getElementById("errormsg").innerHTML = "";
                checkinputs();
            }
        }

        function checkusername() {
            var username = $('#username').val().toLowerCase();
            var regex = /^[a-zA-Z0-9_]+$/;

            if (!regex.test(username)) {
                document.getElementById("errormsg").innerHTML = "Username can only contain letters, numbers, and underscores";
                document.getElementById('createUser').disabled = true;
                return;
            } else {
                document.getElementById("errormsg").innerHTML = "";
            }

            $.ajax({
                url: "<?= base_url() ?>index.php/signup/check_user",
                data: { 'username': username },
                method: "POST"
            }).done(function (data) {
                if (data == 0) {
                    document.getElementById("errormsg").innerHTML = "";
                    checkinputs();
                } else {
                    document.getElementById("errormsg").innerHTML = "Username Already Exists!";
                }
            });
        }

        $(document).ready(function () {
            $('#createUser').click(function (event) {
                event.preventDefault();
                userSignup();
            });
        });

        var User = Backbone.Model.extend({
            urlRoot: '<?= site_url('signup/index_post'); ?>',
            defaults: {
                username: '',
                email: '',
                first_name: '',
                last_name: '',
                password: ''
            },
            validate: function (attrs) {
                if (!attrs.username) {
                    return "Username is required.";
                }
                if (!attrs.email) {
                    return "Email is required.";
                }
                if (!attrs.password) {
                    return "Password is required.";
                }
            }
        });

        var UserCollection = Backbone.Collection.extend({
            model: User
        });

        var usersCollection = new UserCollection();

        function userSignup() {
            var newUser = new User({
                username: $("#username").val().toLowerCase(),
                email: $("#email").val(),
                first_name: $("#first_name").val(),
                last_name: $("#last_name").val(),
                password: $("#password").val()
            });

            usersCollection.create(newUser, {
                wait: true,
                success: function (model, response) {
                    if (response.success) {
                        window.location.href = response.redirect_url;
                    } else {
                        document.getElementById("errormsg").innerHTML = 'Failed to create user: ' + response.error;
                    }
                },
                error: function (model, xhr, options) {
                    document.getElementById("errormsg").innerHTML = 'AJAX error: ' + xhr.statusText;
                }
            });
        }
    </script>
</body>
</html>