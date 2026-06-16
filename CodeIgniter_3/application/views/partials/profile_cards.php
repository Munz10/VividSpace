<?php foreach ($posts as $post): ?>
<div class="col-md-4 mb-3" data-post-id="<?= (int) $post['id']; ?>">
    <div class="post">
        <a onclick="openPostModal(<?= (int) $post['id']; ?>); return false;" href="<?= site_url('post/detail/' . $post['id']); ?>">
            <img src="<?= base_url(ltrim($post['image_path'], '/')); ?>" alt="Post Image" loading="lazy">
        </a>
        <div class="post-body">
            <p><?= htmlspecialchars($post['caption']); ?></p>
            <div class="card-meta">
                <span><span class="heart">&#9829;</span> <?= (int) $post['likes_count']; ?></span>
                <span><i class="bi bi-chat"></i> <?= (int) $post['comments_count']; ?></span>
                <a href="<?= site_url('post/edit/' . $post['id']); ?>" class="ml-auto text-secondary" style="font-size:0.85rem;">Edit</a>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>
