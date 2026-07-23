<?php
// register.php
require_once 'config/database.php';
require_once 'config/auth.php';
redirectIfLoggedIn();

$error = '';
$success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = 'citizen';
    try {
        $stmt = $pdo->prepare("INSERT INTO users (fullname, email, password, role) VALUES (?,?,?,?)");
        $stmt->execute([$fullname, $email, $password, $role]);
        $success = 'Registration successful! Please login.';
    } catch (PDOException $e) {
        $error = 'Email already exists or invalid data.';
    }
}
?>
<!DOCTYPE html>
<html>
<head><title>Register</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"></head>
<body class="auth-wrapper">
<div class="auth-card">
    <div class="auth-brand text-center mb-4"><i class="fas fa-user-plus fs-1 text-primary"></i><h4 class="mt-2">Create Account</h4></div>
    <?php if($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
    <?php if($success): ?><div class="alert alert-success"><?= $success ?> <a href="login.php">Login</a></div><?php endif; ?>
    <form method="POST">
        <div class="mb-3"><label class="form-label">Full Name</label><input type="text" name="fullname" class="form-control" required></div>
        <div class="mb-3"><label class="form-label">Email</label><input type="email" name="email" class="form-control" required></div>
        <div class="mb-3"><label class="form-label">Password</label><input type="password" name="password" class="form-control" required></div>
        <button type="submit" class="btn btn-primary w-100">Register</button>
        <p class="text-center mt-3"><a href="login.php" class="text-decoration-none">Already have an account? Login</a></p>
    </form>
</div>
</body>
</html>