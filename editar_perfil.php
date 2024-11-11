<?php
include 'bd.php';
require 'sesiones.php';
include 'cabecera.php';
comprobar_sesion();

$user_id = $_SESSION['user_id'];

// Obtener la información actual del usuario
$user = obtenerUsuario($pdo, $user_id);

$mensaje = ''; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $telefono = $_POST['telefono'];
    $nombre = $_POST['nombre'];
    $direccion = $_POST['direccion'];
    
    // Actualizar la información del usuario
    actualizarUsuario($pdo, $user_id, $telefono, $nombre, $direccion);

    $mensaje = "Tu perfil se ha actualizado correctamente.";
    header("refresh:3;url=mis_tickets.php");
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Perfil de Usuario</title>
    <link rel="stylesheet" href="Estilos/editar_perfil.css">
</head>
<body>
    <h2>Perfil de Usuario</h2>

    <?php if ($mensaje): ?>
        <p style="color: green;"><?php echo $mensaje; ?></p>
        <p>Serás redirigido a tus tickets en breve...</p>
        <p><a href="mis_tickets.php">Ir a Mis Tickets</a></p>
    <?php else: ?>
        <!-- Mostrar formulario solo si no se ha actualizado el perfil -->
        <form method="POST" action="">
            <label>Nombre</label>
            <input type="text" name="nombre" value="<?php echo htmlspecialchars($user['nombre'] ?? ''); ?>" required>

            <label>Teléfono</label>
            <input type="text" name="telefono" value="<?php echo htmlspecialchars($user['telefono'] ?? ''); ?>" required pattern="^6\d{8}$">

            <label>Dirección</label>
            <input type="text" name="direccion" value="<?php echo htmlspecialchars($user['direccion'] ?? ''); ?>" required pattern="^[A-Za-z0-9\s,.-]+$">

            <button type="submit">Guardar Cambios</button>
        </form>
    <?php endif; ?>

</body>
</html>
