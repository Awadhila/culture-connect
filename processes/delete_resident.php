<?php
session_start();
require_once '../config/connection.php';

if (!isset($_SESSION['is_council_member']) || $_SESSION['is_council_member'] !== true) {
    exit("Unauthorized");
}

if (isset($_GET['delete_id'])) {
    $deleteID = (int)$_GET['delete_id'];

    $conn->begin_transaction();

    try {
        // 1. Delete all votes made by this resident
        $stmtVotes = $conn->prepare("DELETE FROM Votes WHERE UserID = ?");
        $stmtVotes->bind_param("i", $deleteID);
        $stmtVotes->execute();

        // 2. Delete any Council Member role (if they had one)
        $stmtCouncil = $conn->prepare("DELETE FROM Council_Members WHERE UserID = ?");
        $stmtCouncil->bind_param("i", $deleteID);
        $stmtCouncil->execute();

        // 3. Delete the User account
        $stmtUser = $conn->prepare("DELETE FROM Users WHERE UserID = ?");
        $stmtUser->bind_param("i", $deleteID);
        $stmtUser->execute();

        $conn->commit();
        header("Location: /culture-connect/management/manage_residents.php?success=Resident deleted successfully");
        
    } catch (Exception $e) {
        $conn->rollback();
        header("Location: /culture-connect/management/manage_residents.php?error=Delete failed");
    }
    exit();
}