<?php
require 'config.php'; // Include database connection

if (isset($_GET['id'])) {
    $postId = intval($_GET['id']); // Get the post ID from the URL

    try {
        // Step 1: Fetch all associated image paths
        $sqlFetchImages = "SELECT image_path FROM post_images WHERE post_id = :post_id";
        $stmtFetchImages = $pdo->prepare($sqlFetchImages);
        $stmtFetchImages->execute(['post_id' => $postId]);
        $images = $stmtFetchImages->fetchAll(PDO::FETCH_ASSOC);

        // Step 2: Delete the image files from the 'uploads' folder
        foreach ($images as $image) {
            $filePath = $image['image_path'];
            if (file_exists($filePath)) {
                unlink($filePath); // Delete the file
            }
        }

        // Step 3: Delete records from the post_images table
        $sqlDeleteImages = "DELETE FROM post_images WHERE post_id = :post_id";
        $stmtDeleteImages = $pdo->prepare($sqlDeleteImages);
        $stmtDeleteImages->execute(['post_id' => $postId]);

        // Step 4: Fetch the main image path from the posts table
        $sqlFetchMainImage = "SELECT main_image FROM posts WHERE id = :id";
        $stmtFetchMainImage = $pdo->prepare($sqlFetchMainImage);
        $stmtFetchMainImage->execute(['id' => $postId]);
        $mainImage = $stmtFetchMainImage->fetchColumn();

        // Step 5: Delete the main image file
        if ($mainImage && file_exists($mainImage)) {
            unlink($mainImage);
        }

        // Step 6: Delete the post record from the posts table
        $sqlDeletePost = "DELETE FROM posts WHERE id = :id";
        $stmtDeletePost = $pdo->prepare($sqlDeletePost);
        $stmtDeletePost->execute(['id' => $postId]);

        header("Location: list_posts.php?status=deleted"); // Redirect with success status
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    header("Location: list_posts.php?status=error"); // Redirect with error status
    exit();
}
?>
