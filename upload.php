<?php
require 'config.php'; // Include the PDO configuration file

// Directory to store images
$targetDir = "uploads/";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Use PDO's prepared statements for safer data handling
    $title = $_POST["title"];
    $content = $_POST["content"];
    $mainImageFileName = $_POST["main_image"]; // Get the main image file name selected in the form

    try {
        // Insert the post details into the posts table
        $stmt = $pdo->prepare("INSERT INTO posts (title, content) VALUES (:title, :content)");
        $stmt->execute(['title' => $title, 'content' => $content]);
        $postId = $pdo->lastInsertId(); // Get the ID of the newly created post

        // Check if files were uploaded without errors
        if (isset($_FILES["images"]) && count($_FILES["images"]["name"]) > 0) {
            $fileCount = count($_FILES["images"]["name"]);
            $mainImagePath = "";

            for ($i = 0; $i < $fileCount; $i++) {
                $fileName = basename($_FILES["images"]["name"][$i]);
                $targetFilePath = $targetDir . $fileName;
                $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

                // Allow certain file formats
                $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
                if (in_array(strtolower($fileType), $allowedTypes)) {
                    // Move file to target directory
                    if (move_uploaded_file($_FILES["images"]["tmp_name"][$i], $targetFilePath)) {
                        // Check if this image is the selected main image
                        if ($fileName === $mainImageFileName) {
                            $mainImagePath = $targetFilePath; // Store the full path
                        }

                        // Insert image path into post_images table
                        $stmtImage = $pdo->prepare("INSERT INTO post_images (post_id, image_path) VALUES (:post_id, :image_path)");
                        $stmtImage->execute(['post_id' => $postId, 'image_path' => $targetFilePath]);
                    }
                }
            }

            // Update the main image path in the posts table
            if (!empty($mainImagePath)) {
                $stmtUpdateMainImage = $pdo->prepare("UPDATE posts SET main_image = :main_image WHERE id = :id");
                $stmtUpdateMainImage->execute(['main_image' => $mainImagePath, 'id' => $postId]);
            }
        }

        echo "<div class='container mt-5'><div class='alert alert-success'>Blog post created successfully!</div></div>";
    } catch (PDOException $e) {
        // Handle any database errors
        echo "<div class='container mt-5'><div class='alert alert-danger'>Database error: " . $e->getMessage() . "</div></div>";
    }
}
?>
