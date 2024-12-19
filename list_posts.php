<?php
require 'config.php';

// Fetch active posts
$sql = "SELECT * FROM posts WHERE posts.status != 'trashed'";
$stmt = $pdo->query($sql);
$posts = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>List Posts</title>
    <!-- Include Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">Posts</h2>

    <!-- Display success or error messages -->
    <?php if (isset($_GET['message'])): ?>
        <div class="alert alert-success">
            <?php echo htmlspecialchars($_GET['message']); ?>
        </div>
    <?php endif; ?>
    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger">
            <?php echo htmlspecialchars($_GET['error']); ?>
        </div>
    <?php endif; ?>

    <!-- Add navigation to Trash page -->
    <a href="trash.php" class="btn btn-warning mb-3">View Trash</a>

    <!-- List active posts -->
    <?php if (!empty($posts)): ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Content</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($posts as $post): ?>
                <tr>
                    <td><?php echo htmlspecialchars($post['title']); ?></td>
                    <td><?php echo substr(htmlspecialchars($post['content']), 0, 50) . '...'; ?></td>
                    <td><?php echo date('F j, Y', strtotime($post['post_date'])); ?></td>
                    <td>
                        <!-- Move to Trash button -->
                        <a href="delete_post.php?id=<?php echo $post['id']; ?>" 
                           class="btn btn-danger btn-sm" 
                           onclick="return confirm('Are you sure you want to move this post to Trash?');">
                            Move to Trash
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="alert alert-info">No posts available.</p>
    <?php endif; ?>
</div>

<!-- Include Bootstrap JS -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>