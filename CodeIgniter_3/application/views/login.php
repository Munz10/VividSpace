<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - VividSpace</title>
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
            <?php if(isset($error)): ?>
                <div class="alert alert-danger"><?= $error; ?></div>
            <?php endif; ?>
            <?= form_open('login/process'); ?>
                <h3>Login</h3>
                <div class="form-group">
                    <input type="text" class="form-control" name="username" placeholder="Username" required>
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" name="password" placeholder="Password" required>
                </div>
                <button type="submit" class="btn btn-primary">Login</button>
                <p>Don’t have an account? <a href="<?= site_url('signup'); ?>">Sign Up</a> here</p>
            <?= form_close(); ?>
        </div>
    </div>
</div>
<script src="<?= base_url('assets/js/bootstrap.min.js'); ?>"></script>
</body>
</html>
