<?php
// 1. Connection Parameters
$servername = "localhost";
$username = "root"; // Default XAMPP username
$password = "";     // Default XAMPP password is empty
$dbname = "CultureConnect"; // The name of the DB you created in phpMyAdmin

// 2. Establish the Connection
$conn = new mysqli($servername, $username, $password, $dbname);

// 3. Check if the connection worked
if ($conn->connect_error) {
    // If there is an error, stop the script and show the message
    die("Connection failed: " . $conn->connect_error);
}

?>