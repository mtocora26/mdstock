<?php
require_once __DIR__ . '/../dto/PedidoDetalleDTO.php';
class PedidoDetalleDAO {
    private $pdo;
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    public function agregarDetalle($id_pedido, $id_producto, $cantidad, $precio) {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO pedido_detalle (id_pedido, id_producto, cantidad, precio) VALUES (:id_pedido, :id_producto, :cantidad, :precio)");
            $stmt->bindParam(':id_pedido', $id_pedido);
            $stmt->bindParam(':id_producto', $id_producto);
            $stmt->bindParam(':cantidad', $cantidad);
            $stmt->bindParam(':precio', $precio);

            $result = $stmt->execute();

            if (!$result) {
                $errorInfo = $stmt->errorInfo();
                throw new Exception("Error al agregar detalle del pedido: " . $errorInfo[2]);
            }
        } catch (PDOException $e) {
            throw new Exception("Error en agregarDetalle: " . $e->getMessage());
        }
    }
}
