<!DOCTYPE html>
<html lang="es">
<head>
<?php require_once 'sesiones.php';?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="estilos/cabecera.css">
    <title>Página de Usuario</title>
   <style>
    /* Estilo global */
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
}

/* Estilos para la cabecera */
header {
    display: flex;
    justify-content: flex-start;
    background-color: white; 
    padding: 10px 20px;
    color: black;  
    position: fixed;  
    top: 0;  
    left: 0;  
    width: 100%;  
    z-index: 1000;  
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); 
   
}

header a {
    color: black; 
    text-decoration: none;
    padding: 10px 20px;  
    transition: background-color 0.3s ease;
    margin: 0;  
    border: 1px solid black;
}



   </style>
</head>
<body>
    <header>
        <!-- Enlaces de navegación en la cabecera -->
        <a href="login.php">Login</a>
        <a href="crear_ticket.php">Crear tickets</a> 
        <a href="logout.php">Cerrar sesión</a>
    </header>

   

</body>
</html>
