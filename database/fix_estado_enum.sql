-- Agregar 'pendiente_pago' al ENUM de estado
ALTER TABLE pedidos
MODIFY COLUMN estado ENUM('pendiente','pendiente_pago','enviado','entregado','cancelado')
NOT NULL DEFAULT 'pendiente'
COMMENT 'Estado del pedido: pendiente (COD), pendiente_pago (esperando transferencia), enviado, entregado, cancelado';
