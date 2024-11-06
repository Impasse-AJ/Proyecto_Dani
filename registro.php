<?php
session_start();
include 'conexion.php';  // Conexión a la base de datos

$error = ''; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];  

    
    if (strlen($email) < 5 || strpos($email, '@') === false || strpos($email, '.') === false) {
        $error = "El formato del correo electrónico no es válido.";
    } else {
        
        $dominio = substr($email, strpos($email, '@') + 1);  // Extraemos el dominio

        if ($dominio == 'empresa.com') {
            $tipo = 'empleado';  // Es un empleado
        } elseif ( $dominio == 'soporte.empresa.com') {
            $tipo = 'tecnico';  // Es un técnico
        } else {
            $error = "El dominio del correo electrónico no es válido para ningún tipo de usuario.";
        }

        // Verificar si las contraseñas coinciden
        if ($password !== $confirm_password) {
            $error = "Las contraseñas no coinciden.";
        }


        if (!$error) {
            // Verificar si el usuario ya existe
            $sql = "SELECT * FROM usuarios WHERE email = '$email'";
            $result = $pdo->query($sql);
            $user = $result->fetch();  // Esta línea también debe cambiar si no usamos fetch

            if ($user) {
                $error = "Este correo electrónico ya está registrado.";
            } else {
                // Insertar el nuevo usuario en la base de datos (sin usar PDO preparado)
                $sql = "INSERT INTO usuarios (email, password, tipo) VALUES ('$email', '$password', '$tipo')";
                $pdo->query($sql);  

                // Redirigir al login
                header('Location: login.php');
                exit();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro</title>
    <style>
    
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
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
    </style>
</head>
<body>
    <div class="container">
        <h2>Registro de Usuario</h2>

        <?php if ($error) { ?>
            <p class="error"><?php echo $error; ?></p>
        <?php } ?>

        <form method="POST" action="registro.php">
            <label for="email">Correo Electrónico:</label>
            <input type="email" name="email" required><br><br>

            <label for="password">Contraseña:</label>
            <input type="password" name="password" required><br><br>

            <label for="confirm_password">Confirmar Contraseña:</label>
            <input type="password" name="confirm_password" required><br><br>

            <button type="submit">Registrar</button>
        </form>
        
        <br>
        <a href="login.php">¿Ya tienes cuenta? Inicia sesión</a>
    </div>
</body>
</html>
