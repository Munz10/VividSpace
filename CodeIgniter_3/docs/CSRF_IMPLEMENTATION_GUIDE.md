# CSRF Protection Implementation Guide

## What Was Fixed

CSRF (Cross-Site Request Forgery) protection has been enabled to prevent malicious websites from performing unauthorized actions on behalf of logged-in users.

## Changes Made

### 1. Configuration Update
- **File**: `application/config/config.php`
- **Change**: Enabled CSRF protection
```php
$config['csrf_protection'] = TRUE; // Changed from FALSE
```

### 2. Controller Updates

#### Post Controller (`application/controllers/Post.php`)
- Added CSRF token validation (automatic)
- Added input validation
- Return new CSRF token in response
- Improved error handling

#### Follow Controller (`application/controllers/Follow.php`)
- Added CSRF token validation (automatic)
- Added self-follow prevention
- Return new CSRF token in response
- Fixed broken code

### 3. JavaScript Helper
- **File**: `assets/js/csrf-ajax.js`
- Provides helper functions for CSRF-protected AJAX requests
- Automatically handles token refresh

## How to Use in Your Views

### Step 1: Include the JavaScript Helper

Add this to your view files (e.g., profile.php, post_detail.php):

```html
<!-- Include CSRF AJAX Helper -->
<script src="<?= base_url('assets/js/csrf-ajax.js'); ?>"></script>
```

### Step 2: Use the Helper Functions

#### Example 1: Like/Unlike a Post

```html
<button onclick="handleLike(<?= $post['id']; ?>)">
    Like (<span id="likes-count-<?= $post['id']; ?>"><?= $post['likes_count']; ?></span>)
</button>

<script>
function handleLike(postId) {
    toggleLike(postId, function(response) {
        // Update the like count on success
        document.getElementById('likes-count-' + postId).textContent = response.likes_count;
    });
}
</script>
```

#### Example 2: Add a Comment

```html
<form onsubmit="handleComment(event, <?= $post['id']; ?>); return false;">
    <textarea id="comment-text-<?= $post['id']; ?>" placeholder="Add a comment..." required></textarea>
    <button type="submit">Post Comment</button>
</form>

<script>
function handleComment(event, postId) {
    event.preventDefault();
    const content = document.getElementById('comment-text-' + postId).value;
    
    addComment(postId, content, function(response) {
        // Clear the textarea
        document.getElementById('comment-text-' + postId).value = '';
        // Refresh comments or add to list
        alert('Comment added successfully!');
    });
}
</script>
```

#### Example 3: Follow/Unfollow a User

```html
<button id="follow-btn-<?= $user['id']; ?>" 
        onclick="handleFollow(<?= $user['id']; ?>, <?= $is_following ? 'true' : 'false'; ?>)">
    <?= $is_following ? 'Unfollow' : 'Follow'; ?>
</button>

<script>
function handleFollow(userId, isFollowing) {
    const btn = document.getElementById('follow-btn-' + userId);
    
    if (isFollowing) {
        unfollowUser(userId, function(response) {
            btn.textContent = 'Follow';
            btn.onclick = function() { handleFollow(userId, false); };
        });
    } else {
        followUser(userId, function(response) {
            btn.textContent = 'Unfollow';
            btn.onclick = function() { handleFollow(userId, true); };
        });
    }
}
</script>
```

### Step 3: For Regular Forms (Non-AJAX)

CodeIgniter automatically adds CSRF tokens to forms when you use `form_open()`:

```php
<?= form_open('profile/update'); ?>
    <!-- Form fields -->
    <input type="text" name="first_name" value="<?= $profile['first_name']; ?>">
    <button type="submit">Update Profile</button>
<?= form_close(); ?>
```

## Important Notes

1. **Automatic Validation**: CodeIgniter automatically validates CSRF tokens on POST requests when CSRF protection is enabled.

2. **Token Refresh**: CSRF tokens regenerate on every request (configurable). The JavaScript helper automatically updates tokens in responses.

3. **Cookie Required**: CSRF protection uses cookies. Ensure users have cookies enabled.

4. **AJAX Errors**: If you get "The action you have requested is not allowed" errors:
   - Check that CSRF token is being sent
   - Verify the token name matches config (`csrf_test_name`)
   - Check browser console for JavaScript errors

5. **Testing**: When testing AJAX endpoints, include the CSRF token:
   ```javascript
   fetch('/post/toggle_like', {
       method: 'POST',
       headers: {
           'Content-Type': 'application/x-www-form-urlencoded'
       },
       body: 'post_id=123&csrf_test_name=' + getCsrfToken()
   });
   ```

## Security Benefits

✅ **Prevents**: Attackers from making unauthorized requests using victim's session
✅ **Protects**: Like, comment, follow, unfollow, post creation, profile updates
✅ **Validates**: Every POST request includes a valid, time-limited token
✅ **Regenerates**: Tokens refresh automatically, reducing attack window

## Troubleshooting

**Problem**: "The action you have requested is not allowed"
- **Solution**: Include CSRF token in your request

**Problem**: Token expired
- **Solution**: Tokens expire after 7200 seconds (2 hours). Refresh the page.

**Problem**: AJAX request fails
- **Solution**: Check browser console, verify token is being sent, check controller returns new token

## Next Steps

Update your existing view files to:
1. Include the CSRF AJAX helper script
2. Replace direct AJAX calls with the helper functions
3. Test all interactive features (like, comment, follow)

