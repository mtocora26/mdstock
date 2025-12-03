<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "<script>alert('Acceso inválido. Usa el formulario de registro.'); window.location.href = '../view/pages/login-register.php';</script>";
    exit;
}
require_once __DIR__ . '/../model/dao/UsuarioDAO.php';
require_once __DIR__ . '/../model/dto/UsuarioDTO.php';
require_once __DIR__ . '/../model/conexion.php';

$conexion = new Conexion();
$pdo = $conexion->getConexion();
$usuarioDAO = new UsuarioDAO($pdo);

$nombres    = $_POST['nombres']    ?? '';
$apellidos  = $_POST['apellidos']  ?? '';
$correo     = $_POST['correo']     ?? '';
$password   = $_POST['password']   ?? '';

$telefono   = $_POST['telefono']   ?? '';
$estado     = 'activo';

if (empty($nombres) || empty($apellidos) || empty($correo) || empty($password) || empty($telefono)) {
    echo "<script>alert('Por favor completa todos los campos.'); window.location.href = '../view/pages/login-register.php';</script>";
    exit;
}

$passwordHash = password_hash($password, PASSWORD_DEFAULT);
$usuarioDTO = new UsuarioDTO($nombres, $apellidos, $correo, $passwordHash, $telefono, $estado);

try {
    $idGenerado = $usuarioDAO->crearUsuario($usuarioDTO);
    $msg = "Usuario registrado correctamente. ID: " . ($idGenerado !== null ? $idGenerado : "(sin ID)");
    echo "<script>window.onload=function(){alert(" . json_encode($msg) . "); window.location.href = '../view/pages/index.php';}</script>";
} catch (Exception $e) {
    $err = "Error al registrar usuario: " . $e->getMessage();
    echo "<script>window.onload=function(){alert(" . json_encode($err) . "); window.location.href = '../view/pages/login-register.php';}</script>";
}
