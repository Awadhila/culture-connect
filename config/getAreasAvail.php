<?php
require_once 'connection.php'; // Establish the connection to the database

// 1. Define the query to get all area names and IDs
$sql = "SELECT AreaID, Name FROM Area ORDER BY Name ASC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo '<option value="" selected disabled>Select your local area...</option>';
    
    // 2. Loop through the results and create an option for each area
    while($row = $result->fetch_assoc()) {
        echo '<option value="' . $row["AreaID"] . '">' . htmlspecialchars($row["Name"]) . '</option>';
    }
    
    echo '</select>';
} else {
    echo "No areas found. Please run the insertion script first.";
}

$conn->close();
?>