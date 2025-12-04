-- Script para configurar sistema de roles
-- Ejecutar este script para habilitar el sistema de administración

-- 1. Insertar roles básicos
INSERT INTO rol (nombre) VALUES ('administrador');
INSERT INTO rol (nombre) VALUES ('cliente');

-- 2. Asignar rol de administrador al primer usuario
-- IMPORTANTE: Cambia el ID del usuario según tu base de datos
-- Puedes verificar los usuarios con: SELECT * FROM usuarios;
INSERT INTO usuario_rol (id_usuario, id_rol)
VALUES (1, 1); -- Usuario con ID 1 será administrador

-- 3. Todos los demás usuarios serán clientes por defecto
-- Puedes asignar rol de cliente manualmente:
-- INSERT INTO usuario_rol (id_usuario, id_rol) VALUES (2, 2);
-- O ejecutar este query para asignar cliente a todos los usuarios sin rol:
INSERT INTO usuario_rol (id_usuario, id_rol)
SELECT u.id_usuario, 2
FROM usuarios u
LEFT JOIN usuario_rol ur ON u.id_usuario = ur.id_usuario
WHERE ur.id_usuario IS NULL;

-- 4. Verificar roles asignados
SELECT u.id_usuario, u.email, r.nombre as rol
FROM usuarios u
JOIN usuario_rol ur ON u.id_usuario = ur.id_usuario
JOIN rol r ON ur.id_rol = r.id_rol
ORDER BY u.id_usuario;
