<?php
require 'config.php';

if (isset($_GET['id'])) {
    $postId = $_GET['id'];
    $sql = "DELETE FROM posts WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute([$postId])) {
        // Redirect with success message
        header("Location: trash.php?message=Post permanently deleted.");
    } else {
        // Redirect with error message
        header("Location: trash.php?error=Failed to delete post permanently.");
    }
} else {
    // Redirect if no ID is provided
    header("Location: trash.php?error=No post ID provided.");
}
?>
