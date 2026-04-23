<?php
session_start();
require_once '../config/connection.php';

// Security Check
if (!isset($_SESSION['is_council_member']) || $_SESSION['is_council_member'] !== true) {
    exit("Unauthorized access");
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_area'])) {
    $areaId = $_POST['area_id'];
    $areaName = trim($_POST['area_name']);

    if (empty($areaName)) {
        header("Location: ../management/manage_areas.php?error=empty_name");
        exit();
    }

    if (!empty($areaId)) {
        // UPDATE EXISTING
        $sql = "UPDATE Area SET Name = ? WHERE AreaID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $areaName, $areaId);
    } else {
        // INSERT NEW
        $sql = "INSERT INTO Area (Name) VALUES (?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $areaName);
    }

    if ($stmt->execute()) {
        header("Location: ../management/manage_areas.php?success=area_saved");
    } else {
        header("Location: ../management/manage_areas.php?error=db_error");
    }
    
    $stmt->close();
    $conn->close();
    exit();
}
// --- DEEP DELETE LOGIC ---
if (isset($_GET['delete_id'])) {
    $areaID = (int)$_GET['delete_id'];

    // Start a transaction to ensure data integrity
    $conn->begin_transaction();

    try {
        // 1. Delete Votes for all products belonging to SMEs in this area
        $conn->query("DELETE FROM Votes WHERE ProductID IN 
                     (SELECT ProductID FROM Products_Services WHERE SmeID IN 
                     (SELECT SmeID FROM SME WHERE AreaID = $areaID))");

        // 2. Delete Products/Services belonging to SMEs in this area
        $conn->query("DELETE FROM Products_Services WHERE SmeID IN 
                     (SELECT SmeID FROM SME WHERE AreaID = $areaID)");

        // 3. Delete SME Memberships for SMEs in this area
        $conn->query("DELETE FROM SME_Members WHERE SmeID IN 
                     (SELECT SmeID FROM SME WHERE AreaID = $areaID)");

        // 4. Delete the SMEs themselves
        $conn->query("DELETE FROM SME WHERE AreaID = $areaID");

        // 5. Delete Council Member roles for Users in this area
        $conn->query("DELETE FROM Council_Members WHERE UserID IN 
                     (SELECT UserID FROM Users WHERE AreaID = $areaID)");

        // 6. Delete Users in this area
        $conn->query("DELETE FROM Users WHERE AreaID = $areaID");

        // 7. Finally, delete the Area
        $conn->query("DELETE FROM Area WHERE AreaID = $areaID");

        // If we reached here, commit all changes
        $conn->commit();
        header("Location: ../management/manage_areas.php?success=Area and all associated records deleted.");
        
    } catch (Exception $e) {
        // If anything goes wrong, undo everything
        $conn->rollback();
        header("Location: ../management/manage_areas.php?error=Delete failed: " . $e->getMessage());
    }
    exit();
}