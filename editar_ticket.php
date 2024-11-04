<?php
include 'conexion.php';
require 'sesiones.php';
comprobar_sesion();

// Verificar que el usuario sea técnico
if ($_SESSION['tipo'] !== 'tecnico') {
    header("Location: no_autorizado.php");
    exit();
}

// Verificar que se haya especificado el ID del ticket
if (!isset($_GET['id'])) {
    echo "ID de ticket no especificado.";
    exit();
}

$ticket_id = $_GET['id'];

// Obtener los detalles del ticket
$sql = "SELECT * FROM tickets WHERE id = :ticket_id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['ticket_id' => $ticket_id]);
$ticket = $stmt->fetch();

if (!$ticket) {
    echo "Ticket no encontrado.";
    exit();
}

$confirmacion = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Actualizar el estado del ticket
    if (isset($_POST['estado'])) {
        $nuevo_estado = $_POST['estado'];
        $sql = "UPDATE tickets SET estado = :estado WHERE id = :ticket_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['estado' => $nuevo_estado, 'ticket_id' => $ticket_id]);
        $confirmacion = "El estado del ticket #$ticket_id se ha actualizado a '$nuevo_estado'.";
    }

    // Guardar el mensaje en la tabla de mensajes
    if (isset($_POST['mensaje']) && trim($_POST['mensaje']) !== '') {
        $mensaje = trim($_POST['mensaje']);
        $sql = "INSERT INTO mensajes (ticket_id, tecnico_id, mensaje) VALUES (:ticket_id, :tecnico_id, :mensaje)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'ticket_id' => $ticket_id,
            'tecnico_id' => $_SESSION['user_id'],
            'mensaje' => $mensaje
        ]);
        $confirmacion .= " El mensaje se ha guardado en el historial.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Ticket #<?php echo $ticket['id']; ?></title>
</head>
<body>
    <h2>Editar Ticket #<?php echo $ticket['id']; ?></h2>
    <p><strong>Asunto:</strong> <?php echo htmlspecialchars($ticket['asunto']); ?></p>
    <p><strong>Descripción:</strong> <?php echo htmlspecialchars($ticket['descripcion']); ?></p>
    <p><strong>Estado actual:</strong> <?php echo $ticket['estado']; ?></p>
    <p><strong>Fecha de creación:</strong> <?php echo $ticket['fecha_creacion']; ?></p>

    <?php if ($confirmacion) { ?>
        <p style="color: green;"><?php echo $confirmacion; ?></p>
    <?php } ?>

    <form method="POST" action="editar_ticket.php?id=<?php echo $ticket_id; ?>">
        <!-- Actualizar Estado -->
        <label for="estado">Nuevo Estado:</label>
        <select name="estado" required>
            <option value="creado" <?php if ($ticket['estado'] == 'creado') echo 'selected'; ?>>Creado</option>
            <option value="en proceso" <?php if ($ticket['estado'] == 'en proceso') echo 'selected'; ?>>En proceso</option>
            <option value="solucionado" <?php if ($ticket['estado'] == 'solucionado') echo 'selected'; ?>>Solucionado</option>
            <option value="cerrado" <?php if ($ticket['estado'] == 'cerrado') echo 'selected'; ?>>Cerrado</option>
        </select><br><br>

        <!-- Enviar Mensaje -->
        <label for="mensaje">Mensaje de seguimiento:</label><br>
        <textarea name="mensaje"></textarea><br><br>

        <button type="submit">Guardar Cambios</button>
    </form>
</body>
</html>
