<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) : 'Culture Connect'; ?></title>
    <link rel="stylesheet" href="/culture-connect/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="/culture-connect/assets/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="d-flex" id="wrapper">
        <div id="sidebar-wrapper" class="text-white vh-100 position-sticky top-0" style="width: 280px; background-color: #72b1e1; overflow-y: auto;">
            <a class="navbar-brand d-flex align-items-center p-4" href="index.php">
                <img src="assets/img/icons/logo.ico" alt="Logo" width="60" class="me-2">
                <span style="color: #fff; font-weight: 700; letter-spacing: 1px;">CULTURE CONNECT</span>
            </a>
            <div class="sidebar-heading p-4">
                <h4 class="fw-bold mb-0 text-white" style="letter-spacing: -0.5px; text-transform: none;">My Profile</h4>
            </div>
            
            <div class="list-group list-group-flush mt-2">
                
                <a href="profile.php" class="list-group-item list-group-item-action bg-transparent text-white border-0 p-3 d-flex align-items-center text-decoration-none sidebar-link">
                    <img src="assets/img/icons/profile.png" alt="Profile" width="22" height="22" class="me-3">
                    <span class="fs-5 fw-normal">Profile</span>
                </a>
                <a href="votes.php" class="list-group-item list-group-item-action bg-transparent text-white border-0 p-3 d-flex align-items-center text-decoration-none sidebar-link">
                    <img src="assets/img/icons/vote.png" alt="Votes" width="22" height="22" class="me-3">
                    <span class="fs-5 fw-normal">Votes</span>
                </a>
                <?php if (isset($_SESSION['is_council_member']) && $_SESSION['is_council_member'] === true): ?>

                <a href="reports.php" class="list-group-item list-group-item-action bg-transparent text-white border-0 p-3 d-flex align-items-center text-decoration-none sidebar-link">
                    <img src="assets/img/icons/report.png" alt="Report" width="22" height="22" class="me-3">
                    <span class="fs-5 fw-normal">Report</span>
                </a>
                <?php endif; ?>
                <?php if (isset($_SESSION['is_sme_member']) && $_SESSION['is_sme_member'] === true): ?>
                <a href="services.php" class="list-group-item list-group-item-action bg-transparent text-white border-0 p-3 d-flex align-items-center text-decoration-none sidebar-link">
                    <img src="assets/img/icons/Product-Service.png" alt="Services" width="22" height="22" class="me-3">
                    <span class="fs-5 fw-normal">Services</span>
                </a>
                <?php endif; ?>
                <?php if (isset($_SESSION['is_council_member']) && $_SESSION['is_council_member'] === true): ?>
                <a href="areas.php" class="list-group-item list-group-item-action bg-transparent text-white border-0 p-3 d-flex align-items-center text-decoration-none">
                    <img src="assets/img/icons/area.png" alt="Areas" width="22" class="me-3">
                    <span class="fs-5 fw-normal">Areas</span>
                </a>
                <?php endif; ?>
                <?php if (isset($_SESSION['is_sme_member']) && $_SESSION['is_sme_member'] === true): ?>
                <a href="sme-profile.php" class="list-group-item list-group-item-action bg-transparent text-white border-0 p-3 d-flex align-items-center text-decoration-none">
                    <img src="assets/img/icons/profile.png" alt="SME Profile" width="22" class="me-3">
                    <span class="fs-5 fw-normal">SME Profile</span>
                </a>
                <?php endif; ?>
            </div>
        </div>

 
