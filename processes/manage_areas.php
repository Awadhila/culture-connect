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
        header("Location: ../areas.php?error=empty_name");
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
        header("Location: ../areas.php?success=area_saved");
    } else {
        header("Location: ../areas.php?error=db_error");
    }
    
    $stmt->close();
    $conn->close();
    exit();
}