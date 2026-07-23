<?php
// admin/manage_users.php
require_once '../config/database.php';
require_once '../config/auth.php';
requireLogin();
if ($_SESSION['role'] !== 'admin') { header('Location: ../index.php'); exit; }
$users = $pdo->query("SELECT id, fullname, email, role, created_at FROM users ORDER BY created_at DESC")->fetchAll();
?>
<?php include '../includes/header.php'; ?>
<?php include '../includes/sidebar.php'; ?>
<div class="main-content">
    <header class="top-nav"><div><button class="toggle-sidebar" id="toggleSidebarBtn"><i class="fas fa-bars"></i></button><span class="fw-semibold">Manage Users</span></div></header>
    <div class="page-content">
        <div class="card-custom">
            <div class="card-header">All Users</div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Role</th><th>Joined</th></tr></thead>
                    <tbody>
                    <?php foreach($users as $u): ?>
                    <tr><td><?= $u['id'] ?></td><td><?= $u['fullname'] ?></td><td><?= $u['email'] ?></td><td><?= $u['role'] ?></td><td><?= date('Y-m-d', strtotime($u['created_at'])) ?></td></tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php include '../includes/footer.php'; ?>