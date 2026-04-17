<?php
require_once '../config/connection.php';
echo "<h2>Processing Authentication...</h2>";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['first_name'])) {
    echo "Debug: Starting registration process...<br>";

    // 1. Collect standard User data
    $email = $_POST['email'] ?? null;
    $first_name = $_POST['first_name'] ?? null;
    $last_name = $_POST['last_name'] ?? null;
    $dob = $_POST['dob'] ?? null;
    $gender = $_POST['gender'] ?? null; 
    $area_id = $_POST['area_id'] ?? null;
    $password_raw = $_POST['password'] ?? '';

    // Safety check: if core fields are missing, redirect back
    if (!$gender || !$area_id || !$email) {
        header("Location: ../signup.php?error=missing_fields");
        exit();
    }

    $password = password_hash($password_raw, PASSWORD_DEFAULT);

    $conn->begin_transaction();
    try {

        // 2. Create the User entry first
        $userSql = "INSERT INTO Users (Email, Password, First_Name, Last_Name, Date_Of_Birth, Gender, AreaID) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmtUser = $conn->prepare($userSql);
        $stmtUser->bind_param("ssssssi", $email, $password, $first_name, $last_name, $dob, $gender, $area_id);
        $stmtUser->execute();
        $newUserId = $conn->insert_id;

        // 3. Check if 'Register as SME' was selected
        if (isset($_POST['is_sme']) && $_POST['is_sme'] == '1') {
            $sme_name = $_POST['sme_name'];
            $sme_bio = NULL; // Optional field, can be extended in the form later

            // Create the SME profile
            $smeSql = "INSERT INTO SME (AreaID, Name, Email, Bio) VALUES (?, ?, ?, ?)";
            $stmtSme = $conn->prepare($smeSql);
            $stmtSme->bind_param("isss", $area_id, $sme_name, $email, $sme_bio);
            $stmtSme->execute();
            $newSmeId = $conn->insert_id;

            // Link the User to the SME as a 'Manager'
            $memberSql = "INSERT INTO SME_Members (UserID, SmeID, Member_Type) VALUES (?, ?, 'Manager')";
            $stmtMember = $conn->prepare($memberSql);
            $stmtMember->bind_param("ii", $newUserId, $newSmeId);
            $stmtMember->execute();
        }

        $conn->commit();
        header("Location: ../signin.php?success=account_created");
        exit();
    } catch (Exception $e) {
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    // 1. Collect standard User Login data
    $email = $_POST['email'];
    $password = $_POST['password'];

    // 2. Validate credentials (simplified for example)
    $userSql = "SELECT * FROM Users WHERE Email = ?";
    $stmtUser = $conn->prepare($userSql);
    $stmtUser->bind_param("s", $email);
    $stmtUser->execute();
    $result = $stmtUser->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['Password'])) {
            // Login successful
            session_start();
            $_SESSION['is_council_member'] = false;
            $_SESSION['is_sme_member'] = false;
            $_SESSION['user_id'] = $user['UserID'];
            $_SESSION['email'] = $user['Email'];
            $_SESSION['first_name'] = $user['First_Name'];
            $_SESSION['last_name'] = $user['Last_Name'];
            $_SESSION['full_name'] = $user['First_Name'] . ' ' . $user['Last_Name'];
            $_SESSION['area_id'] = $user['AreaID'];
            $_SESSION['dob'] = $user['Date_Of_Birth'];
            $_SESSION['gender'] = $user['Gender'];
            $_SESSION['interests'] = $user['Interests'];
            $_SESSION['profile_image'] = $user['Profile_Image'];
            // check if user is an SME member or council member and set additional session variables if needed


            $councilSql = "SELECT * FROM Council_Members WHERE UserID = ?";
            $stmtCouncil = $conn->prepare($councilSql);
            $stmtCouncil->bind_param("i", $user['UserID']);
            $stmtCouncil->execute();
            $resultcouncil = $stmtCouncil->get_result();

            if ($resultcouncil->num_rows > 0) {
                $councilData = $resultcouncil->fetch_assoc(); 
                $_SESSION['council_id'] = $councilData['ID'];
                $_SESSION['is_council_member'] = true;
            } 
            $smeMemberSql = "SELECT * FROM SME_Members WHERE UserID = ?";
            $stmtSme = $conn->prepare($smeMemberSql);
            $stmtSme->bind_param("i", $user['UserID']);
            $stmtSme->execute();
            $smeResult = $stmtSme->get_result();
            if ($smeResult->num_rows > 0) {
                $smeMember = $smeResult->fetch_assoc();
                $smeSql = "SELECT * FROM SME WHERE SmeID = ?";
                $stmtSmeDetails = $conn->prepare($smeSql);
                $stmtSmeDetails->bind_param("i", $smeMember['SmeID']);
                $stmtSmeDetails->execute();
                $smeDetails = $stmtSmeDetails->get_result()->fetch_assoc();
                $_SESSION['sme_id'] = $smeMember['SmeID'];
                $_SESSION['sme_member_type'] = $smeMember['Member_Type'];
                $_SESSION['sme_name'] = $smeDetails['Name'];  
                $_SESSION['sme_bio'] = $smeDetails['Bio'];
                $_SESSION['email'] = $smeDetails['Email'];
                $_SESSION['is_sme_member'] = true;
            }
            header("Location: ../index.php");
            exit();
        } else {
            header("Location: ../signin.php?error=invalid_credentials");
            exit();
        }
    } else {
        header("Location: ../signin.php?error=invalid_credentials");
        exit();
    }
}

$conn->close();
?>