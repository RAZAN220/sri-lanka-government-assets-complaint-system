<?php
// admin/dashboard.php
require_once '../config/database.php';
require_once '../config/auth.php';
requireLogin();
if ($_SESSION['role'] !== 'admin') { header('Location: ../index.php'); exit; }

$totalUsers = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$totalComplaints = $pdo->query("SELECT COUNT(*) FROM complaints")->fetchColumn();
$resolved = $pdo->query("SELECT COUNT(*) FROM complaints WHERE status='resolved'")->fetchColumn();
?>
<?php include '../includes/header.php'; ?>
<?php include '../includes/sidebar.php'; ?>
<div class="main-content">
    <header class="top-nav"><div><button class="toggle-sidebar" id="toggleSidebarBtn"><i class="fas fa-bars"></i></button><span class="fw-semibold">Admin Dashboard</span></div></header>
    <div class="page-content">
        <div class="row g-4">
            <div class="col-md-3"><div class="stat-card"><div class="stat-number"><?= $totalUsers ?></div><div class="stat-label">Total Users</div></div></div>
            <div class="col-md-3"><div class="stat-card"><div class="stat-number"><?= $totalComplaints ?></div><div class="stat-label">Complaints</div></div></div>
            <div class="col-md-3"><div class="stat-card"><div class="stat-number"><?= $resolved ?></div><div class="stat-label">Resolved</div></div></div>
            <div class="col-md-3"><div class="stat-card"><div class="stat-number"><?= $totalComplaints - $resolved ?></div><div class="stat-label">Open</div></div></div>
        </div>
    </div>
</div>
<?php include '../includes/footer.php'; ?>