-- Primero, eliminar la foreign key existente
ALTER TABLE pedido_detalle
DROP FOREIGN KEY pedido_detalle_ibfk_2;

-- Recrear la foreign key con la configuración correcta
ALTER TABLE pedido_detalle
ADD CONSTRAINT fk_pedido_detalle_producto
FOREIGN KEY (id_producto) REFERENCES productos(id_producto)
ON DELETE RESTRICT
ON UPDATE CASCADE;
