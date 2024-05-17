<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password - VividSpace</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f8f9fa;
        }
        h1 {
            font-family: 'Montserrat', sans-serif;
            font-weight: bold;
            color: #333333;
            text-align: center;
            margin-bottom: 30px;
        }
        h2 {
            margin-top: 1rem;
            text-align: center;
        }
        .container {
            padding: 1rem;
        }
        .header-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
        }
        .logo-and-home {
            display: flex;
            align-items: center;
        }
        .logo-and-home h1 {
            margin-right: 10px;
            margin-bottom: 0;
        }
        .profile-icon {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #333;
        }
        form {
            max-width: 600px;
            margin: auto;
            padding: 2rem;
            background-color: #fff;
            border-radius: 0.5rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.1);
        }
        .resetbtn{
            background-color: #003789;
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header-section">
            <div class="logo-and-home">
                <h1>VividSpace</h1>
                <a href="<?= site_url('profile/feed'); ?>" class="btn btn-primary ml-2">Home</a>
            </div>
            <a href="<?= site_url('profile'); ?>">
                <img src="<?= base_url('Images/user_icon.jpg'); ?>" class="profile-icon" alt="Profile">
            </a>
        </div>
        <h2>Reset Password</h2>
        <form action="<?= site_url('profile/reset_password'); ?>" method="post">
            <div class="form-group">
                <label for="old_password">Old Password</label>
                <input type="password" class="form-control" name="old_password" required>
            </div>
            <div class="form-group">
                <label for="new_password">New Password</label>
                <input type="password" class="form-control" name="new_password" required>
            </div>
            <div class="form-group">
                <label for="confirm_new_password">Confirm New Password</label>
                <input type="password" class="form-control" name="confirm_new_password" required>
            </div>
            <button type="submit" class="btn resetbtn">Reset Password</button>
        </form>
    </div>
</body>
</html>
