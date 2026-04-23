<?php 
session_start();
// Restrict access to council members only as per sidebar logic
if (!isset($_SESSION['is_council_member']) || $_SESSION['is_council_member'] !== true) {
    header("Location: ../index.php");
    exit();
}

$pageTitle = "Manage Areas - Culture Connect";
include '../includes/sidebar.php'; 
require_once '../config/connection.php';
?>

<div id="page-content-wrapper" class="flex-grow-1 p-5">
    <div class="container-fluid p-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">Manage Areas</h2>
            <button class="btn btn-dark rounded-0 fw-bold" data-bs-toggle="modal" data-bs-target="#areaModal" onclick="prepareAdd()">+ ADD NEW AREA</button>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered border-dark">
                <thead>
                    <tr class="text-white">
                        <th style="background-color: #72b1e1 !important; width: 10%;">ID</th>
                        <th style="background-color: #72b1e1 !important;">Area Name</th>
                        <th style="background-color: #72b1e1 !important; width: 20%;" class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Fetching areas as defined in your database schema
                    $sql = "SELECT AreaID, Name FROM Area ORDER BY Name ASC";
                    $result = $conn->query($sql);
                    if ($result && $result->num_rows > 0):
                        while($row = $result->fetch_assoc()):
                    ?>
                    <tr>
                        <td class="fw-bold"><?php echo $row['AreaID']; ?></td>
                        <td><?php echo htmlspecialchars($row['Name']); ?></td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-outline-primary rounded-0" 
                                    onclick="prepareEdit(<?php echo $row['AreaID']; ?>, '<?php echo addslashes($row['Name']); ?>')"
                                    data-bs-toggle="modal" data-bs-target="#areaModal">Edit</button>
                            <button class="btn btn-sm btn-outline-danger rounded-0" 
                                    onclick="confirmDeepDelete(<?php echo $row['AreaID']; ?>, '<?php echo htmlspecialchars($row['Name']); ?>')">
                                Delete
                            </button>
                        </td>
                    </tr>
                    <?php endwhile; else: ?>
                    <tr><td colspan="3" class="text-center">No areas found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="areaModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-0 border-2 border-dark">
            <form action="../processes/manage_areas.php" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="modalTitle">Add New Area</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="area_id" id="modal_area_id">
                    <div class="mb-3">
                        <label class="form-label">Area Name</label>
                        <input type="text" name="area_name" id="modal_area_name" class="form-control border-2 border-dark rounded-0" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-dark rounded-0" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="submit_area" class="btn btn-dark rounded-0">Save Area</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="../assets/js/bootstrap.bundle.min.js"></script>
<script>
function confirmDeepDelete(id, name) {
    const message = `WARNING: DESTRUCTIVE ACTION\n\nDeleting the area "${name}" will permanently delete:\n` +
                    `- ALL Users in this area\n` +
                    `- ALL SMEs/Businesses in this area\n` +
                    `- ALL Products and Services from those businesses\n` +
                    `- ALL Votes associated with those products\n\n` +
                    `Are you absolutely sure you want to proceed?`;
    
    if (confirm(message)) {
        // Redirect to manage_areas.php with the delete_id
        window.location.href = "../processes/manage_areas.php?delete_id=" + id;
    }
}
function prepareAdd() {
    console.log("Add button clicked"); // Debugging line
    document.getElementById('modalTitle').innerText = "Add New Area";
    document.getElementById('modal_area_id').value = "";
    document.getElementById('modal_area_name').value = "";
}

function prepareEdit(id, name) {
    console.log("Edit button clicked for ID: " + id); // Debugging line
    document.getElementById('modalTitle').innerText = "Edit Area Name";
    document.getElementById('modal_area_id').value = id;
    document.getElementById('modal_area_name').value = name;
}
</script>

<?php 
if (isset($conn)) {
    $conn->close(); 
}
?>
</body>
</html>