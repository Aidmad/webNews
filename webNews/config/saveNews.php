<?php
    include '../config/db_config.php'; // Ensure your database connection is established

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Retrieve form data
        $art_title = $_POST['art_title'];
        $author = $_POST['author'];
        $description = $_POST['article_desc'];
        $article = $_POST['article']; // This will contain the HTML content from the contenteditable div
        $current_date = date('Y-m-d'); // Get current date in YYYY-MM-DD format

        // Initialize image path
        $img_path = null; // Default to null in case no image is uploaded

        // Specify the path to save the uploaded image
        $target_dir = '../includes/assets/imgs/';
        $target_file = $target_dir . basename($_FILES['image-upload']['name']);

        // Handle image upload if provided
        if (isset($_FILES['image-upload']) && $_FILES['image-upload']['error'] == 0) {
            // Move uploaded file to the target directory
            if (move_uploaded_file($_FILES['image-upload']['tmp_name'], $target_file)) {
                $img_path = $target_file; // Save the image path
            } else {
                echo "Error uploading image.";
                exit(); // Stop execution if the upload fails
            }
        }

        // Prepare the SQL statement to prevent SQL injection
        $stmt = $conn->prepare("INSERT INTO newstb (art_title, avtor, descr, article, img, data) VALUES (?, ?, ?, ?, ?, ?)");

        // Bind parameters; make sure to include all variables in the same order as the statement
        $stmt->bind_param("ssssss", $art_title, $author, $description, $article, $img_path, $current_date);


    // Execute the statement and check for errors
        if ($stmt->execute()) {
            // Redirect to main page after successful save
            header("Location: ../public/main.php"); // Change this to the correct path
            exit; // Ensure no further code is executed
        } else {
            echo "Error: " . $stmt->error;
        }

        // Close the statement and connection
        $stmt->close();
        $conn->close();
    } else {
        echo "Invalid request method.";
    }
?>