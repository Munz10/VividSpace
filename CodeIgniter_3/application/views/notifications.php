<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Notifications - VividSpace</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/app.css'); ?>">
    <style>
        .container { padding: 1rem; }
        .notif-list { max-width: 640px; margin: 1.5rem auto 0; }
        .notif-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0.75rem 1rem;
            border-bottom: 1px solid #e9ecef;
            background: #fff;
        }
        .notif-item.unread { background: #f0f4ff; }
        .notif-icon { font-size: 1.4rem; width: 32px; text-align: center; }
        .notif-text { flex: 1; font-size: 0.95rem; }
        .notif-time { font-size: 0.8rem; color: #888; white-space: nowrap; }
        .notif-empty { text-align: center; color: #888; padding: 3rem 0; }
    </style>
</head>
<body>
<div class="container">
    <?php $this->load->view('partials/header'); ?>

    <div class="notif-list">
        <h5 class="mb-3">Notifications</h5>

        <?php if (empty($notifications)): ?>
            <div class="notif-empty">No notifications yet.</div>
        <?php else: ?>
            <?php foreach ($notifications as $n): ?>
                <?php
                    switch ($n['type']) {
                        case 'follow':
                            $icon = '&#128100;';
                            $text = '<strong>' . htmlspecialchars($n['actor_username']) . '</strong> started following you.';
                            $href = site_url('profile/view/' . urlencode($n['actor_username']));
                            break;
                        case 'like':
                            $icon = '&#9829;';
                            $text = '<strong>' . htmlspecialchars($n['actor_username']) . '</strong> liked your post.';
                            $href = $n['entity_id'] ? site_url('post/detail/' . (int) $n['entity_id']) : '#';
                            break;
                        case 'comment':
                            $icon = '&#128172;';
                            $text = '<strong>' . htmlspecialchars($n['actor_username']) . '</strong> commented on your post.';
                            $href = $n['entity_id'] ? site_url('post/detail/' . (int) $n['entity_id']) : '#';
                            break;
                        default:
                            $icon = '&#8226;';
                            $text = 'New notification.';
                            $href = '#';
                    }
                    $created = new DateTime($n['created_at']);
                    $now     = new DateTime();
                    $diff    = $now->diff($created);
                    if ($diff->days > 0) {
                        $time_label = $diff->days . 'd ago';
                    } elseif ($diff->h > 0) {
                        $time_label = $diff->h . 'h ago';
                    } elseif ($diff->i > 0) {
                        $time_label = $diff->i . 'm ago';
                    } else {
                        $time_label = 'just now';
                    }
                ?>
                <a href="<?= $href; ?>" class="notif-item <?= $n['is_read'] ? '' : 'unread'; ?>" style="text-decoration:none;color:inherit;">
                    <span class="notif-icon"><?= $icon; ?></span>
                    <span class="notif-text"><?= $text; ?></span>
                    <span class="notif-time"><?= $time_label; ?></span>
                </a>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
