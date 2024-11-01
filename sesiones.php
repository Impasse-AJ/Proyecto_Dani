<?php
session_start();
function comprobar_sesion()
{
    if (!isset($_SESSION['user_id'])) {
        // Redirigir a la página de inicio de sesión si no está autenticado
        header("Location: login.php");
        exit();
    }
}
