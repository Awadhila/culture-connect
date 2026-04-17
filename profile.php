<?php session_start();
//echo "<pre>"; 
//print_r($_SESSION); 
//echo "</pre>";
//die(); ?>
<?php 
$pageTitle = "Profile - Culture Connect";
?>

<?php include 'includes/sidebar.php'; ?>

        <div id="page-content-wrapper" class="flex-grow-1 p-5">
            <div class="container-fluid p-5">
                <h2 class="fw-bold">My Profile</h2>
            </div>
            <div class="row align-items-start">
            
                <div class="col-md-5">
                    <div style="border: 3px solid #72b1e1; padding: 5px;">
                        <img src="<?php echo $_SESSION['profile_image'] ?>" alt="Profile Picture" class="img-fluid" style="display: block; width: 100%;">
                    </div>
                </div>

                <div class="col-md-7 ps-4">
                    <h3 class="fw-bold text-uppercase mb-3" style="letter-spacing: 1px;">Profile</h3>
                    
                    <div class="profile-details fs-5">
                        <div class="row mb-2">
                            <div class="col-5 fw-normal">First Name</div>
                            <div class="col-1 text-center">:</div>
                            <div class="col-6 fw-normal"><?php echo $_SESSION['first_name'] ?></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-5 fw-normal">Last Name</div>
                            <div class="col-1 text-center">:</div>
                            <div class="col-6 fw-normal"><?php echo $_SESSION['last_name'] ?></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-5 fw-normal">Date Of Birth</div>
                            <div class="col-1 text-center">:</div>
                            <div class="col-6 fw-normal"><?php echo $_SESSION['dob'] ?></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-5 fw-normal">Gender</div>
                            <div class="col-1 text-center">:</div>
                            <div class="col-6 fw-normal"><?php echo $_SESSION['gender'] ?></div>
                        </div>
                        <?php if($_SESSION['is_council_member'] !== true): ?>
                        <div class="row mb-2">
                            <div class="col-5 fw-normal">Area</div>
                            <div class="col-1 text-center">:</div>
                            <?php
                            require_once 'config/getArea.php'; // This will output the area name in the desired format
                            ?>  
                        </div>
                        <?php endif; ?>
                        <?php if($_SESSION['is_council_member'] !== true): ?>
                        <div class="row mb-2">
                            <div class="col-5 fw-normal">Interests</div>
                            <div class="col-1 text-center">:</div>
                            <div class="col-6 fw-normal"><?php if ($_SESSION['interests'] !== null && trim($_SESSION['interests']) !== ''){
                                echo $_SESSION['interests'];
                            } else {
                                echo "Not specified";
                            } ?></div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <script src="/culture-connect/assets/js/bootstrap.bundle.min.js"></script>

>
<?php include 'includes/footer.php'; ?>