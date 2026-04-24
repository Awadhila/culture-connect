<?php 
$pageTitle = "Explore - Culture Connect";
include 'includes/header.php'; 
include 'config/connection.php';
include 'processes/load_PS.php';
$currentFilters = $_GET; 
?>

<main class="py-5" style="background-color: #f1f8fc;">
    <div class="container">
        <div class="row">
            
            <aside class="col-lg-3 mb-4">
                <div class="filter-sidebar">
                    <h4 class="fw-bold mb-4" style="color: #72b1e1;">FILTERS</h4>
                    <form action="" method="GET" id="filterForm">
                        <div class="mb-4">
                            <label class="form-label fw-bold small">SEARCH BY NAME</label>
                            <input type="text" name="search" class="form-control border-2 border-dark rounded-0 mb-2" 
                                value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                            <button class="btn btn-dark w-100 rounded-0 fw-bold py-2" type="submit">SEARCH NOW</button>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold small">CATEGORY</label>
                            <select name="cat_id" class="form-select border-2 border-dark rounded-0">
                                <option value="">All Categories</option>
                                <?php 
                                    $current_cat = isset($_GET['cat_id']) ? $_GET['cat_id'] : '';
                                    include 'processes/load_catagories.php'; 
                                ?>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold small">TYPE</label>
                            <div class="form-check">
                                <input class="form-check-input border-dark" type="checkbox" name="type[]" value="products" 
                                    <?php echo (isset($_GET['type']) && in_array('products', (array)$_GET['type'])) ? 'checked' : ''; ?>>
                                <label class="form-check-label">Products</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input border-dark" type="checkbox" name="type[]" value="services" 
                                    <?php echo (isset($_GET['type']) && in_array('services', (array)$_GET['type'])) ? 'checked' : ''; ?>>
                                <label class="form-check-label">Services</label>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold small">PRICE RANGE (£)</label>
                            <div class="d-flex gap-2">
                                <input type="number" min="0" name="min" class="form-control border-2 border-dark rounded-0" placeholder="Min"
                                    value="<?php echo isset($_GET['min']) ? htmlspecialchars($_GET['min']) : ''; ?>">
                                <input type="number" min="0" name="max" class="form-control border-2 border-dark rounded-0" placeholder="Max"
                                    value="<?php echo isset($_GET['max']) ? htmlspecialchars($_GET['max']) : ''; ?>">
                            </div>
                        </div>

                        <?php 
                        $isLoggedIn = isset($_SESSION['user_id']);
                        $isCouncil = $_SESSION['is_council_member'] ?? false;
                        if ($isLoggedIn && !$isCouncil): 
                        ?>
                        <div class="mb-4 pt-3 border-top border-dark">
                            <div class="form-check form-switch">
                                <input class="form-check-input border-dark" type="checkbox" name="local_only" value="1" id="localSwitch"
                                    <?php echo (isset($_GET['local_only']) && $_GET['local_only'] == '1') ? 'checked' : ''; ?>>
                                <label class="form-check-label fw-bold small" for="localSwitch">ONLY MY AREA</label>
                            </div>
                        </div>
                        <?php endif; ?>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-dark rounded-0 fw-bold py-2">APPLY FILTERS</button>
                            <a href="products-and-services.php" class="btn btn-outline-dark rounded-0 fw-bold py-2 text-center text-decoration-none">RESET ALL</a>
                        </div>
                    </form>
                </div>
            </aside>

            <div class="col-lg-9">
                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 g-4">
                    
                    <?php if ($result && $result->num_rows > 0): ?>
                        <?php while($row = $result->fetch_assoc()): ?>

                            <div class="col">
                                <div class="card h-100 product-card">
                                    <img src="<?php echo htmlspecialchars($row['Image']); ?>" 
                                            class="card-img-top rounded-0 border-bottom border-dark" 
                                            alt="Item"
                                            style="height: 200px; object-fit: cover;"
                                            onerror="this.src='assets/img/no_PS.jpg'">
                                    <div class="card-body d-flex flex-column">
                                        <h6 class="fw-bold mb-1">
                                            <?php echo htmlspecialchars($row['Name']); ?>
                                        </h6>
                                        <p class="text-muted small mb-3">
                                            <?php echo htmlspecialchars($row['CategoryName']); ?>
                                        </p>
                                        <div class="d-flex justify-content-between align-items-center mt-auto">
                                            <span class="fw-bold" style="color: #72b1e1;">
                                                £<?php echo number_format($row['Price'], 2); ?>
                                            </span>
                                            <?php 
                                            // This line captures all current filters and adds the specific ID for the link
                                            $viewUrl = "view-item.php?" . http_build_query(array_merge($_GET, ['id' => $row['ProductID']])); 
                                            ?>
                                            <a href="view-item.php?id=<?php echo $viewUrl; ?>" 
                                            class="btn btn-sm btn-outline-dark rounded-0 px-3">
                                            View
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    <?php endwhile; ?>
                    <?php else: ?>
                        <div class="col">
                            <p class="text-center text-muted">No products or services available at the moment.</p>
                        </div>
                    <?php endif; ?>
                </div>

                <?php if ($totalPages > 1): ?>
                <nav class="mt-5">
                    <ul class="pagination justify-content-center">
                        
                        <?php 
                        // Get all current GET parameters (filters, search, etc.)
                        $params = $_GET; 
                        ?>

                        <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                            <?php 
                            $params['page'] = $page - 1;
                            $prevUrl = "?" . http_build_query($params);
                            ?>
                            <a class="page-link border-dark text-dark" href="<?php echo ($page <= 1) ? '#' : $prevUrl; ?>">PREV</a>
                        </li>

                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <?php 
                            $params['page'] = $i;
                            $pageUrl = "?" . http_build_query($params);
                            ?>
                            <li class="page-item <?php echo ($page == $i) ? 'active' : ''; ?>">
                                <a class="page-link <?php echo ($page == $i) ? 'bg-dark border-dark' : 'text-dark border-dark'; ?>" 
                                href="<?php echo $pageUrl; ?>">
                                <?php echo $i; ?>
                                </a>
                            </li>
                        <?php endfor; ?>

                        <li class="page-item <?php echo ($page >= $totalPages) ? 'disabled' : ''; ?>">
                            <?php 
                            $params['page'] = $page + 1;
                            $nextUrl = "?" . http_build_query($params);
                            ?>
                            <a class="page-link border-dark text-dark" href="<?php echo ($page >= $totalPages) ? '#' : $nextUrl; ?>">NEXT</a>
                        </li>
                    </ul>
                </nav>
                <?php endif; ?>
            </div>

        </div>
    </div>
</main>

<?php 
// Only close the connection at the very bottom of the page
if(isset($conn)) $conn->close(); 
include 'includes/footer.php'; 
?>
