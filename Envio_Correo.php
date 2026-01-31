<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/vendor/phpmailer/phpmailer/src/Exception.php';
require_once __DIR__ . '/vendor/phpmailer/phpmailer/src/PHPMailer.php';
require_once __DIR__ . '/vendor/phpmailer/phpmailer/src/SMTP.php';

$correo_destino = "contacto@beatancode.com"; // correo empresa
$nombre_empresa = "BEATANCODE";
$correo_empresa = "contacto@beatancode.com";

$gmail_usuario = "contacto@beatancode.com";
$gmail_clave   = "B3aT4NC0de*"; // contraseña de aplicación


if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.html");

}

/*  DATOS FORMULARIO  */
$nombre  = htmlspecialchars($_POST['nombre'] ?? '');
$email   = htmlspecialchars($_POST['email'] ?? '');
$asunto  = htmlspecialchars($_POST['asunto'] ?? '');
$mensaje = nl2br(htmlspecialchars($_POST['mensaje'] ?? ''));

$numero = "SOL-" . time();
$fecha  = date("d/m/Y H:i");

/*  CORREO PARA BEATANCODE*/
$htmlAdmin = "
<!DOCTYPE html>
<html lang='es'>
<head>
<meta charset='UTF-8'>
<title>BEATANCODE - Nueva Solicitud</title>
</head>

<body style='margin:0;padding:0;background:#ffffff;font-family:Arial,Helvetica,sans-serif;'>

<table width='100%' cellpadding='0' cellspacing='0'>
<tr>
<td align='center' style='padding:30px;'>

<table width='600' cellpadding='0' cellspacing='0'
style='background:#05060a;
border-radius:20px;
border:1px solid rgba(0,240,255,.35);
box-shadow:0 0 45px rgba(0,240,255,.35);
overflow:hidden;'>

<!-- HEADER CON FONDO -->
<tr>
<td align='center'
style='padding:45px 20px;
background-image:url(\"cid:fondo_header\");
background-size:cover;
background-position:center;'>

<img src='cid:logo_beatan'
width='110'
style='display:block;margin-bottom:15px;'>

<h1 style='margin:0;
color:#ffffff;
letter-spacing:2px;
font-size:26px;
text-shadow:
0 0 6px rgba(0,240,255,.8),
0 0 14px rgba(0,240,255,.6);'>
BEATANCODE
</h1>

<p style='margin:10px 0;
color:#c9f9ff;
font-size:15px;
text-shadow:0 0 10px rgba(0,240,255,.8);'>
Nueva solicitud recibida
</p>

<div style='margin-top:12px;
display:inline-block;
padding:6px 14px;
border-radius:20px;
font-size:12px;
color:#00f0ff;
border:1px solid rgba(0,240,255,.5);
background:rgba(0,0,0,.55);
text-shadow:0 0 6px rgba(0,240,255,.9);'>
SOLICITUD #$numero
</div>

</td>
</tr>

<!-- CUERPO -->
<tr>
<td style='padding:35px;color:#e6fbff;'>

<table width='100%' cellpadding='8' cellspacing='0'
style='border:1px solid rgba(0,240,255,.2);
border-radius:14px;
background:linear-gradient(180deg,#080a12,#05060a);'>

<tr><td><strong> Fecha:</strong> $fecha</td></tr>
<tr><td><strong> Nombre:</strong> $nombre</td></tr>
<tr>
<td>
<strong>📧 Email:</strong>
<a href='mailto:$email'
style='color:#7ffaff;
text-decoration:none;
text-shadow:0 0 8px rgba(0,240,255,.9);'>
$email
</a>
</td>
</tr>
<tr><td><strong> Asunto:</strong> $asunto</td></tr>

</table>

<div style='margin-top:25px;
padding:20px;
border-radius:12px;
border:1px solid rgba(0,240,255,.35);
background:rgba(0,240,255,.05);
color:#dffcff;
text-shadow:0 0 6px rgba(0,240,255,.6);'>

<strong style='color:#00f0ff;'>💬 Mensaje:</strong><br><br>
$mensaje

</div>

</td>
</tr>

<!-- FOOTER -->
<tr>
<td align='center'
style='padding:22px;
font-size:12px;
color:#9ef7ff;
background:#05060a;
text-shadow:0 0 6px rgba(0,240,255,.6);'>
© ".date('Y')." BEATANCODE · Secure Message
</td>
</tr>

</table>

</td>
</tr>
</table>

</body>
</html>
";
/*  CORREO PARA EL USUARIO  */
$htmlUsuario = "
<!DOCTYPE html>
<html lang='es'>
<head>
<meta charset='UTF-8'>
<title>BEATANCODE - Mensaje recibido</title>
</head>

<body style='margin:0;padding:0;background:#ffffff;font-family:Arial,Helvetica,sans-serif;'>

<table width='100%' cellpadding='0' cellspacing='0'>
<tr>
<td align='center' style='padding:30px;'>

<table width='600' cellpadding='0' cellspacing='0'
style='background:#05060a;
border-radius:20px;
border:1px solid rgba(0,240,255,.35);
box-shadow:0 0 45px rgba(0,240,255,.35);
overflow:hidden;'>

<tr>
<td align='center'
style='padding:45px 20px;
background-image:url(\"cid:fondo_header\");
background-size:cover;
background-position:center;'>

<img src='cid:logo_beatan'
width='110'
style='display:block;margin-bottom:15px;'>

<h1 style='margin:0;
color:#ffffff;
letter-spacing:2px;
font-size:24px;
text-shadow:0 0 6px rgba(0,240,255,.8);'>
¡Gracias por contactarnos!
</h1>

</td>
</tr>

<tr>
<td style='padding:35px;
color:#e6fbff;
font-size:15px;
line-height:1.6;'>

Hola <strong>$nombre</strong>,<br><br>

Hemos recibido correctamente tu mensaje con el asunto:

<div style='margin:15px 0;
padding:12px;
border-left:4px solid #00f0ff;
background:rgba(0,240,255,.08);'>
<strong>$asunto</strong>
</div>

Nuestro equipo se pondrá en contacto contigo muy pronto <br><br>

Saludos,<br>
<strong>Equipo BEATANCODE</strong>

</td>
</tr>

<tr>
<td align='center'
style='padding:22px;
font-size:12px;
color:#9ef7ff;
background:#05060a;'>
© ".date('Y')." BEATANCODE
</td>
</tr>

</table>

</td>
</tr>
</table>

</body>
</html>
";



$mail = new PHPMailer(true);
$estado = "ok";
$error  = "";

// === CONFIG LOG ===
$logFile = __DIR__ . '/mail.log';

function mail_log($msg) {
    global $logFile;
    $date = date('Y-m-d H:i:s');
    file_put_contents(
        $logFile,
        "[$date] $msg" . PHP_EOL,
        FILE_APPEND | LOCK_EX
    );
}

try {
    mail_log("=== INICIO ENVIO EMAIL ===");

    $mail->isSMTP();
    $mail->Host       = 'smtp.hostinger.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = $correo_empresa;   // correo REAL Hostinger
    $mail->Password   = $gmail_clave;      // clave REAL
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port       = 465;
    $mail->CharSet    = 'UTF-8';

    mail_log("SMTP configurado | FROM: $correo_empresa");

    // === CORREO AL ADMIN ===
    $mail->setFrom($correo_empresa, $nombre_empresa);
    $mail->addAddress($correo_destino);
    $mail->addReplyTo($email, $nombre);
    $mail->isHTML(true);
    $mail->Subject = "Nuevo mensaje de contacto";
    $mail->AddEmbeddedImage(__DIR__.'/img/Logo.png', 'logo_beatan');
    $mail->Body = $htmlAdmin;

    mail_log("Enviando correo ADMIN...");
    $mail->send();
    mail_log("Correo ADMIN enviado OK");

    // === CORREO AL USUARIO ===
    $mail->clearAddresses();
    $mail->clearAttachments();

    $mail->setFrom($correo_empresa, "BEATANCODE");
    $mail->addAddress($email, $nombre);
    $mail->Subject = "Gracias por contactarnos | BEATANCODE";
    $mail->AddEmbeddedImage(__DIR__.'/img/Logo.png', 'logo_beatan');
    $mail->Body = $htmlUsuario;

    mail_log("Enviando correo USUARIO...");
    $mail->send();
    mail_log("Correo USUARIO enviado OK");

    mail_log("=== FIN ENVIO EMAIL ===");

} catch (Exception $e) {
    $estado = "error";
    $error  = $mail->ErrorInfo ?: $e->getMessage();
    mail_log("ERROR ENVIO: " . $error);
}
