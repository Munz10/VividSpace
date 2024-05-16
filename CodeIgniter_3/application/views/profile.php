<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($profile['username']); ?>'s Profile - VividSpace</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        html, body {
            height: 100%;
            background-color: #f8f9fa;
        }
        h1 {
            font-family: 'Montserrat', sans-serif; 
            font-weight: bold; 
            color: #333333; 
            text-align: center;
            margin-bottom: 30px; 
        }
        .container {
            background: #fff;
            border-radius: 0.5rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.1);
            padding: 1rem;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .header-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
        }
        .profile-section {
            display: flex;
            padding-left: 5rem;
            margin-bottom: 1rem;
            margin-top: 1rem;
        }
        .profile-info {
            margin-right: 2rem;
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
            display: flex;
            flex-direction: column;
            justify-content: center;
            flex: 1;
            text-align: center;
            font-size: 18px;
        }
        .post {
            border: 1px solid #ddd;
            border-radius: 0.5rem;
            margin-bottom: 15px;
            overflow: hidden;
        }
        .post-body {
            padding: 10px;
        }
        .logo-and-home {
            display: flex;
            align-items: center;
        }
        .logo-and-home h1 {
            margin-right: 10px;
            margin-bottom: 0; 
        }
        .post img {
            width: 100%;
            height: 400px;
            object-fit: cover;
            border-bottom: 1px solid #ddd;
        }
        hr {
            margin: 2rem;
            border: none;
            border-top: 1px solid #ddd;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header-section">
        <div class="logo-and-home">
            <h1>VividSpace</h1>
            <a href="<?= site_url('profile/feed'); ?>" class="btn btn-primary">Home</a>
        </div>
        <div>
            <a href="<?= site_url('profile/edit'); ?>" class="btn btn-secondary">Edit profile</a>
            <a href="<?= site_url('profile/logout'); ?>" class="btn btn-dark">Log out</a>
        </div>
    </div>

    <div class="profile-section">
        <div class="profile-info">
            <?php if (!empty($profile['profile_image'])): ?>
                <img src="<?= base_url(htmlspecialchars($profile['profile_image'])); ?>" alt="Profile Picture" class="profile-pic">
            <?php else: ?>
                <img src="<?= base_url('Images/default_profile_pic.png'); ?>" alt="Profile Picture" class="profile-pic">
            <?php endif; ?>
            <p>@<?= htmlspecialchars($profile['username']); ?></p>
            <?php if (!empty($profile['first_name']) || !empty($profile['last_name'])): ?>
                <p><?= htmlspecialchars($profile['first_name'] . ' ' . $profile['last_name']); ?></p>
            <?php endif; ?>
            <?php if (!empty($profile['bio'])): ?>
                <p><?= htmlspecialchars($profile['bio']); ?></p>
            <?php endif; ?>
        </div>
        <div class="followers-info">
            <p><strong><?= $followers_count ?></strong> Followers</p>
            <p><strong><?= $following_count ?></strong> Following</p>
        </div>
    </div>

    <hr>

    <?php if (empty($posts)): ?>
        <div class="text-center">
            <h1> No posts yet.</h1>
        </div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($posts as $post): ?>
                <div class="col-md-4 mb-3">
                    <div class="post">
                        <a href="<?= site_url('post/detail/' . $post['id']); ?>">
                            <img src="<?= base_url() . $post['image_path']; ?>" alt="Post Image">
                        </a>
                        <div class="post-body">
                            <p><?= htmlspecialchars($post['caption']); ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
</body>
</html>
