<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Search Users</title>
  </head>
<body>
  <h1>Search Users</h1>
  <form action="<?= base_url('search/result') ?>" method="get">
    <label for="query">Search:</label>
    <input type="text" name="query" id="query" placeholder="Username or Name">
    <button type="submit">Search</button>
  </form>
</body>
</html>
