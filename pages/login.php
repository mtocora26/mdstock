<?php
require_once __DIR__ . '/../config/db.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . '/../view/header.php';

if (!empty($_SESSION['usuario_id'])) {
    header('Location: /mdstock/view/index.php'); exit;
}

$errores = [];
$next = $_POST['next'] ?? $_GET['next'] ?? '/mdstock/view/index.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo   = trim($_POST['correo'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($correo === '' || $password === '') {
        $errores[] = 'Correo y contraseña son obligatorios.';
    } else {
        $stmt = $pdo->prepare("SELECT id_usuario, password, nombres FROM usuario WHERE correo = :correo LIMIT 1");
        $stmt->execute([':correo' => $correo]);
        $user = $stmt->fetch();

        if (!$user || !password_verify($password, $user['password'])) {
            $errores[] = 'Credenciales inválidas.';
        } else {
            // Login ok
            $_SESSION['usuario_id'] = (int)$user['id_usuario'];
            $_SESSION['usuario_nombre'] = $user['nombres'];

            // Sincronización básica del carrito con BD (opcional)
            $stmt = $pdo->prepare("SELECT id_carrito FROM carrito WHERE id_usuario = :u ORDER BY id_carrito DESC LIMIT 1");
            $stmt->execute([':u' => (int)$user['id_usuario']]);
            $car = $stmt->fetch();

            if (!$car) {
                $pdo->prepare("INSERT INTO carrito (id_usuario, fecha_creacion, estado) VALUES (:u, NOW(), 'activo')")
                    ->execute([':u' => (int)$user['id_usuario']]);
                $carId = (int)$pdo->lastInsertId();
            } else {
                $carId = (int)$car['id_carrito'];
            }

            if (!empty($_SESSION['cart'])) {
                foreach ($_SESSION['cart'] as $idProd => $qty) {
                    $idProd = (int)$idProd; $qty = max(1, (int)$qty);
                    $q = $pdo->prepare("SELECT id_detalle, cantidad FROM carrito_detalle WHERE id_carrito = :c AND id_producto = :p");
                    $q->execute([':c' => $carId, ':p' => $idProd]);
                    $line = $q->fetch();

                    if ($line) {
                        $pdo->prepare("UPDATE carrito_detalle SET cantidad = cantidad + :q WHERE id_detalle = :d")
                            ->execute([':q' => $qty, ':d' => (int)$line['id_detalle']]);
                    } else {
                        $pdo->prepare("INSERT INTO carrito_detalle (id_carrito, id_producto, cantidad) VALUES (:c, :p, :q)")
                            ->execute([':c' => $carId, ':p' => $idProd, ':q' => $qty]);
                    }
                }
            }

            header('Location: ' . $next); exit;
        }
    }
}
?>
<main class="container">
  <h1>Iniciar sesión</h1>

  <?php if ($errores): ?>
    <div class="alert alert-danger">
      <ul><?php foreach ($errores as $e) echo "<li>".htmlspecialchars($e)."</li>"; ?></ul>
    </div>
  <?php endif; ?>

  <form method="post" class="form">
    <input type="hidden" name="next" value="<?= htmlspecialchars($next) ?>">
    <label>Correo <input type="email" name="correo" required></label>
    <label>Contraseña <input type="password" name="password" required></label>
    <button type="submit" class="btn btn-primary">Ingresar</button>
  </form>
    <p>¿No tienes cuenta? <a href="/mdstock/pages/registro.php">Regístrate</a></p>
</main>
<?php require_once __DIR__ . '/../view/footer.php'; ?>
