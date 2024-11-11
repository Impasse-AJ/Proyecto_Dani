<?php
include 'conexion.php';
require 'sesiones.php';
include 'cabecera.php';
comprobar_sesion();

$user_id = $_SESSION['user_id'];

// Obtener la información actual del usuario
$sql = "SELECT * FROM usuarios WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$mensaje = ''; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $telefono = $_POST['telefono'];
    $nombre = $_POST['nombre'];
    $direccion = $_POST['direccion'];
    
    // Actualizar la información del usuario
    $sql_update = "UPDATE usuarios SET telefono = ?, nombre = ?, direccion = ? WHERE id = ?";
    $stmt_update = $pdo->prepare($sql_update);
    $stmt_update->execute([$telefono, $nombre, $direccion, $user_id]);

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
            <input type="text" name="nombre" value="<?php echo isset($user['nombre']) ? htmlspecialchars($user['nombre']) : ''; ?>" required>

            <label>Teléfono</label>
            <input type="text" name="telefono" value="<?php echo isset($user['telefono']) ? htmlspecialchars($user['telefono']) : ''; ?> " required>

            <label>Departamento</label>
            <input type="text" name="direccion" value="<?php echo isset($user['direccion']) ? htmlspecialchars($user['direccion']) : ''; ?>" required>

            <button type="submit">Guardar Cambios</button>
        </form>
    <?php endif; ?>

</body>
</html>

