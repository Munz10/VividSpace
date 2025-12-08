# CSRF Protection Testing Guide

## 🚀 How to Test

### Method 1: Using the Test Page (EASIEST)

1. **Start your XAMPP server** (Apache and MySQL)

2. **Open your browser** and navigate to:
   ```
   http://localhost/VividSpace/VividSpace/CodeIgniter_3/index.php/test_csrf
   ```
   
   Or if you have a custom base URL:
   ```
   http://your-base-url/index.php/test_csrf
   ```

3. **Run the 5 tests** by clicking each button:
   - ✅ **Test 1** - Should PASS (using helper function)
   - ✅ **Test 2** - Should PASS (manual AJAX with token)
   - ❌ **Test 3** - Should FAIL (no token - protection working!)
   - ❌ **Test 4** - Should FAIL (invalid token - protection working!)
   - ✅ **Test 5** - Should PASS (form with auto-token)

4. **Expected Results:**
   - Tests 1, 2, and 5 should show **green success messages**
   - Tests 3 and 4 should show **red error messages** (this is GOOD - it means protection is working!)

---

### Method 2: Test with Browser Console

1. Open any page on VividSpace (e.g., login page)

2. Open **Browser Developer Tools** (F12)

3. Go to the **Console** tab

4. **Test WITH CSRF token** (should work):
   ```javascript
   fetch('http://localhost/VividSpace/VividSpace/CodeIgniter_3/index.php/test_csrf/test_protected_action', {
       method: 'POST',
       headers: {
           'Content-Type': 'application/x-www-form-urlencoded'
       },
       body: 'test_data=Hello&csrf_test_name=' + getCsrfToken()
   }).then(r => r.json()).then(console.log);
   ```
   
   **Expected:** Should return success message

5. **Test WITHOUT CSRF token** (should fail):
   ```javascript
   fetch('http://localhost/VividSpace/VividSpace/CodeIgniter_3/index.php/test_csrf/test_protected_action', {
       method: 'POST',
       headers: {
           'Content-Type': 'application/x-www-form-urlencoded'
       },
       body: 'test_data=Hello'  // NO CSRF TOKEN
   }).then(r => r.text()).then(console.log);
   ```
   
   **Expected:** Should return "The action you have requested is not allowed"

---

### Method 3: Test with Existing Features

#### Test Like/Unlike (if you have posts)

1. Login to VividSpace
2. Go to a post with like button
3. Open Browser Console (F12)
4. Run this:
   ```javascript
   // First, include the helper if not already loaded
   var script = document.createElement('script');
   script.src = 'http://localhost/VividSpace/VividSpace/CodeIgniter_3/assets/js/csrf-ajax.js';
   document.head.appendChild(script);
   
   // Wait a moment, then test
   setTimeout(() => {
       toggleLike(1, (response) => {  // Replace 1 with actual post ID
           console.log('Like toggled:', response);
       });
   }, 1000);
   ```

#### Test Follow/Unfollow

```javascript
// Follow a user
followUser(2, (response) => {  // Replace 2 with actual user ID
    console.log('Follow result:', response);
});

// Unfollow a user
unfollowUser(2, (response) => {
    console.log('Unfollow result:', response);
});
```

---

### Method 4: Test with Postman/Thunder Client

1. **Get CSRF Token first:**
   - Visit any VividSpace page in your browser
   - Open Developer Tools → Application → Cookies
   - Find `csrf_cookie_name` and copy its value

2. **Make POST request in Postman:**
   - URL: `http://localhost/VividSpace/VividSpace/CodeIgniter_3/index.php/test_csrf/test_protected_action`
   - Method: POST
   - Body (x-www-form-urlencoded):
     ```
     test_data: Hello from Postman
     csrf_test_name: [paste token here]
     ```
   - Send request

3. **Without token (should fail):**
   - Remove `csrf_test_name` from body
   - Send request
   - Should get error: "The action you have requested is not allowed"

---

## 🔍 What to Look For

### ✅ CSRF Protection is Working When:

1. **Valid requests succeed:**
   - Requests with correct CSRF token return success
   - Response includes new token
   - Actions complete successfully

2. **Invalid requests fail:**
   - Requests without token are blocked
   - Requests with fake/expired tokens are blocked
   - Error message: "The action you have requested is not allowed"

3. **Forms work automatically:**
   - Forms created with `form_open()` work without extra code
   - CSRF token field is automatically added

### ❌ CSRF Protection NOT Working If:

1. Requests without token succeed (BAD!)
2. No error message when token is missing
3. Forms submit without validation

---

## 🐛 Troubleshooting

### Problem: "The action you have requested is not allowed" on VALID requests

**Solution:**
1. Check that token is being sent:
   ```javascript
   console.log('Token:', getCsrfToken());
   ```
2. Verify token name in `config/config.php` matches:
   ```php
   $config['csrf_token_name'] = 'csrf_test_name';
   ```
3. Check cookies are enabled in browser

### Problem: getCsrfToken() returns empty string

**Solution:**
1. Make sure CSRF protection is enabled in `config/config.php`
2. Check cookie name matches config
3. Visit a page first to generate the cookie

### Problem: Test page shows "404 Not Found"

**Solution:**
Check your URL structure. Try:
- `http://localhost/VividSpace/VividSpace/CodeIgniter_3/index.php/test_csrf`
- Or: `http://localhost/VividSpace/CodeIgniter_3/index.php/test_csrf`

Adjust based on your actual folder structure.

---

## 📸 Screenshot of Expected Results

When all tests run successfully, you should see:

- **Test 1:** Green box - "CSRF token validated successfully!"
- **Test 2:** Green box - "CSRF token validated successfully!"
- **Test 3:** Red box - "PROTECTION WORKING! Request was blocked..."
- **Test 4:** Red box - "PROTECTION WORKING! Request was blocked..."
- **Test 5:** Green box - "CodeIgniter automatically added CSRF token..."

---

## 🎯 Real-World Attack Test

To truly verify protection, try this simulated attack:

1. **Create a malicious HTML file** on your desktop:
   ```html
   <!DOCTYPE html>
   <html>
   <body>
   <h1>Malicious Page</h1>
   <script>
   // This attack will FAIL because of CSRF protection
   fetch('http://localhost/VividSpace/VividSpace/CodeIgniter_3/index.php/test_csrf/test_protected_action', {
       method: 'POST',
       body: 'test_data=Malicious'
   }).then(r => r.text()).then(alert);
   </script>
   </body>
   </html>
   ```

2. **Open this file** in your browser (while logged into VividSpace in another tab)

3. **Expected Result:** Alert shows "The action you have requested is not allowed"
   - This proves the attack was blocked! ✅

---

## ✨ Next Steps After Testing

Once you confirm CSRF protection is working:

1. Update your existing views to use the CSRF helper
2. Add the helper script to profile.php, feed.php, etc.
3. Replace existing AJAX calls with helper functions
4. Test all interactive features (like, comment, follow)

Ready to move to **Critical Fix #2: XSS Prevention**? 🔒

