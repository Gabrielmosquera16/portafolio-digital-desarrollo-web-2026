-- Esquema inicial del Portafolio Digital Estudiantil.
-- Importar este archivo desde phpMyAdmin antes de database/seed.sql.
CREATE DATABASE IF NOT EXISTS portafolio_digital
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE portafolio_digital;

CREATE TABLE IF NOT EXISTS perfil_estudiante (
  id TINYINT UNSIGNED NOT NULL PRIMARY KEY,
  nombre_completo VARCHAR(160) NOT NULL,
  formacion VARCHAR(180) NOT NULL,
  estudios_actuales VARCHAR(180) NOT NULL,
  cargo VARCHAR(180) NOT NULL,
  organizacion VARCHAR(180) NOT NULL,
  biografia TEXT NOT NULL,
  expectativas TEXT NOT NULL,
  actualizado_en TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS intereses (
  id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  descripcion VARCHAR(240) NOT NULL DEFAULT '',
  orden SMALLINT UNSIGNED NOT NULL DEFAULT 0,
  UNIQUE KEY uq_intereses_nombre (nombre)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS secciones (
  id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  parent_id SMALLINT UNSIGNED NULL,
  slug VARCHAR(80) NOT NULL,
  nombre VARCHAR(150) NOT NULL,
  descripcion VARCHAR(500) NOT NULL DEFAULT '',
  orden SMALLINT UNSIGNED NOT NULL DEFAULT 0,
  UNIQUE KEY uq_secciones_slug (slug),
  KEY idx_secciones_parent (parent_id),
  CONSTRAINT fk_secciones_parent FOREIGN KEY (parent_id)
    REFERENCES secciones(id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS entradas (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  seccion_id SMALLINT UNSIGNED NOT NULL,
  titulo VARCHAR(150) NOT NULL,
  descripcion TEXT NOT NULL,
  fecha_actividad DATE NULL,
  estado ENUM('borrador','publicado') NOT NULL DEFAULT 'borrador',
  creado_en TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  actualizado_en TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY idx_entradas_seccion_estado (seccion_id, estado),
  CONSTRAINT fk_entradas_seccion FOREIGN KEY (seccion_id)
    REFERENCES secciones(id) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS archivos (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  entrada_id INT UNSIGNED NOT NULL,
  tipo ENUM('enunciado','solucion','material') NOT NULL,
  nombre_original VARCHAR(180) NOT NULL,
  ruta_guardada VARCHAR(255) NOT NULL,
  mime_type VARCHAR(80) NOT NULL DEFAULT 'application/pdf',
  tamano INT UNSIGNED NOT NULL,
  creado_en TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY idx_archivos_entrada (entrada_id),
  CONSTRAINT fk_archivos_entrada FOREIGN KEY (entrada_id)
    REFERENCES entradas(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS administradores (
  id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  usuario VARCHAR(32) NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  creado_en TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uq_administradores_usuario (usuario)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
