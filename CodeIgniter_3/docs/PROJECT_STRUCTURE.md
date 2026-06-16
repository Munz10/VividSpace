# VividSpace — Project Structure

## Complete File Tree

```
VividSpace/
├── README.md
└── CodeIgniter_3/
    ├── index.php                                  # CI entry point
    ├── PROJECT_STRUCTURE.md                       # This file
    │
    ├── application/
    │   ├── config/
    │   │   ├── config.php                         # Base URL, CSRF, session
    │   │   ├── database.php                       # DB credentials
    │   │   ├── autoload.php                       # Auto-loaded helpers & libraries
    │   │   └── routes.php                         # URL routing
    │   │
    │   ├── core/
    │   │   └── MY_Controller.php                  # Base controller; injects notif_unread_count
    │   │
    │   ├── controllers/
    │   │   ├── LandingPage.php                    # Public landing page
    │   │   ├── Login.php                          # Auth: login, logout, process
    │   │   ├── Signup.php                         # Registration + live username/email check
    │   │   ├── Profile.php                        # Own profile, feed, edit, save post,
    │   │   │                                      #   infinite-scroll endpoints, delete account
    │   │   ├── Post.php                           # Create, edit, delete, like, comment,
    │   │   │                                      #   bookmark, modal content endpoint
    │   │   ├── Follow.php                         # do_follow, do_unfollow
    │   │   ├── Search.php                         # Full search, hashtag page, live autocomplete
    │   │   ├── Notifications.php                  # List + mark-read
    │   │   ├── Welcome.php                        # Default CI controller (redirects)
    │   │   ├── Test_csrf.php                      # Dev: CSRF test tool
    │   │   └── Test_xss.php                       # Dev: XSS test tool
    │   │
    │   ├── models/
    │   │   ├── User_model.php                     # Users, follows, search (excludes self)
    │   │   ├── Post_model.php                     # Posts, likes, comments, hashtag queries
    │   │   ├── Bookmark_model.php                 # toggle(), list_for_user()
    │   │   └── Notification_model.php             # create(), list_for_user(), mark_read()
    │   │
    │   ├── views/
    │   │   ├── partials/
    │   │   │   ├── header.php                     # Shared header (logo, nav, search, bell)
    │   │   │   ├── post_card.php                  # Full interactive post card
    │   │   │   ├── feed_cards.php                 # Minimal HTML cards — appended by feed IS
    │   │   │   └── profile_cards.php              # Minimal HTML cards — appended by profile IS
    │   │   │
    │   │   ├── landing_page.php                   # Public homepage
    │   │   ├── login.php                          # Login (dark auth-card layout)
    │   │   ├── signup.php                         # Registration (dark auth-card layout)
    │   │   ├── profile.php                        # Own profile + post grid + infinite scroll
    │   │   ├── user_profile.php                   # Another user's profile (no follow btn for self)
    │   │   ├── edit_profile.php                   # Edit bio, name, profile pic; Delete account link
    │   │   ├── delete_account.php                 # Password-confirmed account deletion
    │   │   ├── feed.php                           # Followed-users feed + infinite scroll + modal
    │   │   ├── create_post.php                    # New post upload form
    │   │   ├── post_detail.php                    # Full post page (like, comment, bookmark)
    │   │   ├── post_edit.php                      # Edit caption/hashtags
    │   │   ├── search_result.php                  # People + posts results (self excluded)
    │   │   ├── hashtag_posts.php                  # Posts by hashtag + modal
    │   │   ├── saved_posts.php                    # Bookmarked posts + modal
    │   │   ├── notifications.php                  # Notification list
    │   │   ├── reset_password.php                 # Password reset form
    │   │   ├── search_form.php                    # Standalone search form (minimal)
    │   │   ├── test_csrf.php                      # Dev: CSRF test UI
    │   │   └── test_xss.php                       # Dev: XSS test UI
    │   │
    │   └── helpers/
    │       └── xss_helper.php                     # esc(), sanitize_input(), linkify_mentions()
    │
    ├── assets/
    │   ├── css/
    │   │   └── app.css                            # Shared styles + Bootstrap Icons CDN import
    │   └── js/
    │       └── csrf-ajax.js                       # csrfPost() — injects CSRF token into AJAX
    │
    ├── Images/
    │   ├── vividSpace_Logo.png                    # Icon only — app header
    │   ├── vividSpace_Intro.png                   # Logo + "VIVIDSpace" name — login/signup
    │   ├── default_profile_pic.png                # Fallback avatar
    │   └── user_icon.jpg                          # Fallback header profile icon
    │
    ├── uploads/                                   # User post images (runtime)
    ├── profile_pics/                              # User profile pictures (runtime)
    ├── docs/                                      # Developer documentation
    └── system/                                    # CodeIgniter 3 core — do not modify
```

---

## File Roles by Area

### Security
| File | Role |
|------|------|
| `config/config.php` | CSRF token name, session encryption key |
| `helpers/xss_helper.php` | Input sanitisation + output escaping functions |
| `assets/js/csrf-ajax.js` | Attaches CSRF token to every AJAX POST |
| `core/MY_Controller.php` | Base controller; all protected routes extend this |

### Authentication
| File | Role |
|------|------|
| `controllers/Login.php` | Validate credentials, regenerate session ID |
| `controllers/Signup.php` | Create user, live AJAX username/email checks |
| `views/login.php` | Auth-card layout with branded dark header |
| `views/signup.php` | Auth-card layout with branded dark header |
| `models/User_model.php` | BCrypt verify, insert user, search (exclude self) |

### Social graph
| File | Role |
|------|------|
| `controllers/Follow.php` | do_follow / do_unfollow — returns updated counts |
| `models/User_model.php` | is_following(), follow(), unfollow() |
| `views/user_profile.php` | Follow/Unfollow button hidden for own profile |

### Posts & interactions
| File | Role |
|------|------|
| `controllers/Post.php` | like, comment, bookmark, modal_content endpoints |
| `models/Post_model.php` | get_posts, search_posts, like/unlike, add_comment |
| `models/Bookmark_model.php` | toggle(), list_for_user() |
| `views/partials/post_card.php` | Dual-icon like/bookmark, comment form, hashtag links |

### Feed & discovery
| File | Role |
|------|------|
| `controllers/Profile.php` | feed(), feed_more(), profile_more() (AJAX endpoints) |
| `views/feed.php` | Infinite scroll + Bootstrap modal |
| `views/profile.php` | Infinite scroll + Bootstrap modal |
| `views/partials/feed_cards.php` | HTML fragment returned by `feed_more` |
| `views/partials/profile_cards.php` | HTML fragment returned by `profile_more` |

### Notifications
| File | Role |
|------|------|
| `core/MY_Controller.php` | Loads unread count, injects into all views |
| `controllers/Notifications.php` | List + mark all read |
| `models/Notification_model.php` | create(), list_for_user(), mark_all_read() |
| `views/notifications.php` | Notification list page |
| `views/partials/header.php` | Bell icon with badge |

### Search
| File | Role |
|------|------|
| `controllers/Search.php` | result(), hashtag(), dynamicResult() |
| `models/User_model.php` | search_users($query, $exclude_id) |
| `models/Post_model.php` | search_posts($query), get_posts_by_hashtag($tag) |
| `views/search_result.php` | People + posts sections |
| `views/hashtag_posts.php` | Grid of posts for one hashtag |

---

## Key Design Patterns

### Shared header injection
`MY_Controller::__construct()` loads `Notification_model`, counts unread for the session user, and calls `$this->load->vars(['notif_unread_count' => $n])`. Any view that calls `$this->load->view('partials/header')` automatically gets the correct count.

### CSRF-safe AJAX
```javascript
csrfPost(url, payload, successCallback, errorCallback);
// csrfPost() reads ci_csrf_token from the page cookie and adds it to payload.
```

### Dual-icon stateful buttons (like / bookmark)
Two `<i>` elements sit inside the button. CSS shows/hides them based on `.liked` / `.saved` classes on the parent button. JS only needs to toggle those classes — no icon class manipulation.

```css
.heart-empty  { display: inline-block; }
.heart-filled { display: none; }
.icon-btn.liked .heart-empty  { display: none; }
.icon-btn.liked .heart-filled { display: inline-block; }
```

### Infinite scroll
A sentinel `<div id="*-sentinel">` sits at the bottom of the post grid. An Intersection Observer fires when it enters the viewport, calls `csrfPost(endpoint + '/' + page, {}, ...)`, and appends the returned HTML. Observer disconnects when the server returns an empty fragment.

### Post modal
`post/modal_content/{id}` renders `partials/post_card.php` to a string via `$this->load->view(..., TRUE)` and returns JSON. JS injects `res.html` into `#modal-post-body`.

---

## Request Flows

### Login
```
login.php  →(AJAX)→  Login::process()  →  User_model::login()
                                        →  session regenerate
                                        →  redirect to feed
```

### Create post
```
create_post.php  →(POST)→  Profile::save_post()
                         →  sanitize_input()
                         →  GD resize
                         →  Post_model::insert()
                         →  redirect to profile
```

### Like a post
```
icon-btn click  →  toggleLike(postId)
               →  csrfPost('post/toggle_like', {post_id})
               →  Post_model::toggle_like()
               →  Notification_model::create()  (if liked)
               →  JSON {is_liked, count}
               →  $btn.toggleClass('liked', is_liked)
```

### Infinite scroll (feed)
```
Intersection Observer fires
  →  csrfPost('profile/feed_more/' + page, {})
  →  Profile::feed_more()  →  Post_model::get_feed_posts($uid, $page)
  →  load_view('partials/feed_cards', data, TRUE)
  →  JSON {status, html}
  →  append html to #feed-grid
```

---

## Statistics

| Area | Count |
|------|-------|
| Controllers | 11 |
| Models | 4 |
| Views (main) | 18 |
| View partials | 4 |
| Helpers | 1 |
| Core extensions | 1 |
| JS assets | 1 |
| CSS assets | 1 |
| DB tables | 7 |

---

**Last updated:** June 2026
