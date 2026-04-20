<?php
// Define the query to get all Category names and IDs
$sql = "SELECT CategoryID, Name FROM Category ORDER BY Name ASC";
$catagory = $conn->query($sql);

if ($catagory->num_rows > 0) {
    while($row = $catagory->fetch_assoc()) {
        // Updated logic: check if the session Name OR the filter ID matches
        $selected = ($current_cat == $row['CategoryID'] || $current_cat == $row['Name']) ? 'selected' : '';
        
        // Use Name as the value for the Interest field
        echo '<option value="' . htmlspecialchars($row["Name"]) . '" ' . $selected . '>' . 
             htmlspecialchars($row["Name"]) . 
             '</option>';
    }
} else {
    echo '<option value="">No categories found</option>';
}
?>