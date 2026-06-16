<?php
/**
 * Shared page header.
 *
 * Optional variables a controller / view can pass in via $this->load->view():
 *   $show_search   bool  Render the user search field on the right (default true)
 *   $show_create   bool  Render the Create button next to Home (default false)
 *   $home_url      string  Override the Home link (default profile/feed)
 *   $actions       string  Raw HTML appended to the right side, before the
 *                          profile icon. Use for page-specific buttons such
 *                          as "Edit profile" / "Log out" on the own profile.
 */

$show_search = isset($show_search) ? (bool) $show_search : false;
$show_create = isset($show_create) ? (bool) $show_create : false;
$home_url    = isset($home_url) && $home_url ? $home_url : site_url('profile/feed');
$actions     = isset($actions) ? $actions : '';
?>
<div class="header-section">
    <div class="logo-and-home">
        <h1>VividSpace</h1>
        <a href="<?= $home_url; ?>" class="btn btn-primary ml-2">Home</a>
        <?php if ($show_create): ?>
            <a href="<?= site_url('profile/create_post'); ?>" class="btn btn-outline-primary ml-2">Create</a>
        <?php endif; ?>
    </div>

    <?php if ($show_search): ?>
        <div class="header-search">
            <form action="<?= site_url('search/result'); ?>" method="get" class="d-flex" autocomplete="off">
                <input type="search" id="header-search-input" class="form-control flex-grow-1" name="query" placeholder="Search users..." required>
                <button type="submit" class="btn btn-outline-secondary ml-2">Go</button>
            </form>
            <div id="header-search-results" class="header-search-results"></div>
        </div>
    <?php endif; ?>

    <div class="d-flex align-items-center" style="gap:14px;">
        <?= $actions ?>
        <a href="<?= site_url('notifications'); ?>" class="notif-bell" title="Notifications">
            &#128276;
            <?php $notif_unread_count = isset($notif_unread_count) ? (int) $notif_unread_count : 0; ?>
            <?php if ($notif_unread_count > 0): ?>
                <span class="badge"><?= $notif_unread_count > 99 ? '99+' : $notif_unread_count; ?></span>
            <?php endif; ?>
        </a>
        <a href="<?= site_url('profile'); ?>" title="My profile">
            <img src="<?= base_url('Images/user_icon.jpg'); ?>" class="profile-icon" alt="Profile">
        </a>
    </div>
</div>
