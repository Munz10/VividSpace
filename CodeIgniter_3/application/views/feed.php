<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
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
        /* You may need to adjust the sizes and padding to match your wireframe */
    </style>
</head>
<body>
<div class="container">
    <div class="d-flex justify-content-between align-items-center my-4">
        <h1>VividSpace</h1>
        <div>
            <a href="<?= site_url('profile/create_post'); ?>" class="btn btn-primary">Create</a>
        </div>
        <!-- Search Form -->
        <form action="<?= site_url('search/result'); ?>" method="get" class="search-bar">
            <input type="search" class="form-control" name="query" placeholder="Search users..." required>
            <button type="submit" class="btn btn-outline-secondary">Search</button>
        </form>
        <div>
            <a href="<?= site_url('profile'); ?>"><img src="<?= base_url('Images/user_icon.jpg'); ?>" class="profile-icon" alt="Profile"></a>
        </div>
    </div>

    <!-- Feed items -->
    <div class="row">
        <?php foreach ($posts as $post): ?>
        <div class="col-md-4 mb-3">
            <div class="card">
                <img src="path_to_post_image" class="card-img-top" alt="Post Image">
                <div class="card-body">
                    <h5 class="card-title">Post <?= $post['id']; ?></h5>
                    <p class="card-text"><?= htmlspecialchars($post['content']); ?></p>
                    <p class="card-text"><small class="text-muted">Posted by <?= htmlspecialchars($post['author']); ?></small></p>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<script src="<?= base_url('assets/js/bootstrap.min.js'); ?>"></script>
</body>
</html>