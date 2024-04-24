<?php if (isset($comments) && is_array($comments) && count($comments) > 0): ?>
    <div class="comments-list">
        <?php foreach ($comments as $comment): ?>
            <div class="comment">
                <p class="comment-author">
                    <strong>@<?= isset($comment['username']) ? htmlspecialchars($comment['username']) : 'Unknown'; ?></strong>
                    <span class="comment-date"><?= isset($comment['created_at']) ? htmlspecialchars($comment['created_at']) : 'Unknown date'; ?></span>
                </p>
                <p class="comment-content"><?= nl2br(htmlspecialchars($comment['content'])); ?></p>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <p>No comments yet.</p>
<?php endif; ?>
