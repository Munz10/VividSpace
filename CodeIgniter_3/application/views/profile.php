<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($profile['username']); ?>'s Profile - VividSpace</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Inline styles are used here for brevity */
        .header-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .profile-section {
            display: flex;
        }
        .profile-info {
            flex: 1;
            text-align: center;
        }
        .profile-pic {
            width: 150px;
            height: 150px;
            background: #ccc;
            border-radius: 50%;
            display: inline-block;
        }
        .followers-info {
            flex: 1;
            text-align: center;
        }
        .content-section {
            background: #f8f9fa;
            padding: 15px;
            margin-top: 20px;
        }
        .post {
            border: 1px solid #ddd; /* Adds a border around each post */
            margin-bottom: 15px; /* Adds space between rows */
        }
        .post img {
            width: 100%; /* Ensures the image takes up the full width of the card */
            height: auto; /* Ensures the image height is automatically adjusted */
            border-bottom: 1px solid #ddd; /* Adds a separator between the image and the caption */
        }
        .post-body {
            padding: 15px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header-section">
        <h1>VividSpace</h1>
        <div>
            <a href="<?= site_url('profile/feed'); ?>" class="btn btn-primary">Home</a>
            <a href="#" class="btn btn-secondary">Edit profile</a>
            <a href="#" class="btn btn-dark">Log out</a>
        </div>
    </div>
    
    <div class="profile-section">
        <div class="profile-info">
            <div class="profile-pic"></div>
            <h3><?= htmlspecialchars($profile['username']); ?></h3>
            <p>First Name - Last Name: <!-- Add dynamic data when available --></p>
            <p>I am interested in: <!-- Add dynamic bio data when available --></p>
        </div>
        <div class="followers-info">
            <p><strong>123</strong> Followers</p> <!-- Replace with dynamic data later -->
            <p><strong>333</strong> Following</p> <!-- Replace with dynamic data later -->
        </div>
    </div>

    <div class="content-section">
        <!-- Additional content goes here -->
    </div>
    <div class="row">
        <?php foreach ($posts as $post): ?>
        <div class="col-md-4"> <!-- This ensures that each post takes up 4 columns in the grid -->
            <div class="post">
                <a href="<?= site_url('post/detail/' . $post['id']); ?>">
                    <img src="<?= base_url() . $post['image_path']; ?>" alt="Post Image">
                </a>
                <div class="post-body">
                    <p><?= htmlspecialchars($post['caption']); ?></p>
                    <!-- Display other post details -->
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<script src="<?= base_url('assets/js/bootstrap.min.js'); ?>"></script>
</body>
</html>
