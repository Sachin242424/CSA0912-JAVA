<?php
session_start();
require 'db.php'; // Include the database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['email']) && isset($_POST['password'])) {
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);

        // Fetch user data
        $query = "SELECT * FROM users WHERE email='$email'";
        $result = mysqli_query($conn, $query);
        
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            if (password_verify($password, $row['password'])) {
                // Successful login
                $_SESSION['username'] = $row['username'];
                header("Location: dashboard.php"); // Redirect to dashboard
            } else {
                echo "Incorrect password.";
            }
        } else {
            echo "No account found with this email.";
        }
    } else {
        echo "Email or password not set.";
    }
}
?>
