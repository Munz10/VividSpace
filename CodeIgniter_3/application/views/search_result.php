<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Results - VividSpace</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .profile-icon {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #333; /* Replace with actual profile image if available */
        }
        .search-result {
            margin-bottom: 10px;
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
            <a href="<?= site_url('profile/feed'); ?>" class="btn btn-primary ml-2">Home</a>
        </div>
        <div class="col-6 col-md-8 d-flex justify-content-end">
            <!-- User Icon -->
            <a href="<?= site_url('profile'); ?>">
                <img src="<?= base_url('Images/user_icon.jpg'); ?>" class="profile-icon" alt="Profile">
            </a>
        </div>
    </div>

    <div class="search-results">
        <h2>Search Results</h2>
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
