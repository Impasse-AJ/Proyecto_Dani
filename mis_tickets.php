<?php
include 'conexion.php';
require 'sesiones.php';
comprobar_sesion();

$is_tecnico = $_SESSION['tipo'] === 'tecnico';

$tickets = [];


if ($is_tecnico) {
    $sql = "SELECT * FROM tickets ORDER BY fecha_creacion DESC";
    $stmt = $pdo->query($sql);
} else {
    $usuario_id = $_SESSION['user_id'];
    $sql = "SELECT * FROM tickets WHERE usuario_id = '$usuario_id' ORDER BY fecha_creacion DESC";
    $stmt = $pdo->query($sql);
}

// array con todos los tickets
$tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);


if ($_SERVER["REQUEST_METHOD"] == "POST" && $is_tecnico) {
    // actualizar el estado del ticket
    if (isset($_POST['ticket_id']) && isset($_POST['estado'])) {
        $ticket_id = $_POST['ticket_id'];
        $nuevo_estado = $_POST['estado'];

        if ($nuevo_estado === 'cerrado') {
            // eliminar el ticket si se cierra
            $sql_eliminar = "DELETE FROM tickets WHERE id = $ticket_id";
            $stmt_delete = $pdo->query($sql_eliminar);
        } else {

            $sql_actualizar = "UPDATE tickets SET estado = '$nuevo_estado' WHERE id = $ticket_id";
    
            if ($pdo->query($sql_actualizar)) {
            } else {
                echo "<p style='color: red;'>Error al actualizar el ticket. Por favor, inténtalo de nuevo.</p>";
            }
        }
    }
}

// consultar los tickets después de cualquier actualización
if ($is_tecnico) {
    $stmt = $pdo->query("SELECT * FROM tickets ORDER BY fecha_creacion DESC");
} else {
    $usuario_id = $_SESSION['user_id'];
    $stmt = $pdo->query("SELECT * FROM tickets WHERE usuario_id = '$usuario_id' ORDER BY fecha_creacion DESC");
}

$tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis Tickets</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>
    <h2><?php echo $is_tecnico ? "Tickets de Todos los Empleados" : "Mis Tickets"; ?></h2>

    <?php if (empty($tickets)) { ?>
        <p>No hay tickets creados.</p>
    <?php } else { ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Asunto</th>
                    <th>Descripción</th>
                    <th>Estado</th>
                    <th>Fecha de Creación</th>
                    <?php if ($is_tecnico) { ?>
                        <th>Acción</th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tickets as $ticket) { ?>
                    <tr>
                        <td><?php echo $ticket['id']; ?></td>
                        <td><?php echo $ticket['asunto']; ?></td>
                        <td><?php echo $ticket['descripcion']; ?></td>
                        <td><?php echo $ticket['estado']; ?></td>
                        <td><?php echo $ticket['fecha_creacion']; ?></td>
                        <?php if ($is_tecnico) { ?>
                            <td>
                                <form method="POST" action="">
                                    <input type="hidden" name="ticket_id" value="<?php echo$ticket['id']; ?>">
                                    <select name="estado" required>
                                        <option value="abierto" <?php if ($ticket['estado'] == 'abierto') echo 'selected'; ?>>Abierto</option>
                                        <option value="en proceso" <?php if ($ticket['estado'] == 'en procreso') echo 'selected'; ?>>En Progreso</option>
                                        <option value="cerrado" <?php if ($ticket['estado'] == 'cerrado') echo 'selected'; ?>>Cerrar</option>
                                    </select>
                                    <button type="submit">Actualizar Estado</button>
                                </form>
                            </td>
                        <?php } ?>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } ?>

    <br>
    <a href="crear_ticket.php">Crear un Nuevo Ticket</a>
</body>
</html>
