<?php
require_once 'sesiones.php';	
comprobar_sesion();
$_SESSION = [];
session_destroy();	// eliminar la sesion
?>

<!DOCTYPE html>
<html>
	<head>
	<link rel="stylesheet" href="estilos/logout.css">
		<meta charset = "UTF-8">
		<title>Sesión cerrada</title>
	</head>
	<body>
		<div class="container">
			<h1>Sesión Cerrada</h1>
			<p>La sesión se cerró correctamente, hasta la próxima.</p>
			<a href="login.php">Ir a la página de login</a>
		</div>
	</body>
</html>