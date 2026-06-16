<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($profile['username']); ?>'s Profile - VividSpace</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/app.css'); ?>">
    <style>
        html, body { height: 100%; }
        .container { background: #fff; border-radius: 0.5rem; box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.1); padding: 1rem; min-height: 100vh; display: flex; flex-direction: column; }
        .profile-section { display: flex; padding-left: 5rem; margin-bottom: 1rem; margin-top: 1rem; }
        .profile-info { margin-right: 2rem; text-align: center; }
        .followers-info { display: flex; flex-direction: column; justify-content: center; flex: 1; text-align: center; font-size: 18px; }
        .post { border: 1px solid #ddd; border-radius: 0.5rem; margin-bottom: 15px; overflow: hidden; cursor: pointer; }
        .post img { width: 100%; height: 300px; object-fit: cover; border-bottom: 1px solid #ddd; }
        .post-body { padding: 10px; }
        hr { margin: 2rem; border: none; border-top: 2px solid #ddd; }
        #profile-sentinel { height: 1px; }
        #profile-loading { text-align: center; padding: 1rem; display: none; }
        .modal-post-wrap { max-height: 85vh; overflow-y: auto; }
        .ajax-error { color: #c82333; font-size: 0.85rem; margin-top: 4px; }
        .icon-btn { cursor: pointer; font-size: 1.4rem; user-select: none; display: inline-flex; align-items: center; gap: 6px; color: #555; transition: transform .1s ease, opacity .15s; }
        .icon-btn:hover { transform: scale(1.1); }
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
    <?php $this->load->view('partials/header', [
        'actions' => '<a href="' . site_url('profile/edit') . '" class="btn btn-secondary btn-sm">Edit profile</a> <a href="' . site_url('profile/logout') . '" class="btn btn-dark btn-sm">Log out</a>',
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
                <p><?= htmlspecialchars(trim($profile['first_name'] . ' ' . $profile['last_name'])); ?></p>
            <?php endif; ?>
            <?php if (!empty($profile['bio'])): ?>
                <p><?= htmlspecialchars($profile['bio']); ?></p>
            <?php endif; ?>
        </div>
        <div class="followers-info">
            <p><strong><?= $followers_count; ?></strong> Followers</p>
            <p><strong><?= $following_count; ?></strong> Following</p>
            <p class="mt-2">
                <a href="<?= site_url('profile/saved'); ?>" class="btn btn-outline-secondary btn-sm"><i class="bi bi-bookmark"></i> Saved Posts</a>
            </p>
        </div>
    </div>

    <hr>

    <?php if (empty($posts)): ?>
        <div class="text-center py-5">
            <p class="text-muted" style="font-size:1.1rem;">You haven't posted anything yet.</p>
            <a href="<?= site_url('profile/create_post'); ?>" class="btn btn-primary mt-2">Create your first post</a>
        </div>
    <?php else: ?>
        <div class="row" id="profile-posts">
            <?php foreach ($posts as $post): ?>
                <div class="col-md-4 mb-3" data-post-id="<?= (int) $post['id']; ?>">
                    <div class="post" onclick="openPostModal(<?= (int) $post['id']; ?>)">
                        <img src="<?= base_url(ltrim($post['image_path'], '/')); ?>" alt="Post Image" loading="lazy">
                        <div class="post-body">
                            <p class="mb-1"><?= htmlspecialchars($post['caption']); ?></p>
                            <div class="card-meta">
                                <span><span class="heart">&#9829;</span> <?= (int) $post['likes_count']; ?></span>
                                <span><i class="bi bi-chat"></i> <?= (int) $post['comments_count']; ?></span>
                                <a href="<?= site_url('post/edit/' . $post['id']); ?>" class="ml-auto text-secondary" style="font-size:0.85rem;" onclick="event.stopPropagation();">Edit</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div id="profile-sentinel"></div>
        <div id="profile-loading">Loading more posts…</div>
    <?php endif; ?>
</div>

<!-- Post detail modal -->
<div class="modal fade" id="post-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header py-2">
                <h6 class="modal-title mb-0">Post</h6>
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
    /* ---- Infinite scroll ---- */
    var profilePage = <?= (int) ($page ?? 1); ?>;
    var profileHasMore = <?= !empty($has_more) ? 'true' : 'false'; ?>;
    var profileLoading = false;

    if (profileHasMore) {
        var sentinel = document.getElementById('profile-sentinel');
        var observer = new IntersectionObserver(function(entries) {
            if (entries[0].isIntersecting && !profileLoading && profileHasMore) {
                profileLoading = true;
                document.getElementById('profile-loading').style.display = 'block';
                csrfPost('<?= site_url('profile/profile_more'); ?>/' + (profilePage + 1), {}, function(res) {
                    document.getElementById('profile-loading').style.display = 'none';
                    profileLoading = false;
                    if (res.status === 'success' && res.html) {
                        $('#profile-posts').append(res.html);
                        profilePage++;
                        profileHasMore = !!res.has_more;
                        if (!profileHasMore) observer.disconnect();
                    }
                }, function() {
                    document.getElementById('profile-loading').style.display = 'none';
                    profileLoading = false;
                });
            }
        }, { rootMargin: '200px' });
        observer.observe(sentinel);
    }

    /* ---- Post modal ---- */
    function openPostModal(postId) {
        $('#modal-post-body').html('<div class="text-center py-4">Loading…</div>');
        $('#post-modal').modal('show');
        csrfPost('<?= site_url('post/modal_content'); ?>/' + postId, {}, function(res) {
            if (res.status === 'success') {
                $('#modal-post-body').html(res.html);
            } else {
                $('#modal-post-body').html('<p class="text-danger">Could not load post.</p>');
            }
        }, function() {
            $('#modal-post-body').html('<p class="text-danger">Request failed.</p>');
        });
    }

    /* ---- Like / Bookmark / Comment (used by modal post_card) ---- */
    function toggleLike(postId) {
        var $btn = $('#like-btn-' + postId);
        if ($btn.hasClass('loading')) return;
        $btn.addClass('loading');
        $('#like-error-' + postId).text('');
        csrfPost('<?= site_url('post/toggle_like'); ?>', { post_id: postId }, function(r) {
            $btn.removeClass('loading');
            if (r.status === 'success') {
                $('#like-count-' + postId).text(r.likes_count);
                $('#heart-' + postId).toggleClass('liked', !!r.is_liked);
            } else {
                $('#like-error-' + postId).text('Could not update like.');
            }
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
        $('#comment-error-' + postId).text('');
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
