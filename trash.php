<?php
require 'config.php';

// Fetch trashed posts
$sql = "SELECT * FROM posts WHERE deleted_at IS NOT NULL ORDER BY deleted_at DESC";
$stmt = $pdo->query($sql);
$trashedPosts = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Trash</title>
    <!-- Include Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">Trash</h2>

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

    <?php if (!empty($trashedPosts)): ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Deleted On</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($trashedPosts as $post): ?>
                <tr>
                    <td><?php echo htmlspecialchars($post['title']); ?></td>
                    <td><?php echo date('F j, Y, g:i a', strtotime($post['deleted_at'])); ?></td>
                    <td>
                        <!-- Restore and Permanent Delete Buttons -->
                        <a href="restore_post.php?id=<?php echo $post['id']; ?>" class="btn btn-success btn-sm">
                            Restore
                        </a>
                        <a href="permanent_delete_post.php?id=<?php echo $post['id']; ?>" 
                           class="btn btn-danger btn-sm" 
                           onclick="return confirm('Are you sure you want to permanently delete this post?');">
                            Delete Permanently
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="alert alert-info">No posts in Trash.</p>
    <?php endif; ?>
</div>

<!-- Include Bootstrap JS -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>