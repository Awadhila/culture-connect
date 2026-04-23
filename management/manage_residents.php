<?php 
session_start();
if (!isset($_SESSION['is_council_member']) || $_SESSION['is_council_member'] !== true) {
    header("Location: ../index.php");
    exit();
}

$pageTitle = "Manage Residents - Culture Connect";
include '../includes/sidebar.php'; 
require_once '../config/connection.php';

// --- PAGINATION LOGIC ---
$limit = 10; // Records per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

// Get total count of residents (Users not in SME_Members)
$countSql = "SELECT COUNT(*) as total FROM Users WHERE UserID NOT IN (SELECT UserID FROM SME_Members)";
$totalResult = $conn->query($countSql);
$totalRecords = $totalResult->fetch_assoc()['total'];
$totalPages = ceil($totalRecords / $limit);
?>

<div id="page-content-wrapper" class="flex-grow-1 p-5">
    <div class="container-fluid p-5">
        <h2 class="fw-bold mb-4">Manage Residents</h2>

        <div class="table-responsive">
            <table class="table table-bordered border-dark align-middle">
                <thead>
                    <tr class="text-white">
                        <th style="background-color: #72b1e1 !important;">Name</th>
                        <th style="background-color: #72b1e1 !important;">Email</th>
                        <th style="background-color: #72b1e1 !important;">Gender</th>
                        <th style="background-color: #72b1e1 !important;">DOB</th>
                        <th style="background-color: #72b1e1 !important;">Area</th>
                        <th style="background-color: #72b1e1 !important;">Interests</th>
                        <th style="background-color: #72b1e1 !important;" class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // SQL with LIMIT and OFFSET for pagination
                    $sql = "SELECT u.UserID, u.First_Name, u.Last_Name, u.Email, u.Gender, u.Date_Of_Birth, u.Interests, a.Name as AreaName 
                            FROM Users u
                            JOIN Area a ON u.AreaID = a.AreaID
                            WHERE u.UserID NOT IN (SELECT UserID FROM SME_Members)
                            ORDER BY u.Last_Name ASC
                            LIMIT ? OFFSET ?";
                    
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("ii", $limit, $offset);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0):
                        while ($row = $result->fetch_assoc()):
                    ?>
                    <tr class="bg-white">
                        <td class="fw-bold"><?php echo htmlspecialchars($row['First_Name'] . ' ' . $row['Last_Name']); ?></td>
                        <td><?php echo htmlspecialchars($row['Email']); ?></td>
                        <td><?php echo htmlspecialchars($row['Gender']); ?></td>
                        <td><?php echo date('d M Y', strtotime($row['Date_Of_Birth'])); ?></td>
                        <td><?php echo htmlspecialchars($row['AreaName']); ?></td>
                        <td style="max-width: 200px;">
                            <small class="text-muted"><?php echo htmlspecialchars($row['Interests']); ?></small>
                        </td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-outline-danger rounded-0" 
                                    onclick="confirmDelete(<?php echo $row['UserID']; ?>, '<?php echo htmlspecialchars($row['First_Name']); ?>')">
                                Delete
                            </button>
                        </td>
                    </tr>
                    <?php endwhile; else: ?>
                    <tr><td colspan="7" class="text-center py-4">No residents found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if ($totalPages > 1): ?>
        <div class="d-flex justify-content-center mt-4">
            <nav aria-label="Resident navigation">
                <ul class="pagination">
                    <li class="page-item <?php if($page <= 1) echo 'disabled'; ?>">
                        <a class="page-link border-dark text-dark rounded-0" href="?page=1">First</a>
                    </li>
                    
                    <li class="page-item <?php if($page <= 1) echo 'disabled'; ?>">
                        <a class="page-link border-dark text-dark rounded-0" href="?page=<?php echo $page - 1; ?>">Prev</a>
                    </li>

                    <li class="page-item disabled">
                        <span class="page-link border-dark text-white bg-dark rounded-0">
                            Page <?php echo $page; ?> of <?php echo $totalPages; ?>
                        </span>
                    </li>

                    <li class="page-item <?php if($page >= $totalPages) echo 'disabled'; ?>">
                        <a class="page-link border-dark text-dark rounded-0" href="?page=<?php echo $page + 1; ?>">Next</a>
                    </li>

                    <li class="page-item <?php if($page >= $totalPages) echo 'disabled'; ?>">
                        <a class="page-link border-dark text-dark rounded-0" href="?page=<?php echo $totalPages; ?>">Last</a>
                    </li>
                </ul>
            </nav>
        </div>
        <?php endif; ?>
    </div>
</div>

<script>
function confirmDelete(id, name) {
    if (confirm("Are you sure you want to delete resident " + name + "? This will also remove all their votes permanently.")) {
        window.location.href = "../processes/delete_resident.php?delete_id=" + id;
    }
}
</script>