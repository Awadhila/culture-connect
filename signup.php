<?php 
$pageTitle = "Register - Culture Connect";
?>
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
<main class="py-5" style="background-color: #f1f8fc; min-height: 80vh;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card border-4 border-dark p-4 shadow-none" style="border-radius: 0 !important; border: 4px solid #000 !important;">
                    <div class="text-center mb-4">
                        <h2 class="fw-bold" style="color: #72b1e1; letter-spacing: -1px;">CREATE ACCOUNT</h2>
                        <p class="text-muted small">Join the Culture Connect community today.</p>
                    </div>
                    <form id="registrationForm" action="processes/auth_logic.php" method="POST" onsubmit="event.preventDefault(); validateForm();" novalidate>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold small text-uppercase">First Name <span id="error-first_name" class="text-danger small fw-bold ms-2"></span></label> 
                                <input type="text" class="form-control border-2 border-dark" name="first_name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold small text-uppercase">Last Name <span id="error-last_name" class="text-danger small fw-bold ms-2"></span></label>
                                <input type="text" class="form-control border-2 border-dark" name="last_name" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold small text-uppercase">Email Address<span id="error-email" class="text-danger small fw-bold ms-2"></span></label>
                            <input type="email" class="form-control border-2 border-dark" name="email" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold small text-uppercase">Password <span id="error-password" class="text-danger small fw-bold ms-2"></span></label>
                                <input type="password" class="form-control border-2 border-dark" name="password" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold small text-uppercase">Confirm Password <span id="error-confirm_password" class="text-danger small fw-bold ms-2"></span></label>
                                <input type="password" class="form-control border-2 border-dark" name="confirm_password" required>
                            </div>
                        </div>

                        <div class="row align-items-end mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-uppercase">Date of Birth <span id="error-dob" class="text-danger small fw-bold ms-2"></span></label>
                                <input type="date" class="form-control border-2 border-dark" name="dob" required>
                            </div>
                            <div class="col-md-3 py-2">
                                <label class="form-label fw-bold small text-uppercase d-block">Gender<span id="error-gender" class="text-danger small fw-bold ms-2"></span></label>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input border-dark" type="radio" name="gender" id="male" value="male" required>
                                    <label class="form-check-label small fw-bold" for="male">Male</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input border-dark" type="radio" name="gender" id="female" value="female">
                                    <label class="form-check-label small fw-bold" for="female">Female</label>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold small text-uppercase">Area <span id="error-area_id" class="text-danger small fw-bold ms-2"></span></label>
                            <select class="form-select border-2 border-dark" name="area_id" style="border-radius: 0 !important;" required>
                                <?php include 'config/getAreasAvail.php'; ?>
                            </select>
                        </div>

                        <div class="form-check form-check-inline mb-3">
                            <input class="form-check-input border-dark" type="checkbox" name="is_sme" id="isSme" value="1" onchange="toggleSmeFields()">
                            <label class="form-check-label small fw-bold" for="isSme">Register as an SME (Business)</label>
                        </div>

                        <div id="smeFields" style="display: none;">
                            <div class="row align-items-end mb-3">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold small text-uppercase">SME Name <span id="error-sme_name" class="text-danger small fw-bold ms-2"></span></label>
                                    <input type="text" class="form-control border-2 border-dark" name="sme_name" id="sme_name">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold small text-uppercase">SME Email <span id="error-sme_email" class="text-danger small fw-bold ms-2"></span></label>
                                    <input type="email" class="form-control border-2 border-dark" name="sme_email" id="sme_email">
                                </div>
                            </div>
                        </div>

                        <button type="submit" name="register" class="btn btn-black-square w-100 mb-3 py-3" style="border: 2px solid #fff !important;">
                            REGISTER NOW
                        </button>
                    </form>
                    <script src="assets/js/registration.js"></script>
                    <div class="text-center">
                        <p class="small fw-bold">Already have an account? <a href="signin.php" style="color: #72b1e1;">Log In</a></p>
                        <a href="index.php" class="text-decoration-none fw-bold small" style="color: #72b1e1;">
                            ← BACK TO HOME
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>