<?php
// config/auth.php
session_start();

function redirectTo($path) {
    $path = ltrim($path, '/');
    header('Location: ' . ($path === '' ? 'index.php' : $path));
    exit;
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isRole($role) {
    return isLoggedIn() && $_SESSION['role'] === $role;
}

function requireRole($role) {
    if (!isLoggedIn()) {
        redirectTo('login.php');
    }
    if ($_SESSION['role'] !== $role && $_SESSION['role'] !== 'admin') {
        // Admin can access all, but we check exact role for specific pages
        if ($_SESSION['role'] !== 'admin') {
            redirectTo('index.php');
        }
    }
}

function requireLogin() {
    if (!isLoggedIn()) {
        redirectTo('login.php');
    }
}

// Redirect if already logged in
function redirectIfLoggedIn() {
    if (isLoggedIn()) {
        redirectTo('index.php');
    }
}
?>