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
    $sql = "SELECT * FROM usuarios WHERE email = '$email' AND password = '$password'";
    $result = $pdo->query($sql);
    $user = $result->fetch();

    // Verificación de usuario
    if ($user) {
        // Si el usuario existe, inicia sesión y guarda la información en la sesión
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['tipo'] = $user['tipo'];

        // Redirección según el rol del usuario
        if ($user['tipo'] == 'tecnico') {
            //header('Location: lista_tickets.php');
            header('Location: crear_ticket.php');
        } else {
            header('Location: mis_tickets.php');
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
</head>

<body>
    <h2>Inicio de Sesión</h2>
    <?php if ($error) { ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php } ?>

    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="email">Email:</label>
        <input type="email" name="email" required><br><br>

        <label for="password">Contraseña:</label>
        <input type="password" name="password" required><br><br>

        <button type="submit">Ingresar</button>
    </form>
</body>

</html>