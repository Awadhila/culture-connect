<?php
session_start();
require_once '../config/connection.php';

$smeID = $_SESSION['sme_id'];

// --- DELETE LOGIC ---
if (isset($_GET['delete_id'])) {
    $productID = $_GET['delete_id'];
    
    // 1. Delete associated votes first to satisfy Foreign Key constraint
    $delVotes = $conn->prepare("DELETE FROM Votes WHERE ProductID = ?");
    $delVotes->bind_param("i", $productID);
    $delVotes->execute();

    // 2. Delete the actual product
    $delProd = $conn->prepare("DELETE FROM Products_Services WHERE ProductID = ? AND SmeID = ?");
    $delProd->bind_param("ii", $productID, $smeID);
    
    if ($delProd->execute()) {
        header("Location: ../services.php?idx=1&success=deleted");
    } else {
        header("Location: ../services.php?error=delete_failed");
    }
    exit();
}

// --- UPDATE & INSERT LOGIC ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'];
    $name = $_POST['name'];
    $desc = $_POST['description'];
    $price = $_POST['price'];
    $catID = $_POST['category_id']; // This must be an INT from the Category table
    $imagePath = $_POST['current_image'] ?? 'assets/img/no_PS.jpg';

    // File Upload Handler
    if (isset($_FILES['service_image']) && $_FILES['service_image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../assets/img/services/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
        
        $fileName = "item_" . time() . "_" . basename($_FILES['service_image']['name']);
        if (move_uploaded_file($_FILES['service_image']['tmp_name'], $uploadDir . $fileName)) {
            $imagePath = 'assets/img/services/' . $fileName;
        }
    }

    if ($action == 'update') {
        $productID = $_POST['product_id'];
        $idx = $_POST['current_idx']; // Redirect back to this specific item

        $sql = "UPDATE Products_Services SET Name = ?, Description = ?, Price = ?, CategoryID = ?, Image = ? 
                WHERE ProductID = ? AND SmeID = ?";
        $stmt = $conn->prepare($sql);
        // Type mapping: s=string, d=double, i=int
        $stmt->bind_param("ssdisii", $name, $desc, $price, $catID, $imagePath, $productID, $smeID);
        $stmt->execute();
        header("Location: ../services.php?idx=$idx&success=updated");
    } else {
        // INSERT Logic
        $sql = "INSERT INTO Products_Services (Name, Description, Price, CategoryID, SmeID, Image) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssdiis", $name, $desc, $price, $catID, $smeID, $imagePath);
        $stmt->execute();

        // Redirect to the last page to show the newly added item
        $res = $conn->query("SELECT COUNT(*) as total FROM Products_Services WHERE SmeID = $smeID");
        $newTotal = $res->fetch_assoc()['total'];
        header("Location: ../services.php?idx=$newTotal&success=added");
    }
    exit();
}