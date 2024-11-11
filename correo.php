<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';  // Asegúrate de tener PHPMailer instalado y autoload configurado

// Configuración de Mailtrap (separada para facilitar cambios)
function configurarMailtrap(PHPMailer $mail)
{
    $mail->isSMTP();
    $mail->Host       = 'sandbox.smtp.mailtrap.io';
    $mail->SMTPAuth   = true;
    $mail->Username   = '52158799bcfb78'; // Sustituye con tus credenciales de Mailtrap
    $mail->Password   = '563b2bbd525cbb';
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 2525;
}

// Función para enviar correo de verificación
function enviarCorreoVerificacion($user_id, $email)
{
    $mail = new PHPMailer(true);

    try {
        configurarMailtrap($mail);  // Aplicamos configuración de Mailtrap

        // Configuración del correo
        $mail->setFrom('no-reply@empresa.com', 'Soporte de Empresa');
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = 'Verificación de correo electrónico';

        // Crear enlace de verificación
        $url_verificacion = "http://localhost/Proyecto_Dani/verificar.php?user_id=$user_id";
        $mail->Body    = "Necesita verificar su correo. <a href='$url_verificacion'>Pulse aquí para confirmarlo</a>.";
        $mail->AltBody = "Necesita verificar su correo. Visite el siguiente enlace para confirmar: $url_verificacion";

        $mail->send();
    } catch (Exception $e) {
        echo "Error al enviar el correo de verificación: {$mail->ErrorInfo}";
    }
}

// Nueva función para enviar un correo de actualización al cambiar el estado del ticket
function enviarActualizacionTicket($email, $ticket_id, $nuevo_estado, $mensaje_tecnico)
{
    $mail = new PHPMailer(true);

    try {
        configurarMailtrap($mail);  // Aplicamos configuración de Mailtrap

        // Configuración del correo
        $mail->setFrom('no-reply@empresa.com', 'Soporte de Empresa');
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = "Actualización en el Ticket #$ticket_id";

        // Contenido del correo
        $mail->Body    = "
            <p>Estimado empleado,</p>
            <p>Su ticket con ID <strong>$ticket_id</strong> ha cambiado de estado a <strong>$nuevo_estado</strong>.</p>
            <p><strong>Mensaje del técnico:</strong> $mensaje_tecnico</p>
            <p>Gracias por su paciencia,</p>
            <p>Equipo de Soporte</p>
        ";
        $mail->AltBody = "Su ticket #$ticket_id ha cambiado a estado '$nuevo_estado'. Mensaje del técnico: $mensaje_tecnico";

        $mail->send();
    } catch (Exception $e) {
        echo "Error al enviar el correo de actualización: {$mail->ErrorInfo}";
    }
}
function enviarCreacionTicket($email, $ticket_id, $asunto, $descripcion)
{
    $mail = new PHPMailer(true);

    try {
        configurarMailtrap($mail);  // Aplicamos configuración de Mailtrap

        // Configuración del correo
        $mail->setFrom('no-reply@empresa.com', 'Soporte de Empresa');
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = "Confirmación de Creación del Ticket #$ticket_id";

        // Contenido del correo
        $mail->Body    = "
            <p>Estimado usuario,</p>
            <p>Su ticket con ID <strong>$ticket_id</strong> ha sido creado exitosamente.</p>
            <p><strong>Asunto:</strong> $asunto</p>
            <p><strong>Descripción:</strong> $descripcion</p>
            <p>Gracias por contactarnos. Nuestro equipo de soporte se pondrá en contacto con usted en breve.</p>
            <p>Equipo de Soporte</p>
        ";
        $mail->AltBody = "Su ticket #$ticket_id ha sido creado. Asunto: $asunto. Descripción: $descripcion.";

        $mail->send();
    } catch (Exception $e) {
        echo "Error al enviar el correo de creación: {$mail->ErrorInfo}";
    }
}
// Función para enviar un correo de recuperación de contraseña
function enviarCorreoRecuperacion($email, $numSeguridad)
{
    $mail = new PHPMailer(true);

    try {
        configurarMailtrap($mail); // Configuración de Mailtrap

        // Configuración del correo
        $mail->setFrom('no-reply@empresa.com', 'Soporte de Empresa');
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = 'Recuperación de Contraseña';

        // Crear enlace y cuerpo del mensaje
        $url_recuperacion = "http://localhost/Proyecto_Dani/restablecer.php?seguridad=$numSeguridad";
        $mail->Body = "
            <p>Hola,</p>
            <p>Hemos recibido una solicitud para restablecer la contraseña de su cuenta.</p>
            <p>Para restablecer su contraseña, haga clic en el siguiente enlace:</p>
            <a href='$url_recuperacion'>Restablecer mi contraseña</a>
            <p>Si no solicitó este cambio, puede ignorar este mensaje.</p>
            <p>Gracias,<br>Equipo de Soporte</p>
        ";
        $mail->AltBody = "Para restablecer su contraseña, visite: $url_recuperacion";

        $mail->send();
        return true;
    } catch (Exception $e) {
        return "Error al enviar el correo de recuperación: {$mail->ErrorInfo}";
    }
}
