<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Results - VividSpace</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/app.css'); ?>">
    <style>
        .container { padding: 1rem; }
        .search-results { max-width: 700px; margin: 30px auto 0; }
        .user-result {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 10px 14px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 0 8px rgba(0,0,0,0.08);
            margin-bottom: 10px;
            text-decoration: none;
            color: inherit;
        }
        .user-result:hover { background: #f8f9fa; text-decoration: none; }
        .user-avatar {
            width: 48px; height: 48px;
            border-radius: 50%;
            object-fit: cover;
            flex-shrink: 0;
        }
        .post-result-card { cursor: pointer; }
        .post-result-card img { width: 100%; height: 200px; object-fit: cover; border-radius: 6px 6px 0 0; }
    </style>
</head>
<body>
<div class="container">
    <?php $this->load->view('partials/header', ['show_search' => true]); ?>

    <div class="search-results">
        <h4>Results for "<?= htmlspecialchars($query); ?>"</h4>

        <!-- User results -->
        <h6 class="mt-4 text-muted text-uppercase" style="font-size:0.8rem;letter-spacing:1px;">People</h6>
        <?php if (empty($users)): ?>
            <p class="text-muted">No users found.</p>
        <?php else: ?>
            <?php foreach ($users as $user): ?>
                <a href="<?= site_url('profile/view/' . urlencode($user['username'])); ?>" class="user-result">
                    <img src="<?= !empty($user['profile_image']) ? base_url(ltrim(htmlspecialchars($user['profile_image']), '/')) : base_url('Images/default_profile_pic.png'); ?>"
                         alt="<?= htmlspecialchars($user['username']); ?>" class="user-avatar">
                    <span>@<?= htmlspecialchars($user['username']); ?></span>
                </a>
            <?php endforeach; ?>
        <?php endif; ?>

        <!-- Post results -->
        <h6 class="mt-4 text-muted text-uppercase" style="font-size:0.8rem;letter-spacing:1px;">Posts</h6>
        <?php if (empty($posts)): ?>
            <p class="text-muted">No posts found.</p>
        <?php else: ?>
            <div class="row">
                <?php foreach ($posts as $post): ?>
                    <div class="col-md-4 mb-3">
                        <a href="<?= site_url('post/detail/' . $post['id']); ?>" class="post-result-card card d-block text-decoration-none text-dark">
                            <img src="<?= base_url(ltrim($post['image_path'], '/')); ?>" alt="Post" loading="lazy">
                            <div class="card-body p-2">
                                <p class="mb-1" style="font-size:0.88rem;"><?= htmlspecialchars($post['caption']); ?></p>
                                <div class="card-meta">
                                    <span><span class="heart">&#9829;</span> <?= (int) $post['likes_count']; ?></span>
                                    <span><i class="bi bi-chat"></i> <?= (int) $post['comments_count']; ?></span>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
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
