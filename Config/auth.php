<?php
// config/auth.php
session_start();

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isRole($role) {
    return isLoggedIn() && $_SESSION['role'] === $role;
}

function requireRole($role) {
    if (!isLoggedIn()) {
        header('Location: ../login.php');
        exit;
    }
    if ($_SESSION['role'] !== $role && $_SESSION['role'] !== 'admin') {
        // Admin can access all, but we check exact role for specific pages
        if ($_SESSION['role'] !== 'admin') {
            header('Location: ../index.php');
            exit;
        }
    }
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: ../login.php');
        exit;
    }
}

// Redirect if already logged in
function redirectIfLoggedIn() {
    if (isLoggedIn()) {
        header('Location: index.php');
        exit;
    }
}
?>