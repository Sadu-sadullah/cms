<?php
require 'config.php';

// Code to handle image upload and selection...
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Post</title>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h2 class="mb-4">Create New Post</h2>

        <form action="upload.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>
            <div class="form-group">
                <label for="content">Content:</label>
                <textarea class="form-control" id="content" name="content" rows="5" required></textarea>
            </div>
            <div class="form-group">
                <label for="images">Upload Images:</label>
                <input type="file" class="form-control-file" id="images" name="images[]" multiple required>
            </div>

            <!-- Display uploaded images and allow main image selection -->
            <div id="image-preview" class="mt-4">
                <!-- JavaScript will populate this section with uploaded image previews -->
            </div>

            <button type="submit" class="btn btn-primary mt-3">Submit Post</button>
        </form>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- JavaScript to handle the display of uploaded images -->
    <script>
        document.getElementById('images').addEventListener('change', function (event) {
            const files = event.target.files;
            const imagePreview = document.getElementById('image-preview');
            imagePreview.innerHTML = ''; // Clear previous images

            // Create a Bootstrap row for the image grid
            const row = document.createElement('div');
            row.classList.add('row');

            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                const reader = new FileReader();

                reader.onload = function (e) {
                    // Create a column for each image
                    const col = document.createElement('div');
                    col.classList.add('col-md-3', 'mb-3'); // Adjust column size as needed

                    // Create a container for the image and radio button
                    const div = document.createElement('div');
                    div.classList.add('form-group', 'text-center'); // Center the image

                    // Create radio button for selecting the main image
                    const radio = document.createElement('input');
                    radio.type = 'radio';
                    radio.name = 'main_image';
                    radio.value = file.name;
                    radio.required = true; // Ensure a main image is selected

                    // Create image element for preview
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.classList.add('img-thumbnail', 'img-fluid'); // Use img-fluid for responsiveness
                    img.style.width = '150px';
                    img.style.height = '150px';
                    img.style.objectFit = 'cover';

                    // Append radio button and image to the container
                    div.appendChild(radio);
                    div.appendChild(img);

                    // Add the container to the column
                    col.appendChild(div);

                    // Add the column to the row
                    row.appendChild(col);
                };

                // Read the image file
                reader.readAsDataURL(file);
            }

            // Append the row to the image preview container
            imagePreview.appendChild(row);
        });
    </script>
</body>

</html>