<?php
session_start();
require_once __DIR__ . '/../model/conexion.php';
require_once __DIR__ . '/../model/dao/ProductoDAO.php';
require_once __DIR__ . '/../model/helpers/RoleHelper.php';

// Verificar que el usuario sea administrador
if (!isset($_SESSION['usuario']['id_usuario'])) {
    $_SESSION['error'] = 'Debes iniciar sesión';
    header('Location: ../view/pages/login-registro.php');
    exit;
}

$pdo = Conexion::getConexion();
$id_usuario = $_SESSION['usuario']['id_usuario'];

if (!RoleHelper::esAdmin($id_usuario, $pdo)) {
    $_SESSION['error'] = 'No tienes permisos de administrador';
    header('Location: ../view/pages/category.php');
    exit;
}

// Procesar acciones
$accion = $_POST['accion'] ?? $_GET['accion'] ?? '';

try {
    switch ($accion) {
        case 'crear':
            $nombre = $_POST['nombre'] ?? '';
            $descripcion = $_POST['descripcion'] ?? '';
            $precio = $_POST['precio'] ?? 0;
            $id_categoria = $_POST['id_categoria'] ?? 1;
            $marca = $_POST['marca'] ?? null;
            $stock = $_POST['stock'] ?? 0;

            if (empty($nombre) || empty($precio)) {
                throw new Exception('Nombre y precio son obligatorios');
            }

            $id_producto = ProductoDAO::crear($nombre, $descripcion, $precio, $id_categoria, $marca, null);

            // Crear registro de inventario
            if ($id_producto && $stock > 0) {
                ProductoDAO::actualizarStock($id_producto, $stock);
            }

            $_SESSION['success'] = 'Producto creado exitosamente';
            header('Location: ../view/admin/productos.php');
            exit;

        case 'actualizar':
            $id_producto = $_POST['id_producto'] ?? 0;
            $nombre = $_POST['nombre'] ?? '';
            $descripcion = $_POST['descripcion'] ?? '';
            $precio = $_POST['precio'] ?? 0;
            $id_categoria = $_POST['id_categoria'] ?? 1;
            $marca = $_POST['marca'] ?? null;
            $stock = $_POST['stock'] ?? null;

            if (empty($id_producto) || empty($nombre) || empty($precio)) {
                throw new Exception('ID, nombre y precio son obligatorios');
            }

            ProductoDAO::actualizar($id_producto, $nombre, $descripcion, $precio, $id_categoria, $marca, null);

            // Actualizar stock si se proporcionó
            if ($stock !== null) {
                ProductoDAO::actualizarStock($id_producto, $stock);
            }

            $_SESSION['success'] = 'Producto actualizado exitosamente';
            header('Location: ../view/admin/productos.php');
            exit;

        case 'eliminar':
            $id_producto = $_GET['id'] ?? 0;

            if (empty($id_producto)) {
                throw new Exception('ID de producto no válido');
            }

            ProductoDAO::eliminar($id_producto);

            $_SESSION['success'] = 'Producto eliminado exitosamente';
            header('Location: ../view/admin/productos.php');
            exit;

        default:
            header('Location: ../view/admin/productos.php');
            exit;
    }
} catch (Exception $e) {
    $_SESSION['error'] = 'Error: ' . $e->getMessage();
    header('Location: ../view/admin/productos.php');
    exit;
}
