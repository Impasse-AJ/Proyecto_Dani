-- Crear la base de datos
CREATE DATABASE soporte_tecnico;

-- Usar la base de datos
USE soporte_tecnico;

-- Tabla de usuarios
CREATE TABLE Usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    tipo ENUM('empleado', 'tecnico') NOT NULL
);

-- Tabla de tickets
CREATE TABLE Tickets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    asunto VARCHAR(255) NOT NULL,
    descripcion TEXT NOT NULL,
    estado ENUM('creado', 'en proceso', 'solucionado', 'cerrado') NOT NULL DEFAULT 'creado',
    fecha_creacion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    usuario_id INT NOT NULL,
    FOREIGN KEY (usuario_id) REFERENCES Usuarios(id) ON DELETE CASCADE
);

-- Tabla de respuestas
CREATE TABLE Respuestas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ticket_id INT NOT NULL,
    mensaje TEXT NOT NULL,
    fecha_respuesta DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    tecnico_id INT NOT NULL,
    FOREIGN KEY (ticket_id) REFERENCES Tickets(id) ON DELETE CASCADE,
    FOREIGN KEY (tecnico_id) REFERENCES Usuarios(id) ON DELETE CASCADE
);