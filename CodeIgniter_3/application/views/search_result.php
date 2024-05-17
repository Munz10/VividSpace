<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results - VividSpace</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #f8f9fa;
            color: #333333;
        }
        .profile-icon {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #333; 
        }
        .search-result {
            margin: 15px 10px;
            padding: 10px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        h1, h3 {
            font-weight: bold;
            text-align: center;
        }
        .search-results {
            margin-top: 30px;
            max-width: 600px; /* Limiting the maximum width */
            margin-left: auto; /* Centering the results */
            margin-right: auto;
        }
        .home-button {
            background-color: #007bff;
            color: #fff;
            border: none;
        }
        .home-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="row align-items-center my-4">
        <div class="col-6 col-md-3 d-flex align-items-center">
            <!-- Logo -->
            <h1>VividSpace</h1>
            <!-- Home button -->
            <a href="<?= site_url('profile/feed'); ?>" class="btn btn-primary ml-2 home-button">Home</a>
        </div>
        <div class="col-6 col-md-8 d-flex justify-content-end">
            <!-- User Icon -->
            <a href="<?= site_url('profile'); ?>">
                <img src="<?= base_url('Images/user_icon.jpg'); ?>" class="profile-icon" alt="Profile">
            </a>
        </div>
    </div>

    <div class="search-results mx-auto"> <!-- Added mx-auto class for horizontal centering -->
        <h3>Search Results</h3>
        <?php if (empty($results)): ?>
            <p>No users found.</p>
        <?php else: ?>
            <?php foreach($results as $user): ?>
                <div class="search-result">
                    <a href="<?= site_url('profile/view/' . $user['username']); ?>">
                        <?= htmlspecialchars($user['username']); ?>
                    </a>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>
