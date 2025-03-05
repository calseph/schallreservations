<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            text-align: center; 
            background-color: #f4f4f4;
            padding-top: 50px;
        }
        .container {
            width: 50%;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        h2 { color: #333; }
        .btn {
            display: block;
            width: 80%;
            margin: 10px auto;
            padding: 15px;
            font-size: 18px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            text-align: center;
            cursor: pointer;
            transition: 0.3s;
        }
        .btn:hover { background-color: #0056b3; }
        .logout { background-color: red; }
        .logout:hover { background-color: darkred; }
    </style>
</head>
<body>

<div class="container">
    <h2>Admin Dashboard</h2>
    <a href="view_reservations.php" class="btn">View All Reservations</a>
    <a href="logout.php" class="btn logout">Logout</a>
</div>

</body>
</html>

