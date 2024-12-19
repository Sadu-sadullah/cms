<?php
require 'config.php';

if (isset($_GET['id'])) {
    $postId = $_GET['id'];
    $sql = "UPDATE posts SET deleted_at = NOW() WHERE id = ?";
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
