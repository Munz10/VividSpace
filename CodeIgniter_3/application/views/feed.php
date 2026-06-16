<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User's Feed - VividSpace</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/app.css'); ?>">
    <style>
        .container {
            background: #fff;
            border-radius: 0.5rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.1);
            padding: 1rem;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .list-group-item {
            border: solid;
            border-radius: 10px;
            background-color: #f8f9fa;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            width: 50%;
        }
        .suggested-profile-icon {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background-color: #e9ecef;
            object-fit: cover;
        }
        .card-img-top {
            width: 100%;
            height: 400px; 
            object-fit: cover;
        }
        .card {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .card-meta {
            display: flex;
            gap: 14px;
            color: #555;
            font-size: 0.9rem;
            margin-top: 6px;
        }
        .card-meta .heart {
            color: #e0245e;
        }
    </style>
</head>
<body>
<div class="container">
    <?php $this->load->view('partials/header', ['show_search' => true, 'show_create' => true]); ?>

    <div class="row mt-4">
    <?php if (!empty($posts)): ?>
        <?php foreach ($posts as $post): ?>
            <div class="col-md-4 mb-3">
                <div class="card">
                    <a href="<?= site_url('post/detail/' . $post['id']); ?>">
                        <img src="<?= base_url(ltrim($post['image_path'], '/')); ?>" class="card-img-top" alt="Post Image">
                    </a>
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($post['caption']); ?></h5>
                        <p class="card-text"><small class="text-muted">Posted by <?= htmlspecialchars($post['author_username']); ?></small></p>
                        <div class="card-meta">
                            <span><span class="heart">&#9829;</span> <?= (int) $post['likes_count']; ?></span>
                            <span>&#128172; <?= (int) $post['comments_count']; ?></span>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="col-md-12 mb-3">
            <div class="col-md-12">
                <h3 class="text-center mb-4">Suggested Users to Follow</h3>
                <div class="d-flex flex-wrap justify-content-center">
                    <?php foreach ($suggested_users as $user): ?>
                        <a href="<?= site_url('profile/view/'.$user['username']); ?>" class="list-group-item list-group-item-action m-2">
                            <div class="text-center">
                                <?php if (!empty($user['profile_image'])): ?>
                                    <img src="<?= base_url(ltrim(htmlspecialchars($user['profile_image']), '/')); ?>" alt="Profile Picture" class="suggested-profile-icon mb-2">
                                <?php else: ?>
                                    <img src="<?= base_url('Images/default_profile_pic.png'); ?>" alt="Profile Picture" class="suggested-profile-icon mb-2">
                                <?php endif; ?>
                                <div><?= htmlspecialchars($user['username']); ?></div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
    </div>

    <?php if (!empty($posts) && (($page ?? 1) > 1 || !empty($has_more))): ?>
        <nav class="mt-3">
            <ul class="pagination justify-content-center">
                <?php if (($page ?? 1) > 1): ?>
                    <li class="page-item"><a class="page-link" href="<?= site_url('profile/feed/' . ($page - 1)); ?>">Previous</a></li>
                <?php endif; ?>
                <?php if (!empty($has_more)): ?>
                    <li class="page-item"><a class="page-link" href="<?= site_url('profile/feed/' . ($page + 1)); ?>">Next</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    <?php endif; ?>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {
        var $input = $('#header-search-input');
        var $results = $('#header-search-results');

        $input.on('input', function() {
            var query = $(this).val();

            if (query.trim() === '') {
                $results.empty().hide();
                return;
            }

            $.ajax({
                url: '<?= site_url('search/dynamicResult'); ?>',
                type: 'get',
                data: { query: query },
                success: function(response) {
                    $results.empty().show();
                    if (response.results.length === 0) {
                        $('<div class="search-result"></div>').text('No results found').appendTo($results);
                        return;
                    }
                    $.each(response.results, function(index, result) {
                        var $row = $('<div class="search-result"></div>').text(result.username);
                        $row.on('click', function() {
                            window.location.href = '<?= site_url('profile/view/'); ?>' + encodeURIComponent(result.username);
                        });
                        $results.append($row);
                    });
                }
            });
        });

        $(document).on('click', function(event) {
            if (!$(event.target).closest('.header-search').length) {
                $results.empty().hide();
            }
        });
    });
</script>
</body>
</html>