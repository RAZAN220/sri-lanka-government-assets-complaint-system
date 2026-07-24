<?php
// index.php
require_once 'config/database.php';
require_once 'config/auth.php';
require_once 'config/functions.php';
requireLogin();
$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

// Stats
$total = getStatusCount($pdo);
$resolved = getStatusCount($pdo, 'resolved');
$pending = getStatusCount($pdo, 'pending');
$inProgress = getStatusCount($pdo, 'in_progress');
?>
<?php include 'includes/header.php'; ?>
<?php include 'includes/sidebar.php'; ?>
<div class="main-content" id="mainContent">
    <header class="top-nav">
        <div>
            <button class="toggle-sidebar" id="toggleSidebarBtn"><i class="fas fa-bars"></i></button>
            <span class="d-none d-md-inline fw-semibold fs-6">Dashboard</span>
        </div>
        <div class="user-dropdown">
            <div class="dropdown">
                <a href="#" class="d-flex align-items-center gap-2 text-decoration-none text-dark" data-bs-toggle="dropdown">
                    <div class="avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width:38px;height:38px;font-weight:600;"><?= strtoupper(substr($_SESSION['fullname'],0,2)) ?></div>
                    <span class="d-none d-sm-inline fw-semibold"><?= $_SESSION['fullname'] ?></span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2">
                    <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                </ul>
            </div>
        </div>
    </header>
    <div class="page-content">
        <div class="row g-4 mb-4">
            <div class="col-6 col-xl-3"><div class="stat-card d-flex justify-content-between"><div><div class="stat-number"><?= $total ?></div><div class="stat-label">Total</div></div><div class="stat-icon bg-primary bg-opacity-10 text-primary"><i class="fas fa-clipboard-list"></i></div></div></div>
            <div class="col-6 col-xl-3"><div class="stat-card d-flex justify-content-between"><div><div class="stat-number"><?= $resolved ?></div><div class="stat-label">Resolved</div></div><div class="stat-icon bg-success bg-opacity-10 text-success"><i class="fas fa-check-circle"></i></div></div></div>
            <div class="col-6 col-xl-3"><div class="stat-card d-flex justify-content-between"><div><div class="stat-number"><?= $pending ?></div><div class="stat-label">Pending</div></div><div class="stat-icon bg-warning bg-opacity-10 text-warning"><i class="fas fa-clock"></i></div></div></div>
            <div class="col-6 col-xl-3"><div class="stat-card d-flex justify-content-between"><div><div class="stat-number"><?= $inProgress ?></div><div class="stat-label">In Progress</div></div><div class="stat-icon bg-info bg-opacity-10 text-info"><i class="fas fa-spinner"></i></div></div></div>
        </div>

        <!-- Recent complaints -->
        <div class="card-custom">
            <div class="card-header">Recent Complaints</div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light"><tr><th>#</th><th>Title</th><th>Category</th><th>District</th><th>Status</th></tr></thead>
                    <tbody>
                        <?php
                        $stmt = $pdo->query("SELECT c.*, cat.name as cat_name, d.name as dist_name FROM complaints c 
                                              JOIN complaint_categories cat ON c.category_id=cat.id 
                                              JOIN districts d ON c.district_id=d.id 
                                              ORDER BY c.created_at DESC LIMIT 5");
                        while($row = $stmt->fetch()): ?>
                        <tr>
                            <td><strong>C-<?= str_pad($row['id'],4,'0',STR_PAD_LEFT) ?></strong></td>
                            <td><?= htmlspecialchars($row['title']) ?></td>
                            <td><span class="badge bg-secondary"><?= $row['cat_name'] ?></span></td>
                            <td><?= $row['dist_name'] ?></td>
                            <td><?= statusBadge($row['status']) ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php include 'includes/footer.php'; ?>