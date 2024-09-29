<?php
session_start();

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

$username = $_SESSION['username']; // Get the logged-in user's username

// Fetch documents for the logged-in user
$query = "SELECT id, file_name, file_path FROM documents WHERE username='$username'";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Retrieve Document - Personal Data Vault</title>
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
            padding: 50px;
            border-radius: 15px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(15px);
            width: 600px;
            text-align: center;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }
        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }
        th, td {
            padding: 15px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        th {
            font-size: 1.2rem;
        }
        td a {
            color: #ffeb3b;
            text-decoration: none;
        }
        td a:hover {
            text-decoration: underline;
        }
        .logout-link {
            display: block;
            margin-top: 20px;
            color: #e0f7fa;
            text-decoration: none;
        }
        .logout-link:hover {
            color: #ffeb3b;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Your Uploaded Documents</h2>
        <?php
        if (mysqli_num_rows($result) > 0) {
            echo "<table>";
            echo "<tr><th>Document Name</th><th>Download</th></tr>";

            // Display each document as a table row
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['file_name']) . "</td>";
                echo "<td><a href='" . $row['file_path'] . "' download>Download</a></td>";
                echo "</tr>";
            }

            echo "</table>";
        } else {
            echo "<p>No documents uploaded yet.</p>";
        }
        ?>
        <a href="dashboard.php" class="logout-link">Go Back to Dashboard</a>
        <a href="logout.php" class="logout-link">Logout</a>
    </div>
</body>
</html>

<?php
// Close the database connection
mysqli_close($conn);
?>
