# INTEGRACIÓN DE BASE DE DATOS - GUÍA DE IMPLEMENTACIÓN

## Estado actual
✅ Completado:
- A. Sesiones y conexión a BD (config/db.php + header.php con require)
- B. Navbar dinámico (includes/navbar.php carga categorías/subcategorías desde BD)
- C. Productos con filtros (productos.php con WHERE dinámico, paginación)

## Próximos pasos: Reemplazar manualmente estos archivos

### 1. detalle_producto.php
Reemplaza TODO el contenido por el snippet que está en este archivo (ver más abajo).
**Qué hace:** 
- Carga producto por ID desde BD
- Muestra todas las imágenes
- Muestra categoría, subcategoría, precio, inventario
- Formulario POST a carrito.php con action=add

### 2. carrito.php
Reemplaza TODO el contenido por el snippet que está en este archivo.
**Qué hace:**
- Maneja carrito en $_SESSION['cart'] (id_producto => cantidad)
- Actions: add, update, remove
- Calcula subtotal + IVA + total
- Tabla editable de cantidades

### 3. checkout.php
Reemplaza TODO el contenido por el snippet que está en este archivo.
**Qué hace:**
- Verifica si usuario está logueado (si no, redirige a login.php)
- Carga direcciones del usuario desde BD
- Formulario de pago
- Resumen del carrito

### 4. login.php & registro.php
Crea estos con validación, password_hash, y middleware.

### 5. Limpieza
Mueve archivos .html viejos a /backups/html_backups/ (ya existen algunos ahí)

---

## SNIPPETS LISTOS PARA PEGAR

### detalle_producto.php

```php
<?php
require_once __DIR__ . '/includes/header.php';

$id = (int)($_GET['id'] ?? 0);

if ($id <= 0) {
    http_response_code(404);
    echo "<main class='container'><div class='alert alert-danger'>Producto no encontrado</div></main>";
    include __DIR__ . '/includes/footer.php';
    exit;
}

// Consultar producto
$sql = "SELECT p.id_producto, p.nombre, p.descripcion, p.precio,
               c.nombre AS categoria, s.nombre AS subcategoria,
               inv.stock_actual
        FROM productos p
        LEFT JOIN categorias c ON c.id_categoria = p.id_categoria
        LEFT JOIN subcategorias s ON s.id_subcategoria = p.id_subcategoria
        LEFT JOIN inventario inv ON inv.id_producto = p.id_producto
        WHERE p.id_producto = :id";

$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $id]);
$prod = $stmt->fetch();

if (!$prod) {
    http_response_code(404);
    echo "<main class='container'><div class='alert alert-danger'>Producto no encontrado</div></main>";
    include __DIR__ . '/includes/footer.php';
    exit;
}

// Todas las imágenes del producto
$imgs = $pdo->prepare("SELECT url, alt FROM imagenes_productos WHERE id_producto = :id ORDER BY id_imagen ASC");
$imgs->execute([':id' => $id]);
$imagenes = $imgs->fetchAll();
?>

<main class="main">

  <div class="page-title light-background">
    <div class="container d-lg-flex justify-content-between align-items-center">
      <h1 class="mb-2 mb-lg-0">Detalle del Producto</h1>
      <nav class="breadcrumbs">
        <ol>
          <li><a href="index.php">Inicio</a></li>
          <li><a href="productos.php">Productos</a></li>
          <li class="current"><?= htmlspecialchars($prod['nombre']) ?></li>
        </ol>
      </nav>
    </div>
  </div>

  <section id="product-details" class="product-details section">
    <div class="container" data-aos="fade-up" data-aos-delay="100">
      <div class="row g-5">

        <div class="col-lg-5" data-aos="fade-up" data-aos-delay="100">
          <div class="product-detail-image">
            <?php if (!empty($imagenes)): ?>
              <img id="mainImage" src="<?= htmlspecialchars($imagenes[0]['url']) ?>" 
                   alt="<?= htmlspecialchars($imagenes[0]['alt'] ?? $prod['nombre']) ?>" class="img-fluid">
              
              <?php if (count($imagenes) > 1): ?>
                <div class="image-thumbnails mt-3 d-flex gap-2">
                  <?php foreach ($imagenes as $im): ?>
                    <img src="<?= htmlspecialchars($im['url']) ?>" 
                         alt="<?= htmlspecialchars($im['alt'] ?? '') ?>" 
                         class="thumb" style="width: 80px; height: 80px; object-fit: cover; cursor: pointer;"
                         onclick="document.getElementById('mainImage').src = this.src">
                  <?php endforeach; ?>
                </div>
              <?php endif; ?>
            <?php else: ?>
              <img src="/assets/img/no-image.png" alt="Sin imagen" class="img-fluid">
            <?php endif; ?>
          </div>
        </div>

        <div class="col-lg-7" data-aos="fade-up" data-aos-delay="200">
          <h2 class="product-title"><?= htmlspecialchars($prod['nombre']) ?></h2>

          <div class="product-category mb-3">
            <small class="text-muted">
              <?= htmlspecialchars($prod['categoria'] ?? 'Categoría no especificada') ?> 
              <?php if (!empty($prod['subcategoria'])): ?>
                / <?= htmlspecialchars($prod['subcategoria']) ?>
              <?php endif; ?>
            </small>
          </div>

          <div class="product-price mb-4">
            <span class="h3">$<?= number_format((float)$prod['precio'], 2) ?></span>
          </div>

          <p class="product-description mb-4">
            <?= nl2br(htmlspecialchars($prod['descripcion'])) ?>
          </p>

          <div class="product-stock mb-4">
            <p class="stock <?= (int)($prod['stock_actual'] ?? 0) > 0 ? 'available' : 'out' ?>">
              <strong>Estado:</strong> 
              <?= (int)($prod['stock_actual'] ?? 0) > 0 ? 
                'Disponible en stock (' . (int)$prod['stock_actual'] . ')' : 
                'Agotado' ?>
            </p>
          </div>

          <form method="POST" action="carrito.php" class="product-actions mb-4">
            <input type="hidden" name="action" value="add">
            <input type="hidden" name="id_producto" value="<?= (int)$prod['id_producto'] ?>">
            
            <div class="d-flex gap-3 align-items-end">
              <div>
                <label class="form-label">Cantidad:</label>
                <input type="number" name="cantidad" class="form-control" value="1" min="1" 
                       max="<?= max(1, (int)($prod['stock_actual'] ?? 0)) ?>" style="width: 100px;">
              </div>
              <button type="submit" class="btn btn-primary btn-lg" 
                      <?= ((int)($prod['stock_actual'] ?? 0) <= 0) ? 'disabled' : '' ?>>
                <i class="bi bi-cart-plus"></i> Agregar al carrito
              </button>
            </div>
          </form>

          <div class="product-meta">
            <p><strong>SKU:</strong> #<?= (int)$prod['id_producto'] ?></p>
            <p><strong>Envío:</strong> Envío gratis en compras mayores a $50</p>
          </div>
        </div>

      </div>
    </div>
  </section>

</main>

<?php include __DIR__ . '/includes/footer.php'; ?>
```

### carrito.php

```php
<?php
require_once __DIR__ . '/includes/header.php';

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
    header('Location: carrito.php');
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
    header('Location: carrito.php');
    exit;
}

if ($action === 'remove') {
    $id = (int)($_GET['id'] ?? 0);
    unset($_SESSION['cart'][$id]);
    header('Location: carrito.php');
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
          <li><a href="index.php">Inicio</a></li>
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
                Tu carrito está vacío. <a href="productos.php">Continuar comprando</a>
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
                            <img src="<?= htmlspecialchars($p['imagen'] ?? '/assets/img/no-image.png') ?>" 
                                 alt="<?= htmlspecialchars($p['nombre']) ?>"
                                 style="width: 80px; height: 80px; object-fit: cover;">
                            <div>
                              <h6><a href="detalle_producto.php?id=<?= (int)$id ?>"><?= htmlspecialchars($p['nombre']) ?></a></h6>
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
                          <a href="carrito.php?action=remove&id=<?= (int)$id ?>" 
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
                  <a href="productos.php" class="btn btn-outline-secondary">Continuar Comprando</a>
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
                <a href="checkout.php" class="btn btn-primary w-100 mb-2">
                  Proceder al Checkout
                </a>
              <?php endif; ?>
              
              <a href="productos.php" class="btn btn-outline-secondary w-100">
                Seguir Comprando
              </a>
            </div>
          </div>
        </div>
      </div>

    </div>
  </section>

</main>

<?php include __DIR__ . '/includes/footer.php'; ?>
```

### checkout.php (básico - requiere login)

```php
<?php
require_once __DIR__ . '/includes/header.php';

// Verificar si está logueado
if (empty($_SESSION['usuario_id'])) {
    header('Location: login.php?next=' . urlencode($_SERVER['REQUEST_URI']));
    exit;
}

// Cargar direcciones del usuario
$stmt = $pdo->prepare("SELECT * FROM direcciones_envio WHERE id_usuario = :u ORDER BY predeterminada DESC, id_direccion DESC");
$stmt->execute([':u' => (int)$_SESSION['usuario_id']]);
$direcciones = $stmt->fetchAll();

$cart = $_SESSION['cart'] ?? [];
if (!$cart) {
    echo "<main class='container'><div class='alert alert-warning'>Tu carrito está vacío.</div></main>";
    include __DIR__ . '/includes/footer.php';
    exit;
}
?>

<main class="main">

  <div class="page-title light-background">
    <div class="container d-lg-flex justify-content-between align-items-center">
      <h1 class="mb-2 mb-lg-0">Checkout</h1>
      <nav class="breadcrumbs">
        <ol>
          <li><a href="index.php">Inicio</a></li>
          <li><a href="carrito.php">Carrito</a></li>
          <li class="current">Checkout</li>
        </ol>
      </nav>
    </div>
  </div>

  <section id="checkout" class="checkout section">
    <div class="container" data-aos="fade-up" data-aos-delay="100">
      <div class="row g-4">

        <div class="col-lg-8">

          <div class="card mb-4" data-aos="fade-up" data-aos-delay="200">
            <div class="card-body">
              <h5 class="card-title">Dirección de Envío</h5>
              
              <form method="POST" action="checkout_procesar.php">
                <?php if (!empty($direcciones)): ?>
                  <?php foreach ($direcciones as $d): ?>
                    <div class="form-check mb-3">
                      <input class="form-check-input" type="radio" name="id_direccion" 
                             value="<?= (int)$d['id_direccion'] ?>" id="dir<?= (int)$d['id_direccion'] ?>"
                             <?= !empty($d['predeterminada']) ? 'checked' : '' ?>>
                      <label class="form-check-label" for="dir<?= (int)$d['id_direccion'] ?>">
                        <?= htmlspecialchars($d['direccion']) ?>, <?= htmlspecialchars($d['ciudad']) ?> (<?= htmlspecialchars($d['estado']) ?>)
                      </label>
                    </div>
                  <?php endforeach; ?>
                <?php else: ?>
                  <p class="alert alert-info">No tienes direcciones guardadas. <a href="perfil.php">Agrega una dirección</a></p>
                <?php endif; ?>
                
                <hr>

                <h5 class="card-title">Método de Pago</h5>
                <div class="form-check mb-3">
                  <input class="form-check-input" type="radio" name="metodo_pago" value="transferencia" id="mp1" checked>
                  <label class="form-check-label" for="mp1">Transferencia Bancaria</label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="metodo_pago" value="contraentrega" id="mp2">
                  <label class="form-check-label" for="mp2">Contra Entrega</label>
                </div>

                <hr>

                <div class="form-check mb-4">
                  <input class="form-check-input" type="checkbox" name="terminos" id="terms" required>
                  <label class="form-check-label" for="terms">
                    Acepto los <a href="tos.php">términos y condiciones</a>
                  </label>
                </div>

                <button type="submit" class="btn btn-primary btn-lg w-100">
                  Confirmar Pedido
                </button>
              </form>
            </div>
          </div>

        </div>

        <div class="col-lg-4">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Resumen del Pedido</h5>
              <!-- Aquí pueden ir los items del carrito si deseas mostrarlos -->
              <a href="carrito.php" class="btn btn-outline-secondary w-100">
                Ver Carrito
              </a>
            </div>
          </div>
        </div>

      </div>
    </div>
  </section>

</main>

<?php include __DIR__ . '/includes/footer.php'; ?>
```

---

## CAMBIOS REALIZADOS

✅ config/db.php → PDO robusto
✅ includes/header.php → sesiones + require DB
✅ includes/navbar.php → categorías dinámicas desde BD
✅ productos.php → filtros, búsqueda, paginación

## SIGUIENTES PASOS

1. Reemplaza detalle_producto.php, carrito.php, checkout.php con los snippets de arriba
2. Crea login.php y registro.php con password_hash (mañana te los paso)
3. Crea checkout_procesar.php para crear pedidos
4. Mueve HTML viejos a /backups/html_backups
