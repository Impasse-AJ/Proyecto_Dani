<?php
$host = 'localhost';
$dbname = 'soporte';
$user = 'root';               // Usuario
$pass = '';                   // Contraseña

try {
    // Crea una instancia de PDO para la conexión
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
} catch (PDOException $e) {
    // Si la conexión falla, muestra el mensaje de error
    echo('Error en la conexión: ' . $e->getMessage());
}
?>
