-- insert_sample_data.sql
-- Script de ejemplo para poblar tablas: categorias, subcategorias, productos, imagenes_productos, inventario
-- ADVERTENCIA: este script asume tablas vacías. Si tus tablas ya contienen datos, adapta o realiza un backup.

START TRANSACTION;

-- Vaciar tablas (opcional)
TRUNCATE TABLE pedidos_detalle;
TRUNCATE TABLE pedidos;
TRUNCATE TABLE carrito_detalle;
TRUNCATE TABLE carrito;
TRUNCATE TABLE inventario;
TRUNCATE TABLE imagenes_productos;
TRUNCATE TABLE productos;
TRUNCATE TABLE subcategorias;
TRUNCATE TABLE categorias;

-- Categorías
INSERT INTO categorias (id_categoria, nombre) VALUES
(1, 'Electrónica'),
(2, 'Hogar'),
(3, 'Moda'),
(4, 'Deportes');

-- Subcategorías
INSERT INTO subcategorias (id_subcategoria, id_categoria, nombre) VALUES
(1, 1, 'Celulares'),
(2, 1, 'Audio'),
(3, 2, 'Cocina'),
(4, 2, 'Decoración'),
(5, 3, 'Ropa'),
(6, 3, 'Calzado'),
(7, 4, 'Fitness'),
(8, 4, 'Outdoor');

-- Productos
INSERT INTO productos (id_producto, nombre, descripcion, precio, id_categoria, id_subcategoria) VALUES
(1, 'Smartphone X200', 'Smartphone X200 con pantalla 6.5\" AMOLED, 128GB, cámara 48MP.', 799.99, 1, 1),
(2, 'Auriculares Pro', 'Auriculares inalámbricos con cancelación de ruido y 30h batería.', 149.90, 1, 2),
(3, 'Licuadora Turbo', 'Licuadora de 1000W para uso doméstico.', 69.50, 2, 3),
(4, 'Lámpara Minimal', 'Lámpara de mesa LED para decoración.', 39.00, 2, 4),
(5, 'Camiseta Sport', 'Camiseta deportiva transpirable.', 19.99, 3, 5),
(6, 'Zapatillas Run', 'Zapatillas para running con amortiguación ligera.', 89.00, 3, 6),
(7, 'Kit Fitness', 'Bandas elásticas y esterilla para entrenar en casa.', 29.99, 4, 7),
(8, 'Mochila Trek', 'Mochila outdoor 30L resistente al agua.', 59.95, 4, 8);

-- Imágenes de productos (rutas relativas en assets)
INSERT INTO imagenes_productos (id_imagen, id_producto, url, alt) VALUES
(1, 1, '/assets/img/product/smartphone-x200-1.jpg', 'Smartphone X200 - frontal'),
(2, 1, '/assets/img/product/smartphone-x200-2.jpg', 'Smartphone X200 - trasera'),
(3, 2, '/assets/img/product/auriculares-pro-1.jpg', 'Auriculares Pro'),
(4, 3, '/assets/img/product/licuadora-turbo-1.jpg', 'Licuadora Turbo'),
(5, 4, '/assets/img/product/lampara-minimal-1.jpg', 'Lámpara Minimal'),
(6, 5, '/assets/img/product/camiseta-sport-1.jpg', 'Camiseta Sport'),
(7, 6, '/assets/img/product/zapatillas-run-1.jpg', 'Zapatillas Run'),
(8, 7, '/assets/img/product/kit-fitness-1.jpg', 'Kit Fitness'),
(9, 8, '/assets/img/product/mochila-trek-1.jpg', 'Mochila Trek');

-- Inventario
INSERT INTO inventario (id_inventario, id_producto, stock_actual) VALUES
(1, 1, 15),
(2, 2, 30),
(3, 3, 20),
(4, 4, 12),
(5, 5, 50),
(6, 6, 25),
(7, 7, 40),
(8, 8, 10);

COMMIT;

-- Nota: Si tu tabla de usuarios se llama 'usuario', puedes crear un usuario de prueba mediante la página de registro
-- o insertar manualmente con un hash de contraseña. Ejemplo en PHP para generar hash:
-- <?php echo password_hash('Password123!', PASSWORD_DEFAULT); ?>

-- Fin del script de datos de ejemplo
