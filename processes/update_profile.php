<?php
session_start();
require_once '../config/connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    $errors = [];

    // 1. Data Collection with Fallbacks (Prevents Nulls)
    // If the POST field is empty or not set, use the current Session value
    $firstName = !empty($_POST['first_name']) ? trim($_POST['first_name']) : $_SESSION['first_name'];
    $lastName  = !empty($_POST['last_name']) ? trim($_POST['last_name']) : $_SESSION['last_name'];
    $dob       = !empty($_POST['dob']) ? $_POST['dob'] : $_SESSION['dob'];
    $gender    = !empty($_POST['gender']) ? $_POST['gender'] : $_SESSION['gender'];
    $interestID = !empty($_POST['interests']) ? $_POST['interests'] : $_SESSION['interests'];
    $interests = $_SESSION['interests']; // Fallback to current session value
    $areaId    = !empty($_POST['area_id']) ? $_POST['area_id'] : $_SESSION['area_id'];
    $profileImage = $_SESSION['profile_image']; 
    // 2. Validation: Name (Letters only)
    if (!preg_match("/^[a-zA-Z ]*$/", $firstName) || !preg_match("/^[a-zA-Z ]*$/", $lastName)) {
        $errors[] = "Names must only contain letters.";
    }

    // 3. Validation: Date of Birth (Not more than 100 years ago)
    $dateOfBirth = new DateTime($dob);
    $today = new DateTime();
    $age = $today->diff($dateOfBirth)->y;

    if ($age > 100) {
        $errors[] = "Date of birth cannot be more than 100 years ago.";
    } elseif ($dateOfBirth > $today) {
        $errors[] = "Date of birth cannot be in the future.";
    }

    // Redirect if there are validation errors
    if (!empty($errors)) {
        $errorMsg = implode(" ", $errors);
        header("Location: ../management/manage_profile.php?error=" . urlencode($errorMsg));
        exit();
    }

    // 4. Handle Profile Image Upload (Fixed tmp_name)
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../assets/img/profiles/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

        $fileExtension = pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION);
        $fileName = "user_" . $userId . "_" . time() . "." . $fileExtension;
        $targetFile = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $targetFile)) {
            $profileImage = 'assets/img/profiles/' . $fileName;
        }
    }
    // 6. If a new interest was selected, convert the ID to a Name
    if ($interestID && is_numeric($interestID)) {
        $catSql = "SELECT Name FROM Category WHERE CategoryID = ?";
        $stmtCat = $conn->prepare($catSql);
        $stmtCat->bind_param("i", $interestID);
        $stmtCat->execute();
        $resCat = $stmtCat->get_result();
        
        if ($rowCat = $resCat->fetch_assoc()) {
            // Set the variable to the Name (e.g., "Handicrafts") instead of the ID (e.g., "4")
            $interests = $rowCat['Name'];
        }
        $stmtCat->close();
    }
    // 7. Database Update
    $sql = "UPDATE Users SET 
            First_Name = ?, 
            Last_Name = ?, 
            Date_Of_Birth = ?, 
            Gender = ?, 
            Interests = ?, 
            AreaID = ?, 
            Profile_Image = ? 
            WHERE UserID = ?";
            
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssisi", $firstName, $lastName, $dob, $gender, $interests, $areaId, $profileImage, $userId);

if ($stmt->execute()) {
    // 1. Update standard User Sessions
    $_SESSION['first_name'] = $firstName;
    $_SESSION['last_name']  = $lastName;
    $_SESSION['dob']        = $dob;
    $_SESSION['gender']     = $gender;
    $_SESSION['interests']  = $interests;
    $_SESSION['area_id']    = $areaId;
    $_SESSION['profile_image'] = $profileImage;
    $_SESSION['interests'] = $interests;
    // 2. Handle SME/Business Session Updates
if (isset($_SESSION['is_sme_member']) && $_SESSION['is_sme_member'] === true) {
    
    // Ensure we have the SmeID from the session
    $smeID = $_SESSION['sme_id'] ?? 0;

    if ($smeID > 0) {
        $smeName = !empty($_POST['sme_name']) ? trim($_POST['sme_name']) : $_SESSION['sme_name'];
        $smeEmail = !empty($_POST['sme_email']) ? trim($_POST['sme_email']) : $_SESSION['sme_email'];
        $smeBio = isset($_POST['sme_bio']) ? trim($_POST['sme_bio']) : $_SESSION['sme_bio'];
        // 1. Update the SME table (Name, Email, and the new AreaID)
        $smeSql = "UPDATE SME SET Name = ?, Email = ?, AreaID = ?, Bio = ? WHERE SmeID = ?";
        $stmtSme = $conn->prepare($smeSql);
        
        // Bind parameters: Name(s), Email(s), AreaID(i), Bio(s), SmeID(i)
        $stmtSme->bind_param("ssisi", $smeName, $smeEmail, $areaId, $smeBio, $smeID);        
        if ($stmtSme->execute()) {
            // 2. Update Sessions so the UI reflects changes immediately
            $_SESSION['sme_name'] = $smeName;
            $_SESSION['sme_email'] = $smeEmail;
            $_SESSION['area_id'] = $areaId; // Update this to match the new location
            $_SESSION['sme_bio'] = $smeBio;
        }
    }
}
    
    header("Location: ../management/manage_profile.php?success=Profile updated successfully");
} else {
    header("Location: ../management/manage_profile.php?error=Database update failed");
}
    $stmt->close();
    $conn->close();
    exit();
}
