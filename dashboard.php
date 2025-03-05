<?php
session_start();
include 'db.php';

// Redirect if not logged in
if (!isset($_SESSION['phoneNo']) && !isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit();
}

$name = ""; // Default name

if (isset($_SESSION['admin'])) {
    $name = "Admin"; // Set name for admin
} elseif (isset($_SESSION['phoneNo'])) {
    $phoneNo = $_SESSION['phoneNo'];

    // Fetch user's name from database
    $query = "SELECT name FROM customer WHERE phoneNo = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $phoneNo);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $name = $row['name']; // Set user's name
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; background-color: #f4f4f4; }
        .container { max-width: 500px; margin: 50px auto; padding: 20px; background: white; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.2); }
        a { display: block; margin: 10px auto; padding: 15px; background: #007BFF; color: white; text-decoration: none; border-radius: 5px; max-width: 300px; }
        a:hover { background: #0056b3; }
    </style>
</head>
<body>

<div class="container">
    <h2>Welcome, <?= htmlspecialchars($name) ?>!</h2>

    <?php if (isset($_SESSION['admin'])): ?>
        <h3>Admin Panel</h3>
        <a href="admin_dashboard.php">Go to Admin Dashboard</a>
        <a href="logout.php">Logout</a>

    <?php elseif (isset($_SESSION['phoneNo'])): ?>
        <h3>Customer Panel</h3>
        <a href="reservation.php">Make a Reservation</a>
        <a href="view_reservations.php">View Your Reservations</a>
        <a href="logout.php">Logout</a>
    <?php endif; ?>
</div>

</body>
</html>
