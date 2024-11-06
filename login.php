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
            header('Location: mis_tickets.php');
        } else {
            header('Location: crear_ticket.php');
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
    <style>
        /* General */
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

        .error {
            color: red;
            margin-bottom: 15px;
            font-size: 14px;
        }

        a {
            color: #007bff;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .register-link {
            margin-top: 15px;
            font-size: 14px;
        }
    </style>
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