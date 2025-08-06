<?php
require_once __DIR__ . '/../config/config.php'; 

// Crear un usuario un usuario y contraseña
$username = 'admin';
$password = 'admin123';  
$hashed_password = password_hash($password, PASSWORD_BCRYPT); // Hashear la contraseña

// Consulta SQL para insertar el usuario
$sql = "INSERT INTO usuarios (usuario, contrasena) VALUES (?, ?)";
$stmt = $pdo->prepare($sql);

// Ejecutar la consulta con los datos
if ($stmt->execute([$username, $hashed_password])) {
    echo "Usuario creado correctamente.";
} else {
    echo "Error al crear el usuario.";
}
?>
