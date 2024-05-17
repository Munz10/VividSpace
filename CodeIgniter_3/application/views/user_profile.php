<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($user_profile['username']); ?>'s Profile - VividSpace</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        html, body {
            height: 100%;
        }
        body {
            background-color: #f8f9fa;
        }
        .container {
            background: #fff;
            border-radius: 0.5rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            padding: 1rem;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .profile-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 1rem;
        }
        .profile-info {
            text-align: center;
        }
        .profile-pic {
            width: 150px;
            height: 150px;
            background: #ccc;
            border-radius: 50%;
            display: inline-block;
        }
        .follow-info {
            text-align: center;
        }
        .post {
            border: 1px solid #ddd;
            border-radius: 0.5rem;
            margin-bottom: 15px;
            overflow: hidden;
        }
        .post img {
            width: 100%;
            height: 400px;
            object-fit: cover;
            border-bottom: 1px solid #ddd;
        }
        .post-body {
            padding: 10px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
        }
        .logo-and-home {
            display: flex;
            align-items: center;
        }
        .logo-and-home h1 {
            margin-right: 10px;
        }
        .profile-icon {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: #ddd;
        }
        .follow-btn-wrapper {
            text-align: right;
            margin-top: 1rem;
            margin-bottom: 1rem;
        }
        .follow-btn {
            margin-right: 1rem;
            margin-top: 1rem;
        }
        .follow-btn.follow {
            background-color: #5bc0de; /* Light blue */
            color: white;
        }
        .follow-btn.unfollow {
            background-color: #c82333; /* Dark red */
            color: white;
        }
        .spacer {
            flex-grow: 1;
        }
        hr {
            margin: 2rem;
            border: none;
            border-top: 2px solid #ddd;
        }
        h1 {
            font-family: 'Montserrat', sans-serif;
            font-weight: bold;
            color: #333333;
            text-align: center;
        }
        .logo-and-home {
            display: flex;
            align-items: center;
        }
        .logo-and-home h1 {
            margin-right: 10px;
            margin-bottom: 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo-and-home">
                <h1>VividSpace</h1>
                <a href="<?= site_url('profile/feed'); ?>" class="btn btn-primary">Home</a>
            </div>
            <div class="profile-icon-container">
                <a href="<?= site_url('profile'); ?>">
                    <img src="<?= base_url('Images/user_icon.jpg'); ?>" class="profile-icon" alt="Profile">
                </a>
            </div>
        </div>
        <div class="profile-header">
            <div class="spacer"></div>
            <button class="btn follow-btn <?= $is_following ? 'unfollow' : 'follow'; ?>" data-following-id="<?= $user_profile['id']; ?>"
                    onclick="toggleFollow(this);">
                <?= $is_following ? 'Unfollow' : 'Follow'; ?>
            </button>
        </div>
        <div class="profile-info">
            <?php if (!empty($user_profile['profile_image'])): ?>
                <img src="<?= base_url(htmlspecialchars($user_profile['profile_image'])); ?>" alt="Profile Picture" class="profile-pic">
            <?php else: ?>
                <img src="<?= base_url('Images/default_profile_pic.png'); ?>" alt="Profile Picture" class="profile-pic">
            <?php endif; ?>
            <p>@<?= htmlspecialchars($user_profile['username']); ?></p>
            <?php if (!empty($user_profile['first_name']) || !empty($user_profile['last_name'])): ?>
                <p><?= htmlspecialchars($user_profile['first_name'] . ' ' . $user_profile['last_name']); ?></p>
            <?php endif; ?>
            <?php if (!empty($user_profile['bio'])): ?>
                <p><?= htmlspecialchars($user_profile['bio']); ?></p>
            <?php endif; ?>
        </div>
        <div class="follow-info">
            <p><strong><?= $followers_count ?></strong> Followers</p>
            <p><strong><?= $following_count ?></strong> Following</p>
        </div>
        <hr>
        <?php if (empty($posts)): ?>
            <div class="text-center">
                <h1>No posts yet.</h1>
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

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="<?= base_url('assets/js/bootstrap.min.js'); ?>"></script>

    <script>
        function toggleFollow(buttonElement) {
            var button = $(buttonElement);
            var followingId = button.data('following-id');
            var isFollowing = button.hasClass('unfollow');

            $.ajax({
                url: '<?= site_url('follow/'); ?>' + (isFollowing ? 'do_unfollow' : 'do_follow'),
                type: 'POST',
                data: { following_id: followingId },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        if (isFollowing) {
                            button.removeClass('unfollow').addClass('follow').text('Follow');
                        } else {
                            button.removeClass('follow').addClass('unfollow').text('Unfollow');
                        }
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error("Error: " + textStatus + ", " + errorThrown);
                }
            });
        }

        $(document).ready(function() {
            $('.follow-btn').on('click', function() {
                toggleFollow(this);
            });
        });
    </script>
</body>
</html>
