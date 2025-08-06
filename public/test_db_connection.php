<?php
require_once __DIR__ . '/../config/config.php'; 

try {
    $pdo = new PDO(
        "mysql:host={$DB_HOST};dbname={$DB_NAME};charset=utf8mb4",
        $DB_USER,
        $DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
    echo "Conexión exitosa a la base de datos!";
} catch (PDOException $e) {
    die('Error de conexión: ' . $e->getMessage());
}
?>
