<?php
include 'bd.php'; // Funciones de base de datos
include 'correo.php'; // Funciones de correo

$mensaje = '';
$mensaje_error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    // Verificar si el correo existe en la base de datos
    $user = obtenerUsuarioPorEmail($pdo, $email);
    
    if ($user) {
        $user_id = $user['id'];
        crearNumeroAleatorio($pdo, $user_id);
        $sql = "SELECT seguridad FROM usuarios WHERE id = :user_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['user_id' => $user_id]);
        $seguridad = $stmt->fetchColumn();
        $resultado_envio = enviarCorreoRecuperacion($email, $seguridad);
        if ($resultado_envio === true) {
            $mensaje = "Se ha enviado un correo con instrucciones para restablecer tu contraseña.";
        } else {
            $mensaje_error = $resultado_envio;
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

        <p>¿Ya tienes cuenta? <a href="login.php">Iniciar sesión</a></p>
    </div>
</body>

</html>