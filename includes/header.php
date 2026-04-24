<?php
// header.php - Include this file at the top of all pages
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) : 'Culture Connect'; ?></title>
    
    <link rel="stylesheet" href="/culture-connect/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="/culture-connect/assets/css/style.css">
    <link rel="icon" type="image/png" href="/culture-connect/assets/img/cc.ico">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <?php 
    // Get the current page name
    $current_page = basename($_SERVER['PHP_SELF']); 
    // List of pages where the navbar should NOT show
    $excluded_pages = ['signin.php', 'signup.php']; 
    ?>

    <?php if (!in_array($current_page, $excluded_pages)): ?>
    <header>
        <nav class="navbar navbar-expand-lg navbar-cc">
            <div class="container">
                <a class="navbar-brand d-flex align-items-center" href="./index.php">
                    <img src="./assets/img/logo.png" alt="Logo" width="60" class="me-2">
                    <span style="color: #72b1e1; font-weight: 700; letter-spacing: 1px;">CULTURE CONNECT</span>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="mainNav">
                    <ul class="navbar-nav ms-auto align-items-center">
                        <li class="nav-item"><a class="nav-link nav-link-cc" href="./index.php">Home</a></li>
                        <li class="nav-item"><a class="nav-link nav-link-cc" href="./about.php">About Us</a></li>
                        <li class="nav-item"><a class="nav-link nav-link-cc" href="./products-and-services.php">Products And Services</a></li>
                        <?php session_start(); ?>
                        <?php if (isset($_SESSION['full_name'])): ?>
                            <li class="nav-item"><a href="./management/manage_profile.php" class="nav-link nav-link-cc ">
                                <?php echo htmlspecialchars($_SESSION['full_name']); ?>
                            </a></li>
                        <?php endif; ?>
                    </ul>
                    <div class="ms-lg-3 d-flex align-items-center">
                        <?php if (isset($_SESSION['full_name'])): ?>
                            <a href="./processes/logout.php" class="ms-3 btn btn-primary-cc" style="height: 35px; padding: 0 15px;">Logout</a>
                        <?php else: ?>
                            <a href="./authentication-registration/signin.php" class="btn btn-primary-cc me-2">Sign In</a>
                            <a href="./authentication-registration/signup.php" class="btn btn-primary-cc">Sign Up</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </nav>
    </header>
    <?php endif; ?>
