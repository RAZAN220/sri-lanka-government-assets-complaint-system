<?php
// api/get_districts.php
require_once '../config/database.php';
if (isset($_GET['province_id'])) {
    $stmt = $pdo->prepare("SELECT id, name FROM districts WHERE province_id = ?");
    $stmt->execute([$_GET['province_id']]);
    echo json_encode($stmt->fetchAll());
}
?>