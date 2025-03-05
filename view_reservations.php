<?php
session_start();
include "db.php"; // Connect to database

$is_admin = isset($_SESSION['admin']); // Check if admin is logged in
$phoneNo = $_SESSION['phoneNo'] ?? null; // Get customer phone number if logged in

if (!$is_admin && !$phoneNo) {
    header("Location: index.php"); // Redirect if neither admin nor customer is logged in
    exit();
}

// Handle cancellation
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cancel_id'])) {
    $cancel_id = $_POST['cancel_id'];

    if ($is_admin) {
        // Admin can cancel any reservation
        $delete_query = "DELETE FROM reservation WHERE num = ?";
        $stmt = $conn->prepare($delete_query);
        $stmt->bind_param("i", $cancel_id);
    } else {
        // Customers can only cancel their own reservations
        $delete_query = "DELETE FROM reservation WHERE num = ? AND phoneNo = ?";
        $stmt = $conn->prepare($delete_query);
        $stmt->bind_param("is", $cancel_id, $phoneNo);
    }

    if ($stmt->execute()) {
        $message = "<p class='success'>Reservation cancelled successfully!</p>";
    } else {
        $message = "<p class='error'>Error cancelling reservation.</p>";
    }
}

// Fetch reservations
$sql = "SELECT reservation.num, reservation.day, reservation.hour, court.id AS court_number, customer.name, customer.phoneNo
        FROM reservation
        JOIN court ON reservation.court_id = court.id
        JOIN customer ON reservation.phoneNo = customer.phoneNo";

if (!$is_admin) {
    $sql .= " WHERE reservation.phoneNo = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $phoneNo);
} else {
    $stmt = $conn->prepare($sql);
}

$stmt->execute();
$result = $stmt->get_result();

// Count total reservations for price calculation
$total_reservations = $result->num_rows;
$total_price = $total_reservations * 10; // RM10 per hour
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Reservations</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; background-color: #f4f4f4; }
        .container { width: 80%; margin: 50px auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.2); }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; border: 1px solid #ddd; }
        th { background-color: #007BFF; color: white; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .btn { padding: 7px 12px; border: none; cursor: pointer; border-radius: 5px; }
        .cancel-btn { background: red; color: white; }
        .cancel-btn:hover { background: darkred; }
        .print-btn { background: green; color: white; margin-left: 10px; }
        .print-btn:hover { background: darkgreen; }
        .back-btn { background: gray; text-decoration: none; display: inline-block; padding: 10px; border-radius: 5px; color: white; margin-top: 10px; }
        .back-btn:hover { background: darkgray; }
        .total-price { font-weight: bold; color: green; margin-top: 20px; }
        .success { color: green; }
        .error { color: red; }
        @media (max-width: 600px) {
            .container { width: 95%; }
            table { font-size: 14px; }
        }
    </style>
    <script>
        function confirmCancel() {
            return confirm("Are you sure you want to cancel this reservation?");
        }

        function printReservations() {
            window.print(); // Open the print dialog
        }
    </script>
</head>
<body>

<div class="container">
    <h2>Reservations</h2>

    <?= $message ?? '' ?> <!-- Show success/error messages -->

    <table>
        <tr>
            <th>Reservation #</th>
            <th>Date</th>
            <th>Time</th>
            <th>Court</th>
            <?php if ($is_admin) echo "<th>Customer Name</th><th>Phone Number</th>"; ?>
            <th>Action</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['num'] ?></td>
                <td><?= $row['day'] ?></td>
                <td><?= $row['hour'] ?></td>
                <td><?= $row['court_number'] ?></td>
                <?php if ($is_admin): ?>
                    <td><?= $row['name'] ?></td>
                    <td><?= $row['phoneNo'] ?></td>
                <?php endif; ?>
                <td>
                    <form method="post" onsubmit="return confirmCancel();">
                        <input type="hidden" name="cancel_id" value="<?= $row['num'] ?>">
                        <button type="submit" class="btn cancel-btn">Cancel</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

    <p class="total-price">Total Amount Owed: RM<?= $total_price ?></p>

    <button class="btn print-btn" onclick="printReservations()">Print Reservations</button>
    <a href="dashboard.php" class="back-btn">Back to Dashboard</a>
</div>

</body>
</html>
