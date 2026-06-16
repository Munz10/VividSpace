###########
VividSpace
###########

A photography-focused social media platform built on CodeIgniter 3.
Share photos, follow creators, discover moments.

Full documentation: see ``../README.md`` in the project root.

***************
Quick Start
***************

1. Create database ``vivid_space_db`` in phpMyAdmin and import the schema
   (full SQL in ``README.md``).
2. Confirm base URL in ``application/config/config.php``.
3. Open ``http://localhost/VividSpace/VividSpace/CodeIgniter_3/``.

***************
Features
***************

- Photo upload with caption and hashtags
- Personalized feed with infinite scroll
- Like, comment, bookmark posts
- Follow / unfollow users
- Real-time user search
- Notifications (likes, comments, follows)
- Hashtag discovery pages
- Saved posts collection
- Edit / delete account

***************
Tech Stack
***************

- **Backend**: CodeIgniter 3, PHP 5.6+
- **Database**: MySQL
- **Frontend**: Bootstrap 4.5.2, Bootstrap Icons 1.11.3, jQuery 3.6.0
- **Security**: CSRF tokens, XSS helper, BCrypt, session fixation fix

***************
License
***************

See ``license.txt``.

***************
Acknowledgements
***************

Built on the `CodeIgniter 3 <https://codeigniter.com>`_ framework.
Security implementations follow OWASP guidelines.
