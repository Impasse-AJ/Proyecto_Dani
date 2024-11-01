<?php
$host = 'localhost';
$dbname = 'empresa';  // Asegúrate de que el nombre coincide con el que creaste
$user = 'root';               // Usuario de la base de datos, generalmente 'root' en local
$pass = '';                   // Contraseña del usuario de la base de datos, por lo general vacía en local

try {
    // Crea una instancia de PDO para la conexión
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
} catch (PDOException $e) {
    // Si la conexión falla, muestra el mensaje de error
    echo('Error en la conexión: ' . $e->getMessage());
}
?>
