<?php
// Include the database connection
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    
    // Hash the password for security
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    
    // Check if the email is already registered
    $check_email_query = "SELECT * FROM users WHERE email='$email'";
    $check_result = mysqli_query($conn, $check_email_query);
    
    if (mysqli_num_rows($check_result) > 0) {
        echo "This email is already registered. Please try a different email.";
    } else {
        // Insert the user data into the database
        $query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";
        
        if (mysqli_query($conn, $query)) {
            // Registration successful
            header("Location: login.html"); // Redirect to login page
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
}
?>
