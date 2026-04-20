<?php
session_start();
require_once '../config/connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $smeID = $_SESSION['sme_id'];
    $action = $_POST['action'];
    $name = $_POST['name'];
    $desc = $_POST['description'];
    $price = $_POST['price'];
    $catID = $_POST['category_id']; // Captured from the dropdown

    if ($action == 'update') {
        $productID = $_POST['product_id']; // Match your Primary Key name
        
        // Image logic remains the same...
        $imagePath = $_POST['current_image'] ?? 'assets/img/no_PS.jpg'; 
        // ... (handle file upload as before)

        $sql = "UPDATE Products_Services SET Name = ?, Description = ?, Price = ?, CategoryID = ?, Image = ? 
                WHERE ProductID = ? AND SmeID = ?";
        $stmt = $conn->prepare($sql);
        // Types: s=string, d=double, i=integer
        // name(s), desc(s), price(d), catID(i), image(s), productID(i), smeID(i) -> ssdisii
        $stmt->bind_param("ssdisii", $name, $desc, $price, $catID, $imagePath, $productID, $smeID);
    } else {
        // Insert Logic
        $sql = "INSERT INTO Products_Services (Name, Description, Price, CategoryID, SmeID) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssdii", $name, $desc, $price, $catID, $smeID);
    }

    if ($stmt->execute()) {
        header("Location: ../services.php?idx=" . ($_GET['idx'] ?? 1) . "&success=1");
    } else {
        echo "Error: " . $conn->error;
    }
}
$conn->close();
exit();