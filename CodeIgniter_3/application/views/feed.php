<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User's Feed - VividSpace</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Roboto', sans-serif; 
            background-color: #f8f9fa; 
        }
        .container {
            background: #fff;
            border-radius: 0.5rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.1);
            padding: 1rem;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        h1 {
            font-family: 'Montserrat', sans-serif; 
            font-weight: bold; 
            color: #333333; 
            text-align: center;
            margin-bottom: 30px; 
        }
        .header-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
        }
        .logo-and-home {
            display: flex;
            align-items: center;
        }
        .logo-and-home h1 {
            margin-right: 10px;
            margin-bottom: 0; 
        }
        .list-group-item {
            border: solid; 
            border-radius: 10px;
            background-color: #f8f9fa; 
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            width: 50%
        }
        .suggested-profile-icon {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background-color: #e9ecef; 
            object-fit: cover;
        } 
        .search-bar {
            width: 100%;
        }
        .profile-icon {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #333; 
        }
        #search-results {
            position: absolute;
            top: 100%;
            left: 0;
            width: 100%;
            background-color: #707070;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            z-index: 9999;
            display: none;
            padding: 10px;
        }
        .search-result {
            padding: 5px 0;
            border-bottom: 3px solid #eee;
            cursor: pointer;
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
    </style>
</head>
<body>
<div class="container">
    <div class="header-section">
        <div class="logo-and-home">
            <h1>VividSpace</h1>
            <a href="<?= site_url('profile/create_post'); ?>" class="btn btn-primary ml-3">Create</a>
        </div>
        <div class="col-12 col-md-6 my-3 my-md-0">
            <form action="<?= site_url('search/result'); ?>" method="get" class="d-flex">
                <input type="search" id="search-input" class="form-control flex-grow-1" name="query" placeholder="Search users..." required>
                <button type="submit" class="btn btn-outline-secondary ml-2">Search</button>
            </form>
            <div id="search-results" class="dropdown-results"></div>
        </div>
        <div class="col-6 col-md-2 d-flex justify-content-end">
            <a href="<?= site_url('profile'); ?>">
                <img src="<?= base_url('Images/user_icon.jpg'); ?>" class="profile-icon" alt="Profile">
            </a>
        </div>
    </div>

    <div class="row mt-4">
    <?php if (!empty($posts)): ?>
        <?php foreach ($posts as $post): ?>
            <div class="col-md-4 mb-3">
                <div class="card">
                    <a href="<?= site_url('post/detail/' . $post['id']); ?>">
                        <img src="<?= base_url() . $post['image_path']; ?>" class="card-img-top" alt="Post Image">
                    </a>
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($post['caption']); ?></h5>
                        <p class="card-text"><small class="text-muted">Posted by <?= htmlspecialchars($post['author_username']); ?></small></p>
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
                                    <img src="<?= base_url(htmlspecialchars($user['profile_image'])); ?>" alt="Profile Picture" class="suggested-profile-icon mb-2">
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
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {
        $('#search-input').on('input', function() {
            var query = $(this).val();

            if (query.trim() === '') {
                $('#search-results').empty().hide();
                return;
            }

            $.ajax({
                url: '<?= site_url('search/dynamicResult'); ?>',
                type: 'get',
                data:{query: query},
                success: function(response) {
                    console.log('Response:', response); 

                    $('#search-results').empty().show();

                    if (response.results.length > 0) {
                        $.each(response.results, function(index, result) {
                            // Use text() instead of HTML concatenation to prevent XSS
                            var searchResult = $('<div class="search-result"></div>').text(result.username);
                            searchResult.click(function() {
                                // Sanitize username before using in URL
                                var safeUsername = encodeURIComponent(result.username);
                                window.location.href = '<?= site_url('profile/view/'); ?>' + safeUsername;
                            });
                            $('#search-results').append(searchResult);
                        });
                    } else {
                        $('#search-results').text('No results found');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                }
            });
        });

        $(document).on('click', function(event) {
            if (!$(event.target).closest('#search-input').length) {
                $('#search-results').empty().hide();
            }
        });
    });
</script>
</body>
</html>