<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Feed - VividSpace</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/app.css'); ?>">
    <style>
        .container {
            background: #fff;
            border-radius: 0.5rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.1);
            padding: 1rem;
            min-height: 100vh;
        }
        .suggested-profile-icon {
            width: 64px; height: 64px;
            border-radius: 50%;
            object-fit: cover;
            background-color: #e9ecef;
        }
        .card { background: #f8f9fa; border-radius: 10px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); cursor: pointer; }
        .card-img-top { width: 100%; height: 280px; object-fit: cover; border-radius: 10px 10px 0 0; }
        #feed-sentinel { height: 1px; }
        #feed-loading { text-align: center; padding: 1rem; display: none; }
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
        .hashtags { font-size: 0.88rem; }
        .hashtag-link { color: #3897f0; margin-right: 4px; }
        .list-group-item { border: solid; border-radius: 10px; background: #f8f9fa; box-shadow: 0 2px 4px rgba(0,0,0,0.1); width: 50%; }
    </style>
</head>
<body>
<div class="container">
    <?php $this->load->view('partials/header', ['show_search' => true, 'show_create' => true]); ?>

    <div class="row mt-4" id="feed-posts">
    <?php if (!empty($posts)): ?>
        <?php foreach ($posts as $post): ?>
            <div class="col-md-4 mb-3 feed-card-col" data-post-id="<?= (int) $post['id']; ?>">
                <div class="card" onclick="openPostModal(<?= (int) $post['id']; ?>)">
                    <img src="<?= base_url(ltrim($post['image_path'], '/')); ?>"
                         class="card-img-top" alt="Post Image" loading="lazy">
                    <div class="card-body">
                        <h6 class="card-title mb-1"><?= htmlspecialchars($post['caption']); ?></h6>
                        <p class="card-text mb-1"><small class="text-muted">by <?= htmlspecialchars($post['author_username']); ?></small></p>
                        <div class="card-meta">
                            <span><i class="bi bi-heart-fill" style="color:#e0245e;font-size:.9rem;"></i> <?= (int) $post['likes_count']; ?></span>
                            <span><i class="bi bi-chat"></i> <?= (int) $post['comments_count']; ?></span>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="col-12 mb-3">
            <h4 class="text-center mb-4">Suggested Users to Follow</h4>
            <div class="d-flex flex-wrap justify-content-center">
                <?php foreach ($suggested_users as $user): ?>
                    <a href="<?= site_url('profile/view/' . $user['username']); ?>" class="list-group-item list-group-item-action m-2">
                        <div class="text-center">
                            <img src="<?= !empty($user['profile_image']) ? base_url(ltrim(htmlspecialchars($user['profile_image']), '/')) : base_url('Images/default_profile_pic.png'); ?>"
                                 alt="Profile" class="suggested-profile-icon mb-2">
                            <div><?= htmlspecialchars($user['username']); ?></div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
    </div>

    <?php if (!empty($posts)): ?>
        <div id="feed-sentinel"></div>
        <div id="feed-loading">Loading more posts…</div>
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
    var feedPage = <?= (int) ($page ?? 1); ?>;
    var feedHasMore = <?= !empty($has_more) ? 'true' : 'false'; ?>;
    var feedLoading = false;
    var feedEndpoint = '<?= site_url('profile/feed_more'); ?>';

    if (feedHasMore) {
        var sentinel = document.getElementById('feed-sentinel');
        var observer = new IntersectionObserver(function(entries) {
            if (entries[0].isIntersecting && !feedLoading && feedHasMore) {
                feedLoading = true;
                document.getElementById('feed-loading').style.display = 'block';
                csrfPost(feedEndpoint + '/' + (feedPage + 1), {}, function(res) {
                    document.getElementById('feed-loading').style.display = 'none';
                    feedLoading = false;
                    if (res.status === 'success' && res.html) {
                        $('#feed-posts').append(res.html);
                        feedPage++;
                        feedHasMore = !!res.has_more;
                        if (!feedHasMore) observer.disconnect();
                    }
                }, function() {
                    document.getElementById('feed-loading').style.display = 'none';
                    feedLoading = false;
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
                $('#like-btn-' + postId).toggleClass('liked', !!r.is_liked);
            } else {
                $('#like-error-' + postId).text('Could not update like.');
            }
        }, function() { $btn.removeClass('loading'); $('#like-error-' + postId).text('Request failed.'); });
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
        }, function() { $submit.prop('disabled', false).text('Post'); $('#comment-error-' + postId).text('Request failed.'); });
    }

    /* ---- Header search dropdown ---- */
    $(document).ready(function() {
        var $input = $('#header-search-input');
        var $results = $('#header-search-results');
        $input.on('input', function() {
            var q = $(this).val().trim();
            if (!q) { $results.empty().hide(); return; }
            $.get('<?= site_url('search/dynamicResult'); ?>', { query: q }, function(res) {
                $results.empty().show();
                if (!res.results || !res.results.length) { $('<div class="search-result">No results found</div>').appendTo($results); return; }
                $.each(res.results, function(i, r) {
                    $('<div class="search-result"></div>').text(r.username).on('click', function() {
                        window.location.href = '<?= site_url('profile/view/'); ?>' + encodeURIComponent(r.username);
                    }).appendTo($results);
                });
            });
        });
        $(document).on('click', function(e) { if (!$(e.target).closest('.header-search').length) $results.empty().hide(); });
    });
</script>
</body>
</html>
