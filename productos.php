<?php
/**
 * productos.php
 * Listado de productos con filtros, búsqueda y paginación
 */

require_once __DIR__ . '/includes/header.php';

// Parámetros
$cat = isset($_GET['cat']) ? (int)$_GET['cat'] : null;
$sub = isset($_GET['sub']) ? (int)$_GET['sub'] : null;
$q   = isset($_GET['q']) ? trim($_GET['q']) : '';
$pagina    = max(1, (int)($_GET['p'] ?? 1));
$porPagina = 12;
$offset    = ($pagina - 1) * $porPagina;

// Construir WHERE dinámico
$where = [];
$params = [];

if ($cat) {
    $where[] = 'p.id_categoria = :cat';
    $params[':cat'] = $cat;
}
if ($sub) {
    $where[] = 'p.id_subcategoria = :sub';
    $params[':sub'] = $sub;
}
if ($q !== '') {
    $where[] = '(p.nombre LIKE :q OR p.descripcion LIKE :q)';
    $params[':q'] = "%{$q}%";
}

$whereSql = $where ? 'WHERE ' . implode(' AND ', $where) : '';

// Conteo total de productos
$sqlCount = "SELECT COUNT(*) AS total FROM productos p $whereSql";
$stmt = $pdo->prepare($sqlCount);
$stmt->execute($params);
$total = (int)$stmt->fetchColumn();

// Consulta de productos + imagen principal + precio + inventario
$sql = "SELECT p.id_producto, p.nombre, p.descripcion, p.precio,
               img.url AS imagen,
               inv.stock_actual
        FROM productos p
        LEFT JOIN (
           SELECT id_producto, MIN(id_imagen) AS first_img
           FROM imagenes_productos
           GROUP BY id_producto
        ) x ON x.id_producto = p.id_producto
        LEFT JOIN imagenes_productos img ON img.id_imagen = x.first_img
        LEFT JOIN inventario inv ON inv.id_producto = p.id_producto
        $whereSql
        ORDER BY p.nombre
        LIMIT :limit OFFSET :offset";

$stmt = $pdo->prepare($sql);
foreach ($params as $k => $v) {
    $stmt->bindValue($k, $v);
}
$stmt->bindValue(':limit', $porPagina, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$productos = $stmt->fetchAll();

?>

<main class="main">

  <!-- Page Title -->
  <div class="page-title light-background">
    <div class="container d-lg-flex justify-content-between align-items-center">
      <h1 class="mb-2 mb-lg-0">Productos</h1>
      <nav class="breadcrumbs">
        <ol>
          <li><a href="/mdstock/index.php">Inicio</a></li>
          <li class="current">Productos</li>
        </ol>
      </nav>
    </div>
  </div><!-- End Page Title -->

  <div class="container">
    <div class="row">

      <div class="col-lg-4 sidebar">
        <div class="widgets-container">

          <!-- Search Widget -->
          <div class="widget-item">
            <h3>Buscar</h3>
            <form method="GET" class="mb-3">
              <?php if ($cat): ?>
                <input type="hidden" name="cat" value="<?= (int)$cat ?>">
              <?php endif; ?>
              <?php if ($sub): ?>
                <input type="hidden" name="sub" value="<?= (int)$sub ?>">
              <?php endif; ?>
              <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Buscar..." value="<?= htmlspecialchars($q) ?>">
                <button class="btn btn-primary" type="submit">
                  <i class="bi bi-search"></i>
                </button>
              </div>
            </form>
          </div>

          <!-- Categories Widget -->
          <div class="widget-item">
            <h3>Categorías</h3>
            <ul class="list-unstyled">
              <li><a href="/mdstock/pages/productos.php">Todas</a></li>
              <?php foreach ($GLOBALS['categorias'] ?? [] as $c): ?>
                <li>
                  <a href="/mdstock/pages/productos.php?cat=<?= (int)$c['id_categoria'] ?>" 
                     class="<?= $cat === (int)$c['id_categoria'] ? 'active' : '' ?>">
                    <?= htmlspecialchars($c['nombre']) ?>
                  </a>
                </li>
              <?php endforeach; ?>
            </ul>
          </div>

        </div>
      </div>

      <div class="col-lg-8">

        <!-- Products Grid -->
        <section id="products" class="products section">

          <div class="container" data-aos="fade-up">

            <?php if (empty($productos)): ?>
              <div class="alert alert-info">
                No se encontraron productos.
              </div>
            <?php else: ?>

              <div class="row g-4">

                <?php foreach ($productos as $prod): ?>
                  <div class="col-lg-6 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="product-card">
                      <div class="product-image">
                        <a href="/mdstock/pages/detalle_producto.php?id=<?= (int)$prod['id_producto'] ?>">
                          <img src="<?= htmlspecialchars($prod['imagen'] ?? '/mdstock/assets/img/no-image.png') ?>" 
                               alt="<?= htmlspecialchars($prod['nombre']) ?>" loading="lazy">
                        </a>
                      </div>
                      <div class="product-info">
                        <span class="product-category">Producto</span>
                        <h5 class="product-title">
                          <a href="/mdstock/pages/detalle_producto.php?id=<?= (int)$prod['id_producto'] ?>">
                            <?= htmlspecialchars($prod['nombre']) ?>
                          </a>
                        </h5>
                        <div class="product-price">
                          <span class="current-price">$<?= number_format((float)$prod['precio'], 2) ?></span>
                        </div>
                        <p class="stock <?= (int)($prod['stock_actual'] ?? 0) > 0 ? 'available' : 'out' ?>">
                          <?= (int)($prod['stock_actual'] ?? 0) > 0 ? 'Disponible' : 'Agotado' ?>
                        </p>
                        <form method="POST" action="/mdstock/pages/carrito.php" class="d-flex gap-2">
                          <input type="hidden" name="action" value="add">
                          <input type="hidden" name="id_producto" value="<?= (int)$prod['id_producto'] ?>">
                          <input type="number" name="cantidad" class="form-control" value="1" min="1" 
                                 max="<?= max(1, (int)($prod['stock_actual'] ?? 0)) ?>" style="max-width: 70px;">
                          <button type="submit" class="btn btn-primary flex-grow-1" 
                                  <?= ((int)($prod['stock_actual'] ?? 0) <= 0) ? 'disabled' : '' ?>>
                            <i class="bi bi-cart-plus"></i>
                          </button>
                        </form>
                      </div>
                    </div>
                  </div>
                <?php endforeach; ?>

              </div>

              <?php
                // Paginación
                $paginas = (int)ceil($total / $porPagina);
                if ($paginas > 1):
              ?>
                <nav class="pagination mt-4" aria-label="Page navigation">
                  <ul class="pagination justify-content-center">
                    <?php for ($p = 1; $p <= $paginas; $p++): 
                      $queryParams = array_filter([
                        'cat' => $cat,
                        'sub' => $sub,
                        'q' => $q,
                        'p' => $p
                      ], fn($v) => $v !== null && $v !== '');
                    ?>
                      <li class="page-item <?= $p === $pagina ? 'active' : '' ?>">
                        <a class="page-link" href="?<?= http_build_query($queryParams) ?>">
                          <?= $p ?>
                        </a>
                      </li>
                    <?php endfor; ?>
                  </ul>
                </nav>
              <?php endif; ?>

            <?php endif; ?>

          </div>

        </section><!-- /Products Section -->

      </div>

    </div>
  </div>

</main>

<?php include __DIR__ . '/includes/footer.php'; ?>
