<?php
require_once 'connection.php'; // Establish the connection to the database

// 1. Define the query to get all area names and IDs
$sql = "SELECT AreaID, Name FROM Area WHERE AreaID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION['area_id']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    
    // 2. Loop through the results and create an option for each area
    while($row = $result->fetch_assoc()) {
       echo '<div class="col-6 fw-normal">' . htmlspecialchars($row["Name"]) . '</div>';
    }
    
    echo '</select>';
} else {
    echo "No areas found. Please run the insertion script first.";
}

$conn->close();
?>