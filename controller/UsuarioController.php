<?php
session_start();
require_once __DIR__ . '/../model/dao/UsuarioDAO.php';
require_once __DIR__ . '/../model/dto/UsuarioDTO.php';
require_once __DIR__ . '/../model/conexion.php';

$conexion = new Conexion();
$pdo = $conexion->getConexion();
$usuarioDAO = new UsuarioDAO($pdo);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "<script>alert('Acceso inválido. Usa el formulario.'); window.location.href = '../view/pages/account.php';</script>";
    exit;
}

// Actualizar datos personales
if (isset($_POST['actualizar_usuario'])) {
    $id_usuario = $_SESSION['usuario']['id_usuario'];
    $nombres    = $_POST['nombres']    ?? '';
    $apellidos  = $_POST['apellidos']  ?? '';
    $email      = $_POST['email']      ?? '';
    $telefono   = $_POST['telefono']   ?? '';

    if (empty($nombres) || empty($apellidos) || empty($email)) {
        echo "<script>alert('Por favor completa todos los campos.'); window.location.href = '../view/pages/account.php';</script>";
        exit;
    }

    try {
        $usuarioDTO = new UsuarioDTO($nombres, $apellidos, $email, '', $telefono, 'activo');
        $usuarioDTO->id_usuario = $id_usuario;
        $usuarioDAO->actualizarUsuario($usuarioDTO);
        // Actualiza la sesión
        $_SESSION['usuario']['nombres'] = $nombres;
        $_SESSION['usuario']['apellidos'] = $apellidos;
        $_SESSION['usuario']['email'] = $email;
        $_SESSION['usuario']['telefono'] = $telefono;
        echo "<script>window.onload=function(){alert('Datos actualizados correctamente.'); window.location.href = '../view/pages/account.php';}</script>";
    } catch (Exception $e) {
        $err = "Error al actualizar usuario: " . $e->getMessage();
        echo "<script>window.onload=function(){alert(" . json_encode($err) . "); window.location.href = '../view/pages/account.php';}</script>";
    }
    exit;
}

// Actualizar contraseña
if (isset($_POST['actualizar_contrasena'])) {
    $id_usuario = $_SESSION['usuario']['id_usuario'];
    $currentPassword = $_POST['currentPassword'] ?? '';
    $newPassword     = $_POST['newPassword'] ?? '';
    $confirmPassword = $_POST['confirmPassword'] ?? '';

    if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
        echo "<script>alert('Completa todos los campos de contraseña.'); window.location.href = '../view/pages/account.php';</script>";
        exit;
    }
    if ($newPassword !== $confirmPassword) {
        echo "<script>alert('Las contraseñas nuevas no coinciden.'); window.location.href = '../view/pages/account.php';</script>";
        exit;
    }

    // Verifica la contraseña actual
    $usuarioActual = $usuarioDAO->buscarPorId($id_usuario);
    if (!$usuarioActual || !password_verify($currentPassword, $usuarioActual->password)) {
        echo "<script>alert('La contraseña actual es incorrecta.'); window.location.href = '../view/pages/account.php';</script>";
        exit;
    }

    try {
        $passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);
        $usuarioDAO->actualizarContrasena($id_usuario, $passwordHash);
        echo "<script>window.onload=function(){alert('Contraseña actualizada correctamente.'); window.location.href = '../view/pages/account.php';}</script>";
    } catch (Exception $e) {
        $err = "Error al actualizar contraseña: " . $e->getMessage();
        echo "<script>window.onload=function(){alert(" . json_encode($err) . "); window.location.href = '../view/pages/account.php';}</script>";
    }
    exit;
}

// Registro de usuario (original)
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
    // Iniciar sesión automáticamente
    if ($idGenerado !== null) {
        $_SESSION['usuario'] = [
            'id_usuario' => $idGenerado,
            'nombres' => $nombres,
            'apellidos' => $apellidos,
            'email' => $correo,
            'telefono' => $telefono
        ];
        echo "<script>window.onload=function(){alert('Usuario registrado y logueado correctamente.'); window.location.href = '../view/pages/account.php';}</script>";
    } else {
        $msg = "Usuario registrado correctamente. (sin ID)";
        echo "<script>window.onload=function(){alert(" . json_encode($msg) . "); window.location.href = '../view/pages/index.php';}</script>";
    }
} catch (Exception $e) {
    $err = "Error al registrar usuario: " . $e->getMessage();
    echo "<script>window.onload=function(){alert(" . json_encode($err) . "); window.location.href = '../view/pages/login-register.php';}</script>";
}
