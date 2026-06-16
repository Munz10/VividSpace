<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Post - VividSpace</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/app.css'); ?>">
    <style>
        .container {
            justify-content: center;
            align-items: center;
            height: 100vh;
            padding: 1rem;
        }
        .form-container {
            max-width: 600px;
            margin: auto;
            padding: 2rem;
            background-color: #fff;
            border-radius: 0.5rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.1);
        }
        .custom-file-upload {
            border: 1px solid #ccc;
            display: inline-block;
            padding: 6px 12px;
            cursor: pointer;
        }
        #image-preview {
            display: none;
            width: 50%;
            height: 500px;
            margin-top: 1rem;
            object-fit: contain;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header-section">
        <div class="logo-and-home">
            <h1>VividSpace</h1>
            <a href="<?= site_url('profile/feed'); ?>" class="btn btn-primary ml-2">Home</a>
        </div>
        <a href="<?= site_url('profile'); ?>">
            <img src="<?= base_url('Images/user_icon.jpg'); ?>" class="profile-icon" alt="Profile">
        </a>
    </div>
    <div class="form-container my-4">
        <h4> Create new post </h4>
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error); ?></div>
        <?php elseif ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($this->session->flashdata('error')); ?></div>
        <?php endif; ?>
        <form action="<?= site_url('profile/save_post'); ?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
            <div class="form-group">
                <label for="postImage" class="custom-file-upload">Choose file</label>
                <input type="file" id="postImage" name="post_image" accept="image/jpeg,image/png,image/gif" hidden onchange="previewImage(); updateFilename(this.value);">
                <span id="file-chosen">No file chosen</span>
            </div>
            <div class="form-group">
                <img id="image-preview" src="#" alt="Image preview" />
            </div>
            <div class="form-group">
                <input type="text" class="form-control" id="caption" name="caption" placeholder="Add a caption">
            </div>
            <div class="form-group">
                <input type="text" class="form-control" id="hashtags" name="hashtags" placeholder="Add hashtags">
            </div>
            <button type="submit" class="btn btn-primary">Post</button>
        </form>
    </div>
</div>
<script>
    function updateFilename(value) {
        if (value) {
            document.getElementById('file-chosen').textContent = value.split('\\').pop();
        }
    }
    function previewImage() {
        var oFReader = new FileReader();
        oFReader.readAsDataURL(document.getElementById("postImage").files[0]);
        oFReader.onload = function (oFREvent) {
            document.getElementById("image-preview").style.display = "block";
            document.getElementById("image-preview").src = oFREvent.target.result;
        };
    }
</script>
</body>
</html>
