
<?php
require_once __DIR__ . '/../conexion.php';
require_once __DIR__ . '/../dto/ProductoDTO.php';

class ProductoDAO {
    public static function buscarProductos($termino) {
        $conn = Conexion::getConexion();
        $sql = "SELECT p.id_producto, p.nombre, p.descripcion, p.precio, p.id_categoria, p.id_subcategoria, p.marca, p.fecha_vencimiento,
                (SELECT urlImagen FROM imagenes_producto WHERE id_producto = p.id_producto ORDER BY orden ASC LIMIT 1) AS imagen,
                (SELECT cantidad_actual FROM inventario WHERE id_producto = p.id_producto LIMIT 1) AS stock
                FROM productos p
                WHERE p.nombre LIKE ? OR p.descripcion LIKE ?";
        $stmt = $conn->prepare($sql);
        $likeTerm = "%" . $termino . "%";
        $stmt->execute([$likeTerm, $likeTerm]);
        $productos = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $productos[] = new ProductoDTO(
                $row['id_producto'],
                $row['nombre'],
                $row['descripcion'],
                $row['precio'],
                $row['id_categoria'],
                $row['id_subcategoria'],
                $row['marca'],
                $row['fecha_vencimiento'],
                $row['imagen'],
                $row['stock']
            );
        }
        return $productos;
    }
    public static function obtenerPorId($id_producto) {
            $conn = Conexion::getConexion();
            $sql = "SELECT p.id_producto, p.nombre, p.descripcion, p.precio, p.id_categoria, p.id_subcategoria, p.marca, p.fecha_vencimiento,
                    (SELECT urlImagen FROM imagenes_producto WHERE id_producto = p.id_producto ORDER BY orden ASC LIMIT 1) AS imagen,
                    (SELECT cantidad_actual FROM inventario WHERE id_producto = p.id_producto LIMIT 1) AS stock
                    FROM productos p WHERE p.id_producto = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$id_producto]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                return new ProductoDTO(
                    $row['id_producto'],
                    $row['nombre'],
                    $row['descripcion'],
                    $row['precio'],
                    $row['id_categoria'],
                    $row['id_subcategoria'],
                    $row['marca'],
                    $row['fecha_vencimiento'],
                    $row['imagen'],
                    $row['stock']
                );
            }
            return null;
        }
    public static function obtenerPorCategoria($categoria_id = null) {
        $conn = Conexion::getConexion();
        $sql = "SELECT p.id_producto, p.nombre, p.descripcion, p.precio, p.id_categoria, p.id_subcategoria, p.marca, p.fecha_vencimiento,
                (SELECT urlImagen FROM imagenes_producto WHERE id_producto = p.id_producto ORDER BY orden ASC LIMIT 1) AS imagen,
                (SELECT cantidad_actual FROM inventario WHERE id_producto = p.id_producto LIMIT 1) AS stock
                FROM productos p";
        if ($categoria_id) {
            $sql .= " WHERE p.id_categoria = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$categoria_id]);
        } else {
            $stmt = $conn->prepare($sql);
            $stmt->execute();
        }
        $productos = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $productos[] = new ProductoDTO(
                $row['id_producto'],
                $row['nombre'],
                $row['descripcion'],
                $row['precio'],
                $row['id_categoria'],
                $row['id_subcategoria'],
                $row['marca'],
                $row['fecha_vencimiento'],
                $row['imagen'],
                $row['stock']
            );
        }
        return $productos;
    }

    // ==================== MÉTODOS CRUD PARA ADMINISTRACIÓN ====================

    /**
     * Obtener todos los productos con paginación (para admin)
     */
    public static function obtenerTodos($limit = 50, $offset = 0) {
        $conn = Conexion::getConexion();
        $sql = "SELECT p.id_producto, p.nombre, p.descripcion, p.precio, p.id_categoria, p.id_subcategoria, p.marca, p.fecha_vencimiento,
            (SELECT urlImagen FROM imagenes_producto WHERE id_producto = p.id_producto ORDER BY orden ASC LIMIT 1) AS imagen,
            (SELECT cantidad_actual FROM inventario WHERE id_producto = p.id_producto LIMIT 1) AS stock
            FROM productos p
            ORDER BY p.id_producto DESC
            LIMIT ? OFFSET ?";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(1, (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(2, (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        $productos = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $productos[] = new ProductoDTO(
                $row['id_producto'],
                $row['nombre'],
                $row['descripcion'],
                $row['precio'],
                $row['id_categoria'],
                $row['id_subcategoria'],
                $row['marca'],
                $row['fecha_vencimiento'],
                $row['imagen'],
                $row['stock']
            );
        }
        return $productos;
    }

    /**
     * Contar total de productos
     */
    public static function contarProductos() {
        $conn = Conexion::getConexion();
        $sql = "SELECT COUNT(*) as total FROM productos";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }

    /**
     * Crear un nuevo producto
     */
    public static function crear($nombre, $descripcion, $precio, $id_categoria, $marca = null, $fecha_vencimiento = null) {
        $conn = Conexion::getConexion();
        $sql = "INSERT INTO productos (nombre, descripcion, precio, id_categoria, marca, fecha_vencimiento)
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$nombre, $descripcion, $precio, $id_categoria, $marca, $fecha_vencimiento]);
        return $conn->lastInsertId();
    }

    /**
     * Actualizar un producto existente
     */
    public static function actualizar($id_producto, $nombre, $descripcion, $precio, $id_categoria, $marca = null, $fecha_vencimiento = null) {
        $conn = Conexion::getConexion();
        $sql = "UPDATE productos
                SET nombre = ?, descripcion = ?, precio = ?, id_categoria = ?, marca = ?, fecha_vencimiento = ?
                WHERE id_producto = ?";
        $stmt = $conn->prepare($sql);
        return $stmt->execute([$nombre, $descripcion, $precio, $id_categoria, $marca, $fecha_vencimiento, $id_producto]);
    }

    /**
     * Eliminar un producto
     */
    public static function eliminar($id_producto) {
        $conn = Conexion::getConexion();

        // Primero eliminar imágenes asociadas
        $sql = "DELETE FROM imagenes_producto WHERE id_producto = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$id_producto]);

        // Eliminar inventario asociado
        $sql = "DELETE FROM inventario WHERE id_producto = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$id_producto]);

        // Finalmente eliminar el producto
        $sql = "DELETE FROM productos WHERE id_producto = ?";
        $stmt = $conn->prepare($sql);
        return $stmt->execute([$id_producto]);
    }

    /**
     * Actualizar stock de un producto
     */
    public static function actualizarStock($id_producto, $cantidad) {
        $conn = Conexion::getConexion();

        // Verificar si existe registro en inventario
        $sql = "SELECT id_inventario FROM inventario WHERE id_producto = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$id_producto]);
        $existe = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existe) {
            // Actualizar
            $sql = "UPDATE inventario SET cantidad_actual = ? WHERE id_producto = ?";
            $stmt = $conn->prepare($sql);
            return $stmt->execute([$cantidad, $id_producto]);
        } else {
            // Insertar
            $sql = "INSERT INTO inventario (id_producto, cantidad_actual) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            return $stmt->execute([$id_producto, $cantidad]);
        }
    }
}
