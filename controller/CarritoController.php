<?php
session_start();
require_once __DIR__ . '/../model/dao/CarritoDAO.php';
require_once __DIR__ . '/../model/conexion.php';

$conexion = new Conexion();
$pdo = $conexion->getConexion();
$carritoDAO = new CarritoDAO($pdo);

// Obtener el id_usuario si está logueado
$id_usuario = isset($_SESSION['usuario']['id_usuario']) ? $_SESSION['usuario']['id_usuario'] : null;

// Obtener o crear carrito
if ($id_usuario) {
    $carrito = $carritoDAO->obtenerCarritoPorUsuario($id_usuario);
    if (!$carrito) {
        $id_carrito = $carritoDAO->crearCarrito($id_usuario);
    } else {
        $id_carrito = $carrito['id_carrito'];
    }
} else {
    // Si no está logueado, puedes manejar el carrito en sesión
    $id_carrito = null;
}

// Función para obtener la cantidad total del carrito
function obtenerCantidadCarrito($carritoDAO, $id_carrito) {
    $cantidadCarrito = 0;

    if ($id_carrito) {
        // Usuario logueado: consultar desde la base de datos
        $cantidadCarrito = $carritoDAO->contarProductos($id_carrito);
    } else {
        // Usuario no logueado: contar desde la sesión
        if (isset($_SESSION['carrito_detalles']) && is_array($_SESSION['carrito_detalles'])) {
            foreach ($_SESSION['carrito_detalles'] as $item) {
                $cantidadCarrito += $item->cantidad;
            }
        }
    }

    return $cantidadCarrito;
}

// Endpoint AJAX para obtener la cantidad del carrito
if (isset($_GET['obtener_cantidad'])) {
    header('Content-Type: application/json');
    echo json_encode(['cantidad' => obtenerCantidadCarrito($carritoDAO, $id_carrito)]);
    exit;
}

// Acciones del carrito
if (isset($_POST['accion'])) {
    $accion = $_POST['accion'];
    $id_producto = $_POST['id_producto'] ?? null;
    $cantidad = $_POST['cantidad'] ?? 1;

    // Guardar depuración en sesión para mostrar en la vista
    $_SESSION['carrito_debug'] = '[DEBUG] Acción: ' . htmlspecialchars($accion) . ', id_carrito: ' . htmlspecialchars($id_carrito) . ', id_producto: ' . htmlspecialchars($id_producto) . ', cantidad: ' . htmlspecialchars($cantidad);
    error_log("Acción recibida: $accion, id_carrito: $id_carrito, id_producto: $id_producto, cantidad: $cantidad");

    if ($id_carrito && $id_producto) {
        // Usuario logueado: actualizar en base de datos
        if ($accion === 'agregar') {
            $carritoDAO->agregarOActualizarProducto($id_carrito, $id_producto, $cantidad);
        }
        if ($accion === 'actualizar') {
            if ($cantidad < 1) {
                $rowsAffected = $carritoDAO->eliminarProducto($id_carrito, $id_producto);
                $_SESSION['carrito_debug'] .= ' | [DEBUG] Producto eliminado';
                // Refrescar detalles después de eliminar
                $detalles = $carritoDAO->obtenerDetallesCarrito($id_carrito);
                $_SESSION['carrito_detalles'] = [];
                require_once __DIR__ . '/../model/dao/ProductoDAO.php';
                foreach ($detalles as $item) {
                    $producto = ProductoDAO::obtenerPorId($item->id_producto);
                    if ($producto) {
                        $detalle = new stdClass();
                        $detalle->id_producto = $producto->id_producto;
                        $detalle->nombre = $producto->nombre;
                        $detalle->imagen = $producto->imagen;
                        $detalle->precio = $producto->precio;
                        $detalle->cantidad = $item->cantidad;
                        $_SESSION['carrito_detalles'][] = $detalle;
                    }
                }
            } else {
                $carritoDAO->actualizarCantidad($id_carrito, $id_producto, $cantidad);
                $_SESSION['carrito_debug'] .= ' | [DEBUG] Cantidad actualizada';
                // Refrescar detalles después de actualizar
                $detalles = $carritoDAO->obtenerDetallesCarrito($id_carrito);
                $_SESSION['carrito_detalles'] = [];
                require_once __DIR__ . '/../model/dao/ProductoDAO.php';
                foreach ($detalles as $item) {
                    $producto = ProductoDAO::obtenerPorId($item->id_producto);
                    if ($producto) {
                        $detalle = new stdClass();
                        $detalle->id_producto = $producto->id_producto;
                        $detalle->nombre = $producto->nombre;
                        $detalle->imagen = $producto->imagen;
                        $detalle->precio = $producto->precio;
                        $detalle->cantidad = $item->cantidad;
                        $_SESSION['carrito_detalles'][] = $detalle;
                    }
                }
            }
        }
        if ($accion === 'eliminar') {
            error_log("Eliminando producto: id_carrito=$id_carrito, id_producto=$id_producto");
            $rowsAffected = $carritoDAO->eliminarProducto($id_carrito, $id_producto);
            error_log("Filas afectadas: $rowsAffected");
        }
        if ($accion === 'vaciar') {
            $carritoDAO->vaciarCarrito($id_carrito);
        }

        // Actualizar la sesión después de modificar la base de datos
        $detalles = $carritoDAO->obtenerDetallesCarrito($id_carrito);
        $_SESSION['carrito_detalles'] = [];
        require_once __DIR__ . '/../model/dao/ProductoDAO.php';
        foreach ($detalles as $item) {
            $producto = ProductoDAO::obtenerPorId($item->id_producto);
            if ($producto) {
                $detalle = new stdClass();
                $detalle->id_producto = $producto->id_producto;
                $detalle->nombre = $producto->nombre;
                $detalle->imagen = $producto->imagen;
                $detalle->precio = $producto->precio;
                $detalle->cantidad = $item->cantidad;
                $_SESSION['carrito_detalles'][] = $detalle;
            }
        }
    } else {
        // Usuario no logueado: manejar carrito en sesión
        require_once __DIR__ . '/../model/dao/ProductoDAO.php';
        // Inicializar el array si no existe
        if (!isset($_SESSION['carrito_detalles'])) {
            $_SESSION['carrito_detalles'] = [];
        }
        if ($accion === 'agregar' && $id_producto) {
            // Buscar si el producto ya está en el carrito
            $existe = false;
            foreach ($_SESSION['carrito_detalles'] as &$item) {
                if (intval($item->id_producto) === intval($id_producto)) {
                    $item->cantidad += intval($cantidad);
                    $existe = true;
                    break;
                }
            }
            unset($item); // Romper la referencia

            if (!$existe) {
                $producto = ProductoDAO::obtenerPorId($id_producto);
                if ($producto) {
                    $detalle = new stdClass();
                    $detalle->id_producto = intval($producto->id_producto);
                    $detalle->nombre = $producto->nombre;
                    $detalle->imagen = $producto->imagen;
                    $detalle->precio = floatval($producto->precio);
                    $detalle->cantidad = intval($cantidad);
                    $_SESSION['carrito_detalles'][] = $detalle;
                }
            }
        }
        if ($accion === 'actualizar' && $id_producto) {
            $nuevoCarrito = [];
            $debugMsg = '';
            foreach ($_SESSION['carrito_detalles'] as $item) {
                if (intval($item->id_producto) === intval($id_producto)) {
                    if (intval($cantidad) > 0) {
                        $item->cantidad = intval($cantidad);
                        $nuevoCarrito[] = $item;
                        $debugMsg = '[DEBUG] Cantidad actualizada';
                    } else {
                        $debugMsg = '[DEBUG] Producto eliminado';
                    }
                } else {
                    $nuevoCarrito[] = $item;
                }
            }
            $_SESSION['carrito_detalles'] = $nuevoCarrito;
            $_SESSION['carrito_debug'] .= ' | ' . $debugMsg;
        }
        if ($accion === 'eliminar' && $id_producto) {
            error_log("Eliminando de sesión - id_producto: $id_producto");
            error_log("Carrito antes: " . print_r($_SESSION['carrito_detalles'], true));

            $nuevoCarrito = [];
            foreach ($_SESSION['carrito_detalles'] as $item) {
                // Comparación estricta convirtiendo ambos a int
                if (intval($item->id_producto) !== intval($id_producto)) {
                    $nuevoCarrito[] = $item;
                }
            }
            $_SESSION['carrito_detalles'] = $nuevoCarrito;

            error_log("Carrito después: " . print_r($_SESSION['carrito_detalles'], true));
        }
        if ($accion === 'vaciar') {
            $_SESSION['carrito_detalles'] = [];
        }
    }
    // Mantener el parámetro debug si está presente
    $debugParam = (isset($_GET['debug']) || isset($_POST['debug'])) ? '?debug=1' : '';
    header('Location: ../view/pages/cart.php' . $debugParam);
    exit;
}

// Para mostrar el carrito en la vista

if ($id_carrito) {
    // Usuario logueado: refrescar detalles desde la base
    $detalles = $carritoDAO->obtenerDetallesCarrito($id_carrito);
    $_SESSION['carrito_detalles'] = [];
    require_once __DIR__ . '/../model/dao/ProductoDAO.php';
    foreach ($detalles as $item) {
        $producto = ProductoDAO::obtenerPorId($item->id_producto);
        if ($producto) {
            $detalle = new stdClass();
            $detalle->id_producto = $producto->id_producto;
            $detalle->nombre = $producto->nombre;
            $detalle->imagen = $producto->imagen;
            $detalle->precio = $producto->precio;
            $detalle->cantidad = $item->cantidad;
            $_SESSION['carrito_detalles'][] = $detalle;
        }
    }
} // Si no está logueado, ya se actualizó la sesión arriba
