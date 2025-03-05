<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            text-align: center; 
            background-color: #f4f4f4; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            height: 100vh; 
            margin: 0;
        }
        .container { 
            max-width: 500px; 
            padding: 20px; 
            background: white; 
            border-radius: 10px; 
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            width: 90%;
        }
        a.button { 
            display: block; 
            margin: 10px auto; 
            padding: 15px; 
            background: #007BFF; 
            color: white; 
            text-decoration: none; 
            border-radius: 5px; 
            max-width: 300px; 
            font-size: 16px;
        }
        a.button:hover { background: #0056b3; }
        .admin-link { 
            margin-top: 15px; 
            font-size: 14px; 
            display: inline-block;
            text-decoration: none; 
            color: #007BFF;
        }
        .admin-link:hover { text-decoration: underline; }
    </style>
</head>
<body>

<div class="container">
    <h2>Welcome to the Reservation System</h2>

    <?php if (isset($_SESSION['admin'])): ?>
        <h3>Admin Panel</h3>
        <a href="admin_dashboard.php" class="button">Go to Admin Dashboard</a>
        <a href="logout.php" class="button">Logout</a>

    <?php elseif (isset($_SESSION['phoneNo'])): ?>
        <h3>Customer Options</h3>
        <a href="reservation.php" class="button">Make a Reservation</a>
        <a href="view_reservations.php" class="button">View Your Reservations</a>
        <a href="logout.php" class="button">Logout</a>

    <?php else: ?>
        <h3>Welcome! Please log in or sign up.</h3>
        <a href="login.php" class="button">Customer Login</a>
        <a href="signup.php" class="button">Sign Up</a>
        <br>
        <a href="admin_login.php" class="admin-link">Admin Login</a> <!-- Simple hyperlink at the bottom -->
    <?php endif; ?>
</div>

</body>
</html>


