-- Esquema de Base de Datos - Sistema Cliente-Servidor
-- Arquitecturas 2025

-- Crear base de datos
CREATE DATABASE IF NOT EXISTS cliente_servidor_db
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE cliente_servidor_db;

-- Tabla de usuarios
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(80) NOT NULL,
    correo VARCHAR(120) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    actualizado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_correo (correo),
    INDEX idx_creado_en (creado_en)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de items
CREATE TABLE IF NOT EXISTS items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    estado ENUM('activo', 'inactivo') DEFAULT 'activo',
    usuario_id INT NOT NULL,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    actualizado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    INDEX idx_usuario_id (usuario_id),
    INDEX idx_estado (estado),
    INDEX idx_creado_en (creado_en)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de sesiones (opcional, para logout)
CREATE TABLE IF NOT EXISTS sesiones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    token_hash VARCHAR(255) NOT NULL,
    expira_en TIMESTAMP NOT NULL,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    INDEX idx_usuario_id (usuario_id),
    INDEX idx_token_hash (token_hash),
    INDEX idx_expira_en (expira_en)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertar datos de prueba (opcional)
INSERT INTO usuarios (nombre, correo, password_hash) VALUES
('Usuario Demo', 'demo@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'), -- password: password
('Admin Test', 'admin@test.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'); -- password: password

INSERT INTO items (nombre, descripcion, estado, usuario_id) VALUES
('Item Demo 1', 'Descripción del primer item de prueba', 'activo', 1),
('Item Demo 2', 'Descripción del segundo item de prueba', 'activo', 1),
('Item Admin 1', 'Item del usuario administrador', 'activo', 2);

-- Crear usuario de base de datos (ajustar según tu configuración)
-- CREATE USER 'cliente_servidor_user'@'localhost' IDENTIFIED BY 'tu_password_segura';
-- GRANT ALL PRIVILEGES ON cliente_servidor_db.* TO 'cliente_servidor_user'@'localhost';
-- FLUSH PRIVILEGES;

-- Verificar tablas creadas
SHOW TABLES;

-- Verificar estructura de tablas
DESCRIBE usuarios;
DESCRIBE items;
DESCRIBE sesiones;
