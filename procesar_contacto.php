<?php
// Configuración
$to = "contacto@beatancode.com"; // Cambia esto por tu email
$subject_prefix = "Nuevo mensaje desde el portafolio - ";

// Validación y sanitización
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = filter_var(trim($_POST["nombre"]), FILTER_SANITIZE_STRING);
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $asunto = filter_var(trim($_POST["asunto"]), FILTER_SANITIZE_STRING);
    $mensaje = filter_var(trim($_POST["mensaje"]), FILTER_SANITIZE_STRING);
    
    // Validar campos
    $errors = [];
    
    if (empty($nombre)) {
        $errors[] = "El nombre es requerido.";
    }
    
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "El email no es válido.";
    }
    
    if (empty($asunto)) {
        $errors[] = "El asunto es requerido.";
    }
    
    if (empty($mensaje)) {
        $errors[] = "El mensaje es requerido.";
    }
    
    // Si no hay errores, enviar el email
    if (empty($errors)) {
        $email_subject = $subject_prefix . $asunto;
        $email_body = "Has recibido un nuevo mensaje desde el formulario de contacto.\n\n";
        $email_body .= "Nombre: $nombre\n";
        $email_body .= "Email: $email\n";
        $email_body .= "Asunto: $asunto\n\n";
        $email_body .= "Mensaje:\n$mensaje\n";
        
        $headers = "From: $email\r\n";
        $headers .= "Reply-To: $email\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion();
        
        if (mail($to, $email_subject, $email_body, $headers)) {
            // Éxito
            header("Location: index.php?mensaje=success");
            exit;
        } else {
            // Error al enviar
            header("Location: index.php?mensaje=error");
            exit;
        }
    } else {
        // Hay errores de validación
        header("Location: index.php?mensaje=validation_error");
        exit;
    }
} else {
    // Acceso directo al archivo
    header("Location: index.php");
    exit;
}
?>
