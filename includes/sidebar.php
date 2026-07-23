<?php
// includes/sidebar.php
$current_page = basename($_SERVER['PHP_SELF']);
$role = $_SESSION['role'] ?? 'citizen';
?>
<nav class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <i class="fas fa-landmark"></i>
        <div>
            <span>AssetComplaint</span>
            <small>Sri Lanka · Gov</small>
        </div>
    </div>
    <div class="sidebar-menu">
        <div class="menu-label">Main</div>
        <a href="../index.php" class="<?= $current_page == 'index.php' ? 'active' : '' ?>"><i class="fas fa-th-large"></i> Dashboard</a>

        <?php if ($role === 'citizen'): ?>
            <a href="../citizen/submit_complaint.php" class="<?= $current_page == 'submit_complaint.php' ? 'active' : '' ?>"><i class="fas fa-plus-circle"></i> New Complaint</a>
            <a href="../citizen/my_complaints.php" class="<?= $current_page == 'my_complaints.php' ? 'active' : '' ?>"><i class="fas fa-list"></i> My Complaints</a>
        <?php elseif ($role === 'district_officer'): ?>
            <a href="../district/complaints.php" class="<?= $current_page == 'complaints.php' ? 'active' : '' ?>"><i class="fas fa-clipboard-list"></i> District Complaints</a>
        <?php elseif ($role === 'province_officer' || $role === 'admin'): ?>
            <a href="../admin/dashboard.php" class="<?= $current_page == 'dashboard.php' ? 'active' : '' ?>"><i class="fas fa-chart-pie"></i> Admin Dashboard</a>
            <a href="../admin/manage_users.php" class="<?= $current_page == 'manage_users.php' ? 'active' : '' ?>"><i class="fas fa-users"></i> Manage Users</a>
        <?php endif; ?>

        <div class="menu-label mt-3">System</div>
        <a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>
</nav>