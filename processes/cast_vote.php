<?php
session_start();
require_once __DIR__ . '/../config/connection.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    $userID = $_SESSION['user_id'];
    $productID = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
    
    // Safety check for role
    $isCouncil = $_SESSION['is_council_member'] ?? false;
    $isSme = $_SESSION['is_sme_member'] ?? false;

    if ($productID > 0 && !$isCouncil && !$isSme) {
        // ON DUPLICATE KEY UPDATE prevents multiple votes from same user for same product
        $sql = "INSERT INTO Votes (UserID, ProductID, Vote_Value) VALUES (?, ?, 1) 
                ON DUPLICATE KEY UPDATE Vote_Date = CURRENT_TIMESTAMP";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $userID, $productID);
        
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Vote recorded successfully!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to record vote.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Unauthorized action.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Login required.']);
}