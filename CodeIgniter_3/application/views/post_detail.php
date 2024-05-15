<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Post Details - VividSpace</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .card {
            border: none;
            align-items : center;
            margin-top :1rem;
            padding: 15px; 
            border-radius: 10px;
            box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.1); /* Added box-shadow for elevation */
            background-color: #fff; /* Added background color */
        }
        .card-img-top {
            border-radius: 0;
        }
        .card-title {
            color: #4B9CD3
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
            font-size: 0.9rem;
            margin-bottom: 7rem;
            margin-left: 3rem;
            align-items: start;
        }
        .profile-icon {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #333; /* Replace with actual image */
        }
        .card-img-top {
            width: 60%;
            height: 500px;
            object-fit: contain;
        }
        .comment-section {
            border-left: 1px solid #ddd;
            padding-left: 20px;
        }
        .interaction-line {
            margin-bottom: 1rem; /* Adjust the margin as needed */
        }
        .btn-danger{
            margin-left: 3rem;
        }
        .like-comment-icons {
            margin-right: 1rem;
        }
        .like-comment-count {
            font-size: 1rem;
        }
    </style>
</head>
<body>
    <div class="container">         
        <div class="row align-items-center my-4">
            <div class="col-6 col-md-8 d-flex align-items-center">
                <!-- Logo -->
                <h1>VividSpace</h1>
                <!-- Home button -->
                <a href="<?= site_url('profile/feed'); ?>" class="btn btn-primary ml-2">Home</a>
            </div>
            <div class="col-6 col-md-4 d-flex justify-content-end">
                <!-- User Icon -->
                <a href="<?= site_url('profile'); ?>">
                    <img src="<?= base_url('Images/user_icon.jpg'); ?>" class="profile-icon" alt="Profile">
                </a>
            </div>
            <div class="col-md-6">
                <!-- Post Content -->
                <div class="card">
                    <img class="card-img-top" src="<?= base_url() . $post['image_path']; ?>" alt="Post Image">
                        <h5 class="card-title">@<?= $post['username']; ?></h5>
                        <p class="card-text"><?= $post['caption']; ?></p>
                        <p class="card-text"><?= $post['hashtags']; ?></p>
                </div>
            </div>
            <!-- Right Side -->
            <div class="col-md-6">
                <!-- Interaction bar -->
                <div class="interaction-bar">
                    <!-- Line 1: Like and Comment Buttons -->
                    <div class="interaction-line">
                        <!-- Like button -->
                        <button onclick="toggleLike(<?= $post['id']; ?>);" class="btn btn-outline-secondary like-comment-icons" style="padding: 0; border: none; background: none;">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="32" height="32">
                                <path id="heart-icon-<?= $post['id']; ?>" fill="#888" d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                            </svg>
                        </button>
                        <!-- Comment button -->
                        <button onclick="toggleCommentSection(<?= $post['id']; ?>);" class="btn btn-outline-secondary like-comment-icons" style="padding: 0; border: none; background: none;">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="32" height="32">
                                <path fill="none" stroke="#000000" stroke-width="2" d="M16.293,2H7.707C6.57,2,5.707,2.866,5.707,4v12c0,1.137,0.863,2,2,2h7.586l3.707,3.707V4 C18.707,2.866,17.844,2,16.293,2z"/>
                            </svg>
                        </button>
                    </div>
                    <!-- Line 2: Number of Likes -->
                    <div class="interaction-line">
                        <span class="card-text like-comment-count" id="like-count-<?= $post['id']; ?>"><?= $post['likes_count']; ?> likes</span>
                    </div>
                    <!-- Line 3: Number of Comments -->
                    <div class="interaction-line">
                        <span class="comments-toggle like-comment-count" onclick="showComments(<?= $post['id']; ?>);">
                            <span id="comment-count-<?= $post['id']; ?>" class="comment-count" style="cursor: pointer;"><?= $post['comments_count']; ?></span> comments
                        </span>
                    </div>
                </div>
                <!-- Comments Section -->
                <div id="comments-container-<?= $post['id']; ?>" style="display:none;">
                    <!-- Dynamic comments will be loaded here -->
                    <div id="comments-content-<?= $post['id']; ?>"></div>
                </div>
                <!-- Comment Form (hidden by default) -->
                <div id="comment-section-<?= $post['id']; ?>" style="display:none;">
                    <form onsubmit="event.preventDefault(); addComment(<?= $post['id']; ?>);">
                        <input type="text" id="comment-content-<?= $post['id']; ?>" class="form-control" placeholder="Write a comment...">
                        <button type="submit" class="btn btn-primary">Post Comment</button>
                    </form>
                </div>
                <?php if ($this->session->userdata('user_id') == $post['user_id']): ?>
                    <!-- Show delete button only if logged-in user is the owner of the post -->
                    <a href="<?= site_url('profile/delete_post/' . $post['id']); ?>" class="btn btn-danger">Delete</a>
                <?php endif; ?>
              </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins and AJAX) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
    $(document).ready(function() {
        // Check the like status of the post when the page loads
        var postId = <?= $post['id']; ?>;
        $.ajax({
            url: '<?= site_url('post/check_like_status'); ?>',
            type: 'POST',
            data: { post_id: postId },
            dataType: 'json',
            success: function(response) {
                if (response.liked) {
                    $('#heart-icon-' + postId).css('fill', 'red');
                }
            }
        });
    });

    function toggleLike(postId) {
        var heartIcon = $('#heart-icon-' + postId);
        var isLiked = heartIcon.css('fill') === 'rgb(255, 0, 0)'; // Check if heart is red (liked)
        
        $.ajax({
            url: '<?= site_url('post/toggle_like'); ?>',
            type: 'POST',
            data: { post_id: postId },
            dataType: 'json',
            success: function(response) {
                var likeCount = response.likes_count;
                $('#like-count-' + postId).text(likeCount + ' likes');

                // Toggle heart color based on like status
                if (isLiked) {
                    heartIcon.css('fill', '#888'); // Change to grey if already liked
                } else {
                    heartIcon.css('fill', 'red'); // Change to red if not liked
                }
            }
        });
    }

    function addComment(postId) {
        var content = $('#comment-content-' + postId).val();
        $.ajax({
            url: '<?= site_url('post/add_comment'); ?>',
            type: 'POST',
            data: { post_id: postId, content: content },
            dataType: 'json',
            success: function(response) {
                if (response.comment) {
                    // Append new comment to the comments container and clear the input field
                    $('#comments-content-' + postId).append('<p><span style="color: #008E97;">@' + response.comment.username + '</span>  ' + response.comment.content + '</p>');
                    $('#comment-content-' + postId).val('');
                    // Update comment count
                    $('#comment-count-' + postId).text(parseInt($('#comment-count-' + postId).text(), 10) + 1);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error adding comment:', error);
            }
        });
    }

        function toggleCommentSection(postId) {
            $('#comment-section-' + postId).toggle();
        }

        // Define the showComments function
        function showComments(postId) {
            $.ajax({
                url: '<?= site_url('post/get_comments'); ?>', // Update URL to match your backend endpoint
                type: 'POST',
                data: { post_id: postId },
                dataType: 'json',
                success: function(response) {
                    var comments = response.comments;
                    var commentsContent = $('#comments-content-' + postId);
                    commentsContent.empty(); // Clear previous comments
                    comments.forEach(function(comment) {
                        // Append comment with username in blue color
                        commentsContent.append('<p><span style="color: #008E97;">@' + comment.username + '</span>  ' + comment.content + '</p>');
                    });
                    $('#comments-container-' + postId).show(); // Show the comments container
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching comments:', error);
                }
            });
        }

        // Function to delete post
        function deletePost(postId) {
            $.ajax({
                url: '<?= site_url('profile/delete_post'); ?>/' + postId,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    // Check if post was deleted successfully
                    if (response.success) {
                        // Remove the post from the DOM
                        $('#post-' + postId).remove();
                        // Optionally, display a success message
                    } else {
                        // Handle error or display a message
                    }
                },
                error: function(xhr, status, error) {
                    // Handle error or display a message
                    console.error('Error:', error);
                }
            });
        }
    </script>
</body>
</html>
