<?php
// Include the verified connection

/**
 * SQL queries sorted by dependency:
 * 1. Area (Independent lookup)
 * 2. Users (Depends on Area)
 * 3. Council_Members (Depends on Users)
 * 4. SME (Depends on Area)
 * 5. SME_Members (Depends on Users & SME)
 */
$sql_queries = [
    "Area" => "CREATE TABLE IF NOT EXISTS Area (
        AreaID INT AUTO_INCREMENT PRIMARY KEY,
        Name VARCHAR(100) NOT NULL UNIQUE
    )",

    "Users" => "CREATE TABLE IF NOT EXISTS Users (
        UserID INT AUTO_INCREMENT PRIMARY KEY,
        Email VARCHAR(100) NOT NULL UNIQUE,
        Password VARCHAR(255) NOT NULL,
        First_Name VARCHAR(50) NOT NULL,
        Last_Name VARCHAR(50) NOT NULL,
        Date_Of_Birth DATE NOT NULL,
        Gender VARCHAR(20) NOT NULL,
        AreaID INT,
        Interests TEXT,
        Profile_Image VARCHAR(255) DEFAULT 'assets/img/no-profile.png',
        FOREIGN KEY (AreaID) REFERENCES Area(AreaID)
    )",

    "Council_Members" => "CREATE TABLE IF NOT EXISTS Council_Members (
        ID INT AUTO_INCREMENT PRIMARY KEY,
        UserID INT UNIQUE,
        FOREIGN KEY (UserID) REFERENCES Users(UserID)
    )",

    "SME" => "CREATE TABLE IF NOT EXISTS SME (
        SmeID INT AUTO_INCREMENT PRIMARY KEY,
        AreaID INT,
        Name VARCHAR(150) NOT NULL,
        Email VARCHAR(100),
        Bio TEXT,
        FOREIGN KEY (AreaID) REFERENCES Area(AreaID)
    )",

    "SME_Members" => "CREATE TABLE IF NOT EXISTS SME_Members (
        UserID INT,
        SmeID INT,
        Member_Type ENUM('Manager', 'Staff') NOT NULL,
        PRIMARY KEY (UserID, SmeID),
        FOREIGN KEY (UserID) REFERENCES Users(UserID),
        FOREIGN KEY (SmeID) REFERENCES SME(SmeID)
    )"
];

echo "<h2>Initializing CultureConnect Tables...</h2>";

foreach ($sql_queries as $tableName => $sql) {
    if ($conn->query($sql) === TRUE) {
        echo "Successfully created/verified table: <strong>$tableName</strong><br>";
    } else {
        echo "Error creating table $tableName: " . $conn->error . "<br>";
    }
}

// Close the connection established in db.php
?>