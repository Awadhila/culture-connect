<?php 
session_start();
if (!isset($_SESSION['is_council_member']) || $_SESSION['is_council_member'] !== true) {
    header("Location: ../index.php"); exit();
}

include '../includes/sidebar.php'; 
require_once '../config/connection.php';

// Pagination Logic
$index = isset($_GET['index']) ? (int)$_GET['index'] : 0;
if ($index < 0) $index = 0;

// Get total count for navigation limits
$totalRes = $conn->query("SELECT COUNT(*) as total FROM SME");
$totalSmes = $totalRes->fetch_assoc()['total'];

// Fetch specific SME and its Manager
$sql = "SELECT s.*, a.Name as AreaName, u.First_Name, u.Last_Name, u.Email as MgrEmail, u.Date_Of_Birth as dob, u.Gender, u.UserID as MgrID, s.Email as SmeEmail
        FROM SME s
        JOIN Area a ON s.AreaID = a.AreaID
        JOIN SME_Members sm ON s.SmeID = sm.SmeID
        JOIN Users u ON sm.UserID = u.UserID
        WHERE sm.Member_Type = 'Manager'
        LIMIT 1 OFFSET $index";

$result = $conn->query($sql);
$sme = $result->fetch_assoc();
?>

<div id="page-content-wrapper" class="flex-grow-1 p-5">
    <div class="container-fluid p-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">SME Management Profile</h2>
            <div class="btn-group">
                <a href="?index=<?php echo $index - 1; ?>" class="btn btn-outline-dark rounded-0 <?php echo ($index <= 0) ? 'disabled' : ''; ?>">Previous</a>
                <span class="btn btn-dark disabled rounded-0"><?php echo ($index + 1) . " of " . $totalSmes; ?></span>
                <a href="?index=<?php echo $index + 1; ?>" class="btn btn-outline-dark rounded-0 <?php echo ($index >= $totalSmes - 1) ? 'disabled' : ''; ?>">Next</a>
            </div>
        </div>

        <?php if ($sme): ?>
        <div class="card border-dark rounded-0 shadow-sm">
            <div class="card-header bg-dark text-white rounded-0">
                <h4 class="mb-0"><?php echo htmlspecialchars($sme['Name']); ?></h4>
            </div>
            <div class="card-body p-4">
                <div class="row">
                    <div class="col-md-6 border-end">
                        <h5 class="text-primary fw-bold">Business Information</h5>
                        <p><strong>Email:</strong> <?php echo htmlspecialchars($sme['SmeEmail']); ?></p>
                        <p><strong>Location:</strong> <?php echo htmlspecialchars($sme['AreaName']); ?></p>
                        <div class="card border-4 border-dark rounded-0 p-4 bg-white">
                            <p class="text-justify fw-semibold" style="line-height: 1.6;">
                                <?php echo htmlspecialchars($sme['Bio']); ?>
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6 ps-md-4">
                        <h5 class="text-success fw-bold">Manager Details</h5>
                        <p><strong>Name:</strong> <?php echo htmlspecialchars($sme['First_Name'] . ' ' . $sme['Last_Name']); ?></p>
                        <p><strong>Email:</strong> <?php echo htmlspecialchars($sme['MgrEmail']); ?></p>
                        <p><strong>Date of Birth:</strong> <?php echo htmlspecialchars($sme['dob']); ?></p>
                        <p><strong>Gender:</strong> <?php echo htmlspecialchars($sme['Gender']); ?></p>

                        <div class="mt-5 pt-4 border-top">
                            <button class="btn btn-danger w-100 rounded-0 fw-bold" 
                                    onclick="confirmDelete(<?php echo $sme['SmeID']; ?>, <?php echo $sme['MgrID']; ?>, '<?php echo addslashes($sme['Name']); ?>')">
                                DELETE BUSINESS & MANAGER ACCOUNT
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php else: ?>
            <div class="alert alert-info">No SMEs found in the database.</div>
        <?php endif; ?>
    </div>
</div>

<script>
function confirmDelete(smeId, mgrId, name) {
    const warning = `CRITICAL ACTION: \n\nDeleting "${name}" will permanently remove:\n` +
                    `- The Business Profile\n` +
                    `- All associated Products and Services\n` +
                    `- All customer votes for those products\n` +
                    `- The Manager's User Account\n\n` +
                    `Do you want to proceed?`;
    
    if (confirm(warning)) {
        window.location.href = `../processes/delete_sme.php?sme_id=${smeId}&mgr_id=${mgrId}`;
    }
}
</script>