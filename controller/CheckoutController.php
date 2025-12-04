<?php
session_start();
require_once __DIR__ . '/../model/conexion.php';
require_once __DIR__ . '/../model/dao/PedidoDAO.php';
require_once __DIR__ . '/../model/dao/PedidoDetalleDAO.php';
require_once __DIR__ . '/../model/dao/DireccionEnvioDAO.php';

// DEBUG: Crear archivo de log
file_put_contents(__DIR__ . '/../checkout_debug.txt', date('Y-m-d H:i:s') . " - Inicio del script\n", FILE_APPEND);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    file_put_contents(__DIR__ . '/../checkout_debug.txt', date('Y-m-d H:i:s') . " - Petición POST recibida\n", FILE_APPEND);

    try {
        // Validar que haya usuario logueado o datos del cliente
        $id_usuario = $_SESSION['usuario']['id_usuario'] ?? null;

        // Obtener datos del formulario
        $nombre = $_POST['first-name'] ?? '';
        $apellido = $_POST['last-name'] ?? '';
        $email = $_POST['email'] ?? '';
        $telefono = $_POST['phone'] ?? '';
        $direccion = $_POST['address'] ?? '';
        $apartamento = $_POST['apartment'] ?? '';
        $ciudad = $_POST['city'] ?? '';
        $departamento = $_POST['state'] ?? '';
        $codigo_postal = $_POST['zip'] ?? '';
        $referencia = $apartamento; // Usamos el campo apartamento como referencia adicional
        $guardarDireccion = isset($_POST['save-address']) ? true : false;
        $metodo_pago = $_POST['payment-method'] ?? 'cod';

        // Calcular totales
        $carrito = $_SESSION['carrito_detalles'] ?? [];
        if (empty($carrito)) {
            header('Location: ../view/pages/cart.php');
            exit;
        }

        $total = 0;
        foreach ($carrito as $item) {
            $total += $item->precio * $item->cantidad;
        }
        $envio = floatval($_POST['shipping'] ?? 4.99);
        if ($total >= 50000) $envio = 0;
        $total_final = $total + $envio;

        // Conectar a BD
        $pdo = Conexion::getConexion();
        $pedidoDAO = new PedidoDAO($pdo);
        $detalleDAO = new PedidoDetalleDAO($pdo);

        // Iniciar transacción
        $pdo->beginTransaction();

        // Determinar estado del pedido según método de pago
        $estado_pedido = ($metodo_pago === 'cod') ? 'pendiente' : 'pendiente_pago';

        // Preparar datos del cliente
        $datosCliente = [
            'nombre' => $nombre . ' ' . $apellido,
            'email' => $email,
            'telefono' => $telefono,
            'direccion' => $direccion . ($apartamento ? ', ' . $apartamento : '') . ', ' . $ciudad . ', ' . $departamento . ' ' . $codigo_postal
        ];


        // Guardar dirección si corresponde
        if ($id_usuario && $guardarDireccion) {
            require_once __DIR__ . '/../model/dao/DireccionEnvioDAO.php';
            $direccionDAO = new DireccionEnvioDAO($pdo);
            $direccionDAO->guardarDireccionPredeterminada($id_usuario, $direccion, $ciudad, $departamento, $codigo_postal, $referencia);
        }

        // Crear pedido con datos del cliente
        file_put_contents(__DIR__ . '/../checkout_debug.txt', date('Y-m-d H:i:s') . " - Creando pedido...\n", FILE_APPEND);
        $id_pedido = $pedidoDAO->crearPedido($id_usuario, $total_final, $estado_pedido, $metodo_pago, $datosCliente);

        if (!$id_pedido) {
            throw new Exception('No se pudo crear el pedido');
        }

        file_put_contents(__DIR__ . '/../checkout_debug.txt', date('Y-m-d H:i:s') . " - Pedido creado con ID: $id_pedido\n", FILE_APPEND);

        // Agregar detalles del pedido
        file_put_contents(__DIR__ . '/../checkout_debug.txt', date('Y-m-d H:i:s') . " - Agregando detalles...\n", FILE_APPEND);
        foreach ($carrito as $item) {
            file_put_contents(__DIR__ . '/../checkout_debug.txt', date('Y-m-d H:i:s') . " - Agregando producto {$item->id_producto}\n", FILE_APPEND);
            $detalleDAO->agregarDetalle($id_pedido, $item->id_producto, $item->cantidad, $item->precio);
            // Descontar inventario
            $stmt = $pdo->prepare("UPDATE inventario SET cantidad_actual = cantidad_actual - :cantidad WHERE id_producto = :id_producto AND cantidad_actual >= :cantidad");
            $stmt->bindParam(':cantidad', $item->cantidad, PDO::PARAM_INT);
            $stmt->bindParam(':id_producto', $item->id_producto, PDO::PARAM_INT);
            $stmt->execute();
        }

        // Confirmar transacción
        file_put_contents(__DIR__ . '/../checkout_debug.txt', date('Y-m-d H:i:s') . " - Haciendo commit...\n", FILE_APPEND);
        $commitResult = $pdo->commit();
        file_put_contents(__DIR__ . '/../checkout_debug.txt', date('Y-m-d H:i:s') . " - Commit exitoso\n", FILE_APPEND);

        // Guardar información de envío en sesión para la página de confirmación
        $_SESSION['pedido_info'] = [
            'id_pedido' => $id_pedido,
            'nombre_completo' => $nombre . ' ' . $apellido,
            'email' => $email,
            'telefono' => $telefono,
            'direccion_completa' => $direccion . ($apartamento ? ', ' . $apartamento : '') . ', ' . $ciudad . ', ' . $departamento . ' ' . $codigo_postal,
            'metodo_pago' => $metodo_pago,
            'total' => $total_final,
            'subtotal' => $total,
            'envio' => $envio
        ];

        // Vaciar carrito
        $_SESSION['carrito_detalles'] = [];

        // Redirigir a página de confirmación
        header('Location: ../view/pages/order-confirmation.php?id_pedido=' . $id_pedido . '&payment=' . $metodo_pago);
        exit;

    } catch (Exception $e) {
        file_put_contents(__DIR__ . '/../checkout_debug.txt', date('Y-m-d H:i:s') . " - ERROR: " . $e->getMessage() . "\n", FILE_APPEND);

        // Revertir transacción en caso de error
        if (isset($pdo) && $pdo->inTransaction()) {
            $pdo->rollBack();
            file_put_contents(__DIR__ . '/../checkout_debug.txt', date('Y-m-d H:i:s') . " - Rollback realizado\n", FILE_APPEND);
        }

        // Redirigir con mensaje de error
        $_SESSION['checkout_error'] = 'Hubo un error al procesar tu pedido: ' . $e->getMessage();
        header('Location: ../view/pages/checkout.php');
        exit;
    }
} else {
    file_put_contents(__DIR__ . '/../checkout_debug.txt', date('Y-m-d H:i:s') . " - No es POST, método: " . $_SERVER['REQUEST_METHOD'] . "\n", FILE_APPEND);
}
