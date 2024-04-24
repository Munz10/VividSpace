<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Post - VividSpace</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body .header-container{
            background-color: #f8f9fa;
        }
        .navbar {
            background: #fff;
            padding: 1rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }
        .container {
            background: #fff;
            padding: 2rem;
            border-radius: 0.5rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }
        .form-group label {
            margin-top: 1rem;
        }
        .btn-primary {
            width: 100%;
            padding: 0.5rem;
            margin-top: 1rem;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .search-bar input {
            margin-left: 1rem;
        }
        .profile-icon {
            width: 50px;
            height: 50px;
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
            display: none; /* Hide the image preview initially */
            width: 100%;   /* Set the width to 100% of the parent */
            margin-top: 1rem; /* Add some spacing */
        }

        /* Replace with actual image */
    </style>
</head>
<body>
    <!-- Header -->
    <div class="container header-container">
        <div class="row align-items-center my-4">
                <div class="col-6 col-md-8 d-flex align-items-center">
                    <!-- Logo -->
                    <h1>VividSpace</h1>
                    <!-- Home button -->
                    <a href="<?= site_url('profile/feed'); ?>" class="btn btn-primary ml-2">Home</a>
                </div>
                <div class="col-6 col-md-4 d-flex justify-content-end">
                    <!-- User Icon -->
                    <a href="<?= site_url('profile'); ?>">
                        <img src="<?= base_url('Images/user_icon.jpg'); ?>" class="profile-icon" alt="Profile">
                    </a>
                </div>
        </div>
    </div>
    <div class="container">
        <!-- Create Post Form -->
        <form action="<?= site_url('profile/save_post'); ?>" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="postImage" class="custom-file-upload">Choose file</label>
                <!-- <input type="file" id="postImage" name="post_image" hidden onchange="updateFilename(this.value)"> -->
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

    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function updateFilename(value) {
            // This function is triggered when the file input changes
            if (value) {
                document.getElementById('file-chosen').textContent = value.split('\\').pop(); // Get the file name
            }
        }
    </script>
    <script>
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
