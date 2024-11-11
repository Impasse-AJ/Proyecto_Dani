<?php
include 'conexion.php';

// Función para insertar un ticket y devolver el ID del ticket recién creado
function insertarTicket($pdo, $usuario_id, $asunto, $descripcion)
{
    $sql = "INSERT INTO tickets (usuario_id, asunto, descripcion) VALUES (:usuario_id, :asunto, :descripcion)";
    $stmt = $pdo->prepare($sql);

    if ($stmt->execute(['usuario_id' => $usuario_id, 'asunto' => $asunto, 'descripcion' => $descripcion])) {
        return $pdo->lastInsertId(); // Devuelve el ID del ticket creado
    }
    return false;
}

// Función para obtener el correo electrónico del usuario por su ID
function obtenerEmailUsuario($pdo, $usuario_id)
{
    $sql = "SELECT email FROM usuarios WHERE id = :usuario_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['usuario_id' => $usuario_id]);
    $usuario = $stmt->fetch();

    return $usuario ? $usuario['email'] : null; // Devuelve el correo o null si no se encuentra
}

// Función para obtener todos los tickets
function obtenerTodosLosTickets($pdo)
{
    $sql = "SELECT id, asunto, estado, fecha_creacion FROM tickets ORDER BY fecha_creacion ASC";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(); // Devuelve todos los registros
}

// Función para obtener los tickets de un usuario específico
function obtenerTicketsUsuario($pdo, $usuario_id)
{
    $sql = "SELECT id, asunto, estado, fecha_creacion FROM tickets WHERE usuario_id = :usuario_id ORDER BY fecha_creacion ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['usuario_id' => $usuario_id]);
    return $stmt->fetchAll();
}

// Función para obtener detalles de un ticket específico
function obtenerDetallesTicket($pdo, $ticket_id)
{
    $sql = "SELECT * FROM tickets WHERE id = :ticket_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['ticket_id' => $ticket_id]);
    return $stmt->fetch();
}

// Función para obtener el historial de mensajes de un ticket
function obtenerHistorialMensajes($pdo, $ticket_id)
{
    $sql = "SELECT id, tecnico_id, fecha, mensaje FROM mensajes WHERE ticket_id = :ticket_id ORDER BY fecha DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['ticket_id' => $ticket_id]);
    $mensajes = $stmt->fetchAll();
    return empty($mensajes) ? "No han habido reportes" : $mensajes;
}

// Función para eliminar un ticket y sus mensajes asociados
function eliminarTicket($pdo, $ticket_id)
{
    $sql = "DELETE FROM mensajes WHERE ticket_id = :ticket_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['ticket_id' => $ticket_id]);

    $sql = "DELETE FROM tickets WHERE id = :ticket_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['ticket_id' => $ticket_id]);
}

// Función para actualizar el estado de un ticket
function actualizarEstadoTicket($pdo, $ticket_id, $nuevo_estado)
{
    $sql = "UPDATE tickets SET estado = :estado WHERE id = :ticket_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['estado' => $nuevo_estado, 'ticket_id' => $ticket_id]);
}

// Función para guardar un mensaje en la tabla de mensajes
function guardarMensaje($pdo, $ticket_id, $tecnico_id, $mensaje)
{
    $sql = "INSERT INTO mensajes (ticket_id, tecnico_id, mensaje) VALUES (:ticket_id, :tecnico_id, :mensaje)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['ticket_id' => $ticket_id, 'tecnico_id' => $tecnico_id, 'mensaje' => $mensaje]);
}

// Verifica si un usuario ya está registrado
function usuarioExiste($pdo, $email)
{
    $sql = "SELECT id FROM usuarios WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['email' => $email]);
    return $stmt->fetch() !== false;
}

// Inserta un nuevo usuario en la base de datos con contraseña hasheada y devuelve su ID
function registrarUsuario($pdo, $email, $password, $tipo)
{
    $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Hashear la contraseña
    $sql = "INSERT INTO usuarios (email, password, tipo) VALUES (:email, :password, :tipo)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['email' => $email, 'password' => $hashed_password, 'tipo' => $tipo]);
    return $pdo->lastInsertId();
}

// Función para obtener un usuario por su correo electrónico
function obtenerUsuarioPorEmail($pdo, $email)
{
    $sql = "SELECT * FROM usuarios WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['email' => $email]);
    return $stmt->fetch();
}

// Función para obtener información de usuario por ID
function obtenerUsuario($pdo, $user_id) {
    $sql = "SELECT * FROM usuarios WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$user_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Función para actualizar información de usuario
function actualizarUsuario($pdo, $user_id, $telefono, $nombre, $direccion) {
    $sql = "UPDATE usuarios SET telefono = ?, nombre = ?, direccion = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$telefono, $nombre, $direccion, $user_id]);
}

function crearNumeroAleatorio($pdo, $user_id) {
    srand (time());
    $rnd1 = rand(1, 1000000000000000000);
    $rnd2 = rand(1, 1000000000000000000);
    $rnd3 = rand(1, 1000000000000000000);
    $rnd4 = rand(1, 1000000000000000000);
    $rnd = "$rnd1"."$rnd2"."$rnd3" . "$rnd4";
    $sql = "UPDATE usuarios SET seguridad = :seguridad WHERE id = :user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['seguridad' => $rnd, 'user_id' => $user_id]);
}