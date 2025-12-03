<?php
require_once __DIR__ . '/../conexion.php';
require_once __DIR__ . '/../dto/ProductoDTO.php';

class ProductoDAO {
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
}
