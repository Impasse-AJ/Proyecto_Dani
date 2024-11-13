<?php
include 'bd.php'; // Funciones de base de datos
include 'correo.php'; // Funciones de correo

$error = '';
$mensaje = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validación de email y dominio
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Formato de correo electrónico no válido.";
    } else {
        $dominio = substr($email, strpos($email, '@') + 1);
        $tipo = ($dominio == 'empresa.com') ? 'empleado' : (($dominio == 'soporte.empresa.com') ? 'tecnico' : '');

        if (!$tipo) {
            $error = "El dominio del correo electrónico no es válido para ningún tipo de usuario.";
        } elseif ($password !== $confirm_password) {
            $error = "Las contraseñas no coinciden.";
        } elseif (usuarioExiste($pdo, $email)) {
            $error = "Este correo electrónico ya está registrado.";
        } else {
            // Registrar usuario y enviar correo de verificación
            $user_id = registrarUsuario($pdo, $email, $password, $tipo);
            crearNumeroAleatorio($pdo,$user_id);
            $sql = "SELECT seguridad FROM usuarios WHERE id = :user_id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['user_id' => $user_id]);
            $seguridad = $stmt->fetchColumn();
            enviarCorreoVerificacion($seguridad, $email);
            // header('Location: registro.php');
            $mensaje = "Datos enviados. Revise su bandeja de entrada para verificar la cuenta.";
            // exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Registro</title>
    <link rel="stylesheet" href="estilos/registro.css">
</head>

<body>
<div class="container">
        <h2>Registro de Usuario</h2>

        <?php if ($error) { ?>
            <p class="error"><?php echo $error; ?></p>
        <?php } ?>

        <?php if ($mensaje) { ?>
            <p style="color: green; font-weight: bold;"><?php echo $mensaje; ?></p>
        <?php } else { ?>

        <form method="POST" action="registro.php">
            <label for="email">Correo Electrónico:</label><br>
            <input type="email" name="email" required><br>

            <label for="password">Contraseña:</label><br>
            <input type="password" name="password" required><br>

            <label for="confirm_password">Confirmar Contraseña:</label><br>
            <input type="password" name="confirm_password" required><br>

            <button type="submit">Registrar</button>
        </form>
        <?php } ?>
        <a href="login.php" class="register-link">¿Ya tienes cuenta? Inicia sesión</a>
    </div>
</body>

</html>