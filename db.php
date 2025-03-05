<?php
$host = "localhost";  
$user = "root";       
$pass = "";  // If you set a password in MySQL, put it here. Otherwise, leave it empty.
$dbname = "hallreserve";  // <-- Make sure this matches your actual database name

$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>