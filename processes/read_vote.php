<?php
// 1. Get IDs from URL and Session
$itemID = isset($_GET['id']) && is_numeric($_GET['id']) ? (int)$_GET['id'] : 0;
$currentUserID = $_SESSION['user_id'] ?? 0;

$item = null; // Initialize to null

if ($itemID > 0) {
    /* 2. Combined SQL Query:
       - Fetches Product/Service details
       - Joins Category (for Name and Type)
       - Joins SME (for Creator info and AreaID)
       - Uses a Subquery to check if the specific logged-in user has already voted
    */
    $sql = "SELECT ps.*, c.Name as CategoryName, c.Type as CategoryType, 
                   s.Name as SmeName, s.Bio as SmeBio, s.AreaID,
                   (SELECT COUNT(*) FROM Votes WHERE UserID = ? AND ProductID = ps.ProductID) as UserHasVoted
            FROM Products_Services ps 
            JOIN Category c ON ps.CategoryID = c.CategoryID 
            JOIN SME s ON ps.SmeID = s.SmeID 
            WHERE ps.ProductID = ? AND ps.Is_Available = 1";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $currentUserID, $itemID);
    $stmt->execute();
    $result = $stmt->get_result();
    $item = $result->fetch_assoc();
}

// 3. Redirect if item doesn't exist or is unavailable
if (!$item) {
    header("Location: products-and-services.php");
    exit();
}

// Variables are now ready for use in the HTML below
$itemLabel = strtoupper($item['CategoryType']); // e.g., "PRODUCT" or "SERVICE"
$hasVoted  = ((int)$item['UserHasVoted'] > 0);
?>