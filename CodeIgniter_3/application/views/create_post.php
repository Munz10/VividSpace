<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Post - VividSpace</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f8f9fa;
        }
        .container {
            justify-content: center;
            align-items: center;
            height: 100vh;
            padding: 1rem;
        }
        h1 {
            font-family: 'Montserrat', sans-serif; 
            font-weight: bold; 
            color: #333333; 
            text-align: center;
            margin-bottom: 30px; 
        }
        .form-container {
            max-width: 600px;
            margin: auto;
            padding: 2rem;
            background-color: #fff;
            border-radius: 0.5rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.1);
        }
        .header-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
        }
        .logo-and-home {
            display: flex;
            align-items: center;
        }
        .logo-and-home h1 {
            margin-right: 10px;
            margin-bottom: 0;
        }
        .profile-icon {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #333;
            display: inline-block;
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
        .btn-primary {
            padding: 0.375rem 0.75rem;
            font-size: 1rem;
            line-height: 1.5;
            border-radius: 0.25rem;
            margin-left: 0.5rem;
        }
        .search-bar input {
            margin-left: 1rem;
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
        <div class="col-6 col-md-8 d-flex justify-content-end">
            <a href="<?= site_url('profile'); ?>">
                <img src="<?= base_url('Images/user_icon.jpg'); ?>" class="profile-icon" alt="Profile">
            </a>
        </div>
    </div>
    <div class="form-container my-4">
        <h4> Create new post </h4>
        <form action="<?= site_url('profile/save_post'); ?>" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="postImage" class="custom-file-upload">Choose file</label>
                <input type="file" id="postImage" name="post_image" hidden onchange="previewImage(); updateFilename(this.value);">
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
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
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
    };
</script>
</body>
</html>
