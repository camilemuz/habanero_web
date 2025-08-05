<?php
// db/seed_admin.php — Ejecutar una sola vez para crear admin.
// Luego BORRAR este archivo.
require_once __DIR__ . '/../config/config.php';

$username = 'admin';
$password_plain = 'cambia_esta_clave_segura';

try {
    $hash = password_hash($password_plain, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO usuarios (usuario, contrasena) VALUES (?, ?)");
    $stmt->execute([$username, $hash]);
    echo "Admin creado.\nUsuario: {$username}\nContraseña: {$password_plain}\n";
} catch (Throwable $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
