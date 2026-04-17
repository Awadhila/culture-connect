<?php

echo "<h2>Inserting Test Data...</h2>";

// 1. Insert 5 Areas
$areas = ['North District', 'South District', 'East Village', 'West End', 'Central Hub'];
foreach ($areas as $areaName) {
    $stmt = $conn->prepare("INSERT IGNORE INTO Area (Name) VALUES (?)");
    $stmt->bind_param("s", $areaName);
    if ($stmt->execute()) {
        echo "Area '$areaName' added successfully.<br>";
    }
}

// 2. Insert 1 User
// Note: We link this user to the first Area (AreaID 1)
$firstName = "Admin";
$lastName = "User";
$email = "admin@cultureconnect.com";
$password = password_hash("Admin123", PASSWORD_DEFAULT); // Secure hashing
$dob = "1992-04-30";
$gender = "Male";
$areaID = NULL;

$userQuery = "INSERT IGNORE INTO Users (Email, Password, First_Name, Last_Name, Date_Of_Birth, Gender, AreaID) 
              VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmtUser = $conn->prepare($userQuery);
$stmtUser->bind_param("ssssssi", $email, $password, $firstName, $lastName, $dob, $gender, $areaID);

if ($stmtUser->execute()) {
    $newUserID = $conn->insert_id;
    echo "User '$firstName $lastName' created successfully.<br>";

    // 3. Assign this User to Council Members (1:1 relationship)
    if ($newUserID > 0) {
        $councilQuery = "INSERT IGNORE INTO Council_Members (UserID) VALUES (?)";
        $stmtCouncil = $conn->prepare($councilQuery);
        $stmtCouncil->bind_param("i", $newUserID);
        if ($stmtCouncil->execute()) {
            echo "User assigned as a Council Member successfully.<br>";
        }
    }
} else {
    echo "Error inserting user: " . $conn->error . "<br>";
}

?>