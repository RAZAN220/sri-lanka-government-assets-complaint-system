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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | Government Asset Complaint System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #0d6efd;
            --primary-dark: #0b5ed7;
            --accent: #0f766e;
        }

        body {
            margin: 0;
            font-family: Inter, "Segoe UI", Arial, sans-serif;
            background: linear-gradient(135deg, #eef5ff 0%, #f8fbff 48%, #e7f6f1 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .auth-shell {
            width: min(100%, 1000px);
            display: grid;
            grid-template-columns: 1.05fr 0.95fr;
            background: #fff;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 20px 50px rgba(15, 23, 42, 0.12);
        }

        .hero-panel {
            background: linear-gradient(135deg, var(--primary-dark) 0%, #135fb9 35%, var(--accent) 100%);
            color: #fff;
            padding: 40px 36px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            width: fit-content;
            padding: 8px 12px;
            border-radius: 999px;
            background: rgba(255,255,255,0.16);
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 16px;
        }

        .hero-panel h1 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 12px;
            line-height: 1.2;
        }

        .hero-panel p {
            margin-bottom: 18px;
            color: rgba(255,255,255,0.9);
            line-height: 1.6;
        }

        .auth-card {
            padding: 40px 36px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .brand-icon {
            width: 70px;
            height: 70px;
            border-radius: 18px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #dbeafe, #d1fae5);
            margin-bottom: 10px;
            overflow: hidden;
            padding: 6px;
            box-shadow: 0 8px 22px rgba(13, 110, 253, 0.12);
        }

        .brand-icon img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            display: block;
        }

        .form-control {
            border-radius: 12px;
            padding: 0.8rem 0.95rem;
            border-color: #dbe4f0;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.15);
        }

        .btn-primary {
            border-radius: 12px;
            padding: 0.8rem 1rem;
            font-weight: 600;
        }

        @media (max-width: 768px) {
            .auth-shell {
                grid-template-columns: 1fr;
            }

            .hero-panel {
                padding: 28px 24px;
            }

            .auth-card {
                padding: 28px 24px;
            }
        }
    </style>
</head>
<body>
    <div class="auth-shell">
        <div class="hero-panel">
            <div class="hero-badge"><i class="fas fa-shield-alt"></i> Secure Government Portal</div>
            <h1>Create your account and start reporting public asset issues.</h1>
            <p>Join the complaint system to submit issues, track progress, and help improve services across Sri Lanka.</p>
        </div>

        <div class="auth-card">
            <div class="text-center mb-4">
                <div class="brand-icon">
                    <img src="images (2).png" alt="Government Logo">
                </div>
                <h3 class="mb-1">Create Account</h3>
                <p class="text-muted mb-0">Register to access the complaint portal</p>
            </div>

            <?php if ($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
            <?php if ($success): ?><div class="alert alert-success"><?= $success ?> <a href="login.php">Login</a></div><?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="fullname" class="form-control" placeholder="Enter your full name" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-control" placeholder="name@example.com" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Create a password" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Register</button>
                <p class="text-center mt-3 mb-0"><a href="login.php" class="text-decoration-none">Already have an account? Login</a></p>
            </form>
        </div>
    </div>
</body>
</html>