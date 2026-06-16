CREATE DATABASE IF NOT EXISTS restaurante CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE restaurante;

CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre_usuario VARCHAR(50) NOT NULL UNIQUE,
    correo VARCHAR(100) NOT NULL,
    contrasena VARCHAR(255) NOT NULL,
    rol ENUM('admin','usuario') NOT NULL DEFAULT 'usuario',
    estado ENUM('activo','inactivo') NOT NULL DEFAULT 'activo'
);

INSERT INTO usuarios (nombre_usuario, correo, contrasena, rol, estado) VALUES
('admin', 'admin@lamesa.com', '$2b$10$ID/2Ushm.w/97puolVSxuuEKg18xv4qmUnnCkTAvE57GfFpASruR2', 'admin', 'activo');
