<?php 
$pageTitle = "Item Details - Culture Connect";
include 'includes/header.php'; 
include 'config/connection.php';
include 'processes/read_vote.php';

?>
<main class="py-5" style="background-color: #f1f8fc;">
    <div class="container">
        <nav class="mb-4">
            <a href="products-and-services.php" class="text-dark text-decoration-none fw-bold">
                &larr; BACK TO EXPLORE
            </a>
        </nav>

        <div class="row g-5">
            <div class="col-md-6">
                <div class="border border-2 border-dark bg-white p-2">
                    <img src="<?php echo htmlspecialchars($item['Image']); ?>" 
                         class="img-fluid w-100" 
                         alt="<?php echo htmlspecialchars($item['Name']); ?>"
                         onerror="this.src='assets/img/no_PS.jpg';"
                         style="max-height: 500px; object-fit: contain;">
                </div>
            </div>

            <div class="col-md-6">
                <div class="ps-md-4">
                    <span class="badge bg-dark rounded-0 mb-2"><?php echo htmlspecialchars($item['CategoryName']); ?></span>
                    <h1 class="fw-bold mb-1"><?php echo htmlspecialchars($item['Name']); ?></h1>
                    <p class="text-muted mb-4">
                        Provided by: <strong><?php echo htmlspecialchars($item['SmeName']); ?></strong> 
                        In <strong><?php echo htmlspecialchars($item['AreaName']); ?></strong>
                    </p>                    
                    <h2 class="fw-bold mb-4" style="color: #72b1e1;">
                        £<?php echo number_format($item['Price'], 2); ?>
                    </h2>

                    <div class="mb-5">
                        <h5 class="fw-bold text-uppercase small border-bottom border-dark pb-2 mb-3">Description</h5>
                        <p class="text-secondary"><?php echo nl2br(htmlspecialchars($item['Description'])); ?></p>
                    </div>

                    <div class="d-grid gap-3">
                        <?php 
                        $isLoggedIn = isset($_SESSION['user_id']);
                        $isCouncil  = $_SESSION['is_council_member'] ?? false;
                        $isSme      = $_SESSION['is_sme_member'] ?? false;
                        $userArea   = $_SESSION['area_id'] ?? null;
                        $isSameArea = ($userArea !== null && $userArea == $item['AreaID']);
                        $hasVoted   = ($item['UserHasVoted'] > 0);
                        $itemLabel  = strtoupper($item['CategoryType']); 

                        // 1. GUEST CHECK: If not logged in, show "Log In To Vote"
                        if (!$isLoggedIn): ?>
                            <h5 class="fw-bold text-center mb-0">VOTE FOR THIS <?php echo $itemLabel; ?></h5>
                            <a href="authentication-registration/signin.php" class="btn btn-outline-dark btn-lg rounded-0 fw-bold py-3">
                                LOG IN TO VOTE
                            </a>

                        <?php 
                        // 2. ELIGIBILITY CHECK: Only Residents in the same area can vote
                        elseif (!$isCouncil && !$isSme && $isSameArea): ?>
                            
                            <h5 class="fw-bold text-center mb-0">VOTE FOR THIS <?php echo $itemLabel; ?></h5>

                            <?php if ($hasVoted): ?>
                                <button class="btn btn-success btn-lg rounded-0 fw-bold py-3 disabled" style="opacity: 0.8;">
                                    <?php echo $itemLabel; ?> VOTED
                                </button>
                            <?php else: ?>
                                <div class="row g-2">
                                    <div class="col-6">
                                        <button class="btn btn-custom-blue btn-lg rounded-0 fw-bold py-3 w-100 btn-vote-yes" 
                                                onclick="castVote(<?php echo $itemID; ?>, 1)">
                                            YES
                                        </button>
                                    </div>
                                    <div class="col-6">
                                        <button class="btn btn-custom-blue btn-lg rounded-0 fw-bold py-3 w-100 btn-vote-no" 
                                                onclick="castVote(<?php echo $itemID; ?>, 0)">
                                            NO
                                        </button>
                                    </div>
                                </div>
                            <?php endif; ?>

                        <?php 
                        // 3. RESTRICTION MESSAGE (Optional): If logged in but wrong area or role
                        elseif ($isLoggedIn && !$isSameArea && !$isCouncil && !$isSme): ?>
                            <p class="text-danger fw-bold text-center small">Voting is only available for residents of <?php echo htmlspecialchars($item['AreaName']); ?>.</p>
                        <?php endif; ?>

                        <button class="btn btn-outline-dark btn-lg rounded-0 fw-bold py-3">CONTACT SME</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-5 pt-5">
            <div class="col-12">
                <div class="p-4 border border-2 border-dark bg-white">
                    <h4 class="fw-bold">About the Creator: <?php echo htmlspecialchars($item['SmeName']); ?></h4>
                    <p class="mb-0"><?php echo htmlspecialchars($item['SmeBio']); ?></p>
                </div>
            </div>
        </div>
    </div>
</main>
<script src="./assets/js/cast-vote.js"></script>
<?php 
$stmt->close();
$conn->close();
include 'includes/footer.php'; 
?>