<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $phoneNo = $_POST['phoneNo'];
    $name = $_POST['name'];

    // Check if phone number length is valid
    if (strlen($phoneNo) < 12 || strlen($phoneNo) > 14) {
        $message = "<p class='error'>Error: Phone number must be between 12 and 14 characters.</p>";
    } else {
        // Check if phone number already exists
        $checkQuery = "SELECT * FROM customer WHERE phoneNo = ?";
        $stmt = $conn->prepare($checkQuery);
        $stmt->bind_param("s", $phoneNo);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // If phone number exists, show error message
            $message = "<p class='error'>Error: Duplicate entry! This phone number is already registered. <a href='login.php'>Login here</a></p>";
        } else {
            // Insert new customer
            $insertQuery = "INSERT INTO customer (phoneNo, name) VALUES (?, ?)";
            $stmt = $conn->prepare($insertQuery);
            $stmt->bind_param("ss", $phoneNo, $name);

            if ($stmt->execute()) {
                $message = "<p class='success'>Sign-up successful! <a href='login.php'>Login here</a></p>";
            } else {
                $message = "<p class='error'>Error: " . $conn->error . "</p>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            text-align: center;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 90%;
            max-width: 400px;
            margin: 50px auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }

        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        button {
            width: 100%;
            padding: 10px;
            background: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 18px;
            cursor: pointer;
            margin-top: 10px;
        }

        button:hover {
            background: #0056b3;
        }

        .success {
            color: green;
            font-weight: bold;
        }

        .error {
            color: red;
            font-weight: bold;
        }

        a {
            color: #007BFF;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        @media (max-width: 600px) {
            .container {
                width: 95%;
                padding: 15px;
            }

            input[type="text"], button {
                font-size: 14px;
            }
        }
    </style>
    <script>
        function validatePhoneNumber() {
            let phoneInput = document.getElementById("phoneNo");
            let phoneValue = phoneInput.value.trim();

            // Ensure input is only numbers and within valid length
            if (!/^\d{12,14}$/.test(phoneValue)) {
                alert("Phone number must be between 12 and 14 digits and contain only numbers.");
                return false; // Prevent form submission
            }
            return true; // Allow submission
        }
    </script>
</head>
<body>

<div class="container">
    <h2>Sign Up</h2>

    <?= $message ?? '' ?> <!-- Display success/error message -->

    <form method="post" onsubmit="return validatePhoneNumber();">
        <input type="text" name="name" placeholder="Full Name" required>
        <input type="text" id="phoneNo" name="phoneNo" placeholder="Phone Number" required minlength="12" maxlength="14" pattern="\d{12,14}" title="Phone number must be 12-14 digits.">
        <button type="submit">Sign Up</button>
    </form>
</div>

</body>
</html>

