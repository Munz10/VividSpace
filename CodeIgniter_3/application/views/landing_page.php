<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome to VividSpace</title>
    <!-- Include Bootstrap CSS from CDN -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<div class="container">
    <header class="d-flex justify-content-between align-items-center my-3">
        <h1>VividSpace</h1>
        <nav>
            <a href="<?= site_url('login'); ?>" class="btn btn-primary">Log In</a>
            <a href="<?= site_url('signup'); ?>" class="btn btn-secondary">Sign Up</a>
        </nav>
    </header>

    <main>
        <div class="row">
            <!-- Static images with Bootstrap classes -->
            <div class="col-md-4 mb-4"><img src="<?= base_url('Images/image_1.jpg'); ?>" class="img-fluid" alt="Static Image"></div>
            <div class="col-md-4 mb-4"><img src="<?= base_url('Images/image_2.jpg'); ?>" class="img-fluid" alt="Static Image"></div>
            <div class="col-md-4 mb-4"><img src="<?= base_url('Images/image_3.jpg'); ?>" class="img-fluid" alt="Static Image"></div>
            <div class="col-md-4 mb-4"><img src="<?= base_url('Images/image_4.jpg'); ?>" class="img-fluid" alt="Static Image"></div>
            <div class="col-md-4 mb-4"><img src="<?= base_url('Images/image_5.jpg'); ?>" class="img-fluid" alt="Static Image"></div>
            <div class="col-md-4 mb-4"><img src="<?= base_url('Images/image_6.jpg'); ?>" class="img-fluid" alt="Static Image"></div>
            <!-- Repeat for other static images -->
        </div>
    </main>
</div>

<script src="<?= base_url('assets/js/script.js'); ?>"></script>
</body>
</html>

