/**
 * CSRF-Protected AJAX Helper for VividSpace
 * 
 * This file provides helper functions to make AJAX requests with CSRF protection.
 * CodeIgniter's CSRF tokens are automatically validated on the server side.
 */

// Get CSRF token from cookie
function getCsrfToken() {
    const name = 'csrf_cookie_name=';
    const decodedCookie = decodeURIComponent(document.cookie);
    const cookieArray = decodedCookie.split(';');
    
    for(let i = 0; i < cookieArray.length; i++) {
        let cookie = cookieArray[i].trim();
        if (cookie.indexOf(name) == 0) {
            return cookie.substring(name.length, cookie.length);
        }
    }
    return '';
}

// Make a CSRF-protected POST request
function csrfPost(url, data, successCallback, errorCallback) {
    // Add CSRF token to data
    data.csrf_test_name = getCsrfToken();
    
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: new URLSearchParams(data),
        credentials: 'same-origin'
    })
    .then(response => response.json())
    .then(data => {
        // Update CSRF token if provided in response
        if (data.csrf_token) {
            updateCsrfCookie(data.csrf_token);
        }
        
        if (successCallback) {
            successCallback(data);
        }
    })
    .catch(error => {
        console.error('AJAX Error:', error);
        if (errorCallback) {
            errorCallback(error);
        }
    });
}

// Update CSRF cookie with new token
function updateCsrfCookie(token) {
    document.cookie = `csrf_cookie_name=${token}; path=/; SameSite=Strict`;
}

// jQuery compatible version (if using jQuery)
if (typeof jQuery !== 'undefined') {
    jQuery.ajaxSetup({
        data: {
            csrf_test_name: getCsrfToken()
        },
        beforeSend: function(xhr) {
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        }
    });
    
    // Update CSRF token after each AJAX request
    jQuery(document).ajaxComplete(function(event, xhr, settings) {
        try {
            const response = JSON.parse(xhr.responseText);
            if (response.csrf_token) {
                updateCsrfCookie(response.csrf_token);
                jQuery.ajaxSetup({
                    data: {
                        csrf_test_name: response.csrf_token
                    }
                });
            }
        } catch(e) {
            // Not a JSON response, ignore
        }
    });
}

// Example usage functions for VividSpace features

/**
 * Toggle like on a post
 * @param {number} postId - The ID of the post
 * @param {function} callback - Callback function to handle response
 */
function toggleLike(postId, callback) {
    csrfPost(
        'post/toggle_like',
        { post_id: postId },
        function(response) {
            if (response.status === 'success') {
                if (callback) callback(response);
            } else {
                console.error('Like toggle failed:', response.message);
            }
        },
        function(error) {
            console.error('Error toggling like:', error);
        }
    );
}

/**
 * Add a comment to a post
 * @param {number} postId - The ID of the post
 * @param {string} content - The comment content
 * @param {function} callback - Callback function to handle response
 */
function addComment(postId, content, callback) {
    if (!content || content.trim() === '') {
        alert('Comment cannot be empty');
        return;
    }
    
    csrfPost(
        'post/add_comment',
        { post_id: postId, content: content },
        function(response) {
            if (response.status === 'success') {
                if (callback) callback(response);
            } else {
                alert('Failed to add comment: ' + response.message);
            }
        },
        function(error) {
            console.error('Error adding comment:', error);
            alert('An error occurred while adding your comment');
        }
    );
}

/**
 * Follow a user
 * @param {number} userId - The ID of the user to follow
 * @param {function} callback - Callback function to handle response
 */
function followUser(userId, callback) {
    csrfPost(
        'follow/do_follow',
        { following_id: userId },
        function(response) {
            if (response.status === 'success') {
                if (callback) callback(response);
            } else {
                alert('Failed to follow user: ' + response.message);
            }
        },
        function(error) {
            console.error('Error following user:', error);
        }
    );
}

/**
 * Unfollow a user
 * @param {number} userId - The ID of the user to unfollow
 * @param {function} callback - Callback function to handle response
 */
function unfollowUser(userId, callback) {
    csrfPost(
        'follow/do_unfollow',
        { following_id: userId },
        function(response) {
            if (response.status === 'success') {
                if (callback) callback(response);
            } else {
                alert('Failed to unfollow user: ' + response.message);
            }
        },
        function(error) {
            console.error('Error unfollowing user:', error);
        }
    );
}

