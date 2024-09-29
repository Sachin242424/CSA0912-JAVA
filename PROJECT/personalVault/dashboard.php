<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Personal Data Vault</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(to right, #00c6ff, #0072ff);
            height: 100vh;
            margin: 0;
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
        }

        .container {
            text-align: center;
            padding: 50px;
            border-radius: 15px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(15px);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            width: 400px;
            max-width: 90%;
        }

        h2 {
            font-size: 2.5rem;
            margin-bottom: 20px;
        }

        a {
            display: block;
            padding: 15px;
            margin: 10px;
            color: #fff;
            text-decoration: none;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            font-size: 1.1rem;
            transition: background-color 0.3s, transform 0.3s;
        }

        a:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-3px);
        }

        .logout-link {
            margin-top: 20px;
            color: #e0f7fa;
            text-decoration: none;
            transition: color 0.3s;
        }

        .logout-link:hover {
            color: #ffeb3b;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
        <a href="upload.php">Upload Document</a>
        <a href="retreive.php">Retrieve Document</a>
        <a href="logout.php" class="logout-link">Logout</a>
    </div>

</body>
</html>
