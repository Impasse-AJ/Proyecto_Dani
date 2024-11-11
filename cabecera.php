<!DOCTYPE html>
<html lang="es">
<head>
    <?php require_once 'sesiones.php';?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página de Usuario</title>
    <style>
        /* Estilo global */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
        }

        /* Estilos para la cabecera */
        header {
            background-color: #333;
            padding: 10px 20px;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        /* Menú principal */
        #menu {
            list-style-type: none;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
        }

        #menu > li {
            position: relative;
        }

        #menu > li > a {
            text-decoration: none;
            color: white;
            padding: 10px 15px;
            display: block;
            font-size: 20px;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        #menu > li > a:hover {
            background-color: #555;
            border-radius: 5px;
        }

        /* Submenú */
        #menu ul {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            background: #333;
            list-style-type: none;
            padding: 10px 0;
            margin: 0;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            border-radius: 5px;
        }

        /* Ítems del submenú */
        #menu ul li {
            width: 150px;
        }

        #menu ul a {
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            display: block;
            transition: background-color 0.3s;
        }

        /* Cambiar color de fondo al pasar el ratón en el submenú */
        #menu ul a:hover {
            background-color: #555;
            border-radius: 5px;
        }

        /* Mostrar el submenú al pasar el ratón */
        #menu > li:hover > ul {
            display: block;
        }
        header a{
            background-color: #333;
        }
    </style>
</head>
<body>
    <header>
        
        <ul id="menu">
            <li>
                <a href="">☰ Menú</a>
                <ul>
                    <li><a href="editar_perfil.php">Perfil</a></li>
                    <li><a href="logout.php">Cerrar sesión</a></li>
                </ul>
            </li>
        </ul>
    </header>
</body>
</html>
