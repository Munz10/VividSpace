<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Post Details - VividSpace</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Other styles -->
</head>
<body>
    <div class="container mt-4">
        <!-- Post Content -->
        <div class="card">
            <img class="card-img-top" src="<?= base_url() . $post['image_path']; ?>" alt="Post Image">
            <div class="card-body">
                <h5 class="card-title">@<?= $post['username']; ?></h5>
                <p class="card-text"><?= $post['caption']; ?></p>
                <p class="card-text"><small><?= $post['created_at']; ?></small></p>
                <p class="card-text"><?= $post['comments_count']; ?> comments</p>
                <p class="card-text"><?= $post['likes_count']; ?> likes</p>
                <!-- Add like and comment buttons -->
                <a href="#" class="btn btn-primary">Like</a>
                <a href="#" class="btn btn-secondary">Comment</a>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
