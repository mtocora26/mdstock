-- Agregar información del cliente a la tabla pedidos
-- Esto permite guardar datos de clientes que compran sin registrarse

ALTER TABLE pedidos
ADD COLUMN nombre_cliente VARCHAR(100) NULL COMMENT 'Nombre completo del cliente',
ADD COLUMN email_cliente VARCHAR(100) NULL COMMENT 'Email del cliente',
ADD COLUMN telefono_cliente VARCHAR(20) NULL COMMENT 'Teléfono del cliente',
ADD COLUMN direccion_envio TEXT NULL COMMENT 'Dirección completa de envío';

-- Índice para búsqueda por email
CREATE INDEX idx_email_cliente ON pedidos(email_cliente);
