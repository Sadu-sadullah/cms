<?php
require 'config.php';

// Fetch all posts from the database
$sql = "SELECT * FROM posts";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Posts</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Manage Posts</h1>

        <!-- Display posts in a table format -->
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Content</th>
                    <th>Main Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($posts): ?>
                    <?php foreach ($posts as $post): ?>
                        <tr>
                            <td><?php echo $post['id']; ?></td>
                            <td><?php echo htmlspecialchars($post['title']); ?></td>
                            <td><?php echo htmlspecialchars(substr($post['content'], 0, 100)); ?>...</td>
                            <td>
                                <?php if ($post['main_image']): ?>
                                    <img src="<?php echo htmlspecialchars($post['main_image']); ?>" width="100" height="60" alt="Main Image">
                                <?php else: ?>
                                    No Image
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="edit_post.php?id=<?php echo $post['id']; ?>" class="btn btn-primary btn-sm">Edit</a>
                                <a href="delete_post.php?id=<?php echo $post['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this post?');">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">No posts found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>