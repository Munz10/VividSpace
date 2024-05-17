<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to VividSpace</title>
    <!-- Include Bootstrap CSS from CDN -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Custom CSS styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }
        h1 {
            font-family: 'Montserrat', sans-serif;
            font-weight: bold;
            color: #333333;
            text-align: center;
        }
        .container {
            padding-top: 2rem;
        }
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }
        main {
            margin-top: 2rem;
        }
        .img-wrapper {
            column-count: 3; /* Set the number of columns */
            column-gap: 20px; /* Adjust the gap between columns */
        }
        .img-wrapper img {
            width: 100%; /* Make images take full width of their container */
            border-radius: 0.5rem;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px; /* Add margin to the bottom of each image */
            break-inside: avoid; /* Prevent images from breaking inside columns */
        }
        .btn {
            font-weight: bold;
            text-transform: uppercase;
            border-radius: 0.5rem;
            margin-right: 1rem;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
        }
        .btn:hover {
            opacity: 0.8;
        }
        h1 {
            color: #333;
        }
    </style>
</head>
<body>

<div class="container">
    <header>
        <h1>VividSpace</h1>
        <nav>
            <a href="<?= site_url('login'); ?>" class="btn btn-primary">Log In</a>
            <a href="<?= site_url('signup'); ?>" class="btn btn-secondary">Sign Up</a>
        </nav>
    </header>

    <main>
        <div class="img-wrapper">
            <!-- Static images with Bootstrap classes -->
            <?php for ($i = 1; $i <= 6; $i++): ?>
            <img src="<?= base_url('Images/image_' . $i . '.jpg'); ?>" alt="Static Image <?= $i; ?>">
            <?php endfor; ?>
        </div>
    </main>
</div>

<script src="<?= base_url('assets/js/script.js'); ?>"></script>
</body>
</html>
