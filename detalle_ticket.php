<?php
include 'conexion.php';
require 'sesiones.php';

comprobar_sesion();

// Verificar que el usuario sea técnico
if ($_SESSION['tipo'] !== 'tecnico') {
    header("Location: mis_tickets.php");
    exit();
}

// Obtener todos los tickets
$sql = "SELECT id, asunto, estado, fecha_creacion FROM tickets ORDER BY fecha_creacion ASC";
$tickets = $pdo->query($sql)->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Tickets</title>
</head>
<body>
    <h2>Gestión de Tickets</h2>

    <table>
        <tr>
            <th>ID</th>
            <th>Asunto</th>
            <th>Estado</th>
            <th>Fecha de Creación</th>
        </tr>
        <?php foreach ($tickets as $ticket) { ?>
            <tr>
                <td><a href="editar_ticket.php?id=<?php echo $ticket['id']; ?>"><?php echo $ticket['id']; ?></a></td>
                <td><?php echo htmlspecialchars($ticket['asunto']); ?></td>
                <td><?php echo $ticket['estado']; ?></td>
                <td><?php echo $ticket['fecha_creacion']; ?></td>
            </tr>
        <?php } ?>
    </table>
</body>
</html>