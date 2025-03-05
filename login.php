<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $phoneNo = $_POST['phoneNo'];

    // Check if phone number length is valid
    if (!preg_match('/^\d{12,14}$/', $phoneNo)) {
        $error = "Error: Phone number must be between 12 and 14 digits.";
    } else {
        // Check if phone number exists in the customer table
        $query = "SELECT * FROM customer WHERE phoneNo = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $phoneNo);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $_SESSION['phoneNo'] = $phoneNo; // Set customer session
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "No account found. Please sign up.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Login</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            background-color: #f4f4f4; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            height: 100vh; 
            margin: 0;
        }
        .container {
            background: white; 
            padding: 20px; 
            border-radius: 10px; 
            box-shadow: 0 4px 8px rgba(0,0,0,0.2); 
            width: 100%;
            max-width: 400px; /* Mobile friendly */
            text-align: center;
        }
        input {
            width: 100%; 
            padding: 10px; 
            margin: 10px 0; 
            border: 1px solid #ddd; 
            border-radius: 5px;
            font-size: 16px;
        }
        button {
            width: 100%;
            padding: 12px;
            background: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }
        button:hover { background: #0056b3; }
        .error { color: red; font-weight: bold; }
        .signup-link { 
            display: block; 
            margin-top: 10px; 
            color: #007BFF; 
            text-decoration: none; 
            font-size: 14px;
        }
        .signup-link:hover { text-decoration: underline; }
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
    <h2>Customer Login</h2>

    <?php if (isset($error)): ?>
        <p class="error"><?= $error ?></p>
    <?php endif; ?>

    <!-- Customer Login Form -->
    <form method="post" onsubmit="return validatePhoneNumber();">
        <input type="text" id="phoneNo" name="phoneNo" placeholder="Enter Phone Number" required minlength="12" maxlength="14" pattern="\d{12,14}" title="Phone number must be 12-14 digits.">
        <button type="submit">Login</button>
    </form>

    <a href="signup.php" class="signup-link">Don't have an account? Sign up</a>
</div>

</body>
</html>

