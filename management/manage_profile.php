<?php 
session_start();
$pageTitle = "Profile - Culture Connect";
include '../includes/sidebar.php'; 
require_once '../config/connection.php';

// Check if user has already voted to determine if Area can be changed
$userID = $_SESSION['user_id'];
$voteCheckSql = "SELECT COUNT(*) as vote_count FROM Votes WHERE UserID = ?";
$stmtVote = $conn->prepare($voteCheckSql);
$stmtVote->bind_param("i", $userID);
$stmtVote->execute();
$hasVoted = ($stmtVote->get_result()->fetch_assoc()['vote_count'] > 0);
?>

<div id="page-content-wrapper" class="flex-grow-1 p-5">
    <div class="container-fluid p-5">
        <h2 class="fw-bold">My Profile</h2>
    </div>
    
    <form action="../processes/update_profile.php" method="POST" enctype="multipart/form-data">
        <div class="row align-items-start">
            <div class="col-md-5">
                <div style="border: 3px solid #72b1e1; padding: 5px; position: relative;">
                    <img src="../<?php echo $_SESSION['profile_image'] ?>" id="profilePreview" alt="Profile Picture" class="img-fluid" style="display: block; width: 100%;">
                    <div class="mt-2">
                        <input type="file" name="profile_image" id="profileInput" class="form-control border-2 border-dark rounded-0 d-none" accept="image/*">
                        <button type="button" class="btn btn-sm btn-dark rounded-0 w-100 mt-2 edit-btn" onclick="document.getElementById('profileInput').classList.toggle('d-none')">Change Photo</button>
                    </div>
                </div>
            </div>

            <div class="col-md-7 ps-4">
                <h3 class="fw-bold text-uppercase mb-3" style="letter-spacing: 1px;">Profile Information</h3>
                
                <div class="profile-details fs-5">
                    <div class="row mb-3 align-items-center">
                        <div class="col-4 fw-normal">First Name</div>
                        <div class="col-1 text-center">:</div>
                        <div class="col-5">
                            <input type="text" name="first_name" class="form-control border-2 border-dark rounded-0 d-none" value="<?php echo $_SESSION['first_name'] ?>">
                            <span class="field-text"><?php echo $_SESSION['first_name'] ?></span>
                        </div>
                        <div class="col-2"><button type="button" class="btn btn-sm btn-outline-primary rounded-0 edit-trigger">Edit</button></div>
                    </div>

                    <div class="row mb-3 align-items-center">
                        <div class="col-4 fw-normal">Last Name</div>
                        <div class="col-1 text-center">:</div>
                        <div class="col-5">
                            <input type="text" name="last_name" class="form-control border-2 border-dark rounded-0 d-none" value="<?php echo $_SESSION['last_name'] ?>">
                            <span class="field-text"><?php echo $_SESSION['last_name'] ?></span>
                        </div>
                        <div class="col-2"><button type="button" class="btn btn-sm btn-outline-primary rounded-0 edit-trigger">Edit</button></div>
                    </div>

                    <div class="row mb-3 align-items-center">
                        <div class="col-4 fw-normal">Date Of Birth</div>
                        <div class="col-1 text-center">:</div>
                        <div class="col-5">
                            <input type="date" name="dob" class="form-control border-2 border-dark rounded-0 d-none" value="<?php echo $_SESSION['dob'] ?>">
                            <span class="field-text"><?php echo $_SESSION['dob'] ?></span>
                        </div>
                        <div class="col-2"><button type="button" class="btn btn-sm btn-outline-primary rounded-0 edit-trigger">Edit</button></div>
                    </div>

                    <div class="row mb-3 align-items-center">
                        <div class="col-4 fw-normal">Gender</div>
                        <div class="col-1 text-center">:</div>
                        <div class="col-5">
                            <div class="gender-inputs d-none">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input border-dark" type="radio" name="gender" value="Male" <?php echo ($_SESSION['gender'] == 'Male') ? 'checked' : ''; ?>>
                                    <label class="form-check-label small">Male</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input border-dark" type="radio" name="gender" value="Female" <?php echo ($_SESSION['gender'] == 'Female') ? 'checked' : ''; ?>>
                                    <label class="form-check-label small">Female</label>
                                </div>
                            </div>
                            <span class="field-text"><?php echo $_SESSION['gender'] ?></span>
                        </div>
                        <div class="col-2"><button type="button" class="btn btn-sm btn-outline-primary rounded-0 edit-trigger">Edit</button></div>
                    </div>

                    <?php if($_SESSION['is_council_member'] !== true): ?>
                    <div class="row mb-3 align-items-center">
                        <div class="col-4 fw-normal">Area</div>
                        <div class="col-1 text-center">:</div>
                        <div class="col-5">
                            <?php if ($hasVoted): ?>
                                <input type="hidden" name="area_id" value="<?php echo $_SESSION['area_id']; ?>">
                                
                                <span class="field-text d-block">
                                    <?php require '../processes/getArea.php'; ?>
                                </span>
                                
                                <div class="text-muted small italic mt-1" style="font-size: 0.75rem; line-height: 1;">
                                    Cannot change area after voting
                                </div>

                            <?php else: ?>
                                <select name="area_id" class="form-select border-2 border-dark rounded-0 d-none">
                                    <?php 
                                    // Use your connection to fetch areas
                                    $areaSql = "SELECT AreaID, Name FROM Area ORDER BY Name ASC";
                                    $areaResult = $conn->query($areaSql);
                                    while($area = $areaResult->fetch_assoc()) {
                                        $selected = ($area['AreaID'] == $_SESSION['area_id']) ? 'selected' : '';
                                        echo "<option value='{$area['AreaID']}' $selected>{$area['Name']}</option>";
                                    }
                                    ?>
                                </select>
                                <span class="field-text">
                                    <?php require '../processes/getArea.php'; ?>
                                </span>
                            <?php endif; ?>
                        </div>
                        <div class="col-2">
                            <?php if (!$hasVoted): ?>
                                <button type="button" class="btn btn-sm btn-outline-primary rounded-0 edit-trigger">Edit</button>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    <?php if($_SESSION['is_council_member'] !== true && $_SESSION['is_sme_member'] !== true): ?>
                    <div class="row mb-3 align-items-center">
                        <div class="col-4 fw-normal">Interests</div>
                        <div class="col-1 text-center">:</div>
                        <div class="col-5">
                            <select name="interests" class="form-select border-2 border-dark rounded-0 d-none">
                                <option value="">Select an Interest Category</option>
                                <?php 
                                    // Set current_cat to Name for the 'selected' check in load_catagories.php
                                    $current_cat = $_SESSION['interests'] ?? ''; 
                                    include '../processes/load_catagories.php'; 
                                ?>
                            </select>

                            <span class="field-text">
                                <?php 
                                if (!empty($_SESSION['interests'])) {
                                    echo htmlspecialchars($_SESSION['interests']);
                                } else {
                                    // Display "No Interests" if the field is null or empty
                                    echo '<span class="text-muted italic">No Interests</span>';
                                }
                                ?>
                            </span>
                        </div>
                        <div class="col-2">
                            <button type="button" class="btn btn-sm btn-outline-primary rounded-0 edit-trigger">Edit</button>
                        </div>
                    </div>
                    <?php endif; ?>
                    <?php if (isset($_SESSION['is_sme_member']) && $_SESSION['is_sme_member'] === true): ?>
                    <div class="row mb-3 align-items-center">
                        <div class="col-4 fw-normal">Business Name</div>
                        <div class="col-1 text-center">:</div>
                        <div class="col-5">
                            <input type="text" name="sme_name" class="form-control border-2 border-dark rounded-0 d-none" 
                                value="<?php echo htmlspecialchars($_SESSION['sme_name'] ?? ''); ?>">
                            <span class="field-text"><?php echo htmlspecialchars($_SESSION['sme_name'] ?? 'Not set'); ?></span>
                        </div>
                        <div class="col-2">
                            <button type="button" class="btn btn-sm btn-outline-primary rounded-0 edit-trigger">Edit</button>
                        </div>
                    </div>

                    <div class="row mb-3 align-items-center">
                        <div class="col-4 fw-normal">Business Email</div>
                        <div class="col-1 text-center">:</div>
                        <div class="col-5">
                            <input type="email" name="sme_email" class="form-control border-2 border-dark rounded-0 d-none" 
                                value="<?php echo htmlspecialchars($_SESSION['sme_email'] ?? ''); ?>">
                            <span class="field-text"><?php echo htmlspecialchars($_SESSION['sme_email'] ?? 'Not set'); ?></span>
                        </div>
                        <div class="col-2">
                            <button type="button" class="btn btn-sm btn-outline-primary rounded-0 edit-trigger">Edit</button>
                        </div>
                    </div>
                    <div class="row mb-3 align-items-center">
                        <div class="col-4 fw-normal">Products & Services</div>
                        <div class="col-1 text-center">:</div>
                        <div class="col-5">
                            <?php
                            // Query to count services for this specific SME
                            $smeID = $_SESSION['sme_id']; // Ensure this is set in your login/session logic
                            $countSql = "SELECT COUNT(*) as total FROM Products_Services WHERE SMEID = ?";
                            $stmtCount = $conn->prepare($countSql);
                            $stmtCount->bind_param("i", $smeID);
                            $stmtCount->execute();
                            $serviceCount = $stmtCount->get_result()->fetch_assoc()['total'];
                            ?>
                            <span class="badge bg-dark rounded-0 fs-6"><?php echo $serviceCount; ?> Items Listed</span>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-10 pb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label class="fw-bold text-uppercase small" >Business Bio</label>
                            </div>
                        </div>
                        <div class="col-2 ps-3">
                            <button type="button" class="btn btn-sm btn-outline-primary rounded-0 edit-trigger">Edit</button>
                        </div>
                        <div class="col-11">
                                <div class="field-text p-3 border border-2 border-dark bg-light" style="min-height: 120px; white-space: pre-wrap;"><?php echo htmlspecialchars($_SESSION['sme_bio'] ?? 'No bio available.'); ?></div>
                                <textarea name="sme_bio" claas= "justify-content-start align-items-start" 
                                        class="form-control border-2 border-dark rounded-0 d-none" 
                                        rows="5" 
                                        placeholder="Describe your business..."><?php echo htmlspecialchars($_SESSION['sme_bio'] ?? ''); ?>
                                </textarea>
                        </div>
                    </div>
                <?php endif; ?>
                    <div class="row mt-4">
                        <div class="col-10">
                            <button type="submit" id="saveChanges" class="btn btn-dark rounded-0 fw-bold w-100 d-none">SAVE ALL CHANGES</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script src="../assets/js/update_profile.js"></script>
<?php   
if (isset($conn)) {
    $conn->close(); 
}
?>