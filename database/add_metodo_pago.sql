-- Agregar columna metodo_pago a la tabla pedidos
ALTER TABLE pedidos
ADD COLUMN metodo_pago VARCHAR(20) DEFAULT 'cod'
COMMENT 'Método de pago: cod (contra entrega) o bank_transfer (transferencia)';

-- Actualizar pedidos existentes para que tengan el método de pago por defecto
UPDATE pedidos SET metodo_pago = 'cod' WHERE metodo_pago IS NULL OR metodo_pago = '';
