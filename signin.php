<?php 
$pageTitle = "Sign In - Culture Connect";
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
<main class="d-flex align-items-center justify-content-center" style="min-height: 80vh;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card border-4 border-dark p-4 shadow-none" style="border-radius: 0 !important;">
                    <div class="text-center mb-4">
                        <h2 class="fw-bold" style="color: #72b1e1; letter-spacing: -1px;">SIGN IN</h2>
                        <p class="text-muted small">Welcome back to Culture Connect</p>
                        <p class="small fw-bold">
                            Don't have an account? 
                            <a href="signup.php" class="text-decoration-none" style="color: #72b1e1;">Click here to create one</a>
                        </p>
                    </div>
                    <?php if (isset($_GET['error']) && $_GET['error'] == 'invalid_credentials'): ?>
                        <div class="alert alert-danger border-2 border-dark py-2 mb-3 d-flex align-items-center" style="border-radius: 0;">
                            <span class="fw-bold small text-uppercase">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i> 
                                Invalid email or password. Please try again.
                            </span>
                        </div>
                    <?php endif; ?>
                    <form id="SignInForm"action="processes/auth_logic.php" method="POST" onsubmit="return validateForm();" novalidate>
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-uppercase">Email Address<span id="error-email" class="text-danger small fw-bold ms-2"></span></label>
                            <input type="email" class="form-control form-control-lg border-2 border-dark" name="email" required style="border-radius: 0 !important;">
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold small text-uppercase">Password<span id="error-password" class="text-danger small fw-bold ms-2"></span></label>
                            <input type="password" class="form-control form-control-lg border-2 border-dark" name="password" required style="border-radius: 0 !important;">
                        </div>

                        <button type="submit" name="login" class="btn btn-black-square w-100 mb-3 py-3">
                            LOG IN
                        </button>
                    </form>
                    <script src="assets/js/signin.js"></script>
                    <div class="text-center">
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