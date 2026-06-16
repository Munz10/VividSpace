<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Post - VividSpace</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/app.css'); ?>">
    <style>
        .container { padding: 1rem; }
        .form-container {
            max-width: 600px;
            margin: auto;
            padding: 2rem;
            background-color: #fff;
            border-radius: 0.5rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.1);
        }
        .post-preview {
            width: 100%;
            max-height: 400px;
            object-fit: cover;
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
        }
    </style>
</head>
<body>
<div class="container">
    <?php $this->load->view('partials/header'); ?>
    <div class="form-container my-4">
        <h4>Edit Post</h4>

        <img src="<?= base_url(ltrim($post['image_path'], '/')); ?>" alt="Post image" class="post-preview">

        <form action="<?= site_url('post/update'); ?>" method="post">
            <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
            <input type="hidden" name="id" value="<?= (int) $post['id']; ?>">

            <div class="form-group">
                <label for="caption">Caption</label>
                <textarea class="form-control" id="caption" name="caption" rows="3"><?= htmlspecialchars($post['caption'] ?? ''); ?></textarea>
            </div>
            <div class="form-group">
                <label for="hashtags">Hashtags</label>
                <input type="text" class="form-control" id="hashtags" name="hashtags"
                       value="<?= htmlspecialchars($post['hashtags'] ?? ''); ?>"
                       placeholder="#nature #travel">
            </div>
            <div class="d-flex" style="gap:10px;">
                <button type="submit" class="btn btn-primary">Save</button>
                <a href="<?= site_url('post/detail/' . (int) $post['id']); ?>" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
</body>
</html>
