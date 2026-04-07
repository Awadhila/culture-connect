<?php
// header.php - Include this file at the top of all pages
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) : 'Culture Connect'; ?></title>
    <link rel="stylesheet" href="<?php echo basename(__DIR__) === 'includes' ? '../' : ''; ?>css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo basename(__DIR__) === 'includes' ? '../' : ''; ?>css/style.css">
</head>
<body>
    <header>
        <nav class="navbar">
            <div class="container">
                <h1><a href="<?php echo basename(__DIR__) === 'includes' ? '../' : ''; ?>index.php">Culture Connect</a></h1>
                <ul class="nav-links">
                    <li><a href="<?php echo basename(__DIR__) === 'includes' ? '../' : ''; ?>index.php">Home</a></li>
                    <li><a href="<?php echo basename(__DIR__) === 'includes' ? '../' : ''; ?>about.php">About</a></li>
                    <li><a href="<?php echo basename(__DIR__) === 'includes' ? '../' : ''; ?>contact.php">Contact</a></li>
                    <li>
                        <div class="auth-links">
                            <a href="signin.php">Sign In</a>
                            <a href="signup.php">Sign Up</a>
                        </div>
                    </li>
                </ul>

            </div>
        </nav>
    </header>
