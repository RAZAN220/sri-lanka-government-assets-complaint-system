<?php
// district/complaints.php
require_once '../config/database.php';
require_once '../config/auth.php';
requireLogin();
if ($_SESSION['role'] !== 'district_officer') { header('Location: ../index.php'); exit; }
$district_id = $_SESSION['district_id'];

// Fetch complaints for this district
$stmt = $pdo->prepare("SELECT c.*, cat.name as cat_name, u.fullname as citizen, d.name as dist_name 
                        FROM complaints c 
                        JOIN complaint_categories cat ON c.category_id=cat.id 
                        JOIN users u ON c.user_id=u.id 
                        JOIN districts d ON c.district_id=d.id 
                        WHERE c.district_id = ? ORDER BY c.created_at DESC");
$stmt->execute([$district_id]);
$complaints = $stmt->fetchAll();

// Update status
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['complaint_id'], $_POST['status'])) {
    $stmt = $pdo->prepare("UPDATE complaints SET status = ? WHERE id = ? AND district_id = ?");
    $stmt->execute([$_POST['status'], $_POST['complaint_id'], $district_id]);
    header('Location: complaints.php');
    exit;
}
?>
<?php include '../includes/header.php'; ?>
<?php include '../includes/sidebar.php'; ?>
<div class="main-content">
    <header class="top-nav"><div><button class="toggle-sidebar" id="toggleSidebarBtn"><i class="fas fa-bars"></i></button><span class="fw-semibold">District Complaints</span></div></header>
    <div class="page-content">
        <div class="card-custom">
            <div class="card-header">Manage Complaints</div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead><tr><th>#</th><th>Citizen</th><th>Title</th><th>Category</th><th>Status</th><th>Action</th></tr></thead>
                    <tbody>
                    <?php foreach($complaints as $row): ?>
                    <tr>
                        <td><strong>C-<?= str_pad($row['id'],4,'0',STR_PAD_LEFT) ?></strong></td>
                        <td><?= $row['citizen'] ?></td>
                        <td><?= htmlspecialchars($row['title']) ?></td>
                        <td><?= $row['cat_name'] ?></td>
                        <td><?= statusBadge($row['status']) ?></td>
                        <td>
                            <form method="POST" class="d-flex gap-2">
                                <input type="hidden" name="complaint_id" value="<?= $row['id'] ?>">
                                <select name="status" class="form-select form-select-sm" style="width:140px;">
                                    <option value="pending" <?= $row['status']=='pending'?'selected':'' ?>>Pending</option>
                                    <option value="under_review" <?= $row['status']=='under_review'?'selected':'' ?>>Review</option>
                                    <option value="assigned" <?= $row['status']=='assigned'?'selected':'' ?>>Assigned</option>
                                    <option value="in_progress" <?= $row['status']=='in_progress'?'selected':'' ?>>Progress</option>
                                    <option value="resolved" <?= $row['status']=='resolved'?'selected':'' ?>>Resolved</option>
                                    <option value="rejected" <?= $row['status']=='rejected'?'selected':'' ?>>Rejected</option>
                                </select>
                                <button type="submit" class="btn btn-sm btn-primary">Update</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php include '../includes/footer.php'; ?>