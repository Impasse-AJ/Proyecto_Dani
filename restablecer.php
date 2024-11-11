<?php
session_start();
include 'conexion.php';  // Conexión a la base de datos
include 'correo.php';    // Funciones de correo

$mensaje = '';
$mensaje_error = '';

// Verificar si el parámetro 'user_id' está presente y es válido
$numSeguridad = $_GET['seguridad'] ?? null;
if ($numSeguridad) {
    $consulta = "SELECT * FROM usuarios WHERE seguridad = :numSeguridad";
    $stmt = $pdo->prepare($consulta);
    $stmt->execute(['numSeguridad' => $numSeguridad]);


    // Verificar si el usuario existe
    if ($stmt->rowCount() > 0) {
        // Verificar si el formulario de nueva contraseña fue enviado
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $nueva_contraseña = $_POST['nueva_contraseña'];
            $confirmar_contraseña = $_POST['confirmar_contraseña'];
            
            // Verificar que las contraseñas coinciden
            if ($nueva_contraseña == $confirmar_contraseña) {
                // Encriptar la nueva contraseña
                $nueva_contraseña_hash = password_hash($nueva_contraseña, PASSWORD_DEFAULT);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                // Actualizar la contraseña en la base de datos
                $sql = "UPDATE usuarios SET password = :password WHERE id = :user_id";
                $stmt_update = $pdo->prepare($sql);
                $stmt_update->execute(['password' => $nueva_contraseña_hash, 'user_id' => $user['id']]);
                
                // Comprobar si la actualización fue exitosa
                if ($stmt_update->rowCount() > 0) {
                    $mensaje = "Tu contraseña ha sido restablecida con éxito. Ahora puedes iniciar sesión.";
                } else {
                    $mensaje_error = "Error al restablecer la contraseña. Intenta nuevamente.";
                }
            } else {
                $mensaje_error = "Las contraseñas no coinciden. Por favor, inténtalo de nuevo.";
            }
        }
    } else {
        $mensaje_error = "No se ha encontrado el usuario. Verifique el enlace que ha recibido.";
    }
} else {
    $mensaje_error = "ID de usuario no válido.";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Restablecer Contraseña</title>
    <link rel="stylesheet" href="estilos/recuperar.css">
</head>
<body>
    <div class="container">
        <h2>Restablecer Contraseña</h2>

        <!-- Mostrar mensaje de error o éxito -->
        <?php if ($mensaje) { ?>
            <p class="success"><?php echo $mensaje; ?></p>
        <?php } ?>
        <?php if ($mensaje_error) { ?>
            <p class="error"><?php echo $mensaje_error; ?></p>
        <?php } ?>

        <!-- Formulario para ingresar la nueva contraseña -->
        <?php if (empty($mensaje) && empty($mensaje_error) && $stmt->rowCount() > 0) { ?>
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?seguridad=$numSeguridad"; ?>">
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
