<?php
require 'config.php';

$post_id = $_GET['id'];

// Delete related images first
$sql = "DELETE FROM post_images WHERE post_id = :post_id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['post_id' => $post_id]);

// Delete the post
$sql = "DELETE FROM posts WHERE id = :post_id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['post_id' => $post_id]);

// Redirect back to the listing page
header("Location: list_posts.php");
exit;
?>
