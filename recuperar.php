<?php
session_start();
include 'conexion.php'; // Conexión a la base de datos
include 'correo.php';   // Funciones de correo
$mensaje = '';
$mensaje_error = '';

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    
    // Verificar si el correo existe en la base de datos
    $sql = "SELECT * FROM usuarios WHERE email = '$email'";
    $result = $pdo->query($sql);
    $user = $result->fetch();

    if ($user) {
        // Generar el enlace de recuperación con el ID del usuario
        $user_id = $user['id'];
        $url_recuperacion = "http://localhost/Proyecto_Dani/restablecer.php?user_id=$user_id";

        // Enviar el correo de recuperación
        $mail = new PHPMailer(true);
        try {
            configurarMailtrap($email);  // Configuración de Mailtrap

            // Configuración del correo
            $mail->setFrom('no-reply@empresa.com', 'Soporte de Empresa');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'Recuperación de Contraseña';

            // Crear cuerpo del mensaje
            $mail->Body = "
                <p>Hola,</p>
                <p>Hemos recibido una solicitud para restablecer la contraseña de su cuenta.</p>
                <p>Para restablecer su contraseña, haga clic en el siguiente enlace:</p>
                <a href='$url_recuperacion'>Restablecer mi contraseña</a>
                <p>Si no solicitó este cambio, puede ignorar este mensaje.</p>
                <p>Gracias,<br>Equipo de Soporte</p>
            ";
            $mail->AltBody = "Hemos recibido una solicitud para restablecer la contraseña de su cuenta. Para restablecer su contraseña, visite: $url_recuperacion";

            // Enviar correo
            $mail->send();
            
            // Mensaje de éxito
            $mensaje = "Se ha enviado un correo con instrucciones para restablecer tu contraseña.";
        } catch (Exception $e) {
            // Si ocurre un error en el envío del correo
            $mensaje_error = "Error al enviar el correo de recuperación: {$mail->ErrorInfo}";
        }
    } else {
        $mensaje_error = "No hemos encontrado ningún usuario con ese correo electrónico.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recuperar Contraseña</title>
    <link rel="stylesheet" href="estilos/recuperar.css">
    
</head>

<body>
    <div class="container">
        <h2>Recuperar Contraseña</h2>

        <!-- Mostrar mensaje de error o éxito -->
        <?php if ($mensaje) { ?>
            <p class="success"><?php echo $mensaje; ?></p>
        <?php } ?>
        <?php if ($mensaje_error) { ?>
            <p class="error"><?php echo $mensaje_error; ?></p>
        <?php } ?>

        <!-- Formulario de recuperación de contraseña -->
        <?php if (empty($mensaje)) { ?>
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <label for="email">Correo Electrónico:</label>
                <input type="email" name="email" required><br><br>

                <button type="submit">Verificar Correo</button>
            </form>
        <?php } ?>

       
        <?php if (!empty($mensaje) && empty($mensaje_error)) { ?>
            <h3>Restablecer Contraseña</h3>
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <input type="hidden" name="email" value="<?php echo $email; ?>">

                <label for="nueva_contraseña">Nueva Contraseña:</label>
                <input type="password" name="nueva_contraseña" required><br><br>

                <label for="confirmar_contraseña">Confirmar Contraseña:</label>
                <input type="password" name="confirmar_contraseña" required><br><br>

                <button type="submit">Restablecer Contraseña</button>
            </form>
        <?php } ?>

        <p>¿Ya tienes cuenta? <a href="login.php">Iniciar sesión</a></p>
    </div>
</body>

</html>
