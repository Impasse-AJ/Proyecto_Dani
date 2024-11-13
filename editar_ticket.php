<?php
include 'correo.php'; // Incluir archivo con la función de correo
include 'bd.php'; // Incluir archivo con funciones de base de datos
require 'sesiones.php';
comprobar_sesion();

// Verificar que el usuario sea técnico

// Verificar que se haya especificado el ID del ticket
if (!isset($_GET['id'])) {
    echo "ID de ticket no especificado.";
    exit();
}

$ticket_id = $_GET['id'];

// Obtener detalles del ticket y el email del usuario asociado
$ticket = obtenerDetallesTicket($pdo, $ticket_id);
if (!$ticket) {
    echo "Ticket no encontrado.";
    exit();
}
$email_usuario = obtenerEmailUsuario($pdo, $ticket['usuario_id']);

$confirmacion = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nuevo_estado = $_POST['estado'] ?? $ticket['estado'];
    $mensaje_tecnico = trim($_POST['mensaje'] ?? '');

    // Actualizar el estado del ticket y eliminar si es "cerrado"
    if ($nuevo_estado !== $ticket['estado']) {
        if ($nuevo_estado === 'cerrado') {
            eliminarTicket($pdo, $ticket_id);
            header("Location: detalle_ticket.php");
            exit();
        }
        actualizarEstadoTicket($pdo, $ticket_id, $nuevo_estado);
        $confirmacion = "El estado del ticket #$ticket_id se ha actualizado a '$nuevo_estado'.";
    }

    // Guardar el mensaje en la tabla de mensajes
    if ($mensaje_tecnico !== '') {
        guardarMensaje($pdo, $ticket_id, $_SESSION['user_id'], $mensaje_tecnico);
        $confirmacion .= " El mensaje se ha guardado en el historial.";
    }

    // Enviar correo de actualización al usuario si se cambió el estado o se agregó un mensaje
    if ($nuevo_estado !== $ticket['estado'] || $mensaje_tecnico !== '') {
        enviarActualizacionTicket($email_usuario, $ticket_id, $nuevo_estado, $mensaje_tecnico);
    }
}

// Obtener el historial de mensajes
$historialMensajes = obtenerHistorialMensajes($pdo, $ticket_id);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<link rel="stylesheet" href="estilos/editar_ticket.css">
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

    <h3>Historial de Mensajes</h3>
    <?php if (is_string($historialMensajes)) { ?>
        <p><?php echo $historialMensajes; ?></p>
    <?php } else { ?>
        <table>
            <tr>
                <th>ID Mensaje</th>
                <th>Correo</th>
                <th>Fecha/Hora</th>
                <th>Mensaje</th>
            </tr>
            <?php foreach ($historialMensajes as $mensaje) {
                // Si el mensaje fue enviado por un técnico (tecnico_id no es NULL)
                if ($mensaje['tecnico_id']) {
                    // Obtener el correo del técnico usando su ID
                    $correo = obtenerCorreoTecnico($pdo, $mensaje['tecnico_id']);
                } else {
                    // Si el mensaje fue enviado por el usuario (tecnico_id es NULL)
                    // Obtener el correo del usuario usando su ID
                    $correo = obtenerEmailUsuario($pdo, $mensaje['usuario_id']);
                }
            ?>
                <tr>
                    <td><?php echo $mensaje['id']; ?></td>
                    <td><?php echo $correo ? htmlspecialchars($correo) : 'Correo no disponible'; ?></td>
                    <td><?php echo $mensaje['fecha']; ?></td>
                    <td><?php echo htmlspecialchars($mensaje['mensaje']); ?></td>
                </tr>
            <?php } ?>
        </table>
    <?php } ?>

    <!-- El formulario siempre debe mostrarse independientemente del historial de mensajes -->
    <form method="POST" action="editar_ticket.php?id=<?php echo $ticket_id; ?>">
        <label for="estado">Nuevo Estado</label>
        <select name="estado" required>
            <option value="creado" <?php if ($ticket['estado'] == 'creado') echo 'selected'; ?>>Creado</option>
            <option value="en proceso" <?php if ($ticket['estado'] == 'en proceso') echo 'selected'; ?>>En proceso</option>
            <option value="solucionado" <?php if ($ticket['estado'] == 'solucionado') echo 'selected'; ?>>Solucionado</option>
            <option value="cerrado" <?php if ($ticket['estado'] == 'cerrado') echo 'selected'; ?>>Cerrado</option>
        </select><br>

        <label for="mensaje">Mensaje de seguimiento</label><br>
        <textarea name="mensaje"></textarea><br><br>

        <button type="submit">Guardar Cambios</button>
    </form>

</body>
</html>

