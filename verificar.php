<?php
include 'conexion.php';

// Verificar que se haya recibido el user_id
if (!isset($_GET['seguridad'])) {
    echo "ID de usuario no especificado.";
    exit();
}

$numSeguridad = $_GET['seguridad'];

// Actualizar el estado de verificación en la base de datos
$sql = "UPDATE usuarios SET verificado = 1 WHERE seguridad = :numSeguridad";
$stmt = $pdo->prepare($sql);
$stmt->execute(['numSeguridad' => $numSeguridad]);

// Comprobar si la actualización fue exitosa
if ($stmt->rowCount() > 0) {
    echo "Correo verificado con éxito. Ahora puede iniciar sesión <a href='http://localhost/Proyecto_Dani/login.php'>Pulsa Aquí</a>.";
} else {
    echo "La verificación ha fallado o el usuario ya está verificado.";
}
