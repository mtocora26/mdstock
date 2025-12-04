<?php
require_once __DIR__ . '/../conexion.php';
require_once __DIR__ . '/../dto/CarritoDetalleDTO.php';

class CarritoDAO {
        // Agregar o actualizar producto en el carrito
        public function agregarOActualizarProducto($id_carrito, $id_producto, $cantidad) {
            // Verificar si el producto ya está en el carrito
            $stmt = $this->pdo->prepare("SELECT cantidad FROM carrito_detalle WHERE id_carrito = :id_carrito AND id_producto = :id_producto");
            $stmt->bindParam(':id_carrito', $id_carrito);
            $stmt->bindParam(':id_producto', $id_producto);
            $stmt->execute();
            $row = $stmt->fetch();
            if ($row) {
                // Si existe, sumar la cantidad
                $nuevaCantidad = $row['cantidad'] + $cantidad;
                $this->actualizarCantidad($id_carrito, $id_producto, $nuevaCantidad);
            } else {
                // Si no existe, agregar nuevo
                $this->agregarProducto($id_carrito, $id_producto, $cantidad);
            }
        }
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Obtener el carrito por usuario
    public function obtenerCarritoPorUsuario($id_usuario) {
        $stmt = $this->pdo->prepare("SELECT * FROM carrito WHERE id_usuario = :id_usuario");
        $stmt->bindParam(':id_usuario', $id_usuario);
        $stmt->execute();
        return $stmt->fetch();
    }

    // Crear un carrito para el usuario
    public function crearCarrito($id_usuario) {
        $stmt = $this->pdo->prepare("INSERT INTO carrito (id_usuario, fecha_creacion) VALUES (:id_usuario, NOW())");
        $stmt->bindParam(':id_usuario', $id_usuario);
        $stmt->execute();
        return $this->pdo->lastInsertId();
    }

    // Obtener los productos del carrito
    public function obtenerDetallesCarrito($id_carrito) {
        $stmt = $this->pdo->prepare("SELECT * FROM carrito_detalle WHERE id_carrito = :id_carrito");
        $stmt->bindParam(':id_carrito', $id_carrito);
        $stmt->execute();
        $detalles = [];
        while ($row = $stmt->fetch()) {
            $detalle = new CarritoDetalleDTO();
            $detalle->id_detalle = $row['id_detalle'];
            $detalle->id_carrito = $row['id_carrito'];
            $detalle->id_producto = $row['id_producto'];
            $detalle->cantidad = $row['cantidad'];
            $detalles[] = $detalle;
        }
        return $detalles;
    }

    // Agregar producto al carrito
    public function agregarProducto($id_carrito, $id_producto, $cantidad) {
        $stmt = $this->pdo->prepare("INSERT INTO carrito_detalle (id_carrito, id_producto, cantidad) VALUES (:id_carrito, :id_producto, :cantidad)");
        $stmt->bindParam(':id_carrito', $id_carrito);
        $stmt->bindParam(':id_producto', $id_producto);
        $stmt->bindParam(':cantidad', $cantidad);
        $stmt->execute();
    }

    // Actualizar cantidad de producto
    public function actualizarCantidad($id_carrito, $id_producto, $cantidad) {
        $stmt = $this->pdo->prepare("UPDATE carrito_detalle SET cantidad = :cantidad WHERE id_carrito = :id_carrito AND id_producto = :id_producto");
        $stmt->bindParam(':cantidad', $cantidad);
        $stmt->bindParam(':id_carrito', $id_carrito);
        $stmt->bindParam(':id_producto', $id_producto);
        $stmt->execute();
    }

    // Eliminar producto del carrito
    public function eliminarProducto($id_carrito, $id_producto) {
        $stmt = $this->pdo->prepare("DELETE FROM carrito_detalle WHERE id_carrito = :id_carrito AND id_producto = :id_producto");
        $stmt->bindParam(':id_carrito', $id_carrito, PDO::PARAM_INT);
        $stmt->bindParam(':id_producto', $id_producto, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount();
    }

    // Vaciar carrito
    public function vaciarCarrito($id_carrito) {
        $stmt = $this->pdo->prepare("DELETE FROM carrito_detalle WHERE id_carrito = :id_carrito");
        $stmt->bindParam(':id_carrito', $id_carrito);
        $stmt->execute();
    }

    // Contar cantidad total de productos en el carrito
    public function contarProductos($id_carrito) {
        $stmt = $this->pdo->prepare("SELECT COALESCE(SUM(cantidad), 0) as total FROM carrito_detalle WHERE id_carrito = :id_carrito");
        $stmt->bindParam(':id_carrito', $id_carrito);
        $stmt->execute();
        $row = $stmt->fetch();
        return $row['total'];
    }
}
