<?php
require 'config.php';

if (isset($_GET['id'])) {
    $postId = $_GET['id'];
    $sql = "UPDATE posts SET status = 'trashed' WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $postId]);

    // Update associated images to 'trashed'
    $sqlImages = "UPDATE post_images SET status = 'trashed' WHERE post_id = :id";
    $stmtImages = $pdo->prepare($sqlImages);
    $stmtImages->execute(['id' => $postId]);
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute([$postId])) {
        // Redirect with success message
        header("Location: list_posts.php?message=Post moved to Trash successfully.");
    } else {
        // Redirect with error message
        header("Location: list_posts.php?error=Failed to move post to Trash.");
    }
} else {
    // Redirect if no ID is provided
    header("Location: list_posts.php?error=No post ID provided.");
}
?>
