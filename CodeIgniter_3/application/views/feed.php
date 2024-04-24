<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>User's Feed - VividSpace</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Additional styles can go here */
        .search-bar {
            width: 100%; /* Or any other width */
        }
        .profile-icon {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #333; /* Replace with actual image */
        }
        /* remove for original image view */
        .card-img-top {
            width: 100%;
            height: 400px; /* or any other height */
            object-fit: cover;
        }

        /* You may need to adjust the sizes and padding to match your wireframe */
    </style>
</head>
<body>
<div class="container">
    <div class="row align-items-center my-4">
        <!-- Logo and Create button, allocated 4 units -->
        <div class="col-6 col-md-4 d-flex align-items-center">
            <h1>VividSpace</h1>
            <a href="<?= site_url('profile/create_post'); ?>" class="btn btn-primary ml-3">Create</a>
        </div>
        <!-- Search Form, allocated 4 units -->
        <div class="col-12 col-md-4 my-3 my-md-0">
            <form action="<?= site_url('search/result'); ?>" method="get" class="d-flex">
                <input type="search" class="form-control flex-grow-1" name="query" placeholder="Search users..." required>
                <button type="submit" class="btn btn-outline-secondary ml-2">Search</button>
            </form>
        </div>
        <!-- User Icon, allocated 4 units -->
        <div class="col-6 col-md-4 d-flex justify-content-end">
            <a href="<?= site_url('profile'); ?>">
                <img src="<?= base_url('Images/user_icon.jpg'); ?>" class="profile-icon" alt="Profile">
            </a>
        </div>
    </div>

    <!-- Feed items -->
    <div class="row">
        <?php foreach ($posts as $post): ?>
            <div class="col-md-4 mb-3">
                <div class="card">
                    <a href="<?= site_url('post/detail/' . $post['id']); ?>">
                        <img src="<?= base_url() . $post['image_path']; ?>" class="card-img-top" alt="Post Image">
                    </a>
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($post['caption']); ?></h5>
                        <p class="card-text"><small class="text-muted">Posted by <?= htmlspecialchars($post['author_username']); ?></small></p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script src="<?= base_url('assets/js/bootstrap.min.js'); ?>"></script>
</body>
</html>