<?php
require 'config.php';

// Fetch post information based on the ID provided in the URL
$post_id = $_GET['id'];
$sql = "SELECT * FROM posts WHERE id = :post_id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['post_id' => $post_id]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

// Fetch images related to the post
$sql = "SELECT * FROM post_images WHERE post_id = :post_id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['post_id' => $post_id]);
$images = $stmt->fetchAll(PDO::FETCH_ASSOC);

// If the form is submitted for updating the post
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $main_image = $_POST['main_image'];

    // Update the post data
    $updateSql = "UPDATE posts SET title = :title, content = :content, main_image = :main_image WHERE id = :post_id";
    $updateStmt = $pdo->prepare($updateSql);
    $updateStmt->execute([
        'title' => $title,
        'content' => $content,
        'main_image' => $main_image,
        'post_id' => $post_id
    ]);

    // Redirect back to the listing page
    header("Location: list_posts.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Post</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .image-selection img {
            cursor: pointer;
            margin: 5px;
            border: 2px solid transparent;
            transition: border-color 0.3s;
        }
        .image-selection img.selected {
            border-color: #007bff; /* Highlight color */
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Edit Post</h1>

        <!-- Post Editing Form -->
        <form method="post">
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($post['title']); ?>" required>
            </div>

            <div class="form-group">
                <label for="content">Content</label>
                <textarea name="content" class="form-control" required><?php echo htmlspecialchars($post['content']); ?></textarea>
            </div>

            <!-- Hidden input to store the selected main image path -->
            <input type="hidden" name="main_image" id="main_image" value="<?php echo htmlspecialchars($post['main_image']); ?>">

            <!-- Images Display for management -->
            <div class="form-group">
                <label>Click on an image to set it as the main image</label>
                <div class="image-selection">
                    <?php foreach ($images as $image): ?>
                        <img src="<?php echo htmlspecialchars($image['image_path']); ?>" 
                             class="img-thumbnail <?php echo ($post['main_image'] === $image['image_path']) ? 'selected' : ''; ?>" 
                             width="150" 
                             data-image-path="<?php echo htmlspecialchars($image['image_path']); ?>">
                    <?php endforeach; ?>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Save Changes</button>
            <a href="list_posts.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>

    <script>
        // JavaScript to handle image selection
        document.addEventListener('DOMContentLoaded', function() {
            const images = document.querySelectorAll('.image-selection img');
            const mainImageInput = document.getElementById('main_image');

            images.forEach(image => {
                image.addEventListener('click', function() {
                    // Remove 'selected' class from all images
                    images.forEach(img => img.classList.remove('selected'));

                    // Add 'selected' class to the clicked image
                    this.classList.add('selected');

                    // Update the hidden input with the selected image path
                    mainImageInput.value = this.getAttribute('data-image-path');
                });
            });
        });
    </script>
</body>
</html>
