<?php
session_start();
header('Content-Type: application/json');

// Devuelve el contador y los productos del carrito
$cantidadCarrito = 0;
$productos = [];
if (isset($_SESSION['usuario']['id_usuario'])) {
    require_once __DIR__ . '/../model/dao/CarritoDAO.php';
    require_once __DIR__ . '/../model/conexion.php';
    require_once __DIR__ . '/../model/dao/ProductoDAO.php';
    $conexion = new Conexion();
    $pdo = $conexion->getConexion();
    $carritoDAO = new CarritoDAO($pdo);
    $carrito = $carritoDAO->obtenerCarritoPorUsuario($_SESSION['usuario']['id_usuario']);
    if ($carrito) {
        $cantidadCarrito = $carritoDAO->contarProductos($carrito['id_carrito']);
        $detalles = $carritoDAO->obtenerDetallesCarrito($carrito['id_carrito']);
        foreach ($detalles as $item) {
            $producto = ProductoDAO::obtenerPorId($item->id_producto);
            if ($producto) {
                $productos[] = [
                    'id_producto' => $producto->id_producto,
                    'nombre' => $producto->nombre,
                    'imagen' => $producto->imagen,
                    'precio' => $producto->precio,
                    'cantidad' => $item->cantidad
                ];
            }
        }
    }
} else {
    if (isset($_SESSION['carrito_detalles']) && is_array($_SESSION['carrito_detalles'])) {
        foreach ($_SESSION['carrito_detalles'] as $item) {
            $cantidadCarrito += $item->cantidad;
            $productos[] = [
                'id_producto' => $item->id_producto,
                'nombre' => $item->nombre,
                'imagen' => $item->imagen,
                'precio' => $item->precio,
                'cantidad' => $item->cantidad
            ];
        }
    }
}
echo json_encode([
    'cantidad' => $cantidadCarrito,
    'productos' => $productos
]);
