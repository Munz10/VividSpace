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
            height: 400px; 
            object-fit: cover; 
            border-bottom: 1px solid #ddd; 
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
            padding: 0.375rem 0.75rem; /* Bootstrap's .btn padding
            text-decoration: none;
            color: #007bff; /* Bootstrap primary color */
            background-color: transparent;
            border: 1px solid transparent;
            border-color: #007bff;
            border-radius: 0.25rem;
        }
        .profile-icon {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: #ddd;
        }
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
        .post-grid {
            grid-template-columns: repeat(3, 1fr); 
        }
        .follow-btn-wrapper {
            text-align: right;
            margin-top: 1rem;
            margin-bottom: 1rem;
        }

        .follow-btn {
            margin-right: 1rem;
        }
        .spacer {
            flex-grow: 1; /* Allow the spacer to grow and push the follow button to the right */
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
            <div class="spacer"></div>
            <button class="btn btn-primary follow-btn" data-following-id="<?= $user_profile['id']; ?>"
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

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="<?= base_url('assets/js/bootstrap.min.js'); ?>"></script>

    <script>
        function toggleFollow(buttonElement) {
            var button = $(buttonElement);
            var followingId = button.data('following-id');
            var isFollowing = button.text().trim() === 'Unfollow';

            $.ajax({
                url: '<?= site_url('follow/'); ?>' + (isFollowing ? 'do_unfollow' : 'do_follow'),
                type: 'POST',
                data: { following_id: followingId },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        // Toggle the button text based on the action taken
                        button.text(isFollowing ? 'Follow' : 'Unfollow');
                    } 
                    // else {
                    //     alert('Could not update the follow status.');
                    // }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    // If there is an error with the request
                    console.error("Error: " + textStatus + ", " + errorThrown);
                }
            });
        }

        // Since you're using jQuery, make sure to wrap your function calls inside a document ready block
        $(document).ready(function() {
            $('.follow-btn').on('click', function() {
                toggleFollow(this);
            });
        });
    </script>
</body>
</html>
