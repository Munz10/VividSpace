<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($profile['username']); ?>'s Profile - VividSpace</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/app.css'); ?>">
    <style>
        html, body { height: 100%; }
        .container {
            background: #fff;
            border-radius: 0.5rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.1);
            padding: 1rem;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
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
        .post img {
            width: 100%;
            height: 400px;
            object-fit: cover;
            border-bottom: 1px solid #ddd;
        }
        hr {
            margin: 2rem;
            border: none;
            border-top: 2px solid #ddd;
        }
    </style>
</head>
<body>
<div class="container">
    <?php $this->load->view('partials/header', [
        'actions' => '<a href="' . site_url('profile/edit') . '" class="btn btn-secondary">Edit profile</a> <a href="' . site_url('profile/logout') . '" class="btn btn-dark">Log out</a>',
    ]); ?>

    <div class="profile-section">
        <div class="profile-info">
            <?php if (!empty($profile['profile_image'])): ?>
                <img src="<?= base_url(ltrim(htmlspecialchars($profile['profile_image']), '/')); ?>" alt="Profile Picture" class="profile-pic">
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
                            <img src="<?= base_url(ltrim($post['image_path'], '/')); ?>" alt="Post Image">
                        </a>
                        <div class="post-body">
                            <p><?= htmlspecialchars($post['caption']); ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if (($page ?? 1) > 1 || !empty($has_more)): ?>
            <nav class="mt-3">
                <ul class="pagination justify-content-center">
                    <?php if (($page ?? 1) > 1): ?>
                        <li class="page-item"><a class="page-link" href="<?= site_url('profile/index/' . ($page - 1)); ?>">Previous</a></li>
                    <?php endif; ?>
                    <?php if (!empty($has_more)): ?>
                        <li class="page-item"><a class="page-link" href="<?= site_url('profile/index/' . ($page + 1)); ?>">Next</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        <?php endif; ?>
    <?php endif; ?>
</div>
</body>
</html>
