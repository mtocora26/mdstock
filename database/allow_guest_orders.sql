-- Permitir pedidos sin usuario y sin dirección guardada (compras como invitado)

ALTER TABLE pedidos
MODIFY COLUMN id_usuario INT NULL COMMENT 'ID del usuario (NULL para compras como invitado)';

ALTER TABLE pedidos
MODIFY COLUMN id_direccion INT NULL COMMENT 'ID de dirección guardada (NULL para invitados)';
