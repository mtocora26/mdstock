<?php
require_once __DIR__ . '/../config/db.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . '/../view/header.php';

// Si ya está logueado, redirige
if (!empty($_SESSION['usuario_id'])) {
  header('Location: /mdstock/view/index.php'); exit;
}

$errores = [];
$exito = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombres   = trim($_POST['nombres'] ?? '');
    $apellidos = trim($_POST['apellidos'] ?? '');
    $correo    = trim($_POST['correo'] ?? '');
    $telefono  = trim($_POST['telefono'] ?? '');
    $password  = $_POST['password'] ?? '';
    $password2 = $_POST['password2'] ?? '';

    if ($nombres === '' || $apellidos === '' || $correo === '' || $password === '') {
        $errores[] = 'Todos los campos marcados son obligatorios.';
    }
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $errores[] = 'Correo electrónico inválido.';
    }
    if ($password !== $password2) {
        $errores[] = 'Las contraseñas no coinciden.';
    }
    if (strlen($password) < 6) {
        $errores[] = 'La contraseña debe tener al menos 6 caracteres.';
    }

    if (!$errores) {
        $stmt = $pdo->prepare("SELECT id_usuario FROM usuario WHERE correo = :correo LIMIT 1");
        $stmt->execute([':correo' => $correo]);
        if ($stmt->fetch()) {
            $errores[] = 'Ya existe una cuenta con ese correo.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO usuario (nombres, apellidos, correo, telefono, password, fecha_registro) 
                                   VALUES (:nombres, :apellidos, :correo, :telefono, :password, NOW())");
            $stmt->execute([
                ':nombres'   => $nombres,
                ':apellidos' => $apellidos,
                ':correo'    => $correo,
                ':telefono'  => $telefono,
                ':password'  => $hash,
            ]);
            $exito = true;
        }
    }
}

?>
<main class="container">
  <h1>Crear cuenta</h1>

  <?php if ($exito): ?>
    <div class="alert alert-success">Registro exitoso. Ahora puedes <a href="/mdstock/pages/login.php">iniciar sesión</a>.</div>
  <?php endif; ?>

  <?php if ($errores): ?>
    <div class="alert alert-danger">
      <ul><?php foreach ($errores as $e) echo "<li>".htmlspecialchars($e)."</li>"; ?></ul>
    </div>
  <?php endif; ?>

  <form method="post" class="form">
    <label>Nombre(s)* <input type="text" name="nombres" required></label>
    <label>Apellidos* <input type="text" name="apellidos" required></label>
    <label>Correo* <input type="email" name="correo" required></label>
    <label>Teléfono <input type="text" name="telefono"></label>
    <label>Contraseña* <input type="password" name="password" required></label>
    <label>Confirmar contraseña* <input type="password" name="password2" required></label>
    <button type="submit" class="btn btn-primary">Registrarme</button>
  </form>
</main>
<?php require_once __DIR__ . '/../view/footer.php'; ?>
