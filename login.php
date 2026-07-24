<?php
// login.php
require_once 'config/database.php';
require_once 'config/auth.php';
redirectIfLoggedIn();

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['fullname'] = $user['fullname'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['province_id'] = $user['province_id'];
        $_SESSION['district_id'] = $user['district_id'];
        header('Location: index.php');
        exit;
    } else {
        $error = 'Invalid email or password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Government Asset Complaint System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #0d6efd;
            --primary-dark: #0b5ed7;
            --accent: #0f766e;
            --bg: #f4f8ff;
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

        .hero-list {
            list-style: none;
            padding: 0;
            margin: 0;
            display: grid;
            gap: 10px;
        }

        .hero-list li {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.95rem;
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
            <h1>Report and track public asset issues with confidence.</h1>
            <p>Citizens and officers can submit complaints, monitor progress, and support better public services across Sri Lanka.</p>
            <ul class="hero-list">
                <li><i class="fas fa-check-circle"></i> Fast complaint submission</li>
                <li><i class="fas fa-check-circle"></i> Transparent status tracking</li>
                <li><i class="fas fa-check-circle"></i> Role-based access for staff and citizens</li>
            </ul>
        </div>

        <div class="auth-card">
            <div class="text-center mb-4">
                <div class="brand-icon">
                    <img src="images (2).png" alt="Government Logo">
                </div>
                <h3 class="mb-1">Welcome Back</h3>
                <p class="text-muted mb-0">Sign in to continue to your dashboard</p>
            </div>

            <?php if ($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-control" placeholder="name@example.com" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Login</button>
                <p class="text-center mt-3 mb-0"><a href="register.php" class="text-decoration-none">Don't have an account? Register</a></p>
            </form>
        </div>
    </div>
</body>
</html>