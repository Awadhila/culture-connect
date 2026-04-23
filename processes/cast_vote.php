<?php
session_start();

// 1. Correct the path to connection.php
require_once __DIR__ . '/../config/connection.php';

// 2. Clear buffers to ensure only JSON is sent
ob_clean(); 
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    $userID = $_SESSION['user_id'];
    $productID = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
    
    $isCouncil = $_SESSION['is_council_member'] ?? false;
    $isSme = $_SESSION['is_sme_member'] ?? false;

    if ($productID > 0 && !$isCouncil && !$isSme) {
        $voteValue = isset($_POST['vote_value']) ? (int)$_POST['vote_value'] : 1;

        // Use the correct logic for Vote_Value
        $sql = "INSERT INTO Votes (UserID, ProductID, Vote_Value) VALUES (?, ?, ?) 
                ON DUPLICATE KEY UPDATE Vote_Value = ?, Vote_Date = CURRENT_TIMESTAMP";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiii", $userID, $productID, $voteValue, $voteValue);
        
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Your vote has been recorded!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Database error.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid ID or role.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Login required.']);
}
exit();