<?php
require_once __DIR__ . '/../config/connection.php'; 

// 1. Capture All Filter Parameters
$itemsPerPage = 12;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $itemsPerPage;

// Sanitize inputs
$searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';
$categoryID = isset($_GET['cat_id']) ? $_GET['cat_id'] : ''; 
$typeFilter = isset($_GET['type']) ? (array)$_GET['type'] : [];
$minPrice = isset($_GET['min']) && is_numeric($_GET['min']) ? (float)$_GET['min'] : 0;
$maxPrice = isset($_GET['max']) && is_numeric($_GET['max']) ? (float)$_GET['max'] : 999999;
$localOnly = isset($_GET['local_only']) && $_GET['local_only'] == '1';

// 2. Build Dynamic WHERE Clause
$whereConditions = ["ps.Is_Available = 1"];
$params = [];
$types = "";

// A. Search Filter
if ($searchTerm !== '') {
    $whereConditions[] = "ps.Name LIKE ?";
    $params[] = "%$searchTerm%";
    $types .= "s";
}

// B. Category Filter (Dropdown)
if (!empty($categoryID) && is_numeric($categoryID)) {
    $whereConditions[] = "ps.CategoryID = ?";
    $params[] = (int)$categoryID;
    $types .= "i";
}

// C. Type Filter (Checkboxes)
if (!empty($typeFilter)) {
    $typeSubConditions = [];
    if (in_array('products', $typeFilter)) $typeSubConditions[] = "c.Type = 'Product'";
    if (in_array('services', $typeFilter)) $typeSubConditions[] = "c.Type = 'Service'";
    
    if (!empty($typeSubConditions)) {
        $whereConditions[] = "(" . implode(" OR ", $typeSubConditions) . ")";
    }
}

// D. Local Area Filter (Only applies if user is logged in)
if ($localOnly && isset($_SESSION['area_id'])) {
    $whereConditions[] = "s.AreaID = ?";
    $params[] = $_SESSION['area_id'];
    $types .= "i";
}

// E. Price Range Filter
$whereConditions[] = "ps.Price >= ? AND ps.Price <= ?";
$params[] = $minPrice;
$params[] = $maxPrice;
$types .= "dd";

$whereClause = "WHERE " . implode(" AND ", $whereConditions);

// 3. Get Total Count (Join SME to access AreaID)
$totalSql = "SELECT COUNT(*) as total 
             FROM Products_Services ps 
             JOIN Category c ON ps.CategoryID = c.CategoryID 
             JOIN SME s ON ps.SmeID = s.SmeID
             $whereClause";

$stmtTotal = $conn->prepare($totalSql);
if (!empty($params)) {
    $stmtTotal->bind_param($types, ...$params);
}
$stmtTotal->execute();
$totalRows = $stmtTotal->get_result()->fetch_assoc()['total'];
$totalPages = ceil($totalRows / $itemsPerPage);

// 4. Fetch Filtered Results
$sql = "SELECT ps.*, c.Name as CategoryName
        FROM Products_Services ps 
        JOIN Category c ON ps.CategoryID = c.CategoryID 
        JOIN SME s ON ps.SmeID = s.SmeID
        $whereClause
        ORDER BY ps.ProductID ASC
        LIMIT ? OFFSET ?";

$stmt = $conn->prepare($sql);
$finalParams = $params;
$finalParams[] = $itemsPerPage;
$finalParams[] = $offset;
$finalTypes = $types . "ii";

$stmt->bind_param($finalTypes, ...$finalParams);
$stmt->execute();
$result = $stmt->get_result();
?>