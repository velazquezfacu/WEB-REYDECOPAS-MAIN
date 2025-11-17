-- Script para agregar campo de foto de perfil a la tabla usuarios
-- Ejecutar este script en phpMyAdmin o consola MySQL

ALTER TABLE usuarios
ADD COLUMN foto_perfil VARCHAR(255) DEFAULT NULL AFTER telefono;

-- El campo almacenará la ruta relativa de la imagen, por ejemplo: 'uploads/fotos_perfil/123_foto.jpg'
