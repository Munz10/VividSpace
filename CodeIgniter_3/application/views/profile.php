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
            align-items: flex-start; /* This will align items to the start of the container */
            padding-top: 20px; /* Adjust this value as needed to create more space from the top */
            margin-bottom: 20px;
        }
        .profile-section {
            display: flex;
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
            flex-direction: column; /* Stack followers/following vertically */
            justify-content: center; /* Center vertically */
            flex: 1;
            text-align: center;
            margin-top:30px;
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
        .logo-and-home {
            display: flex;
            align-items: center;
        }

        .logo-and-home h1 {
            margin-right: 10px; /* Adjust space between logo and button as needed */
        }
        .post img {
            width: 100%; 
            height: 400px; 
            object-fit: cover; 
            border-bottom: 1px solid #ddd; 
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

    <div class="content-section">  
    <div class="row">
        <?php foreach ($posts as $post): ?>
            <div class="col-md-4 mb-3">
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
</div>
</body>
</html>