# VividSpace

A photography-focused social media platform built with CodeIgniter 3. Share photos, follow creators, and explore moments.

![Security Status](https://img.shields.io/badge/Security-Production%20Ready-brightgreen)
![CSRF Protection](https://img.shields.io/badge/CSRF-Enabled-blue)
![XSS Protection](https://img.shields.io/badge/XSS-Protected-blue)
![PHP](https://img.shields.io/badge/PHP-5.3.7%2B-blue)
![CodeIgniter](https://img.shields.io/badge/CodeIgniter-3-orange)

---

## Quick Start

### Prerequisites
- XAMPP (Apache + MySQL + PHP 5.3.7+)
- MySQL 5.0.7+
- Modern web browser

### Installation

1. **Clone / copy** the project into `C:/xampp/htdocs/VividSpace/`

2. **Database setup** — open phpMyAdmin at `http://localhost/phpmyadmin`, create database `vivid_space_db`, then run the full schema from the [Database Setup](#database-setup) section below.

3. **Configure base URL** — already set in `application/config/config.php`:
   ```
   http://localhost/VividSpace/VividSpace/CodeIgniter_3/
   ```

4. **Open the app:**
   ```
   http://localhost/VividSpace/VividSpace/CodeIgniter_3/
   ```

---

## Features

### Accounts & Profiles
- Secure registration — username/email uniqueness checked live via AJAX
- BCrypt password hashing
- Login / logout with server-side session management
- Edit profile: display name, bio, profile picture (auto-resized with GD)
- Follow / unfollow users; self-follow prevented
- View follower / following counts on any profile
- Delete account (password confirmation required)

### Posts
- Upload photos (JPG, PNG, GIF — max 2 MB, auto-resized to 1280 px max)
- Add caption and hashtags
- Edit or delete your own posts
- Like / unlike with animated icon states
- Comment system with `@mention` linking
- Bookmark / save posts to a private collection
- Individual post detail page

### Discovery
- Personalized feed — posts from followed users, newest first
- Infinite scroll on feed and profile grids (Intersection Observer)
- Hashtag pages — all posts tagged with a given hashtag
- Saved Posts page — your bookmarked posts
- User search — full results page + live header autocomplete (self excluded)

### Notifications
- Bell icon in header with unread badge count
- Notifications for: new follower, like on your post, comment on your post
- Mark all as read on visit

### UI / UX
- Brand colour: vivid purple (`#7C3AED`)
- Bootstrap 4.5.2 responsive grid
- Bootstrap Icons 1.11.3 (loaded via shared CSS — no per-page CDN link needed)
- Shared header partial with home, create, search, notifications, and profile links
- Post preview modal (Bootstrap modal + AJAX) — open any post without leaving the page
- Dark-gradient auth pages with integrated logo card
- Lazy-loaded post images

---

## Security

### CSRF Protection
All POST requests (including every AJAX call) require a valid CodeIgniter CSRF token. The shared `assets/js/csrf-ajax.js` helper injects the token automatically.

### XSS Prevention
- `sanitize_input()` in models strips dangerous content before database storage
- `esc()` / `htmlspecialchars()` used on all user-generated output
- `linkify_mentions()` builds `@mention` links from pre-escaped HTML — no second-pass injection risk

### Password Security
BCrypt (`PASSWORD_BCRYPT`) — passwords are never stored or logged in plain text.

### SQL Injection
CodeIgniter Query Builder throughout — no raw SQL with user input.

### File Upload
Whitelist validation (image types only), 2 MB size cap, sanitised filenames.

### Session Security
Session fixation prevented — session ID regenerated on login.

### Self-follow / Self-search
Users cannot follow themselves; the logged-in user is excluded from all search results.

---

## Project Structure

```
VividSpace/
├── README.md
└── CodeIgniter_3/
    ├── index.php                          # Application entry point
    ├── application/
    │   ├── config/
    │   │   ├── config.php                 # Base URL, CSRF settings
    │   │   ├── database.php               # DB credentials
    │   │   ├── autoload.php               # Auto-loaded helpers/models
    │   │   └── routes.php                 # URL routing
    │   ├── core/
    │   │   └── MY_Controller.php          # Base controller — injects notif count
    │   ├── controllers/
    │   │   ├── LandingPage.php            # Public landing page
    │   │   ├── Login.php                  # Authentication
    │   │   ├── Signup.php                 # Registration + live validation
    │   │   ├── Profile.php                # Own profile, feed, AJAX endpoints
    │   │   ├── Post.php                   # Post CRUD, like, comment, bookmark
    │   │   ├── Follow.php                 # Follow / unfollow
    │   │   ├── Search.php                 # Full search + live autocomplete
    │   │   ├── Notifications.php          # Notification list + mark-read
    │   │   ├── Test_csrf.php              # Dev: CSRF testing tool
    │   │   └── Test_xss.php               # Dev: XSS testing tool
    │   ├── models/
    │   │   ├── User_model.php             # Users, follows, search
    │   │   ├── Post_model.php             # Posts, likes, comments, hashtags
    │   │   ├── Bookmark_model.php         # Bookmarks (toggle, list)
    │   │   └── Notification_model.php     # Notifications (create, list, mark-read)
    │   ├── views/
    │   │   ├── partials/
    │   │   │   ├── header.php             # Shared page header
    │   │   │   ├── post_card.php          # Full interactive post card
    │   │   │   ├── feed_cards.php         # Minimal card for feed AJAX append
    │   │   │   └── profile_cards.php      # Minimal card for profile AJAX append
    │   │   ├── landing_page.php           # Public homepage
    │   │   ├── login.php                  # Login
    │   │   ├── signup.php                 # Registration
    │   │   ├── profile.php                # Own profile + post grid
    │   │   ├── user_profile.php           # Another user's profile
    │   │   ├── edit_profile.php           # Edit profile form
    │   │   ├── delete_account.php         # Account deletion confirmation
    │   │   ├── feed.php                   # Followed-users feed
    │   │   ├── create_post.php            # New post form
    │   │   ├── post_detail.php            # Individual post page
    │   │   ├── post_edit.php              # Edit post form
    │   │   ├── search_result.php          # Search results (people + posts)
    │   │   ├── hashtag_posts.php          # Posts by hashtag
    │   │   ├── saved_posts.php            # Bookmarked posts
    │   │   ├── notifications.php          # Notification list
    │   │   ├── reset_password.php         # Password reset
    │   │   ├── test_csrf.php              # Dev: CSRF test UI
    │   │   └── test_xss.php               # Dev: XSS test UI
    │   └── helpers/
    │       └── xss_helper.php             # esc(), sanitize_input(), linkify_mentions()
    ├── assets/
    │   ├── css/
    │   │   └── app.css                    # Shared styles + Bootstrap Icons import
    │   └── js/
    │       └── csrf-ajax.js               # csrfPost() AJAX helper
    ├── Images/
    │   ├── vividSpace_Logo.png            # Icon only — used in app header
    │   ├── vividSpace_Intro.png           # Logo + name — used on login/signup
    │   ├── default_profile_pic.png        # Fallback avatar
    │   └── user_icon.jpg                  # Fallback header profile icon
    ├── uploads/                           # User post images
    ├── profile_pics/                      # User profile pictures
    ├── docs/                              # Developer documentation
    └── system/                            # CodeIgniter core (do not modify)
```

---

## Database Setup

```sql
CREATE DATABASE vivid_space_db CHARACTER SET utf8 COLLATE utf8_general_ci;
USE vivid_space_db;

-- Users
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    first_name VARCHAR(50),
    last_name VARCHAR(50),
    bio TEXT,
    profile_image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_username (username),
    INDEX idx_email (email)
);

-- Posts
CREATE TABLE posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    caption TEXT,
    image_path VARCHAR(255) NOT NULL,
    hashtags VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_posts (user_id, created_at)
);

-- Follows
CREATE TABLE user_follows (
    id INT AUTO_INCREMENT PRIMARY KEY,
    follower_id INT NOT NULL,
    following_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (follower_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (following_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_follow (follower_id, following_id),
    INDEX idx_follower (follower_id),
    INDEX idx_following (following_id)
);

-- Likes
CREATE TABLE likes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT NOT NULL,
    user_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_like (post_id, user_id),
    INDEX idx_post_likes (post_id)
);

-- Comments
CREATE TABLE comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT NOT NULL,
    user_id INT NOT NULL,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_post_comments (post_id, created_at)
);

-- Bookmarks
CREATE TABLE bookmarks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    post_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
    UNIQUE KEY unique_bookmark (user_id, post_id),
    INDEX idx_user_bookmarks (user_id)
);

-- Notifications
CREATE TABLE notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id   INT NOT NULL,
    actor_id  INT NOT NULL,
    type      VARCHAR(50) NOT NULL,
    entity_id INT,
    is_read   TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id)  REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (actor_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_notifs (user_id, is_read)
);
```

---

## URL Reference

| Page | URL |
|------|-----|
| Landing page | `/` |
| Login | `/login` |
| Sign up | `/signup` |
| My profile | `/profile` |
| My feed | `/profile/feed` |
| Create post | `/profile/create_post` |
| Edit profile | `/profile/edit` |
| Saved posts | `/profile/saved` |
| Delete account | `/profile/delete_account` |
| Another user's profile | `/profile/view/{username}` |
| Post detail | `/post/detail/{id}` |
| Search | `/search/result?query=...` |
| Hashtag page | `/search/hashtag/{tag}` |
| Notifications | `/notifications` |
| CSRF test (dev) | `/test_csrf` |
| XSS test (dev) | `/test_xss` |

---

## Tech Stack

| Layer | Technology |
|-------|-----------|
| Backend framework | CodeIgniter 3 |
| Language | PHP 5.3.7+ |
| Database | MySQL (MySQLi driver) |
| Auth | BCrypt (`PASSWORD_BCRYPT`) |
| Sessions | Server-side (CI session library) |
| Image processing | PHP GD (resize on upload) |
| CSS framework | Bootstrap 4.5.2 |
| Icons | Bootstrap Icons 1.11.3 |
| JavaScript | jQuery 3.6.0 |
| AJAX security | Custom `csrfPost()` helper |
| Infinite scroll | Intersection Observer API |

---

## Developer Notes

### Adding a new AJAX endpoint
1. Create the controller method and return `$this->output->set_content_type('application/json')->set_output(json_encode($data))`.
2. Call it from JS with `csrfPost(url, payload, successFn)` — CSRF token is injected automatically.

### Using `csrfPost()`
```javascript
csrfPost(
    '<?= site_url('controller/method'); ?>',
    { key: value },
    function(response) { /* success */ },
    function()         { /* error   */ }
);
```

### XSS helper functions (auto-loaded)
| Function | Use |
|----------|-----|
| `esc($str)` | Escape HTML for display |
| `esc_attr($str)` | Escape HTML attribute values |
| `esc_url($url)` | Sanitize URL output |
| `esc_js($str)` | Escape for inline JavaScript |
| `sanitize_input($str)` | Strip dangerous content before DB insert |
| `linkify_mentions($html)` | Convert `@user` to profile links (input must already be HTML-escaped) |
| `clean_filename($name)` | Sanitize uploaded file names |

### Notification count in all views
`MY_Controller` loads `Notification_model` and injects `$notif_unread_count` into every view via `$this->load->vars()`. Any view that includes `partials/header.php` gets the live badge automatically.

---

## Troubleshooting

**CSRF error ("The action you have requested is not allowed")**
Include `assets/js/csrf-ajax.js` and use `csrfPost()` for all AJAX calls. Plain `$.post()` will be rejected.

**Database connection error**
Check `application/config/database.php`:
```php
'hostname' => 'localhost',
'username' => 'root',
'password' => '',
'database' => 'vivid_space_db',
```

**Uploaded images not showing**
Ensure these directories exist and Apache has write permission:
- `CodeIgniter_3/uploads/`
- `CodeIgniter_3/profile_pics/`

**Bootstrap Icons not rendering**
`app.css` imports the CDN at the top. Every page that loads `app.css` gets the icons — no separate `<link>` tag needed.

---

## Security Checklist

| Feature | Status |
|---------|--------|
| CSRF — all POST/AJAX | Enabled |
| XSS — input sanitize + output escape | Active |
| SQL injection — Query Builder | Protected |
| Password — BCrypt | Hashed |
| Session fixation — ID regenerated on login | Fixed |
| File upload — type + size validation | Active |
| Self-follow prevention | Enforced |
| Self-search exclusion | Enforced |

---

## Documentation

Full guides are in `CodeIgniter_3/docs/`:

- [CSRF Implementation Guide](CodeIgniter_3/docs/CSRF_IMPLEMENTATION_GUIDE.md)
- [CSRF Testing Guide](CodeIgniter_3/docs/CSRF_TESTING_GUIDE.md)
- [XSS Protection Guide](CodeIgniter_3/docs/XSS_PROTECTION_GUIDE.md)
- [Security Fixes Summary](CodeIgniter_3/docs/SECURITY_FIXES_SUMMARY.md)
- [Project Structure](CodeIgniter_3/docs/PROJECT_STRUCTURE.md)

---

## License

MIT — free to use and modify for educational and commercial purposes.

---

**VividSpace** — The Photographer's Social Network  
**Version:** 1.2.0 | **Last updated:** June 2026
