<?php foreach ($posts as $post): ?>
<div class="col-md-4 mb-3 feed-card-col" data-post-id="<?= (int) $post['id']; ?>">
    <div class="card" style="cursor:pointer;" onclick="openPostModal(<?= (int) $post['id']; ?>)">
        <img src="<?= base_url(ltrim($post['image_path'], '/')); ?>"
             class="card-img-top" alt="Post Image" loading="lazy">
        <div class="card-body">
            <h5 class="card-title"><?= htmlspecialchars($post['caption']); ?></h5>
            <p class="card-text">
                <small class="text-muted">Posted by <?= htmlspecialchars($post['author_username']); ?></small>
            </p>
            <div class="card-meta">
                <span><i class="bi bi-heart-fill" style="color:#e0245e;font-size:.9rem;"></i> <?= (int) $post['likes_count']; ?></span>
                <span><i class="bi bi-chat"></i> <?= (int) $post['comments_count']; ?></span>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>
