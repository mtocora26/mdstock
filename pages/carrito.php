<?php
require_once __DIR__ . '/../config/db.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . '/../view/header.php';

if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = [];
}

$action = $_POST['action'] ?? $_GET['action'] ?? null;

if ($action === 'add') {
    $id = (int)($_POST['id_producto'] ?? 0);
    $qty = max(1, (int)($_POST['cantidad'] ?? 1));
    if ($id > 0) {
        $_SESSION['cart'][$id] = ($_SESSION['cart'][$id] ?? 0) + $qty;
    }
    header('Location: /mdstock/pages/carrito.php');
    exit;
}

if ($action === 'update') {
    foreach ($_POST['cantidades'] ?? [] as $id => $qty) {
        $id = (int)$id;
        $qty = max(0, (int)$qty);
        if ($qty === 0) {
            unset($_SESSION['cart'][$id]);
        } else {
            $_SESSION['cart'][$id] = $qty;
        }
    }
    header('Location: /mdstock/pages/carrito.php');
    exit;
}

if ($action === 'remove') {
    $id = (int)($_GET['id'] ?? 0);
    unset($_SESSION['cart'][$id]);
    header('Location: /mdstock/pages/carrito.php');
    exit;
}

$items = [];
$byId = [];
$subtotal = 0.0;

$ids = array_map('intval', array_keys($_SESSION['cart']));

if ($ids) {
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $stmt = $pdo->prepare(
        "SELECT p.id_producto, p.nombre, p.precio, img.url AS imagen
         FROM productos p
         LEFT JOIN (
            SELECT id_producto, MIN(id_imagen) AS first_img
            FROM imagenes_productos
            GROUP BY id_producto
         ) x ON x.id_producto = p.id_producto
         LEFT JOIN imagenes_productos img ON img.id_imagen = x.first_img
         WHERE p.id_producto IN ($placeholders)"
    );
    $stmt->execute($ids);
    $items = $stmt->fetchAll();
    
    foreach ($items as $it) {
        $byId[$it['id_producto']] = $it;
    }
    
    foreach ($_SESSION['cart'] as $id => $qty) {
        $precio = (float)($byId[$id]['precio'] ?? 0);
        $subtotal += $precio * $qty;
    }
}

$impuesto = $subtotal * 0.19;
$total = $subtotal + $impuesto;

?>

<main class="main">

  <div class="page-title light-background">
    <div class="container d-lg-flex justify-content-between align-items-center">
      <h1 class="mb-2 mb-lg-0">Carrito de Compras</h1>
      <nav class="breadcrumbs">
        <ol>
          <li><a href="/index.php">Inicio</a></li>
          <li class="current">Carrito</li>
        </ol>
      </nav>
    </div>
  </div>

  <section id="cart" class="cart section">
    <div class="container" data-aos="fade-up" data-aos-delay="100">
      <div class="row g-4">
        <div class="col-lg-8" data-aos="fade-up" data-aos-delay="200">
          <div class="cart-items">
            
            <?php if (empty($_SESSION['cart'])): ?>
              <div class="alert alert-info">
                Tu carrito está vacío. <a href="/mdstock/pages/productos.php">Continuar comprando</a>
              </div>
            <?php else: ?>

              <form method="POST">
                <input type="hidden" name="action" value="update">
                
                <table class="table">
                  <thead>
                    <tr>
                      <th>Producto</th>
                      <th class="text-center">Cantidad</th>
                      <th class="text-end">Precio</th>
                      <th class="text-end">Total</th>
                      <th class="text-center">Acción</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($_SESSION['cart'] as $id => $qty): 
                      $p = $byId[$id] ?? null;
                      if (!$p) continue;
                      $totalLinea = (float)$p['precio'] * $qty;
                    ?>
                      <tr>
                        <td>
                          <div class="d-flex gap-3">
                                 <img src="<?= htmlspecialchars($p['imagen'] ?? '/mdstock/assets/img/no-image.png') ?>" 
                                 alt="<?= htmlspecialchars($p['nombre']) ?>"
                                 style="width: 80px; height: 80px; object-fit: cover;">
                            <div>
                              <h6><a href="/mdstock/pages/detalle_producto.php?id=<?= (int)$id ?>"><?= htmlspecialchars($p['nombre']) ?></a></h6>
                              <small class="text-muted">SKU: #<?= (int)$id ?></small>
                            </div>
                          </div>
                        </td>
                        <td class="text-center">
                          <input type="number" name="cantidades[<?= (int)$id ?>]" 
                                 class="form-control" value="<?= (int)$qty ?>" min="0" style="max-width: 80px; margin: 0 auto;">
                        </td>
                        <td class="text-end">$<?= number_format((float)$p['precio'], 2) ?></td>
                        <td class="text-end"><strong>$<?= number_format($totalLinea, 2) ?></strong></td>
                        <td class="text-center">
                          <a href="/mdstock/pages/carrito.php?action=remove&id=<?= (int)$id ?>" 
                             class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar del carrito?')">
                            <i class="bi bi-trash"></i>
                          </a>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>

                <div class="d-flex gap-3">
                  <button type="submit" class="btn btn-primary">Actualizar Carrito</button>
                  <a href="/mdstock/pages/productos.php" class="btn btn-outline-secondary">Continuar Comprando</a>
                </div>
              </form>

            <?php endif; ?>

          </div>
        </div>

        <div class="col-lg-4" data-aos="fade-up" data-aos-delay="300">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Resumen del Carrito</h5>

              <div class="d-flex justify-content-between mb-3 pb-3 border-bottom">
                <span>Subtotal:</span>
                <span>$<?= number_format($subtotal, 2) ?></span>
              </div>

              <div class="d-flex justify-content-between mb-3 pb-3 border-bottom">
                <span>IVA (19%):</span>
                <span>$<?= number_format($impuesto, 2) ?></span>
              </div>

              <div class="d-flex justify-content-between mb-4 h5">
                <span>Total:</span>
                <span>$<?= number_format($total, 2) ?></span>
              </div>

              <?php if (!empty($_SESSION['cart'])): ?>
                <a href="/mdstock/pages/checkout.php" class="btn btn-primary w-100 mb-2">
                  Proceder al Checkout
                </a>
              <?php endif; ?>
              
              <a href="/mdstock/pages/productos.php" class="btn btn-outline-secondary w-100">
                Seguir Comprando
              </a>
            </div>
          </div>
        </div>
      </div>

    </div>
  </section>

</main>

<?php require_once __DIR__ . '/../view/footer.php'; ?>
