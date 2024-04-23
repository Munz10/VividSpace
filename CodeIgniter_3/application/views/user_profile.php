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
            border-radius: 50%;
            background: url('path_to_profile_image'); /* Replace with path to user's profile image */
            background-size: cover;
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
    </style>
</head>
<body>
    <div class="container">
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
                    <img src="<?= base_url() . $post['image_path']; ?>" alt="Post Image">
                    <!-- Add other post details if needed -->
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script src="<?= base_url('assets/js/bootstrap.min.js'); ?>"></script>
</body>
</html>
