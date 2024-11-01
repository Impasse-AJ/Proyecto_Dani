<?php
include 'conexion.php';
require 'sesiones.php';
comprobar_sesion();

$error = '';
$confirmacion = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validación de datos del formulario
    $asunto = trim($_POST['asunto']);
    $descripcion = trim($_POST['descripcion']);

    // Restricciones de longitud
    if (strlen($asunto) > 100) {
        $error = "El asunto no puede exceder los 100 caracteres.";
    } elseif (strlen($descripcion) > 1000) {
        $error = "La descripción no puede exceder los 1000 caracteres.";
    } else {
        // Inserción en la base de datos
        $usuario_id = $_SESSION['user_id'];  // ID del usuario en sesión, técnico o empleado
        $sql = "INSERT INTO tickets (usuario_id, asunto, descripcion) VALUES ('$usuario_id', '$asunto', '$descripcion')";

        if ($pdo->query($sql)) {
            $confirmacion = "Ticket creado exitosamente.";
        } else {
            $error = "Hubo un problema al crear el ticket. Inténtalo de nuevo.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Ticket</title>
</head>
<body>
    <h2>Crear un Nuevo Ticket</h2>

    <?php if ($error) { ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php } elseif ($confirmacion) { ?>
        <p style="color: green;"><?php echo $confirmacion; ?></p>
    <?php } ?>

    <form method="POST" action="crear_ticket.php">
        <label for="asunto">Asunto:</label><br>
        <input type="text" name="asunto" maxlength="100" required><br><br>

        <label for="descripcion">Descripción:</label><br>
        <textarea name="descripcion" maxlength="1000" required></textarea><br><br>

        <button type="submit">Crear Ticket</button>
    </form>

    <br>
    <a href="mis_tickets.php">Ver Mis Tickets</a> <!-- Enlace para ver los tickets del usuario -->
</body>
</html>
