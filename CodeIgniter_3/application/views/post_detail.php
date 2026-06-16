<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Post Details - VividSpace</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/app.css'); ?>">
    <style>
        .container { padding-top: 2rem; }
        .card { border: none; border-radius: 0; align-items: center; }
        .card-img-top {
            border-radius: 0;
            width: 50%;
            height: 500px;
            object-fit: cover;
        }
        .card-title { margin-bottom: 0.5rem; }
        .card-text { font-size: 0.9rem; margin-right: 0.5rem; display: inline; }
        .hashtags { font-size: 0.9rem; margin-top: 4px; }
        .hashtag-link { color: #3897f0; margin-right: 4px; }
        .interaction-bar {
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 16px;
        }
        /* icon-btn, heart, bookmark styles come from app.css */
        .comment-item {
            padding: 6px 0;
            border-bottom: 1px solid #f0f0f0;
            font-size: 0.9rem;
        }
        .comment-item strong { margin-right: 6px; }
        .ajax-error { color: #c82333; font-size: 0.85rem; margin-top: 4px; }
    </style>
</head>
<body>
    <div class="container">
        <?php $this->load->view('partials/header'); ?>
        <div class="card">
            <img class="card-img-top" src="<?= esc_url(base_url(ltrim($post['image_path'], '/'))); ?>" alt="Post Image">
            <div class="card-body">
                <h5 class="card-title">
                    <a href="<?= site_url('profile/view/' . urlencode($post['username'])); ?>">
                        @<?= esc($post['username']); ?>
                    </a>
                </h5>
                <p class="card-text"><?= esc($post['caption']); ?></p>
                <p class="card-text mt-1"><small class="text-muted"><?= esc($post['created_at']); ?></small></p>

                <!-- Hashtags as clickable links -->
                <?php if (!empty($post['hashtags'])): ?>
                    <p class="hashtags">
                        <?php
                        $tags = preg_split('/[\s,]+/', $post['hashtags'], -1, PREG_SPLIT_NO_EMPTY);
                        foreach ($tags as $tag):
                            $clean = ltrim($tag, '#');
                        ?>
                            <a href="<?= site_url('search/hashtag/' . urlencode($clean)); ?>" class="hashtag-link">#<?= esc($clean); ?></a>
                        <?php endforeach; ?>
                    </p>
                <?php endif; ?>

                <!-- Interaction bar -->
                <div class="interaction-bar mt-2">
                    <span id="like-btn-<?= $post['id']; ?>" class="icon-btn <?= !empty($is_liked) ? 'liked' : ''; ?>" title="Like" onclick="toggleLike(<?= $post['id']; ?>);">
                        <i class="bi bi-heart heart-empty"></i>
                        <i class="bi bi-heart-fill heart-filled"></i>
                        <span class="count" id="like-count-<?= $post['id']; ?>"><?= (int) $post['likes_count']; ?></span>
                    </span>
                    <span id="comment-toggle-<?= $post['id']; ?>" class="icon-btn" title="Comment" onclick="toggleCommentForm(<?= $post['id']; ?>);">
                        <i class="bi bi-chat"></i>
                        <span class="count" id="comment-count-<?= $post['id']; ?>"><?= (int) $post['comments_count']; ?></span>
                    </span>
                    <span id="bm-btn-<?= $post['id']; ?>" class="icon-btn <?= !empty($is_saved) ? 'saved' : ''; ?>" title="Save" onclick="toggleBookmark(<?= $post['id']; ?>);">
                        <i class="bi bi-bookmark bm-empty"></i>
                        <i class="bi bi-bookmark-fill bm-filled"></i>
                    </span>
                    <?php if ($this->session->userdata('user_id') == $post['user_id']): ?>
                        <a href="<?= site_url('post/edit/' . $post['id']); ?>" class="btn btn-outline-secondary btn-sm">Edit</a>
                        <form method="post" action="<?= site_url('profile/delete_post/' . $post['id']); ?>" style="margin:0;" onsubmit="return confirm('Delete this post?');">
                            <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    <?php endif; ?>
                </div>
                <div id="like-error-<?= $post['id']; ?>" class="ajax-error"></div>

                <!-- Existing comments -->
                <div id="comments-container-<?= $post['id']; ?>" class="mt-2">
                    <?php foreach ($comments as $c): ?>
                        <div class="comment-item">
                            <strong><a href="<?= site_url('profile/view/' . urlencode($c['username'])); ?>">@<?= esc($c['username']); ?></a></strong>
                            <?= linkify_mentions(esc($c['content'])); ?>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Comment form (toggled by the 💬 button) -->
                <div id="comment-section-<?= $post['id']; ?>" style="display:none;" class="mt-2">
                    <form onsubmit="event.preventDefault(); addComment(<?= $post['id']; ?>);">
                        <div class="input-group">
                            <input type="text" id="comment-content-<?= $post['id']; ?>" class="form-control" placeholder="Write a comment...">
                            <div class="input-group-append">
                                <button type="submit" id="comment-submit-<?= $post['id']; ?>" class="btn btn-primary">Post</button>
                            </div>
                        </div>
                        <div id="comment-error-<?= $post['id']; ?>" class="ajax-error"></div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="<?= base_url('assets/js/csrf-ajax.js'); ?>"></script>

    <script>
        function toggleLike(postId) {
            var $btn = $('#like-btn-' + postId);
            if ($btn.hasClass('loading')) return;
            $btn.addClass('loading');
            $('#like-error-' + postId).text('');

            csrfPost(
                '<?= site_url('post/toggle_like'); ?>',
                { post_id: postId },
                function(response) {
                    $btn.removeClass('loading');
                    if (response.status !== 'success') {
                        $('#like-error-' + postId).text('Could not update like.');
                        return;
                    }
                    $('#like-count-' + postId).text(response.likes_count);
                    $('#like-btn-' + postId).toggleClass('liked', !!response.is_liked);
                },
                function() {
                    $btn.removeClass('loading');
                    $('#like-error-' + postId).text('Request failed. Please try again.');
                }
            );
        }

        function toggleCommentForm(postId) {
            $('#comment-section-' + postId).toggle();
            if ($('#comment-section-' + postId).is(':visible')) {
                $('#comment-content-' + postId).focus();
            }
        }

        function toggleBookmark(postId) {
            var $btn = $('#bm-btn-' + postId);
            if ($btn.hasClass('loading')) return;
            $btn.addClass('loading');
            csrfPost(
                '<?= site_url('post/toggle_bookmark'); ?>',
                { post_id: postId },
                function(response) {
                    $btn.removeClass('loading');
                    if (response.status === 'success') {
                        $btn.toggleClass('saved', !!response.is_saved);
                    }
                },
                function() { $btn.removeClass('loading'); }
            );
        }

        function addComment(postId) {
            var content = $('#comment-content-' + postId).val().trim();
            if (!content) return;

            var $submit = $('#comment-submit-' + postId);
            $submit.prop('disabled', true).text('Posting…');
            $('#comment-error-' + postId).text('');

            csrfPost(
                '<?= site_url('post/add_comment'); ?>',
                { post_id: postId, content: content },
                function(response) {
                    $submit.prop('disabled', false).text('Post');
                    if (response.status !== 'success') {
                        $('#comment-error-' + postId).text('Could not post comment.');
                        return;
                    }
                    var $item = $('<div class="comment-item"></div>');
                    $item.append($('<strong></strong>').text('@<?= addslashes($this->session->userdata('username')); ?> '));
                    $item.append(document.createTextNode(content));
                    $('#comments-container-' + postId).append($item);
                    $('#comment-content-' + postId).val('');
                    var current = parseInt($('#comment-count-' + postId).text(), 10) || 0;
                    $('#comment-count-' + postId).text(current + 1);
                },
                function() {
                    $submit.prop('disabled', false).text('Post');
                    $('#comment-error-' + postId).text('Request failed. Please try again.');
                }
            );
        }
    </script>
</body>
</html>
