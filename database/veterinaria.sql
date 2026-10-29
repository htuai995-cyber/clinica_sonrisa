CREATE DATABASE IF NOT EXISTS clinica_odontologica_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE clinica_odontologica_db;

CREATE TABLE IF NOT EXISTS usuarios (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(120) NOT NULL,
    email VARCHAR(180) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    rol ENUM('admin', 'odontologo', 'recepcion') NOT NULL DEFAULT 'recepcion',
    activo TINYINT(1) NOT NULL DEFAULT 1,
    creado TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS password_resets (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT UNSIGNED NOT NULL,
    token_hash CHAR(64) NOT NULL UNIQUE,
    expira DATETIME NOT NULL,
    FOREIGN KEY (usuario_id) REFERENCES usuarios (id) ON DELETE CASCADE
) ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS pacientes (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombres VARCHAR(120) NOT NULL,
    apellidos VARCHAR(120) NOT NULL,
    dni VARCHAR(15) UNIQUE NOT NULL,
    telefono VARCHAR(25),
    email VARCHAR(150),
    direccion VARCHAR(255),
    fecha_nacimiento DATE
) ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS especialidades (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(120) NOT NULL UNIQUE
) ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS odontologos (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombres VARCHAR(120) NOT NULL,
    apellidos VARCHAR(120) NOT NULL,
    dni VARCHAR(15) UNIQUE NOT NULL,
    especialidad_id INT UNSIGNED NOT NULL,
    telefono VARCHAR(25),
    email VARCHAR(150),
    FOREIGN KEY (especialidad_id) REFERENCES especialidades (id)
) ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS servicios (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(120) NOT NULL,
    descripcion TEXT,
    precio DECIMAL(10, 2) NOT NULL DEFAULT 0,
    duracion_min INT
) ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS citas (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    paciente_id INT UNSIGNED NOT NULL,
    odontologo_id INT UNSIGNED NOT NULL,
    servicio_id INT UNSIGNED NOT NULL,
    fecha_hora DATETIME NOT NULL,
    estado ENUM('pendiente', 'confirmada', 'atendida', 'cancelada') DEFAULT 'pendiente',
    motivo TEXT,
    FOREIGN KEY (paciente_id) REFERENCES pacientes (id),
    FOREIGN KEY (odontologo_id) REFERENCES odontologos (id),
    FOREIGN KEY (servicio_id) REFERENCES servicios (id),
    INDEX (fecha_hora)
) ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS diagnosticos (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    cita_id INT UNSIGNED NOT NULL,
    diagnostico TEXT NOT NULL,
    observaciones TEXT,
    fecha DATE,
    FOREIGN KEY (cita_id) REFERENCES citas (id)
) ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS tratamientos (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    diagnostico_id INT UNSIGNED NOT NULL,
    descripcion_tratamiento TEXT NOT NULL,
    indicaciones TEXT,
    costo DECIMAL(10, 2),
    FOREIGN KEY (diagnostico_id) REFERENCES diagnosticos (id)
) ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS pagos (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    cita_id INT UNSIGNED NOT NULL,
    monto DECIMAL(10, 2) NOT NULL,
    metodo ENUM('efectivo', 'tarjeta_credito', 'tarjeta_debito', 'yape') NOT NULL,
    estado ENUM('pendiente', 'pagado', 'anulado') DEFAULT 'pendiente',
    referencia VARCHAR(120),
    fecha_pago DATETIME,
    FOREIGN KEY (cita_id) REFERENCES citas (id)
) ENGINE=INNODB;

INSERT INTO servicios(nombre, descripcion, precio, duracion_min)
SELECT 'Consulta Odontológica General', 'Evaluación bucal inicial y diagnóstico', 50, 30
WHERE NOT EXISTS (
    SELECT 1 FROM servicios WHERE nombre = 'Consulta Odontológica General'
);
