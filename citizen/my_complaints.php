<?php
// citizen/my_complaints.php
require_once '../config/database.php';
require_once '../config/auth.php';
requireLogin();
$user_id = $_SESSION['user_id'];
$complaints = $pdo->prepare("SELECT c.*, cat.name as cat_name, d.name as dist_name FROM complaints c 
                             JOIN complaint_categories cat ON c.category_id=cat.id 
                             JOIN districts d ON c.district_id=d.id 
                             WHERE c.user_id = ? ORDER BY c.created_at DESC");
$complaints->execute([$user_id]);
?>
<?php include '../includes/header.php'; ?>
<?php include '../includes/sidebar.php'; ?>
<div class="main-content">
    <header class="top-nav"><div><button class="toggle-sidebar" id="toggleSidebarBtn"><i class="fas fa-bars"></i></button><span class="fw-semibold">My Complaints</span></div></header>
    <div class="page-content">
        <div class="card-custom">
            <div class="card-header">Your Complaints</div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead><tr><th>#</th><th>Title</th><th>Category</th><th>District</th><th>Status</th><th>Date</th></tr></thead>
                    <tbody>
                    <?php while($row = $complaints->fetch()): ?>
                    <tr>
                        <td><strong>C-<?= str_pad($row['id'],4,'0',STR_PAD_LEFT) ?></strong></td>
                        <td><?= htmlspecialchars($row['title']) ?></td>
                        <td><?= $row['cat_name'] ?></td>
                        <td><?= $row['dist_name'] ?></td>
                        <td><?= statusBadge($row['status']) ?></td>
                        <td><?= date('Y-m-d', strtotime($row['created_at'])) ?></td>
                    </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php include '../includes/footer.php'; ?>