<?php
session_start();
include 'conexion.php'; // Conexión a la base de datos

// Variables para mensajes
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
        // Si el usuario existe, mostramos el formulario para restablecer la contraseña
        if (isset($_POST['nueva_contraseña'])) {
            $nueva_contraseña = $_POST['nueva_contraseña'];
            $confirmar_contraseña = $_POST['confirmar_contraseña'];
            
            // Verificar que las contraseñas coinciden
            if ($nueva_contraseña == $confirmar_contraseña) {
                // Actualizar la contraseña en la base de datos
                $sql = "UPDATE usuarios SET password = '$nueva_contraseña' WHERE email = '$email'";
                $pdo->exec($sql);
                $mensaje = "Tu contraseña ha sido cambiada exitosamente.";
            } else {
                $mensaje_error = "Las contraseñas no coinciden. Intenta nuevamente.";
            }
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
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7fc;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100%;
        }

        .container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        h2 {
            margin-bottom: 20px;
            color: #333;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        label {
            margin-bottom: 5px;
            text-align: center;
            width: 100%;
        }

        input[type="email"], input[type="password"] {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
            width: 100%;
            max-width: 320px;
        }

        button {
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            width: 100%;
            max-width: 320px;
        }

        button:hover {
            background-color: #0056b3;
        }

        .error, .success {
            margin-bottom: 15px;
            font-size: 14px;
        }

        .error {
            color: red;
        }

        .success {
            color: green;
        }

        a {
            color: #007bff;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

    </style>
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
