<?php
// citizen/submit_complaint.php
require_once '../config/database.php';
require_once '../config/auth.php';
requireLogin();
if ($_SESSION['role'] !== 'citizen') { redirectTo('index.php'); }

$provinces = $pdo->query("SELECT * FROM provinces")->fetchAll();
$categories = $pdo->query("SELECT * FROM complaint_categories")->fetchAll();
$districts = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $desc = $_POST['description'];
    $cat_id = $_POST['category_id'];
    $prov_id = $_POST['province_id'];
    $dist_id = $_POST['district_id'];
    $lat = $_POST['lat'] ?? null;
    $lng = $_POST['lng'] ?? null;
    $address = $_POST['address'] ?? '';

    $stmt = $pdo->prepare("INSERT INTO complaints (user_id, category_id, province_id, district_id, title, description, location_lat, location_lng, address) VALUES (?,?,?,?,?,?,?,?,?)");
    $stmt->execute([$_SESSION['user_id'], $cat_id, $prov_id, $dist_id, $title, $desc, $lat, $lng, $address]);
    $complaint_id = $pdo->lastInsertId();

    // Handle image uploads
    if (!empty($_FILES['images']['name'][0])) {
        $upload_dir = '../assets/uploads/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
        foreach ($_FILES['images']['tmp_name'] as $key => $tmp) {
            if ($_FILES['images']['error'][$key] === 0) {
                $ext = pathinfo($_FILES['images']['name'][$key], PATHINFO_EXTENSION);
                $filename = 'complaint_' . $complaint_id . '_' . time() . '_' . $key . '.' . $ext;
                move_uploaded_file($tmp, $upload_dir . $filename);
                $pdo->prepare("INSERT INTO complaint_images (complaint_id, image_path) VALUES (?,?)")->execute([$complaint_id, 'assets/uploads/' . $filename]);
            }
        }
    }
    echo "<script>Swal.fire('Success','Complaint submitted!','success').then(()=>window.location='my_complaints.php');</script>";
}
?>
<?php include '../includes/header.php'; ?>
<?php include '../includes/sidebar.php'; ?>
<div class="main-content">
    <header class="top-nav"><div><button class="toggle-sidebar" id="toggleSidebarBtn"><i class="fas fa-bars"></i></button><span class="fw-semibold">Submit Complaint</span></div></header>
    <div class="page-content">
        <div class="card-custom">
            <div class="card-header">New Complaint</div>
            <div class="card-body">
                <form method="POST" enctype="multipart/form-data" id="complaintForm">
                    <div class="row g-3">
                        <div class="col-md-6"><label class="form-label">Title</label><input type="text" name="title" class="form-control" required></div>
                        <div class="col-md-6"><label class="form-label">Category</label>
                            <select name="category_id" class="form-select" required>
                                <?php foreach($categories as $c): ?><option value="<?= $c['id'] ?>"><?= $c['name'] ?></option><?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6"><label class="form-label">Province</label>
                            <select name="province_id" class="form-select" id="provinceSelect" required>
                                <option value="">Select</option>
                                <?php foreach($provinces as $p): ?><option value="<?= $p['id'] ?>"><?= $p['name'] ?></option><?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6"><label class="form-label">District</label>
                            <select name="district_id" class="form-select" id="districtSelect" required><option value="">Select Province first</option></select>
                        </div>
                        <div class="col-12"><label class="form-label">Description</label><textarea name="description" class="form-control" rows="3" required></textarea></div>
                        <div class="col-md-6"><label class="form-label">Address</label><input type="text" name="address" class="form-control" placeholder="Near Galle Road..."></div>
                        <div class="col-md-3"><label class="form-label">Latitude</label><input type="text" name="lat" id="lat" class="form-control" placeholder="6.9271"></div>
                        <div class="col-md-3"><label class="form-label">Longitude</label><input type="text" name="lng" id="lng" class="form-control" placeholder="79.8612"></div>
                        <div class="col-12"><label class="form-label">Upload Images</label><input type="file" name="images[]" class="form-control" multiple accept="image/*"></div>
                        <div class="col-12"><button type="submit" class="btn btn-primary">Submit Complaint</button></div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
// Dynamic districts
document.getElementById('provinceSelect').addEventListener('change', function(){
    let prov = this.value;
    let distSelect = document.getElementById('districtSelect');
    if(prov) {
        fetch('../api/get_districts.php?province_id='+prov)
        .then(r=>r.json())
        .then(data => {
            distSelect.innerHTML = '<option value="">Select District</option>';
            data.forEach(d => distSelect.innerHTML += `<option value="${d.id}">${d.name}</option>`);
        });
    } else {
        distSelect.innerHTML = '<option value="">Select Province first</option>';
    }
});
// Get GPS location
if(navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(pos => {
        document.getElementById('lat').value = pos.coords.latitude;
        document.getElementById('lng').value = pos.coords.longitude;
    });
}
</script>
<?php include '../includes/footer.php'; ?>