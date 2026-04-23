<?php 
session_start();
$pageTitle = "Voting Reports - Culture Connect";
include '../includes/sidebar.php'; 
require_once '../config/connection.php';

$userID = $_SESSION['user_id'];
$isCouncil = $_SESSION['is_council_member'] ?? false;
$isSme = $_SESSION['is_sme_member'] ?? false;
$userArea = $_SESSION['area_id'];

// Determine View Mode
$view = 'resident';
if ($isCouncil) $view = 'council';
elseif ($isSme) $view = 'sme';

// General Pagination Setup
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
?>

<div id="page-content-wrapper" class="flex-grow-1 p-5">
    <div class="container-fluid d-flex flex-column" style="min-height: 85vh;">
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">Voting Dashboard</h2>
            <span class="badge p-2 px-3" style="background-color: #72b1e1;">Role: <?php echo ucfirst($view); ?></span>
        </div>

        <div class="flex-grow-1">

<?php if ($view == 'resident'): ?>
                <?php 
                    $limit = 8;
                    $offset = ($page - 1) * $limit;
                    $countSql = "SELECT COUNT(*) as total FROM Votes WHERE UserID = ?";
                    $stmtC = $conn->prepare($countSql);
                    $stmtC->bind_param("i", $userID);
                    $stmtC->execute();
                    $totalItems = $stmtC->get_result()->fetch_assoc()['total'];
                    $totalPages = ceil($totalItems / $limit);
                ?>
                <div style="min-height: 580px;">
                    <div class="card border-0 shadow-sm rounded-0 overflow-hidden border border-dark">
                        <table class="table table-hover mb-0">
                            <thead style="background-color: #72b1e1;" class="text-white">
                                <tr>
                                    <th class="p-3 fw-bold text-uppercase" style="letter-spacing: 1px; border-bottom: 2px solid #5a90b8;">Product/Service</th>
                                    <th class="p-3 fw-bold text-uppercase text-center" style="letter-spacing: 1px; border-bottom: 2px solid #5a90b8;">My Vote</th>
                                    <th class="p-3 fw-bold text-uppercase text-center" style="letter-spacing: 1px; border-bottom: 2px solid #5a90b8;">Date Voted</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql = "SELECT v.*, ps.Name FROM Votes v 
                                        JOIN Products_Services ps ON v.ProductID = ps.ProductID 
                                        WHERE v.UserID = ? ORDER BY v.Vote_Date DESC LIMIT ? OFFSET ?";
                                $stmt = $conn->prepare($sql);
                                $stmt->bind_param("iii", $userID, $limit, $offset);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                while($row = $result->fetch_assoc()): ?>
                                    <tr class="align-middle">
                                        <td class="p-3 fw-semibold"><?php echo htmlspecialchars($row['Name']); ?></td>
                                        <td class="p-3 text-center">
                                            <span class="badge <?php echo $row['Vote_Value'] == 1 ? 'bg-success' : 'bg-danger'; ?> rounded-0 px-3 py-2">
                                                <?php echo $row['Vote_Value'] == 1 ? 'YES' : 'NO'; ?>
                                            </span>
                                        </td>
                                        <td class="p-3 text-center text-muted"><?php echo date('d M Y', strtotime($row['Vote_Date'])); ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            <?php elseif ($view == 'sme'): ?>
                <?php 
                    $limit = 4;
                    $offset = ($page - 1) * $limit;
                    $smeID = $_SESSION['sme_id'];
                    $countSql = "SELECT COUNT(*) as total FROM Products_Services WHERE SmeID = ?";
                    $stmtC = $conn->prepare($countSql);
                    $stmtC->bind_param("i", $smeID);
                    $stmtC->execute();
                    $totalItems = $stmtC->get_result()->fetch_assoc()['total'];
                    $totalPages = ceil($totalItems / $limit);
                ?>
                <div style="min-height: 450px;"> <div class="row g-4">
                        <?php
                        // CHANGE 2: Fetch all attributes for the product/service
                        $sql = "SELECT ps.*, 
                                SUM(CASE WHEN v.Vote_Value = 1 THEN 1 ELSE 0 END) as yes_count,
                                SUM(CASE WHEN v.Vote_Value = 0 THEN 1 ELSE 0 END) as no_count
                                FROM Products_Services ps
                                LEFT JOIN Votes v ON ps.ProductID = v.ProductID
                                WHERE ps.SmeID = ? GROUP BY ps.ProductID LIMIT ? OFFSET ?";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("iii", $smeID, $limit, $offset);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        while($row = $result->fetch_assoc()): 
                            // Determine image path (with fallback)
                            $imagePath = !empty($row['Image']) ? '../' . $row['Image'] : '../assets/img/no_PS.jpg';
                        ?>
                            <div class="col-md-6">
                                <div class="card border-dark rounded-0 h-100 shadow-sm overflow-hidden">
                                    <div class="row g-0 h-100">
                                        <div class="col-4 position-relative bg-light border-end border-dark">
                                            <img src="<?php echo htmlspecialchars($imagePath); ?>" 
                                                 alt="<?php echo htmlspecialchars($row['Name']); ?>" 
                                                 class="img-fluid h-100 w-100 object-fit-cover">
                                            <span class="position-absolute top-0 start-0 badge bg-dark rounded-0 m-2">
                                                £<?php echo number_format($row['Price'], 2); ?>
                                            </span>
                                        </div>
                                        <div class="col-8 d-flex flex-column">
                                            <div class="card-body p-3 flex-grow-1">
                                                <h5 class="fw-bold mb-1 text-truncate"><?php echo htmlspecialchars($row['Name']); ?></h5>
                                                <p class="small text-muted mb-0 text-truncate-2" style="height: 40px;">
                                                    <?php echo htmlspecialchars($row['Description']); ?>
                                                </p>
                                            </div>
                                            <div class="card-footer bg-white border-top border-dark-subtle p-3 mt-auto">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span class="text-success fw-bold fs-5">YES: <?php echo $row['yes_count']; ?></span>
                                                    <span class="text-danger fw-bold fs-5">NO: <?php echo $row['no_count']; ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>

            <?php elseif ($view == 'council'): ?>
                <?php 
                    $limit = 3;
                    $offset = ($page - 1) * $limit;
                    $selectedSme = $_GET['sme_id'] ?? 0;
                    $totalResidents = 1; 
                    $areaName = "Select an SME";

                    if ($selectedSme) {
                        $areaQ = "SELECT a.Name, a.AreaID FROM Area a JOIN SME s ON a.AreaID = s.AreaID WHERE s.SmeID = ?";
                        $stArea = $conn->prepare($areaQ);
                        $stArea->bind_param("i", $selectedSme);
                        $stArea->execute();
                        $aRes = $stArea->get_result()->fetch_assoc();
                        if ($aRes) {
                            $areaName = $aRes['Name'];
                            $popQ = "SELECT COUNT(*) as total FROM Users WHERE AreaID = ? 
                                     AND UserID NOT IN (SELECT UserID FROM Council_Members) 
                                     AND UserID NOT IN (SELECT UserID FROM SME_Members)";
                            $stPop = $conn->prepare($popQ);
                            $stPop->bind_param("i", $aRes['AreaID']);
                            $stPop->execute();
                            $totalResidents = $stPop->get_result()->fetch_assoc()['total'] ?: 1;
                        }
                    }
                ?>
                <div class="bg-white p-4 border border-dark mb-4 shadow-sm">
                    <form method="GET" class="row align-items-center">
                        <div class="col-md-7">
                            <select name="sme_id" class="form-select rounded-0 border-dark" onchange="this.form.submit()">
                                <option value="">--- Choose SME ---</option>
                                <?php
                                $smes = $conn->query("SELECT SmeID, Name FROM SME ORDER BY Name ASC");
                                while($s = $smes->fetch_assoc()) {
                                    $sel = ($selectedSme == $s['SmeID']) ? 'selected' : '';
                                    echo "<option value='{$s['SmeID']}' $sel>{$s['Name']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-5 text-end text-muted small">Viewing Area: <strong><?php echo htmlspecialchars($areaName); ?></strong></div>
                    </form>
                </div>

                <div style="min-height: 500px;">
                    <?php if ($selectedSme): 
                        $countSql = "SELECT COUNT(*) as total FROM Products_Services WHERE SmeID = ?";
                        $stmtC = $conn->prepare($countSql);
                        $stmtC->bind_param("i", $selectedSme);
                        $stmtC->execute();
                        $totalItems = $stmtC->get_result()->fetch_assoc()['total'];
                        $totalPages = ceil($totalItems / $limit);

                        $sql = "SELECT ps.Name, 
                                SUM(CASE WHEN v.Vote_Value = 1 THEN 1 ELSE 0 END) as yes_count,
                                SUM(CASE WHEN v.Vote_Value = 0 THEN 1 ELSE 0 END) as no_count
                                FROM Products_Services ps
                                LEFT JOIN Votes v ON ps.ProductID = v.ProductID
                                WHERE ps.SmeID = ? GROUP BY ps.ProductID LIMIT ? OFFSET ?";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("iii", $selectedSme, $limit, $offset);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        while($row = $result->fetch_assoc()): 
                            $tv = $row['yes_count'] + $row['no_count'];
                            $reach = ($totalResidents > 0) ? ($tv / $totalResidents) * 100 : 0;
                            $yP = ($tv > 0) ? ($row['yes_count'] / $tv) * 100 : 0;
                            $nP = ($tv > 0) ? ($row['no_count'] / $tv) * 100 : 0;
                    ?>
                        <div class="card border-dark rounded-0 p-4 mb-3">
                            <h5 class="fw-bold"><?php echo htmlspecialchars($row['Name']); ?></h5>
                            <p class="small text-muted">Area Reach: <?php echo round($reach, 1); ?>% (<?php echo $tv; ?> Votes / <?php echo $totalResidents; ?> Residents in <?php echo $areaName; ?>)</p>
                            <div class="progress rounded-0 border border-dark" style="height: 30px;">
                                <div class="progress-bar bg-success" style="width:<?php echo $yP; ?>%"><?php echo $row['yes_count']; ?> YES</div>
                                <div class="progress-bar bg-danger" style="width:<?php echo $nP; ?>%"><?php echo $row['no_count']; ?> NO</div>
                            </div>
                        </div>
                    <?php endwhile; endif; ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="mt-auto py-4">
            <?php if (isset($totalPages) && $totalPages > 1): ?>
                <nav>
                    <ul class="pagination justify-content-center mb-0">
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                                <a class="page-link border-dark text-dark" href="?<?php echo isset($selectedSme) ? 'sme_id='.$selectedSme.'&' : ''; ?>page=<?php echo $i; ?>"><?php echo $i; ?></a>
                            </li>
                        <?php endfor; ?>
                    </ul>
                </nav>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $conn->close(); ?>