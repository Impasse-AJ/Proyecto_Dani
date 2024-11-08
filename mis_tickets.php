<?php
require 'sesiones.php';
include 'bd.php'; // Incluir archivo con funciones de base de datos
include 'cabecera.php'; // Incluir el archivo que contiene la cabezera
comprobar_sesion();

// Verificar si el usuario es un empleado
if ($_SESSION['tipo'] !== 'empleado') {
    // Redirigir a una página de error o de inicio si el usuario no es un empleado
    header("Location: detalle_ticket.php");
    exit();
}

// Obtener el ID del usuario en sesión y sus tickets
$usuario_id = $_SESSION['user_id'];
$tickets = obtenerTicketsUsuario($pdo, $usuario_id); // Llamar a la función de bd.php

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Mis Tickets</title>
    <link rel="stylesheet" href="estilos/misTickets.css">
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
                    <td><?php echo htmlspecialchars(strtoupper($ticket['asunto'])); ?></td>
                    <td><?php echo strtoupper($ticket['estado']); ?></td>
                    <td><?php echo $ticket['fecha_creacion']; ?></td>
                </tr>
            <?php } ?>
        </table>
    <?php } ?>

    <br>
    <a href="crear_ticket.php">Crear Nuevo Ticket</a> 
    <a href="editar_perfil.php">Mi perfil</a>
</body>

</html>