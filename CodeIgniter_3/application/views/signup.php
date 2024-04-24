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
        <form method="post" action="<?= site_url('signup/process'); ?>">
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
</body>
</html>
