<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Post Details - VividSpace</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Other styles -->
    <style>
        /* Add your custom styles here to match the wireframe layout */
        body {
            background-color: #f8f9fa;
        }
        .container {
            padding-top: 2rem;
        }
        .card {
            border: none;
            border-radius: 0;
            align-items : center;
        }
        .card-img-top {
            border-radius: 0;
        }
        .card-title {
            margin-bottom: 0.5rem;
        }
        .card-text {
            font-size: 0.9rem;
            margin-right: 0.5rem;
            display: inline;
        }
        .btn {
            font-size: 0.9rem;
        }
        .interaction-bar {
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }
        .profile-icon {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #333; /* Replace with actual image */
        }
        
        .card-img-top {
            width: 50%;
            height: 500px;
            object-fit: contain;
        }
       
        /* You will need to adjust the styles to exactly match your wireframe */
    </style>
</head>
<body>
    <div class="container">
        <div class="row align-items-center my-4">
            <div class="col-6 col-md-8 d-flex align-items-center">
                <!-- Logo -->
                <h1>VividSpace</h1>
                <!-- Home button -->
                <a href="<?= site_url('profile/feed'); ?>" class="btn btn-primary ml-2">Home</a>
            </div>
            <div class="col-6 col-md-4 d-flex justify-content-end">
                <!-- User Icon -->
                <a href="<?= site_url('profile'); ?>">
                    <img src="<?= base_url('Images/user_icon.jpg'); ?>" class="profile-icon" alt="Profile">
                </a>
            </div>
        </div>
        <!-- Post Content -->
        <div class="card">
            <img class="card-img-top" src="<?= base_url() . $post['image_path']; ?>" alt="Post Image">
            <div class="card-body">
                <h5 class="card-title">@<?= $post['username']; ?></h5>
                <p class="card-text"><?= $post['caption']; ?></p>
                <p class="card-text"><small><?= $post['created_at']; ?></small></p>
                <!-- Interaction bar -->
                <div class="interaction-bar">
                    <!-- Make comments count clickable -->
                    <span class="comments-toggle" onclick="showComments(<?= $post['id']; ?>);">
                        <span id="comment-count-<?= $post['id']; ?>"><?= $post['comments_count']; ?></span> comments
                    </span>
                    <span class="card-text" id="like-count-<?= $post['id']; ?>"><?= $post['likes_count']; ?> likes</span>
                    <button onclick="toggleLike(<?= $post['id']; ?>);" class="btn btn-outline-secondary">Like</button>
                    <button onclick="toggleCommentSection(<?= $post['id']; ?>);" class="btn btn-outline-secondary">Comment</button>
                    <?php if ($this->session->userdata('user_id') == $post['user_id']): ?>
                        <!-- Show delete button only if logged-in user is the owner of the post -->
                        <a href="<?= site_url('profile/delete_post/' . $post['id']); ?>" class="btn btn-danger">Delete</a>
                    <?php endif; ?>
                </div>
                <!-- Comments Section -->
                <div id="comments-container-<?= $post['id']; ?>" style="display:none;">
                    <!-- Dynamic comments will be loaded here -->
                </div>
                <!-- Comment Form (hidden by default) -->
                <div id="comment-section-<?= $post['id']; ?>" style="display:none;">
                    <form onsubmit="event.preventDefault(); addComment(<?= $post['id']; ?>);">
                        <input type="text" id="comment-content-<?= $post['id']; ?>" class="form-control" placeholder="Write a comment...">
                        <button type="submit" class="btn btn-primary">Post Comment</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins and AJAX) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        function toggleLike(postId) {
            $.ajax({
                url: '<?= site_url('post/toggle_like'); ?>',
                type: 'POST',
                data: { post_id: postId },
                dataType: 'json',
                success: function(response) {
                    $('#like-count-' + postId).text(response.likes_count);
                }
            });
        }

        function addComment(postId) {
            var content = $('#comment-content-' + postId).val();
            $.ajax({
                url: '<?= site_url('post/add_comment'); ?>',
                type: 'POST',
                data: { post_id: postId, content: content },
                dataType: 'json',
                success: function(response) {
                    if (response.comment) {
                        // Append new comment and clear the input field
                        $('#comment-section-' + postId).append('<p>' + response.comment.content + '</p>');
                        $('#comment-content-' + postId).val('');
                        // Update comment count
                        $('#comment-count-' + postId).text(parseInt($('#comment-count-' + postId).text(), 10) + 1);
                    }
                }
            });
        }

        function toggleCommentSection(postId) {
            $('#comment-section-' + postId).toggle();
        }

        public function delete_post($post_id) {
            // First, delete associated likes
            $this->db->where('post_id', $post_id);
            $this->db->delete('likes');

            // First, delete associated likes
            $this->db->where('post_id', $post_id);
            $this->db->delete('comments');

            // Then delete the post
            $this->db->where('id', $post_id);
            $this->db->delete('posts');

            // Check if any rows were affected
            if ($this->db->affected_rows() > 0) {
                // Post deleted successfully
                return true;
            } else {
                // Post not found or not deleted
                return false;
            }
        }
    </script>
</body>
</html>
