<?php
// Define the query to get all Category names and IDs
$sql = "SELECT CategoryID, Name FROM Category ORDER BY Name ASC";
$catagory = $conn->query($sql);

if ($catagory->num_rows > 0) {
    while($row = $catagory->fetch_assoc()) {
        // Compare against ID for the selected state
        $selected = ($current_cat == $row['CategoryID']) ? 'selected' : '';
        
        // CHANGE: value must be the ID, not the Name
        echo '<option value="' . $row["CategoryID"] . '" ' . $selected . '>' . 
             htmlspecialchars($row["Name"]) . 
             '</option>';
    }
} else {
    echo '<option value="">No categories found</option>';
}
?>