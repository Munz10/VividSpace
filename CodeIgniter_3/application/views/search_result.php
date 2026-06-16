<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Results - VividSpace</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/app.css'); ?>">
    <style>
        .container { padding: 1rem; }
        .search-result {
            margin: 15px 10px;
            padding: 10px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .search-results {
            margin-top: 30px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }
    </style>
</head>
<body>
<div class="container">
    <?php $this->load->view('partials/header', ['show_search' => true]); ?>

    <div class="search-results mx-auto"> <!-- Added mx-auto class for horizontal centering -->
        <h3>Search Results</h3>
        <?php if (empty($results)): ?>
            <p>No users found.</p>
        <?php else: ?>
            <?php foreach($results as $user): ?>
                <div class="search-result">
                    <a href="<?= site_url('profile/view/' . $user['username']); ?>">
                        <?= htmlspecialchars($user['username']); ?>
                    </a>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
