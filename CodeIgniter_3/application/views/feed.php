<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>User's Feed - VividSpace</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Roboto', sans-serif; /* Set default font */
            background-color: #f8f9fa; /* Light background color */
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
            border-radius: 10px; /* Optional: Add some border radius */
            background-color: #f8f9fa; /* Light gray background color */
            box-shadow: 0 2px 4px rgba(0,0,0,0.1); /* Optional: Add shadow for a lifted appearance */
            width: 50%
        }
        .suggested-profile-icon {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background-color: #e9ecef; /* Light gray background color */
            object-fit: cover; /* Ensure the image fits into the circle */
        } 
        .search-bar {
            width: 100%; /* Or any other width */
        }
        .profile-icon {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #333; /* Replace with actual image */
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
            cursor: pointer; /* Add cursor pointer to indicate clickable */
        }
        /* remove for original image view */
        .card-img-top {
            width: 100%;
            height: 400px; /* or any other height */
            object-fit: cover;
        }
        .card {
            background-color: #f8f9fa; /* Light gray background color */
            padding: 15px; /* Add padding to create space */
            border-radius: 10px; /* Optional: Rounded corners */
            box-shadow: 0 2px 4px rgba(0,0,0,0.1); /* Optional: Box shadow for lifted appearance */
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header-section">
        <!-- Logo and Create button, allocated 4 units -->
        <div class="logo-and-home">
            <h1>VividSpace</h1>
            <a href="<?= site_url('profile/create_post'); ?>" class="btn btn-primary ml-3">Create</a>
        </div>
        <!-- Search Form, allocated 4 units -->
        <div class="col-12 col-md-6 my-3 my-md-0">
            <form action="<?= site_url('search/result'); ?>" method="get" class="d-flex">
                <input type="search" id="search-input" class="form-control flex-grow-1" name="query" placeholder="Search users..." required>
                <button type="submit" class="btn btn-outline-secondary ml-2">Search</button>
            </form>
            <!-- Search Results Container -->
            <div id="search-results" class="dropdown-results"></div> <!-- Added dropdown-results class -->
        </div>
        <!-- Search Results Container -->
        <div id="search-results"></div>
        <!-- User Icon, allocated 4 units -->
        <div class="col-6 col-md-2 d-flex justify-content-end">
            <a href="<?= site_url('profile'); ?>">
                <img src="<?= base_url('Images/user_icon.jpg'); ?>" class="profile-icon" alt="Profile">
            </a>
        </div>
    </div>

    <!-- Feed items -->
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
        <div class="col-md-12 mb-3"> <!-- Center the suggested users list -->
            <div class="col-md-12">
                <h3 class="text-center mb-4">Suggested Users to Follow</h3> <!-- Center the heading -->
                <div class="d-flex flex-wrap justify-content-center"> <!-- Center the content -->
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
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    $(document).ready(function() {
        // Listen for input changes in the search bar
        $('#search-input').on('input', function() {
            // Get the search query
            var query = $(this).val();

            if (query.trim() === '') {
                // If the search query is empty, hide the search results
                $('#search-results').empty().hide();
                return;
            }

            // Send AJAX request to the server
            $.ajax({
                url: '<?= site_url('search/dynamicResult'); ?>',
                type: 'get',
                data:{query: query},
                success: function(response) {
                    console.log('Response:', response); 

                    // Clear previous results and show the search results container
                    $('#search-results').empty().show();

                    // Check if there are any results
                    if (response.results.length > 0) {
                        // Iterate over the search results and create search result items
                        $.each(response.results, function(index, result) {
                            var searchResult = $('<div class="search-result">' + result.username + '</div>');
                            searchResult.click(function() {
                                // Redirect to the user's profile page when clicked
                                window.location.href = '<?= site_url('profile/view/'); ?>' + result.username;
                            });
                            $('#search-results').append(searchResult);
                        });
                    } else {
                        // If no results found, display a message
                        $('#search-results').text('No results found');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                }
            });
        });

        // Hide search results when clicking outside of the search bar
        $(document).on('click', function(event) {
            if (!$(event.target).closest('#search-input').length) {
                $('#search-results').empty().hide();
            }
        });
    });
</script>


</body>
</html>
