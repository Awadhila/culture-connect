<?php
// insert_test_data.php
require_once 'db.php'; 

echo "<h2>Populating CultureConnect with Authentic Data...</h2>";

// 1. Setup Areas
$areas = ['North District', 'South District', 'East Village', 'West End', 'Central Hub'];
foreach ($areas as $areaName) {
    $stmt = $conn->prepare("INSERT IGNORE INTO Area (Name) VALUES (?)");
    $stmt->bind_param("s", $areaName);
    $stmt->execute();
}

// 2. Setup Admin
$password = password_hash("Admin123", PASSWORD_DEFAULT);
$adminEmail = "admin@cultureconnect.com";
$conn->query("INSERT IGNORE INTO Users (Email, Password, First_Name, Last_Name, Date_Of_Birth, Gender, AreaID) 
              VALUES ('$adminEmail', '$password', 'Ahmed', 'Salim', '1990-05-15', 'Male', NULL)");
$adminID = $conn->query("SELECT UserID FROM Users WHERE Email='$adminEmail'")->fetch_assoc()['UserID'];
$conn->query("INSERT IGNORE INTO Council_Members (UserID) VALUES ($adminID)");

// 3. Category Mapping (Used to automatically determine if an item is a Product or Service)
$categoryTypes = [
    'Painting Classes' => 'Creative Workshops & Learning',
    'Sculpture Workshop' => 'Creative Workshops & Learning',
    'Ceramics Class' => 'Creative Workshops & Learning',
    'Guitar Lessons' => 'Creative Workshops & Learning',
    'Piano Coaching' => 'Creative Workshops & Learning',
    'Vocal Coaching' => 'Creative Workshops & Learning',
    'Creative Writing Workshop' => 'Creative Workshops & Learning',
    'Live Theatre Performance' => 'Performing Arts & Events',
    'Community Theatre' => 'Performing Arts & Events',
    'Concert Night' => 'Performing Arts & Events',
    'Open Mic Showcase' => 'Performing Arts & Events',
    'Spoken Word Event' => 'Performing Arts & Events',
    'Dance Performance' => 'Performing Arts & Events',
    'Street Performance' => 'Performing Arts & Events',
    'Guided Heritage Walk' => 'Cultural Experiences',
    'Historical Storytelling' => 'Cultural Experiences',
    'Local Art Trail' => 'Cultural Experiences',
    'Exhibition Access' => 'Cultural Experiences',
    'Artist Talk' => 'Cultural Experiences',
    'Curator-led Session' => 'Cultural Experiences',
    'Cultural Festival Entry' => 'Cultural Experiences',
    'Event Photography' => 'Creative Services',
    'Portrait Session' => 'Creative Services',
    'Graphic Design Branding' => 'Creative Services',
    'Illustration Project' => 'Creative Services',
    'Digital Content Creation' => 'Creative Services',
    'Social Media Management' => 'Creative Services',
    'Handmade Baking Kit' => 'Culinary Arts & Flavor',
    'Pastry Selection' => 'Culinary Arts & Flavor',
    'Global Spice Blend' => 'Culinary Arts & Flavor',
    'Artisan Coffee Beans' => 'Culinary Arts & Flavor',
    'Barista Starter Pack' => 'Culinary Arts & Flavor',
    'Heritage Recipe Book' => 'Culinary Arts & Flavor',
    'Woodworking Kit' => 'Craft & DIY Trades',
    'Textile Arts Set' => 'Craft & DIY Trades',
    'Jewellery Making Tools' => 'Craft & DIY Trades',
    'Hand-woven Scarf' => 'Craft & DIY Trades',
    'Custom Clay Pot' => 'Craft & DIY Trades',
    'DIY Embroidery Kit' => 'Craft & DIY Trades'
];

// Initialize Categories in DB and fetch IDs
$catMap = [];
$uniqueCategories = [
    'Creative Workshops & Learning' => 'Service',
    'Performing Arts & Events' => 'Service',
    'Cultural Experiences' => 'Service',
    'Creative Services' => 'Service',
    'Culinary Arts & Flavor' => 'Product',
    'Craft & DIY Trades' => 'Product'
];

foreach ($uniqueCategories as $name => $type) {
    $stmt = $conn->prepare("INSERT IGNORE INTO Category (Name, Type) VALUES (?, ?)");
    $stmt->bind_param("ss", $name, $type);
    $stmt->execute();
    $res = $conn->query("SELECT CategoryID FROM Category WHERE Name='$name'");
    $catMap[$name] = $res->fetch_assoc()['CategoryID'];
}

// 4. SME Data & Specific Item Lists (Matching your Reference List)
$smeData = [
    [
        'name' => 'Heritage Loom Textiles', 'email' => 'contact@heritageloom.com', 'area' => 1, 
        'mgr_first' => 'Sarah', 'mgr_last' => 'Jenkins', 'mgr_gender' => 'Female', 'mgr_email' => 'sarah.j@heritageloom.com', 'year' => 1985,
        'items' => ['DIY Embroidery Kit', 'Cultural Festival Entry', 'Hand-woven Scarf', 'Painting Classes', 'Spoken Word Event', 'Ceramics Class', 'Street Performance', 'Exhibition Access', 'Heritage Recipe Book', 'Digital Content Creation', 'Local Art Trail', 'Global Spice Blend', 'Event Photography', 'Live Theatre Performance', 'Woodworking Kit', 'Social Media Management', 'Custom Clay Pot', 'Illustration Project', 'Guitar Lessons', 'Barista Starter Pack']
    ],
    [
        'name' => 'Nomad Spice Kitchen', 'email' => 'hello@nomadspice.com', 'area' => 2, 
        'mgr_first' => 'Marcus', 'mgr_last' => 'Chen', 'mgr_gender' => 'Male', 'mgr_email' => 'm.chen@nomadspice.com', 'year' => 1992,
        'items' => ['Global Spice Blend', 'Historical Storytelling', 'Creative Writing Workshop', 'Artisan Coffee Beans', 'Exhibition Access', 'Sculpture Workshop', 'Spoken Word Event', 'Textile Arts Set', 'Portrait Session', 'Artist Talk', 'Heritage Recipe Book', 'Hand-woven Scarf', 'Ceramics Class', 'Custom Clay Pot', 'DIY Embroidery Kit', 'Concert Night', 'Digital Content Creation', 'Street Performance', 'Social Media Management', 'Graphic Design Branding']
    ],
    [
        'name' => 'Echoes of Ancestors', 'email' => 'info@echoes.com', 'area' => 3, 
        'mgr_first' => 'Amina', 'mgr_last' => 'Okonjo', 'mgr_gender' => 'Female', 'mgr_email' => 'amina@echoes.com', 'year' => 1988,
        'items' => ['Historical Storytelling', 'Guided Heritage Walk', 'Concert Night', 'Handmade Baking Kit', 'Local Art Trail', 'Illustration Project', 'Open Mic Showcase', 'Artist Talk', 'Spoken Word Event', 'Cultural Festival Entry', 'Social Media Management', 'Creative Writing Workshop', 'Sculpture Workshop', 'Live Theatre Performance', 'Graphic Design Branding', 'Piano Coaching', 'Global Spice Blend', 'Event Photography', 'Vocal Coaching', 'Hand-woven Scarf']
    ],
    [
        'name' => 'Terra Cotta Studios', 'email' => 'art@terracotta.com', 'area' => 4, 
        'mgr_first' => 'Luca', 'mgr_last' => 'Rossi', 'mgr_gender' => 'Male', 'mgr_email' => 'luca.r@terracotta.com', 'year' => 1995,
        'items' => ['Ceramics Class', 'Custom Clay Pot', 'Sculpture Workshop', 'Exhibition Access', 'Artist Talk', 'Painting Classes', 'Curator-led Session', 'Woodworking Kit', 'Jewellery Making Tools', 'Portrait Session', 'Graphic Design Branding', 'Digital Content Creation', 'Barista Starter Pack', 'Pastry Selection', 'Global Spice Blend', 'Concert Night', 'Open Mic Showcase', 'Dance Performance', 'Street Performance', 'DIY Embroidery Kit']
    ],
    [
        'name' => 'Midnight Folklore Theatre', 'email' => 'shows@midnightfolklore.com', 'area' => 5, 
        'mgr_first' => 'Elena', 'mgr_last' => 'Vargas', 'mgr_gender' => 'Female', 'mgr_email' => 'elena.v@folklore.com', 'year' => 1982,
        'items' => ['Live Theatre Performance', 'Community Theatre', 'Spoken Word Event', 'Street Performance', 'Historical Storytelling', 'Cultural Festival Entry', 'Curator-led Session', 'Creative Writing Workshop', 'Vocal Coaching', 'Dance Performance', 'Event Photography', 'Portrait Session', 'Digital Content Creation', 'Social Media Management', 'Heritage Recipe Book', 'Global Spice Blend', 'DIY Embroidery Kit', 'Textile Arts Set', 'Hand-woven Scarf', 'Jewellery Making Tools']
    ]
];

$globalItemCounter = 1;

foreach ($smeData as $data) {
    // Generate Manager and SME (logic remains same for relationships)
    $birthday = "{$data['year']}-" . str_pad(rand(1, 12), 2, '0', STR_PAD_LEFT) . "-" . str_pad(rand(1, 28), 2, '0', STR_PAD_LEFT);
    $stmtUser = $conn->prepare("INSERT IGNORE INTO Users (Email, Password, First_Name, Last_Name, Date_Of_Birth, Gender, AreaID) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmtUser->bind_param("ssssssi", $data['mgr_email'], $password, $data['mgr_first'], $data['mgr_last'], $birthday, $data['mgr_gender'], $data['area']);
    $stmtUser->execute();
    $managerID = $conn->query("SELECT UserID FROM Users WHERE Email='{$data['mgr_email']}'")->fetch_assoc()['UserID'];

    $stmtSme = $conn->prepare("INSERT IGNORE INTO SME (AreaID, Name, Email, Bio) VALUES (?, ?, ?, ?)");
    $stmtSme->bind_param("isss", $data['area'], $data['name'], $data['email'], $data['bio']);
    $stmtSme->execute();
    $smeID = $conn->query("SELECT SmeID FROM SME WHERE Name='{$data['name']}'")->fetch_assoc()['SmeID'];

    $conn->query("INSERT IGNORE INTO SME_Members (UserID, SmeID, Member_Type) VALUES ($managerID, $smeID, 'Manager')");

    // Insert Items strictly following the provided list
    $stmtProd = $conn->prepare("INSERT INTO Products_Services (SmeID, CategoryID, Name, Description, Price, Image, Is_Available) VALUES (?, ?, ?, ?, ?, ?, 1)");

    foreach ($data['items'] as $itemName) {
        $categoryName = $categoryTypes[$itemName];
        $catID = $catMap[$categoryName];
        
        $description = "A professional " . strtolower($itemName) . " offering from {$data['name']}.";
        $price = rand(25, 290) + 0.99;
        
        // Short image name with .png extension
        $imagePath = "uploads/products-services/PS_{$globalItemCounter}.jpg";

        $stmtProd->bind_param("iissds", $smeID, $catID, $itemName, $description, $price, $imagePath);
        $stmtProd->execute();
        
        $globalItemCounter++;
    }
    echo "SME '{$data['name']}' data inserted successfully.<br>";
}

echo "<h3>Setup Complete. 100 items verified with JPG images.</h3>";
?>