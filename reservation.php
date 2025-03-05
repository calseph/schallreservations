<?php
session_start();
include 'db.php';

if (!isset($_SESSION['phoneNo'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $phoneNo = $_SESSION['phoneNo'];
    $court_id = $_POST['court_id'];
    $day = $_POST['day'];
    $hours = $_POST['hours'] ?? []; // Array of selected hours

    if (!empty($hours)) {
        foreach ($hours as $hour) {
            $query = "INSERT INTO reservation (phoneNo, court_id, day, hour) VALUES ('$phoneNo', '$court_id', '$day', '$hour')";
            $conn->query($query);
        }
        echo "<script>alert('Reservation successful!');</script>";
    } else {
        echo "<script>alert('Please select at least one time slot.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Make a Reservation</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; background-color: #f4f4f4; }
        .container { width: 80%; margin: 50px auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.2); }
        .checkbox-group { display: flex; flex-wrap: wrap; justify-content: center; }
        label { margin: 5px; }
        .price { font-weight: bold; margin-top: 10px; color: green; }
        button { margin-top: 10px; padding: 10px; border: none; background: #007BFF; color: white; border-radius: 5px; cursor: pointer; }
        button:hover { background: #0056b3; }
        .back-btn { display: block; margin-top: 10px; text-decoration: none; color: white; background: gray; padding: 10px; border-radius: 5px; }
        .back-btn:hover { background: darkgray; }
    </style>
    <script>
        function updatePrice() {
            let checkboxes = document.querySelectorAll('input[name="hours[]"]:checked');
            let totalPrice = checkboxes.length * 10; // RM10 per hour
            document.getElementById('totalPrice').innerText = "Total Price: RM" + totalPrice;
        }
    </script>
</head>
<body>

<div class="container">
    <h2>Make a Reservation</h2>
    <form method="post">
        <label>Select Court:</label>
        <select name="court_id">
            <option value="1">Court 1</option>
            <option value="2">Court 2</option>
            <option value="3">Court 3</option>
        </select>

        <label>Select Date:</label>
        <input type="date" name="day" required>

        <label>Select Time Slots:</label>
        <div class="checkbox-group">
            <?php
            for ($i = 8; $i <= 23; $i++) {
                $time = sprintf("%02d:00", $i);
                echo "<label><input type='checkbox' name='hours[]' value='$time' onclick='updatePrice()'> $time - " . sprintf("%02d:00", $i+1) . "</label>";
            }
            ?>
        </div>

        <p class="price" id="totalPrice">Total Price: RM0</p>

        <button type="submit">Reserve</button>
    </form>

    <a href="dashboard.php" class="back-btn">Back to Dashboard</a>
</div>

</body>
</html>

