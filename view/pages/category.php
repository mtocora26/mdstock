<?php require_once '../header.php'; ?>
<?php
require_once '../../controller/ProductoController.php';

$categoria_id = isset($_GET['categoria']) ? $_GET['categoria'] : null;
$productos = ProductoController::productosPorCategoria($categoria_id);
if (!is_array($productos)) {
    $productos = [];
}

// Paginación
$productos_por_pagina = 8;
$pagina_actual = isset($_GET['pagina']) ? max(1, intval($_GET['pagina'])) : 1;
$total_productos = count($productos);
$total_paginas = ($total_productos > 0) ? ceil($total_productos / $productos_por_pagina) : 1;
$inicio = ($pagina_actual - 1) * $productos_por_pagina;
$productos_pagina = array_slice($productos, $inicio, $productos_por_pagina);
?>

<main class="main">

    <!-- Page Title -->
    <div class="page-title light-background">
      <div class="container d-lg-flex justify-content-between align-items-center">
        <h1 class="mb-2 mb-lg-0">Category</h1>
  
      </div>
    </div><!-- End Page Title -->

    <div class="container">
      <div class="row">

        <div class="col-lg-4 sidebar">

          <div class="widgets-container">

            <!-- Product Categories Widget -->
            <div class="product-categories-widget widget-item">

              <h3 class="widget-title">Categorías</h3>
              <ul class="category-tree list-unstyled mb-0">
                <li class="category-item">
                  <a href="category.php?categoria=1" class="category-link">Bebidas</a>
                </li>
                <li class="category-item">
                  <a href="category.php?categoria=2" class="category-link">Snacks</a>
                </li>
                <li class="category-item">
                  <a href="category.php?categoria=3" class="category-link">Dulcería</a>
                </li>
              </ul>

            </div>
            <!--/Product Categories Widget -->

          </div>

        </div>

        <div class="col-lg-8">

          <!-- Category Header Section -->
          <section id="category-header" class="category-header section">

      

          </section><!-- /Category Header Section -->

          <!-- Category Product List Section -->
          <section id="category-product-list" class="category-product-list section">

            <div class="container" data-aos="fade-up" data-aos-delay="100">
              <div class="row gy-4">
                <?php foreach ($productos_pagina as $producto) { ?>
                <div class="col-lg-6">
                  <div class="product-box">
                    <div class="product-thumb position-relative">
                      <img src="<?php echo $producto->imagen; ?>" alt="Product Image" class="main-img" loading="lazy">
                      <div class="product-thumb-overlay">
                        <form method="post" action="../../controller/CarritoController.php">
                          <input type="hidden" name="accion" value="agregar">
                          <input type="hidden" name="id_producto" value="<?php echo $producto->id_producto; ?>">
                          <input type="hidden" name="cantidad" value="1">
                          <button type="submit" class="add-to-cart-btn w-100<?php echo ($producto->stock <= 0) ? ' disabled' : ''; ?>">
                            <?php echo ($producto->stock <= 0) ? 'Sin stock' : 'Agregar al carrito'; ?>
                          </button>
                        </form>
                      </div>
                    </div>
                    <div class="product-content">
                      <div class="product-details">
                        <h3 class="product-title">
                          <a href="product-details.php?id=<?php echo $producto->id_producto; ?>">
                            <?php echo $producto->nombre; ?>
                          </a>
                        </h3>
                        <div class="product-price">
                          <span>$<?php echo intval($producto->precio); ?></span>
                        </div>
                        <div class="product-description">
                          <small><?php echo $producto->descripcion; ?></small>
                        </div>
                        <div class="product-stock">
                          <small>Stock: <?php echo $producto->stock; ?></small>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <?php } ?>
              </div>
            </div>

          </section><!-- /Category Product List Section -->

          <!-- Category Pagination Section -->
          <section id="category-pagination" class="category-pagination section">

            <div class="container">
              <nav class="d-flex justify-content-center" aria-label="Page navigation">
                <ul>
                  <li>
                    <a href="?categoria=<?php echo urlencode($categoria_id); ?>&pagina=<?php echo max(1, $pagina_actual - 1); ?>" aria-label="Página anterior" <?php if ($pagina_actual <= 1) echo 'class="disabled"'; ?>>
                      <i class="bi bi-arrow-left"></i>
                      <span class="d-none d-sm-inline">Anterior</span>
                    </a>
                  </li>

                  <?php
                  // Mostrar máximo 5 páginas (actual, 2 antes, 2 después)
                  $start = max(1, $pagina_actual - 2);
                  $end = min($total_paginas, $pagina_actual + 2);
                  if ($start > 1) {
                    echo '<li><a href="?categoria=' . urlencode($categoria_id) . '&pagina=1">1</a></li>';
                    if ($start > 2) echo '<li class="ellipsis">...</li>';
                  }
                  for ($i = $start; $i <= $end; $i++) {
                    $active = ($i == $pagina_actual) ? ' class="active"' : '';
                    echo '<li><a href="?categoria=' . urlencode($categoria_id) . '&pagina=' . $i . '"' . $active . '>' . $i . '</a></li>';
                  }
                  if ($end < $total_paginas) {
                    if ($end < $total_paginas - 1) echo '<li class="ellipsis">...</li>';
                    echo '<li><a href="?categoria=' . urlencode($categoria_id) . '&pagina=' . $total_paginas . '">' . $total_paginas . '</a></li>';
                  }
                  ?>

                  <li>
                    <a href="?categoria=<?php echo urlencode($categoria_id); ?>&pagina=<?php echo min($total_paginas, $pagina_actual + 1); ?>" aria-label="Página siguiente" <?php if ($pagina_actual >= $total_paginas) echo 'class="disabled"'; ?>>
                      <span class="d-none d-sm-inline">Siguiente</span>
                      <i class="bi bi-arrow-right"></i>
                    </a>
                  </li>
                </ul>
              </nav>
            </div>

          </section><!-- /Category Pagination Section -->

        </div>

      </div>
    </div>

  </main>

<?php require_once '../footer.php'; ?>
