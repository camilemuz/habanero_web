<?php
require_once __DIR__ . '/../config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $correo = trim($_POST['correo'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $mensaje = trim($_POST['mensaje'] ?? '');

    if ($nombre && filter_var($correo, FILTER_VALIDATE_EMAIL) && $telefono && $mensaje) {
        $subject = "Contacto desde sitio: {$nombre}";
        $body = "Nombre: {$nombre}\nCorreo: {$correo}\nTeléfono: {$telefono}\n\nMensaje:\n{$mensaje}";
        $headers = "From: no-reply@localhost\r\nReply-To: {$correo}\r\n";

        // Nota: mail() en local puede no funcionar sin SMTP. (Opcional: usar PHPMailer)
        if (@mail($CONTACT_TO, $subject, $body, $headers)) {
            header('Location: index.php?ok=1#contacto');
            exit;
        } else {
            header('Location: index.php?ok=0#contacto');
            exit;
        }
    } else {
        header('Location: index.php?ok=0#contacto');
        exit;
    }
}
header('Location: index.php');
exit;
