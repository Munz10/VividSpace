<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Saved Posts - VividSpace</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/app.css'); ?>">
    <style>
        .container { padding: 1rem; }
        .post-card { cursor: pointer; }
        .post-card img { width: 100%; height: 240px; object-fit: cover; border-radius: 6px 6px 0 0; }
        .modal-post-wrap { max-height: 85vh; overflow-y: auto; }
        .ajax-error { color: #c82333; font-size: 0.85rem; margin-top: 4px; }
        .icon-btn { cursor: pointer; font-size: 1.4rem; user-select: none; display: inline-flex; align-items: center; gap: 6px; color: #555; }
        .icon-btn.loading { pointer-events: none; opacity: 0.5; }
        .icon-btn .count { font-size: 0.9rem; color: #555; }
        .heart { color: #ccc; }
        .heart.liked { color: #e0245e; }
        .bm-icon { color: #ccc; }
        .icon-btn.saved .bm-icon { color: #f5a623; }
        .comment-item { padding: 5px 0; border-bottom: 1px solid #f0f0f0; font-size: 0.88rem; }
        .interaction-bar { display: flex; align-items: center; gap: 14px; margin: 8px 0; }
        .hashtag-link { color: #3897f0; margin-right: 4px; }
    </style>
</head>
<body>
<div class="container">
    <?php $this->load->view('partials/header'); ?>

    <h4 class="mt-3"><i class="bi bi-bookmark-fill"></i> Saved Posts</h4>

    <?php if (empty($posts)): ?>
        <div class="text-center py-5">
            <p class="text-muted" style="font-size:1.1rem;">You haven't saved any posts yet.</p>
            <a href="<?= site_url('profile/feed'); ?>" class="btn btn-primary mt-2">Explore Feed</a>
        </div>
    <?php else: ?>
        <div class="row mt-3">
            <?php foreach ($posts as $post): ?>
                <div class="col-md-4 mb-3">
                    <div class="card post-card" onclick="openPostModal(<?= (int) $post['id']; ?>)">
                        <img src="<?= base_url(ltrim($post['image_path'], '/')); ?>" alt="Post" loading="lazy">
                        <div class="card-body p-2">
                            <p class="mb-0 text-muted" style="font-size:0.82rem;">
                                by <a href="<?= site_url('profile/view/' . urlencode($post['author_username'])); ?>" onclick="event.stopPropagation();">@<?= htmlspecialchars($post['author_username']); ?></a>
                            </p>
                            <p class="mb-1" style="font-size:0.88rem;"><?= htmlspecialchars($post['caption']); ?></p>
                            <div class="card-meta">
                                <span><span class="heart">&#9829;</span> <?= (int) $post['likes_count']; ?></span>
                                <span><i class="bi bi-chat"></i> <?= (int) $post['comments_count']; ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<!-- Post detail modal -->
<div class="modal fade" id="post-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header py-2">
                <h6 class="modal-title mb-0">Saved Post</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span>&times;</span></button>
            </div>
            <div class="modal-body modal-post-wrap" id="modal-post-body">
                <div class="text-center py-4">Loading…</div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
<script src="<?= base_url('assets/js/csrf-ajax.js'); ?>"></script>

<script>
    function openPostModal(postId) {
        $('#modal-post-body').html('<div class="text-center py-4">Loading…</div>');
        $('#post-modal').modal('show');
        csrfPost('<?= site_url('post/modal_content'); ?>/' + postId, {}, function(res) {
            if (res.status === 'success') { $('#modal-post-body').html(res.html); }
            else { $('#modal-post-body').html('<p class="text-danger">Could not load post.</p>'); }
        }, function() { $('#modal-post-body').html('<p class="text-danger">Request failed.</p>'); });
    }

    function toggleLike(postId) {
        var $btn = $('#like-btn-' + postId);
        if ($btn.hasClass('loading')) return;
        $btn.addClass('loading');
        csrfPost('<?= site_url('post/toggle_like'); ?>', { post_id: postId }, function(r) {
            $btn.removeClass('loading');
            if (r.status === 'success') { $('#like-count-' + postId).text(r.likes_count); $('#heart-' + postId).toggleClass('liked', !!r.is_liked); }
        }, function() { $btn.removeClass('loading'); });
    }

    function toggleBookmark(postId) {
        var $btn = $('#bm-btn-' + postId);
        if ($btn.hasClass('loading')) return;
        $btn.addClass('loading');
        csrfPost('<?= site_url('post/toggle_bookmark'); ?>', { post_id: postId }, function(r) {
            $btn.removeClass('loading');
            if (r.status === 'success') $btn.toggleClass('saved', !!r.is_saved);
        }, function() { $btn.removeClass('loading'); });
    }

    function addComment(postId) {
        var $input = $('#comment-content-' + postId);
        var content = $input.val().trim();
        if (!content) return;
        var $submit = $('#comment-submit-' + postId);
        $submit.prop('disabled', true).text('Posting…');
        csrfPost('<?= site_url('post/add_comment'); ?>', { post_id: postId, content: content }, function(r) {
            $submit.prop('disabled', false).text('Post');
            if (r.status === 'success') {
                var $item = $('<div class="comment-item"></div>');
                $item.append($('<strong></strong>').text('@<?= addslashes($this->session->userdata('username')); ?> '));
                $item.append(document.createTextNode(content));
                $('#comments-container-' + postId).append($item);
                $input.val('');
                var n = parseInt($('#comment-count-' + postId).text(), 10) || 0;
                $('#comment-count-' + postId).text(n + 1);
            } else {
                $('#comment-error-' + postId).text('Could not post comment.');
            }
        }, function() { $submit.prop('disabled', false).text('Post'); });
    }
</script>
</body>
</html>
