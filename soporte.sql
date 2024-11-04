CREATE DATABASE soporte;
USE soporte;

-- Tabla de usuarios
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    tipo ENUM('empleado', 'tecnico') NOT NULL
);

-- Tabla de tickets con clave foránea a usuarios
CREATE TABLE tickets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    asunto VARCHAR(255) NOT NULL,
    descripcion TEXT NOT NULL,
    estado ENUM('creado', 'en proceso', 'solucionado', 'cerrado') DEFAULT 'creado',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

-- Tabla de mensajes con claves foráneas a tickets y usuarios
CREATE TABLE mensajes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ticket_id INT NOT NULL,
    tecnico_id INT NULL,  -- Permitir NULL para usar ON DELETE SET NULL
    mensaje TEXT NOT NULL,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ticket_id) REFERENCES tickets(id) ON DELETE CASCADE,
    FOREIGN KEY (tecnico_id) REFERENCES usuarios(id) ON DELETE SET NULL
);

-- Insertar datos de técnicos en la tabla de usuarios
INSERT INTO usuarios (email, password, tipo) VALUES
    ('abraham@empresa.com', '1234', 'tecnico'),
    ('alejo@empresa.com', '1234', 'tecnico'),
    ('alex@empresa.com', '1234', 'tecnico');
