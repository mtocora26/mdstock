<?php
require_once __DIR__ . '/../dto/PedidoDTO.php';
class PedidoDAO {
    private $pdo;
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    public function crearPedido($id_usuario, $total, $estado = 'pendiente', $metodo_pago = 'cod', $datosCliente = []) {
        try {
            // Construir query con o sin datos del cliente
            if (!empty($datosCliente)) {
                $stmt = $this->pdo->prepare("INSERT INTO pedidos (id_usuario, fecha, total, estado, metodo_pago, nombre_cliente, email_cliente, telefono_cliente, direccion_envio) VALUES (:id_usuario, NOW(), :total, :estado, :metodo_pago, :nombre, :email, :telefono, :direccion)");
                $stmt->bindParam(':nombre', $datosCliente['nombre']);
                $stmt->bindParam(':email', $datosCliente['email']);
                $stmt->bindParam(':telefono', $datosCliente['telefono']);
                $stmt->bindParam(':direccion', $datosCliente['direccion']);
            } else {
                $stmt = $this->pdo->prepare("INSERT INTO pedidos (id_usuario, fecha, total, estado, metodo_pago) VALUES (:id_usuario, NOW(), :total, :estado, :metodo_pago)");
            }

            // Vincular parámetros comunes
            if ($id_usuario !== null) {
                $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
            } else {
                $stmt->bindValue(':id_usuario', null, PDO::PARAM_NULL);
            }
            $stmt->bindParam(':total', $total);
            $stmt->bindParam(':estado', $estado);
            $stmt->bindParam(':metodo_pago', $metodo_pago);

            $result = $stmt->execute();

            if (!$result) {
                throw new Exception('Error al ejecutar INSERT en pedidos');
            }

            $lastId = $this->pdo->lastInsertId();
            if (!$lastId) {
                throw new Exception('No se generó ID del pedido');
            }

            return $lastId;
        } catch (PDOException $e) {
            // Registrar el error específico
            throw new Exception('Error en crearPedido: ' . $e->getMessage());
        }
    }

    // Obtener pedidos de un usuario
    public function obtenerPedidosPorUsuario($id_usuario, $limit = 10, $offset = 0) {
        $stmt = $this->pdo->prepare("
            SELECT p.*,
                   COUNT(pd.id_detalle) as total_items
            FROM pedidos p
            LEFT JOIN pedido_detalle pd ON p.id_pedido = pd.id_pedido
            WHERE p.id_usuario = :id_usuario
            GROUP BY p.id_pedido
            ORDER BY p.fecha DESC, p.id_pedido DESC
            LIMIT :limit OFFSET :offset
        ");
        $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Contar total de pedidos de un usuario
    public function contarPedidosPorUsuario($id_usuario) {
        $stmt = $this->pdo->prepare("
            SELECT COUNT(*) as total
            FROM pedidos
            WHERE id_usuario = :id_usuario
        ");
        $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }

    // Obtener detalles de un pedido específico
    public function obtenerPedidoConDetalles($id_pedido) {
        // Obtener info del pedido
        $stmt = $this->pdo->prepare("SELECT * FROM pedidos WHERE id_pedido = :id_pedido");
        $stmt->bindParam(':id_pedido', $id_pedido, PDO::PARAM_INT);
        $stmt->execute();
        $pedido = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($pedido) {
            // Obtener productos del pedido
                        $stmt = $this->pdo->prepare("
                                SELECT pd.*, pr.nombre,
                                    (SELECT urlImagen FROM imagenes_producto WHERE id_producto = pr.id_producto ORDER BY orden ASC LIMIT 1) AS imagen
                                FROM pedido_detalle pd
                                LEFT JOIN productos pr ON pd.id_producto = pr.id_producto
                                WHERE pd.id_pedido = :id_pedido
                        ");
            $stmt->bindParam(':id_pedido', $id_pedido, PDO::PARAM_INT);
            $stmt->execute();
            $pedido['detalles'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        return $pedido;
    }
}
