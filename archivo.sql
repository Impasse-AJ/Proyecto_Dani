CREATE DATABASE empresa;
USE empresa;

-- Tabla de usuarios
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    tipo ENUM('empleado', 'tecnico') NOT NULL
);

-- Tabla de tickets
CREATE TABLE tickets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    asunto VARCHAR(255) NOT NULL,
    descripcion TEXT NOT NULL,
    estado ENUM('creado', 'en proceso', 'solucionado', 'cerrado') DEFAULT 'creado',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

-- Tabla de mensajes
CREATE TABLE mensajes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ticket_id INT NOT NULL,
    tecnico_id INT NOT NULL,
    mensaje TEXT NOT NULL,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ticket_id) REFERENCES tickets(id) ON DELETE CASCADE,
    FOREIGN KEY (tecnico_id) REFERENCES usuarios(id) ON DELETE SET NULL
);
