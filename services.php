<?php 
session_start();
if (!isset($_SESSION['is_sme_member']) || $_SESSION['is_sme_member'] !== true) {
    header("Location: index.php");
    exit();
}

$pageTitle = "My Products & Services";
include 'includes/sidebar.php'; 
require_once 'config/connection.php';

$smeID = $_SESSION['sme_id']; // Ensure this is stored during login

// 1. Get total count for subheading
$countSql = "SELECT COUNT(*) as total FROM Products_Services WHERE SMEID = ?";
$stmtCount = $conn->prepare($countSql);
$stmtCount->bind_param("i", $smeID);
$stmtCount->execute();
$totalItems = $stmtCount->get_result()->fetch_assoc()['total'];

// 2. Handle Pagination Index
$currentIndex = isset($_GET['idx']) ? (int)$_GET['idx'] : 1;
if ($currentIndex < 1) $currentIndex = 1;
if ($currentIndex > $totalItems && $totalItems > 0) $currentIndex = $totalItems;

// 3. Fetch the specific product/service with Category Name
$offset = $currentIndex - 1;
$service = null;
if ($totalItems > 0) {
    // Joining Products_Services (p) with Category (c)
    $sql = "SELECT p.*, c.Name as CategoryName 
            FROM Products_Services p 
            LEFT JOIN Category c ON p.CategoryID = c.CategoryID 
            WHERE p.SmeID = ? 
            LIMIT 1 OFFSET ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $smeID, $offset);
    $stmt->execute();
    $service = $stmt->get_result()->fetch_assoc();
}
?>
<style>
.product-card {
        min-height: 600px; /* Slightly taller to account for the wider spread */
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    
 
    .product-body {
        flex-grow: 1;
    }

    .service-preview-container {
        height: 350px; 
        background-color: #f8f9fa;
        height: 300px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        border: 2px solid #72b1e1;
    }

    .service-preview-container img {
        max-height: 100%;
        object-fit: contain;
    }
</style>
<div id="page-content-wrapper" class="flex-grow-1 p-5">
    <div class="container-fluid" style="max-width: 1200px;">
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold"><?php echo htmlspecialchars($_SESSION['sme_name']); ?></h2>
                <h5 class="text-muted italic">Product <?php echo $currentIndex; ?> out of <?php echo $totalItems; ?></h5>
            </div>
            <button class="btn btn-dark rounded-0 fw-bold" data-bs-toggle="modal" data-bs-target="#serviceModal">+ ADD NEW</button>
        </div>

        <?php if ($service): ?>
        <div class="card border-2 border-dark rounded-0 p-4 product-card">
            
            <div class="product-body">
                <form action="processes/manage_services.php" method="POST" enctype="multipart/form-data" id="serviceForm">
                    <input type="hidden" name="service_id" value="<?php echo $service['ProductID']; ?>">
                    
                    <div class="row">
                        <div class="col-md-5">
                            <div class="service-preview-container p-2 mb-3">
                                <img src="<?php echo $service['Image'] ?: 'assets/img/placeholder.png'; ?>" 
                                     id="servicePreview" class="img-fluid">
                            </div>
                            <input type="file" name="service_image" id="serviceImageInput" class="d-none" accept="image/*">
                            <button type="button" class="btn btn-sm btn-dark rounded-0 w-100 mb-3" 
                                    onclick="document.getElementById('serviceImageInput').click()">Change Photo</button>
                        </div>

                        <div class="col-md-7">
                            <div class="mb-3">
                                <div class="d-flex justify-content-between">
                                    <label class="fw-bold">Name</label>
                                    <button type="button" class="btn btn-link btn-sm p-0 text-decoration-none edit-trigger">Edit</button>
                                </div>
                                <input type="text" name="name" value="<?php echo htmlspecialchars($service['Name']); ?>" class="form-control border-dark rounded-0 d-none">
                                <div class="field-text p-2 border border-transparent"><?php echo htmlspecialchars($service['Name']); ?></div>
                            </div>

                            <div class="mb-3">
                                <div class="d-flex justify-content-between">
                                    <label class="fw-bold">Description</label>
                                    <button type="button" class="btn btn-link btn-sm p-0 text-decoration-none edit-trigger">Edit</button>
                                </div>
                                <textarea name="description" class="form-control border-dark rounded-0 d-none" rows="4"><?php echo htmlspecialchars($service['Description']); ?></textarea>
                                <div class="field-text p-2 border border-transparent" style="min-height: 100px;"><?php echo htmlspecialchars($service['Description']); ?></div>
                            </div>

                            <div class="mb-3">
                                <div class="d-flex justify-content-between">
                                    <label class="fw-bold">Price (RM)</label>
                                    <button type="button" class="btn btn-link btn-sm p-0 text-decoration-none edit-trigger">Edit</button>
                                </div>
                                <input type="number" step="0.01" name="price" value="<?php echo $service['Price']; ?>" class="form-control border-dark rounded-0 d-none">
                                <div class="field-text p-2 border border-transparent"><?php echo number_format($service['Price'], 2); ?></div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <label class="fw-bold">Category</label>
                                <button type="button" class="btn btn-link btn-sm p-0 text-decoration-none edit-trigger">Edit</button>
                            </div>
                            <select name="category_id" class="form-control form-select border-dark rounded-0 d-none">
                                <?php 
                                    $current_cat = $service['CategoryID'] ?? ''; 
                                    include 'processes/load_catagories.php'; 
                                ?>
                            </select>
                            <div class="field-text p-2">
                                <?php echo htmlspecialchars($service['CategoryName'] ?? 'Uncategorized'); ?>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="product-footer border-top border-dark pt-3 mt-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="nav-btns">
                        <a href="?idx=<?php echo $currentIndex - 1; ?>" class="btn btn-outline-dark rounded-0 <?php echo $currentIndex <= 1 ? 'disabled' : ''; ?>">Previous</a>
                        <a href="?idx=<?php echo $currentIndex + 1; ?>" class="btn btn-outline-dark rounded-0 <?php echo $currentIndex >= $totalItems ? 'disabled' : ''; ?>">Next</a>
                    </div>
                    
                    <button type="submit" form="serviceForm" name="action" value="update" id="saveServiceBtn" 
                            class="btn btn-primary rounded-0 fw-bold d-none">SAVE ALL CHANGES</button>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<div class="modal fade" id="serviceModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-2 border-dark rounded-0">
            <form action="processes/manage_services.php" method="POST" enctype="multipart/form-data">
                <div class="modal-header"><h5>Add New Product/Service</h5></div>
                <div class="modal-body">
                    <input type="text" name="name" placeholder="Product Name" class="form-control mb-3 rounded-0" required>
                    
                    <select name="category" class="form-control form-select mb-3 rounded-0" required>
                        <option value="" disabled selected>Select Category</option>
                        <?php 
                            $current_cat = ""; // No default for new items
                            include 'processes/load_catagories.php'; 
                        ?>
                    </select>

                    <textarea name="description" placeholder="Description" class="form-control mb-3 rounded-0" required></textarea>
                    <input type="number" step="0.01" name="price" placeholder="Price" class="form-control mb-3 rounded-0" required>
                    <input type="file" name="image" class="form-control rounded-0">
                </div>
                <div class="modal-footer">
                    <button type="submit" name="action" value="insert" class="btn btn-dark rounded-0">Add Service</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="assets/js/bootstrap.bundle.min.js"></script>
<script>
// Toggle Edit Mode
document.querySelectorAll('.edit-trigger').forEach(button => {
    button.addEventListener('click', function() {
        const container = this.closest('.mb-3');
        // Added 'select' to the querySelector
        const input = container.querySelector('input, textarea, select');
        const span = container.querySelector('.field-text');
        
        if (input && span) {
            input.classList.remove('d-none');
            span.classList.add('d-none');
            document.getElementById('saveServiceBtn').classList.remove('d-none');
            this.classList.add('d-none'); 
        }
    });
});

// Preview Image and show Save button
document.getElementById('serviceImageInput').addEventListener('change', function(e) {
    const reader = new FileReader();
    reader.onload = function(event) {
        document.getElementById('servicePreview').src = event.target.result;
        document.getElementById('saveServiceBtn').classList.remove('d-none');
    };
    reader.readAsDataURL(e.target.files[0]);
});
</script>