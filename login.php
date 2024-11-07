<?php
session_start();
include 'conexion.php'; // Conexión a la base de datos

// Variable para almacenar el mensaje de error
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Captura los datos del formulario
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Consulta directa para verificar el email y la contraseña
    $sql = "SELECT * FROM usuarios WHERE email = '$email'";
    $result = $pdo->query($sql);
    $user = $result->fetch();

    // Verificación de usuario
    if ($user && password_verify($password, $user['password'])) {
       
        if($user['verificado'] == 1){
            // Si el usuario existe, inicia sesión y guarda la información en la sesión
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['tipo'] = $user['tipo'];

        // Redirección según el rol del usuario
        if ($user['tipo'] == 'tecnico') {
            header('Location: mis_tickets.php');
        } else {
            header('Location: crear_ticket.php');
        }
    }else{
        $error = "Falta hacer la verificacion";
    }
} else {
    // Si los datos son incorrectos, muestra un mensaje de error
    $error = "Usuario o contraseña incorrectos.";
}
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión</title>
    
        <link rel="stylesheet" href="estilos/login.css">
 
</head>

<body>
    <div class="container">
        <h2>Inicio de Sesión</h2>

        <?php if ($error) { ?>
            <p class="error"><?php echo $error; ?></p>
        <?php } ?>

        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <label for="email">Correo Electrónico:</label>
            <input type="email" name="email" required><br><br>

            <label for="password">Contraseña:</label>
            <input type="password" name="password" required><br><br>

            <button type="submit">Ingresar</button>
        </form>
        
        <!-- Enlace para registro -->
        <div class="register-link">
            <p>¿No tienes cuenta aún? <a href="registro.php">Regístrate aquí</a></p>
        </div>
        
        <!-- Enlace para olvidaste contraseña -->
        <div class="register-link">
            <p>Has olvidado tu Contraseña?<a href="recuperar.php"> Pulsa aquí</a></p>
        </div>
    </div>
</body>

</html>