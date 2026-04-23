<?php
session_start();
require_once '../config/connection.php';

if (!isset($_SESSION['is_council_member'])) exit("Unauthorized");

if (isset($_GET['id'])) {
    $productID = (int)$_GET['id'];

    $conn->begin_transaction();
    try {
        // 1. Delete associated votes
        $stmtVotes = $conn->prepare("DELETE FROM Votes WHERE ProductID = ?");
        $stmtVotes->bind_param("i", $productID);
        $stmtVotes->execute();

        // 2. Delete the product
        $stmtProd = $conn->prepare("DELETE FROM Products_Services WHERE ProductID = ?");
        $stmtProd->bind_param("i", $productID);
        $stmtProd->execute();

        $conn->commit();
        header("Location: /culture-connect/management/manage_PS_Admin.php?success=Product removed");
    } catch (Exception $e) {
        $conn->rollback();
        header("Location: /culture-connect/management/manage_PS_Admin.php?error=Delete failed");
    }
    exit();
}
?>