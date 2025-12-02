<?php
require_once __DIR__ . '/../config/db.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . '/../view/header.php';

if (empty($_SESSION['usuario_id'])) {
    header('Location: /mdstock/pages/login.php?next=/mdstock/pages/checkout.php'); exit;
}

$cart = $_SESSION['cart'] ?? [];
$id_usuario = (int)$_SESSION['usuario_id'];
$id_direccion = (int)($_POST['id_direccion'] ?? 0);
$metodo_pago = trim($_POST['metodo_pago'] ?? 'transferencia');

if (!$cart) { http_response_code(400); echo "El carrito está vacío."; require_once __DIR__ . '/../view/footer.php'; exit; }
if ($id_direccion <= 0) { http_response_code(400); echo "Selecciona una dirección de envío."; require_once __DIR__ . '/../view/footer.php'; exit; }

// Cargar productos del carrito
$ids = array_map('intval', array_keys($cart));
$in = implode(',', array_fill(0, count($ids), '?'));
$stmt = $pdo->prepare("SELECT id_producto, nombre, precio FROM productos WHERE id_producto IN ($in)");
$stmt->execute($ids);
$productos = $stmt->fetchAll();
$byId = [];
foreach ($productos as $p) { $byId[$p['id_producto']] = $p; }

$total = 0.0;

// Abre transacción
$pdo->beginTransaction();

try {
    // Validar stock suficiente
    $val = $pdo->prepare("SELECT stock_actual FROM inventario WHERE id_producto = :p LIMIT 1");
    foreach ($cart as $idProd => $qty) {
        $val->execute([':p' => (int)$idProd]);
        $row = $val->fetch();
        $stock = (int)($row['stock_actual'] ?? 0);
        if ($stock < $qty) {
            throw new Exception("Stock insuficiente para el producto ID {$idProd}");
        }
        $precio = (float)($byId[$idProd]['precio'] ?? 0);
        $total += $precio * $qty;
    }

    // Crear pedido
    $insPed = $pdo->prepare("INSERT INTO pedidos (id_usuario, id_direccion, fecha_pedido, estado, total) 
                             VALUES (:u, :d, NOW(), 'pendiente', :t)");
    $insPed->execute([':u' => $id_usuario, ':d' => $id_direccion, ':t' => $total]);
    $id_pedido = (int)$pdo->lastInsertId();

    // Insertar detalle y descontar stock
    $insDet = $pdo->prepare("INSERT INTO pedidos_detalle (id_pedido, id_producto, cantidad, precio_unitario, total_linea)
                             VALUES (:pe, :pr, :c, :pu, :tl)");
    $updInv = $pdo->prepare("UPDATE inventario SET stock_actual = stock_actual - :c WHERE id_producto = :p");

    foreach ($cart as $idProd => $qty) {
        $precio = (float)($byId[$idProd]['precio'] ?? 0);
        $insDet->execute([
            ':pe' => $id_pedido,
            ':pr' => (int)$idProd,
            ':c'  => (int)$qty,
            ':pu' => $precio,
            ':tl' => $precio * $qty,
        ]);
        $updInv->execute([':c' => (int)$qty, ':p' => (int)$idProd]);
    }

    $pdo->commit();

    // Limpiar carrito sesión
    $_SESSION['cart'] = [];

    header('Location: /mdstock/pages/order-confirmation.php?id=' . $id_pedido);
    exit;

} catch (Exception $e) {
    $pdo->rollBack();
    http_response_code(409);
    echo "No se pudo procesar el pedido: " . htmlspecialchars($e->getMessage());
    require_once __DIR__ . '/../view/footer.php';
    exit;
}

?>
