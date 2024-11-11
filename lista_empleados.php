<?php
require 'sesiones.php';
include 'bd.php'; // Incluir archivo con funciones de base de datos
include 'cabecera.php'; // Incluir el archivo que contiene la cabezera
comprobar_sesion();


if ($_SESSION['tipo'] !== 'tecnico') {
    header("Location: mis_tickets.php");
    exit();
}

$empleados = obtenerListaEmpleados($pdo);

?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Empleados</title>
    <link rel="stylesheet" href="estilos/lista_empleados.css">
</head>
<body>
    <h2>Lista de Empleados</h2>
    <table>
        <tr>
            <th>Nombre</th>
            <th>Email</th>
            <th>Dirección</th>
            <th>Teléfono</th>
        </tr>
        <?php foreach ($empleados as $empleado) { ?>
            <tr>
                <td><?php echo htmlspecialchars($empleado['nombre']); ?></td>
                <td><?php echo htmlspecialchars($empleado['email']); ?></td>
                <td><?php echo htmlspecialchars($empleado['direccion']); ?></td>
                <td><?php echo htmlspecialchars($empleado['telefono']); ?></td>
            </tr>
        <?php } ?>
    </table>
</body>
</html>


