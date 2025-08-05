<?php
// lib/auth.php
require_once __DIR__ . '/../config/config.php';

function is_logged_in(): bool {
    return isset($_SESSION['admin_id']);
}

function require_login(): void {
    if (!is_logged_in()) {
        header('Location: login.php'); // relativo a /public
        exit;
    }
}

function login(string $username, string $password): bool {
    global $pdo;
    $stmt = $pdo->prepare("SELECT id, usuario, contrasena FROM usuarios WHERE usuario = ? LIMIT 1");
    $stmt->execute([$username]);
    $user = $stmt->fetch();
    if ($user && password_verify($password, $user['contrasena'])) {
        $_SESSION['admin_id'] = (int)$user['id'];
        $_SESSION['admin_user'] = $user['usuario'];
        return true;
    }
    return false;
}

function logout(): void {
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params['path'], $params['domain'],
            $params['secure'], $params['httponly']
        );
    }
    session_destroy();
}

function csrf_token(): string {
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function verify_csrf(string $token): bool {
    return isset($_SESSION['csrf']) && hash_equals($_SESSION['csrf'], $token);
}
