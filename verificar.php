<?php
include 'conexion.php';

// Verificar que se haya recibido el user_id
if (!isset($_GET['user_id'])) {
    echo "ID de usuario no especificado.";
    exit();
}

$user_id = $_GET['user_id'];

// Actualizar el estado de verificación en la base de datos
$sql = "UPDATE usuarios SET verificado = 1 WHERE id = :user_id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['user_id' => $user_id]);

// Comprobar si la actualización fue exitosa
if ($stmt->rowCount() > 0) {
    echo "Correo verificado con éxito. Ahora puede iniciar sesión <a href='http://localhost/Proyecto_Dani/login.php'>Pulsa Aquí</a>.";
} else {
    echo "La verificación ha fallado o el usuario ya está verificado.";
}
