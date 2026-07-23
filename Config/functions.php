<?php
// config/functions.php
function statusBadge($status) {
    $map = [
        'pending' => 'badge-pending',
        'under_review' => 'badge-review',
        'assigned' => 'badge-assigned',
        'in_progress' => 'badge-progress',
        'resolved' => 'badge-resolved',
        'rejected' => 'badge-rejected'
    ];
    $label = ucfirst(str_replace('_', ' ', $status));
    return "<span class='badge-status " . ($map[$status] ?? 'badge-secondary') . "'>$label</span>";
}

function getStatusCount($pdo, $status = null) {
    $sql = "SELECT COUNT(*) FROM complaints";
    if ($status) {
        $sql .= " WHERE status = :status";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['status' => $status]);
    } else {
        $stmt = $pdo->query($sql);
    }
    return $stmt->fetchColumn();
}

function getUserName($pdo, $id) {
    $stmt = $pdo->prepare("SELECT fullname FROM users WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetchColumn() ?: 'Unknown';
}

function getDistrictName($pdo, $id) {
    $stmt = $pdo->prepare("SELECT name FROM districts WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetchColumn() ?: 'N/A';
}

function getProvinceName($pdo, $id) {
    $stmt = $pdo->prepare("SELECT name FROM provinces WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetchColumn() ?: 'N/A';
}

function getCategoryName($pdo, $id) {
    $stmt = $pdo->prepare("SELECT name FROM complaint_categories WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetchColumn() ?: 'N/A';
}
?>