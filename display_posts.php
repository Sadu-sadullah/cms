<?php
require 'config.php';

// Fetch posts and associated images from the database
$sql = "SELECT posts.id, posts.title, posts.content, posts.post_date, posts.main_image, post_images.image_path 
FROM posts 
LEFT JOIN post_images ON posts.id = post_images.post_id 
WHERE posts.status != 'trashed' 
ORDER BY posts.post_date DESC;
";
$stmt = $pdo->query($sql);

$posts = [];
while ($row = $stmt->fetch()) {
    $postId = $row['id'];

    // Set the main image for the post
    $posts[$postId]['title'] = $row['title'];
    $posts[$postId]['content'] = $row['content'];
    $posts[$postId]['post_date'] = $row['post_date'];
    $posts[$postId]['main_image'] = $row['main_image'];

    // Add other images except the main image to the gallery
    if ($row['image_path'] !== $row['main_image']) {
        $posts[$postId]['images'][] = $row['image_path'];
    }
}

// Check if the status parameter is set in the URL
$status = isset($_GET['status']) ? $_GET['status'] : '';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Products</title>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <!-- Lightbox CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css" rel="stylesheet">

    <style>
        /* Style for the image overlay indicating it's clickable */
        .image-container {
            position: relative;
            cursor: pointer;
        }

        .image-container:hover .overlay {
            opacity: 1;
        }

        .overlay {
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(0, 0, 0, 0.5);
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            opacity: 0;
            transition: opacity 0.3s;
            pointer-events: none;
        }

        .gallery-indicator {
            position: absolute;
            bottom: 10px;
            right: 10px;
            background: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 12px;
        }
    </style>
</head>

<body class="bg-light">
    <div class="container mt-5">
        <h2 class="mb-4">Our Products</h2>
        <div class="row">
            <?php
            if (!empty($posts)) {
                foreach ($posts as $postId => $post) {
                    ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 shadow-sm">
                            <?php if (!empty($post['main_image'])) { ?>
                                <!-- Display the main image separately -->
                                <div class="image-container">
                                    <a href="<?php echo htmlspecialchars($post['main_image']); ?>"
                                        data-lightbox="post-<?php echo $postId; ?>"
                                        data-title="<?php echo htmlspecialchars($post['title']); ?>"
                                        class="position-relative d-block">

                                        <!-- Main Image to be displayed -->
                                        <img src="<?php echo htmlspecialchars($post['main_image']); ?>" class="card-img-top mb-2"
                                            alt="Main Post Image">

                                        <!-- Show overlay only on the main image -->
                                        <div class="overlay">
                                            <i class="fas fa-search-plus fa-2x"></i>
                                        </div>
                                    </a>
                                </div>
                            <?php } ?>

                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($post['title']); ?></h5>
                                <p class="card-text">
                                    <?php
                                    // Display a shortened preview of content
                                    echo strlen($post['content']) > 100 ?
                                        substr(htmlspecialchars($post['content']), 0, 100) . '...' :
                                        htmlspecialchars($post['content']);
                                    ?>
                                </p>
                            </div>
                            <div class="card-footer">
                                <small class="text-muted">Posted on
                                    <?php echo date('F j, Y, g:i a', strtotime($post['post_date'])); ?></small>
                            </div>

                            <!-- Gallery Images -->
                            <?php if (!empty($post['images'])) { ?>
                                <div class="image-gallery mt-2">
                                    <?php
                                    $imageCount = count($post['images']);
                                    foreach ($post['images'] as $imagePath) { ?>
                                        <a href="<?php echo htmlspecialchars($imagePath); ?>"
                                            data-lightbox="post-<?php echo $postId; ?>"
                                            data-title="<?php echo htmlspecialchars($post['title']); ?>" class="d-none">
                                            <img src="<?php echo htmlspecialchars($imagePath); ?>" alt="Gallery Image">
                                        </a>
                                    <?php } ?>

                                    <!-- Gallery indicator is outside of <a> tag, not blocking any clicks -->
                                    <?php if ($imageCount > 0) { ?>
                                        <div class="gallery-indicator">
                                            <?php echo $imageCount + 1; ?> Images
                                        </div>
                                    <?php } ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <?php
                }
            } else {
                echo "<div class='col-12'><div class='alert alert-info'>No posts available.</div></div>";
            }
            ?>
        </div>
    </div>

    <!-- Bootstrap Modal for Notifications -->
    <div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="statusModalLabel">Notification</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Status message will be populated by JavaScript -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Lightbox JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var status = "<?php echo $status; ?>"; // Get status from PHP

            // Display Bootstrap modal based on the status
            if (status === "success") {
                $('.modal-body').text('Post created successfully!');
                $('#statusModal').modal('show');
            } else if (status === "error") {
                $('.modal-body').text('There was an error while creating the post.');
                $('#statusModal').modal('show');
            }
        });
    </script>
</body>

</html>