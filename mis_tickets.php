<?php
include 'conexion.php';
require 'sesiones.php';
comprobar_sesion();

// Verificar si el usuario es un empleado
if ($_SESSION['tipo'] !== 'empleado') {
    // Redirigir a una página de error o de inicio si el usuario no es un empleado
    header("Location: detalle_ticket.php");
    exit();
}

// Obtener el ID del usuario en sesión
$usuario_id = $_SESSION['user_id'];

// Consulta para obtener solo los tickets del empleado en sesión, en orden descendente
$sql = "SELECT id, asunto, estado, fecha_creacion FROM tickets WHERE usuario_id = :usuario_id ORDER BY fecha_creacion DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute(['usuario_id' => $usuario_id]);
$tickets = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis Tickets</title>
</head>
<body>
    <h2>Mis Tickets</h2>

    <?php if (empty($tickets)) { ?>
        <p>No tienes tickets creados.</p>
    <?php } else { ?>
        <table>
            <tr>
                <th>ID</th>
                <th>Asunto</th>
                <th>Estado</th>
                <th>Fecha de Creación</th>
            </tr>
            <?php foreach ($tickets as $ticket) { ?>
                <tr>
                    <td><?php echo $ticket['id']; ?></td>
                    <td><?php echo htmlspecialchars($ticket['asunto']); ?></td>
                    <td><?php echo $ticket['estado']; ?></td>
                    <td><?php echo $ticket['fecha_creacion']; ?></td>
                </tr>
            <?php } ?>
        </table>
    <?php } ?>

    <br>
    <a href="crear_ticket.php">Crear Nuevo Ticket</a> <!-- Enlace para crear un nuevo ticket -->
</body>
</html>
