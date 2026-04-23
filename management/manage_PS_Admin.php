<?php 
session_start();
if (!isset($_SESSION['is_council_member']) || $_SESSION['is_council_member'] !== true) {
    header("Location: ../index.php"); exit();
}

include '../includes/sidebar.php'; 
require_once '../config/connection.php';

// Pagination Configuration
$recordsPerPage = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $recordsPerPage;

// Get Total Records for Pagination
$totalResult = $conn->query("SELECT COUNT(*) as total FROM Products_Services");
$totalRecords = $totalResult->fetch_assoc()['total'];
$totalPages = ceil($totalRecords / $recordsPerPage);

// Fetch 10 Products with Business and Category info
$sql = "SELECT p.ProductID, p.Name, p.Price, s.Name as BusinessName, c.Name as CategoryName 
        FROM Products_Services p
        JOIN SME s ON p.SmeID = s.SmeID
        JOIN Category c ON p.CategoryID = c.CategoryID
        ORDER BY p.ProductID DESC
        LIMIT ? OFFSET ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $recordsPerPage, $offset);
$stmt->execute();
$result = $stmt->get_result();
?>

<div id="page-content-wrapper" class="flex-grow-1 p-5">
    <div class="container-fluid p-5">
        <h2 class="fw-bold mb-4">Product & Service Management</h2>

        <div class="table-responsive">
            <table class="table table-bordered border-dark align-middle bg-white">
                <thead>
                    <tr class="text-white">
                        <th style="background-color: #72b1e1 !important;">Product Name</th>
                        <th style="background-color: #72b1e1 !important;">Business</th>
                        <th style="background-color: #72b1e1 !important;">Category</th>
                        <th style="background-color: #72b1e1 !important;">Price</th>
                        <th style="background-color: #72b1e1 !important;" class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['Name']); ?></td>
                        <td><?php echo htmlspecialchars($row['BusinessName']); ?></td>
                        <td><?php echo htmlspecialchars($row['CategoryName']); ?></td>
                        <td>$<?php echo number_format($row['Price'], 2); ?></td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-outline-danger rounded-0" 
                                    onclick="confirmDelete(<?php echo $row['ProductID']; ?>, '<?php echo htmlspecialchars($row['Name']); ?>')">
                                Delete
                            </button>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-4">
            <nav aria-label="Product navigation">
                <ul class="pagination">
                    <li class="page-item <?php if($page <= 1) echo 'disabled'; ?>">
                        <a class="page-link border-dark text-dark rounded-0" href="?page=1">First</a>
                    </li>
                    <li class="page-item <?php if($page <= 1) echo 'disabled'; ?>">
                        <a class="page-link border-dark text-dark rounded-0" href="?page=<?php echo $page - 1; ?>">Prev</a>
                    </li>
                    <li class="page-item disabled">
                        <span class="page-link border-dark text-white bg-dark rounded-0">Page <?php echo $page; ?> of <?php echo $totalPages; ?></span>
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
    </div>
</div>

<script>
function confirmDelete(id, name) {
    if (confirm(`Are you sure you want to delete "${name}"? This will also remove all associated votes.`)) {
        window.location.href = "../processes/delete_product.php?id=" + id;
    }
}
</script>