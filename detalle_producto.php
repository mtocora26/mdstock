<?php
/**
 * Detalle de producto - detalle_producto.php
 */

include 'includes/header.php';

// Si usarás BD, aquí irían las consultas
// $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
// $stmt = ejecutar_query("SELECT * FROM productos WHERE id = ?", [$id]);
// $producto = $stmt->fetch();
?>

<main class="main">

  <!-- Page Title -->
  <div class="page-title light-background">
    <div class="container d-lg-flex justify-content-between align-items-center">
      <h1 class="mb-2 mb-lg-0">Detalle del Producto</h1>
      <nav class="breadcrumbs">
        <ol>
          <li><a href="/mdstock/index.php">Inicio</a></li>
          <li><a href="/mdstock/pages/productos.php">Productos</a></li>
          <li class="current">Detalle</li>
        </ol>
      </nav>
    </div>
  </div><!-- End Page Title -->

  <!-- Product Details Section -->
  <section id="product-details" class="product-details section">

    <div class="container" data-aos="fade-up" data-aos-delay="100">

      <div class="row g-5">

        <!-- Product Image -->
        <div class="col-lg-5" data-aos="fade-up" data-aos-delay="100">
          <div class="product-detail-image">
            <img src="/mdstock/assets/img/product/product-1.webp" alt="Producto" class="img-fluid">
          </div>
        </div>

        <!-- Product Info -->
        <div class="col-lg-7" data-aos="fade-up" data-aos-delay="200">
          <h2 class="product-title">Nombre del Producto</h2>

          <div class="product-rating mb-4">
            <div class="stars">
              <i class="bi bi-star-fill"></i>
              <i class="bi bi-star-fill"></i>
              <i class="bi bi-star-fill"></i>
              <i class="bi bi-star-fill"></i>
              <i class="bi bi-star"></i>
            </div>
            <span class="review-count">(25 reviews)</span>
          </div>

          <div class="product-price mb-4">
            <span class="current-price h3">$99.99</span>
            <span class="original-price text-muted"><del>$129.99</del></span>
          </div>

          <p class="product-description mb-4">
            Descripción detallada del producto. Aquí va la información importante sobre características, materiales, dimensiones, etc.
          </p>

          <div class="product-options mb-4">
            <div class="mb-3">
              <label class="form-label">Cantidad:</label>
              <input type="number" class="form-control" style="max-width: 100px;" value="1" min="1">
            </div>
          </div>

          <div class="product-actions d-flex gap-3 mb-4">
            <a href="/mdstock/pages/carrito.php" class="btn btn-primary btn-lg flex-grow-1">
              <i class="bi bi-cart-plus"></i> Agregar al carrito
            </a>
            <button class="btn btn-outline-secondary btn-lg">
              <i class="bi bi-heart"></i>
            </button>
          </div>

          <div class="product-meta">
            <p><strong>SKU:</strong> #1234</p>
            <p><strong>Disponibilidad:</strong> <span class="badge bg-success">En stock</span></p>
            <p><strong>Envío:</strong> Envío gratis en compras mayores a $50</p>
          </div>
        </div>

      </div>

    </div>

  </section><!-- /Product Details Section -->

</main>

<?php include 'includes/footer.php'; ?>
