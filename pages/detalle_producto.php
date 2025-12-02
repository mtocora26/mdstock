<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../view/header.php';

$id = (int)($_GET['id'] ?? 0);

if ($id <= 0) {
    http_response_code(404);
    echo "<main class='container'><div class='alert alert-danger'>Producto no encontrado</div></main>";
    require_once __DIR__ . '/../view/footer.php';
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
    require_once __DIR__ . '/../view/footer.php';
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
          <li><a href="/index.php">Inicio</a></li>
          <li><a href="/mdstock/pages/productos.php">Productos</a></li>
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
              <img src="/mdstock/assets/img/no-image.png" alt="Sin imagen" class="img-fluid">
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

          <form method="POST" action="/mdstock/pages/carrito.php" class="product-actions mb-4">
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

<?php require_once __DIR__ . '/../view/footer.php'; ?>
