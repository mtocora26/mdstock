<?php
require_once __DIR__ . '/../config/db.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . '/../view/header.php';
$id = (int)($_GET['id'] ?? 0);
?>
<main class="container">
  <h1>¡Pedido confirmado!</h1>
  <p>Tu número de pedido es <strong>#<?= $id ?></strong>. Te enviamos un correo con los detalles.</p>
  <p><a href="/mdstock/pages/productos.php">Seguir comprando</a></p>
</main>
<?php require_once __DIR__ . '/../view/footer.php'; ?>
