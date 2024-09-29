<?php
session_start();

// Enable error reporting to troubleshoot issues
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if the user is logged in, if not, redirect to login page
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

require 'db.php'; // Include the database connection

// Check for successful database connection
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

$uploadMessage = ''; // Variable to store upload status message

// Handle file upload logic
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['document'])) {
    $file = $_FILES['document'];
    
    // File variables
    $fileName = mysqli_real_escape_string($conn, $file['name']);
    $fileTmpName = $file['tmp_name'];
    $fileSize = $file['size'];
    $fileError = $file['error'];
    $fileType = $file['type'];

    // Check for file upload errors
    if ($fileError === 0) {
        if ($fileSize < 10000000) { // Limit file size to 10MB
            $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            $allowed = array('jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx', 'txt');

            if (in_array($fileExt, $allowed)) {
                // Create a unique name for the file
                $fileNameNew = uniqid('', true) . "." . $fileExt;
                $fileDestination = 'uploads/' . $fileNameNew;
                
                // Check if the directory is writable
                if (!is_dir('uploads')) {
                    mkdir('uploads', 0777, true);
                }

                // Move file to the uploads directory
                if (move_uploaded_file($fileTmpName, $fileDestination)) {
                    // Insert file information into the database
                    $username = $_SESSION['username'];
                    $query = "INSERT INTO documents (username, file_name, file_path) VALUES ('$username', '$fileName', '$fileDestination')";
                    
                    if (mysqli_query($conn, $query)) {
                        // Success message
                        $uploadMessage = "<p>File uploaded successfully!</p>";
                    } else {
                        $uploadMessage = "<p>Error inserting file information into the database: " . mysqli_error($conn) . "</p>";
                    }
                } else {
                    $uploadMessage = "<p>Error moving the uploaded file.</p>";
                }
            } else {
                $uploadMessage = "<p>Invalid file type. Allowed types: jpg, jpeg, png, pdf, doc, docx, txt.</p>";
            }
        } else {
            $uploadMessage = "<p>File size exceeds the 10MB limit.</p>";
        }
    } else {
        $uploadMessage = "<p>Error uploading file. Code: $fileError</p>";
    }
} else {
    $uploadMessage = "<p>No file uploaded.</p>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Status</title>
    <style>
        body {
            background: linear-gradient(to right, #00c6ff, #0072ff);
            color: #fff;
            font-family: 'Arial', sans-serif;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .container {
            text-align: center;
            padding: 50px;
            border-radius: 15px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(15px);
            width: 400px;
        }
        .container a, .container button {
            margin-top: 20px;
            display: inline-block;
            padding: 10px 20px;
            background-color: rgba(255, 255, 255, 0.2);
            color: white;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            cursor: pointer;
        }
        .container button:hover, .container a:hover {
            background-color: rgba(255, 255, 255, 0.3);
        }
    </style>
</head>
<body>
    <div class="container">
        <?php
        // Display the upload message
        echo $uploadMessage;

        // If file was uploaded successfully, show the options
        if (!empty($uploadMessage)) {
            ?>
            <form action="upload.php" method="post" enctype="multipart/form-data">
                <label for="file">Upload Another File:</label>
                <input type="file" name="document" id="document" required>
                <button type="submit">Upload</button>
            </form>
            <a href="dashboard.php">Go Back to Dashboard</a>
            <?php
        }
        ?>
    </div>
</body>
</html>
