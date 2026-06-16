<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Post Details - VividSpace</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/app.css'); ?>">
    <style>
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
            font-size: 1rem;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 16px;
        }
        .icon-btn {
            cursor: pointer;
            font-size: 1.6rem;
            line-height: 1;
            user-select: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: #555;
            transition: transform 0.1s ease;
        }
        .icon-btn:hover {
            transform: scale(1.1);
        }
        .icon-btn .count {
            font-size: 0.95rem;
            color: #555;
        }
        .heart {
            color: #ccc;
        }
        .heart.liked {
            color: #e0245e;
        }
        .card-img-top {
            width: 50%;
            height: 500px;
            object-fit: cover;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php $this->load->view('partials/header'); ?>
        <!-- Post Content -->
        <div class="card">
            <img class="card-img-top" src="<?= esc_url(base_url(ltrim($post['image_path'], '/'))); ?>" alt="Post Image">
            <div class="card-body">
                <h5 class="card-title">@<?= esc($post['username']); ?></h5>
                <p class="card-text"><?= esc($post['caption']); ?></p>
                <p class="card-text"><small><?= esc($post['created_at']); ?></small></p>
                <!-- Interaction bar -->
                <div class="interaction-bar">
                    <span class="icon-btn" title="Like" onclick="toggleLike(<?= $post['id']; ?>);">
                        <span id="heart-<?= $post['id']; ?>" class="heart <?= !empty($is_liked) ? 'liked' : '' ?>">&#9829;</span>
                        <span class="count" id="like-count-<?= $post['id']; ?>"><?= $post['likes_count']; ?></span>
                    </span>
                    <span class="icon-btn" title="Comment" onclick="toggleCommentSection(<?= $post['id']; ?>);">
                        &#128172;
                        <span class="count" id="comment-count-<?= $post['id']; ?>"><?= $post['comments_count']; ?></span>
                    </span>
                    <?php if ($this->session->userdata('user_id') == $post['user_id']): ?>
                        <form method="post" action="<?= site_url('profile/delete_post/' . $post['id']); ?>" style="margin:0;" onsubmit="return confirm('Delete this post?');">
                            <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
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

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="<?= base_url('assets/js/csrf-ajax.js'); ?>"></script>

    <script>
        function toggleLike(postId) {
            csrfPost(
                '<?= site_url('post/toggle_like'); ?>',
                { post_id: postId },
                function(response) {
                    if (response.status !== 'success') {
                        return;
                    }
                    $('#like-count-' + postId).text(response.likes_count);
                    $('#heart-' + postId).toggleClass('liked', !!response.is_liked);
                }
            );
        }

        function addComment(postId) {
            var content = $('#comment-content-' + postId).val();
            if (!content || content.trim() === '') {
                return;
            }
            csrfPost(
                '<?= site_url('post/add_comment'); ?>',
                { post_id: postId, content: content },
                function(response) {
                    if (response.status !== 'success') {
                        return;
                    }
                    var commentNode = $('<p></p>').text(content);
                    $('#comments-container-' + postId).append(commentNode).show();
                    $('#comment-content-' + postId).val('');
                    var current = parseInt($('#comment-count-' + postId).text(), 10) || 0;
                    $('#comment-count-' + postId).text(current + 1);
                }
            );
        }

        function showComments(postId) {
            $('#comments-container-' + postId).toggle();
        }

        function toggleCommentSection(postId) {
            $('#comment-section-' + postId).toggle();
        }
    </script>
</body>
</html>
