<?php
/**
 * Reusable post card for the post-detail modal and the standalone detail page.
 * Expects: $post, $comments, $is_liked, $is_saved (all set by the caller).
 */
$post_id = (int) $post['id'];
?>
<div class="post-card-inner">
    <img src="<?= esc_url(base_url(ltrim($post['image_path'], '/'))); ?>"
         alt="Post image" class="post-card-img" loading="lazy">
    <div class="post-card-body">
        <h6 class="mb-1">
            <a href="<?= site_url('profile/view/' . urlencode($post['username'])); ?>">
                @<?= esc($post['username']); ?>
            </a>
        </h6>
        <p class="mb-1"><?= esc($post['caption']); ?></p>
        <?php if (!empty($post['hashtags'])): ?>
            <p class="hashtags mb-1">
                <?php
                $tags = preg_split('/[\s,]+/', $post['hashtags'], -1, PREG_SPLIT_NO_EMPTY);
                foreach ($tags as $tag):
                    $clean = ltrim($tag, '#');
                ?>
                    <a href="<?= site_url('search/hashtag/' . urlencode($clean)); ?>" class="hashtag-link">#<?= esc($clean); ?></a>
                <?php endforeach; ?>
            </p>
        <?php endif; ?>
        <small class="text-muted"><?= esc($post['created_at']); ?></small>

        <div class="interaction-bar mt-2">
            <span id="like-btn-<?= $post_id; ?>" class="icon-btn <?= !empty($is_liked) ? 'liked' : ''; ?>" onclick="toggleLike(<?= $post_id; ?>);" title="Like">
                <i class="bi bi-heart heart-empty"></i>
                <i class="bi bi-heart-fill heart-filled"></i>
                <span class="count" id="like-count-<?= $post_id; ?>"><?= (int) $post['likes_count']; ?></span>
            </span>
            <span id="bm-btn-<?= $post_id; ?>" class="icon-btn <?= !empty($is_saved) ? 'saved' : ''; ?>"
                  onclick="toggleBookmark(<?= $post_id; ?>);" title="Save">
                <i class="bi bi-bookmark bm-empty"></i>
                <i class="bi bi-bookmark-fill bm-filled"></i>
            </span>
            <?php if ($this->session->userdata('user_id') == $post['user_id']): ?>
                <a href="<?= site_url('post/edit/' . $post_id); ?>" class="btn btn-outline-secondary btn-sm">Edit</a>
                <form method="post" action="<?= site_url('profile/delete_post/' . $post_id); ?>"
                      style="margin:0;" onsubmit="return confirm('Delete this post?');">
                    <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>"
                           value="<?= $this->security->get_csrf_hash(); ?>">
                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                </form>
            <?php endif; ?>
        </div>
        <div id="like-error-<?= $post_id; ?>" class="ajax-error"></div>

        <div id="comments-container-<?= $post_id; ?>" class="mt-2">
            <?php foreach ($comments as $c): ?>
                <div class="comment-item">
                    <strong>@<?= htmlspecialchars($c['username']); ?></strong>
                    <?= linkify_mentions(htmlspecialchars($c['content'])); ?>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="mt-2">
            <form onsubmit="event.preventDefault(); addComment(<?= $post_id; ?>);">
                <div class="input-group">
                    <input type="text" id="comment-content-<?= $post_id; ?>"
                           class="form-control form-control-sm" placeholder="Write a comment…">
                    <div class="input-group-append">
                        <button type="submit" id="comment-submit-<?= $post_id; ?>"
                                class="btn btn-primary btn-sm">Post</button>
                    </div>
                </div>
                <div id="comment-error-<?= $post_id; ?>" class="ajax-error"></div>
            </form>
        </div>
    </div>
</div>
