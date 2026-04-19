<?php
// 1. Define the query to get all Category names and IDs
$sql = "SELECT CategoryID, Name FROM Category ORDER BY Name ASC";
$catagory = $conn->query($sql);

if ($catagory->num_rows > 0) {
    // 2. Loop through the results
    while($row = $catagory->fetch_assoc()) {
        // Check if this row matches the category selected in the URL
        // $current_cat comes from the logic in products-and-services.php
        $selected = ($current_cat == $row['CategoryID']) ? 'selected' : '';
        
        echo '<option value="' . $row["CategoryID"] . '" ' . $selected . '>' . 
             htmlspecialchars($row["Name"]) . 
             '</option>';
    }
} else {
    echo '<option value="">No categories found</option>';
}
?>