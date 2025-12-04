<?php
session_start();
require_once __DIR__ . '/../model/dao/UsuarioDAO.php';
require_once __DIR__ . '/../model/dto/UsuarioDTO.php';
require_once __DIR__ . '/../model/conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "<script>alert('Acceso inválido. Usa el formulario de login.'); window.location.href = '../view/pages/login-register.php';</script>";
    exit;
}

$correo = $_POST['correo'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($correo) || empty($password)) {
    echo "<script>window.onload=function(){alert('Por favor ingresa correo y contraseña.'); window.location.href = '../view/pages/login-register.php';}</script>";
    exit;
}

$conexion = new Conexion();
$pdo = $conexion->getConexion();
$usuarioDAO = new UsuarioDAO($pdo);

$usuario = $usuarioDAO->buscarPorCorreo($correo);

if ($usuario && password_verify($password, $usuario->password)) {
    $_SESSION['usuario'] = [
        'id_usuario' => $usuario->id_usuario,
        'nombres' => $usuario->nombres,
        'apellidos' => $usuario->apellidos,
        'correo' => $usuario->correo,
        'email' => $usuario->correo, // para compatibilidad con la vista
        'telefono' => $usuario->telefono ?? ''
    ];

    // Sincronizar carrito de sesión con el de la base de datos
    require_once __DIR__ . '/../model/dao/CarritoDAO.php';
    $carritoDAO = new CarritoDAO($pdo);
    $id_carrito = null;
    $carrito = $carritoDAO->obtenerCarritoPorUsuario($usuario->id_usuario);
    if (!$carrito) {
        $id_carrito = $carritoDAO->crearCarrito($usuario->id_usuario);
    } else {
        $id_carrito = $carrito['id_carrito'];
    }
    // Si hay productos en el carrito de sesión, agregarlos al carrito de la base
    if (!empty($_SESSION['carrito_detalles'])) {
        foreach ($_SESSION['carrito_detalles'] as $item) {
            $carritoDAO->agregarOActualizarProducto($id_carrito, $item->id_producto, $item->cantidad);
        }
        // Limpiar el carrito de sesión
        unset($_SESSION['carrito_detalles']);
    }

    echo "<script>window.onload=function(){alert('Login exitoso. Bienvenido ' + " . json_encode($usuario->nombres) . "); window.location.href = '../view/pages/index.php';}</script>";
} else {
    echo "<script>window.onload=function(){alert('Correo o contraseña incorrectos.'); window.location.href = '../view/pages/login-register.php';}</script>";
}
