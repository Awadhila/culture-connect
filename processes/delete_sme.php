<?php
session_start();
require_once '../config/connection.php';

if (!isset($_SESSION['is_council_member'])) exit("Unauthorized");

if (isset($_GET['sme_id']) && isset($_GET['mgr_id'])) {
    $smeID = (int)$_GET['sme_id'];
    $mgrID = (int)$_GET['mgr_id'];

    $conn->begin_transaction();
    try {
        // 1. Delete Votes for products owned by this SME
        $conn->query("DELETE FROM Votes WHERE ProductID IN (SELECT ProductID FROM Products_Services WHERE SmeID = $smeID)");
        
        // 2. Delete Products/Services
        $conn->query("DELETE FROM Products_Services WHERE SmeID = $smeID");

        // 3. Delete SME Memberships
        $conn->query("DELETE FROM SME_Members WHERE SmeID = $smeID");

        // 4. Delete the SME itself
        $conn->query("DELETE FROM SME WHERE SmeID = $smeID");

        // 5. Delete the Manager's user account
        $conn->query("DELETE FROM Users WHERE UserID = $mgrID");

        $conn->commit();
        header("Location: /culture-connect/management/manage_smes.php?success=SME and Manager removed.");
    } catch (Exception $e) {
        $conn->rollback();
        header("Location: /culture-connect/management/manage_smes.php?error=Delete failed");
    }
    exit();
}
?>