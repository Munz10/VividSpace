<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>User's Feed - VividSpace</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Additional styles can go here */
        .search-bar {
            width: 100%; /* Or any other width */
        }
        .profile-icon {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #333; /* Replace with actual image */
        }
        .search-results {
            position: absolute;
            top: 100%;
            left: 0;
            width: 100%;
            background: #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            z-index: 9999;
            display: none;
            padding: 10px;
        }
        .search-result {
            padding: 5px 0;
            border-bottom: 1px solid #eee;
        }
        /* remove for original image view */
        .card-img-top {
            width: 100%;
            height: 400px; /* or any other height */
            object-fit: cover;
        }

        /* You may need to adjust the sizes and padding to match your wireframe */
    </style>
</head>
<body>
<div class="container">
    <div class="row align-items-center my-4">
        <!-- Logo and Create button, allocated 4 units -->
        <div class="col-6 col-md-4 d-flex align-items-center">
            <h1>VividSpace</h1>
            <a href="<?= site_url('profile/create_post'); ?>" class="btn btn-primary ml-3">Create</a>
        </div>
        <!-- Search Form, allocated 4 units -->
        <div class="col-12 col-md-4 my-3 my-md-0">
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
        <div class="col-6 col-md-4 d-flex justify-content-end">
            <a href="<?= site_url('profile'); ?>">
                <img src="<?= base_url('Images/user_icon.jpg'); ?>" class="profile-icon" alt="Profile">
            </a>
        </div>
    </div>

    <!-- Feed items -->
    <div class="row">
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
                                // Handle click event for each search result item
                                // You can perform any action here, such as redirecting to the user's profile page
                                console.log('Clicked on:', result.username);
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