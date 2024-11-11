CREATE DATABASE soporte;
USE soporte;

-- Tabla de usuarios
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    tipo ENUM('empleado', 'tecnico') NOT NULL,
    verificado INT DEFAULT 0,  -- Estado de verificación (0 = no verificado, 1 = verificado)
    telefono VARCHAR(15),     
    nombre VARCHAR(50),         
    direccion VARCHAR(50),
    seguridad VARCHAR(100)   
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
INSERT INTO usuarios (email, password, tipo, verificado) VALUES
    ('abraham@empresa.com', '$2y$10$.Nv85hA0moj4J05x30kE0eg.4gvNXbyk8Ti2Fcyj7m13xq.rOEoM2', 'tecnico',1),
    ('alejo@empresa.com', '$2y$10$Or3EsqlxG0HUetfHEODN7.G.dIfhlNw30SjpbHjg7E2cVjvAxV27u', 'tecnico',1),
    ('alex@empresa.com', '$2y$10$jywtKbPKRlXFxMZrBXUInOQJUdQG7N0jDO1DaKP6c687nIeR.EIZu', 'tecnico',1),
    ('empleado1@empresa.com', '$2y$10$Kqu8hAbbuAprxq7kMzEc8uW5lwwo/wIBJacByYVrO.Ea8RLU6m.K2', 'empleado',1);