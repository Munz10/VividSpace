<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($user_profile['username']); ?>'s Profile - VividSpace</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            background: #fff;
            padding: 2rem;
            margin-top: 2rem;
            border-radius: 0.5rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }
        .profile-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .profile-info {
            text-align: center;
        }
        .profile-image {
            width: 150px;
            height: 150px;
            background: #ccc;
            border-radius: 50%;
            display: inline-block;
        }
        .follow-info {
            text-align: center;
        }
        .post-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            grid-gap: 1rem;
            margin-top: 2rem;
        }
        .post {
            border: 1px solid #ddd;
            border-radius: 0.5rem;
            overflow: hidden;
        }
        .post img {
            width: 100%;
            height: auto;
        }
        .header {
            display: flex;
            justify-content: space-between; /* Aligns logo/home to the left and profile to the right */
            align-items: center;
            padding: 1rem;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .logo-and-home {
            display: flex;
            align-items: center;
        }
        .logo {
            font-size: 1.5rem; /* Unchanged font size */
            font-weight: bold; /* Unchanged font weight */
            margin-right: 10px; /* Space between the logo and home button */
        }
        .home-button {
            padding: 0.375rem 0.75rem; /* Bootstrap's .btn padding, adjust if needed */
            text-decoration: none;
            color: #007bff; /* Bootstrap primary color */
            background-color: transparent;
            border: 1px solid transparent;
            border-color: #007bff;
            border-radius: 0.25rem;
        }
        .profile-icon-container {
            /* No changes here, but you could add styles if needed */
        }
        .profile-icon {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: #ddd; /* Placeholder style */
        }

        /* Update to profile info */
        .profile-header {
            margin-top: 1rem;
            text-align: center;
        }

        .profile-header h1 {
            display: inline-block;
            vertical-align: middle;
            margin-right: 10px;
        }

        .follow-info {
            display: flex;
            justify-content: space-evenly;
            padding: 1rem;
        }

        /* Update to post grid */
        .post-grid {
            grid-template-columns: repeat(3, 1fr); /* Adjust this to match the wireframe */
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo-and-home">
                <!-- Logo and home button are now inline -->
                <h1><span>VividSpace</span></h1>
                <a href="<?= site_url('profile/feed'); ?>" class="home-button">Home</a>
            </div>
            <div class="profile-icon-container">
                <a href="<?= site_url('profile'); ?>">
                    <img src="<?= base_url('Images/user_icon.jpg'); ?>" class="profile-icon" alt="Profile">
                </a>
            </div>
        </div>
        <div class="profile-header">
            <h1><?= htmlspecialchars($user_profile['username']); ?></h1>
            <button class="btn btn-primary">Follow</button>
        </div>
        <div class="profile-info">
            <div class="profile-image"></div>
            <p>First Name - Last Name: <?= isset($user_profile['first_name']) && isset($user_profile['last_name']) ? htmlspecialchars($user_profile['first_name'] . ' ' . $user_profile['last_name']) : 'N/A'; ?></p>
            <p>I am interested in: <?= isset($user_profile['bio']) ? htmlspecialchars($user_profile['bio']) : 'Not specified'; ?></p>
        </div>
        <div class="follow-info">
            <p><strong>123</strong> Followers</p> <!-- Hardcoded for now -->
            <p><strong>456</strong> Following</p> <!-- Hardcoded for now -->
        </div>
        <div class="post-grid">
            <?php foreach ($posts as $post): ?>
                <div class="post">
                    <a href="<?= site_url('post/detail/' . $post['id']); ?>">
                        <img src="<?= base_url() . $post['image_path']; ?>" alt="Post Image">
                    </a>
                    <p><?= htmlspecialchars($post['caption']); ?></p>
                    <!-- Add other post details if needed -->
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script src="<?= base_url('assets/js/bootstrap.min.js'); ?>"></script>
</body>
</html>
