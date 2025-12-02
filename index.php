<?php
/**
 * Página principal - index.php
 * Incluye header, navbar, footer
 */

// Incluir configuración (opcional, si usarás BD)
// include 'config/db.php';

// Incluir header (contiene doctype, head, body abierto, header HTML, navbar)
include 'includes/header.php';
?>

<main class="main">

  <!-- Hero Section -->
  <section id="hero" class="hero section">

    <div class="container" data-aos="fade-up" data-aos-delay="100">
      <div class="row align-items-center">
        <div class="col-lg-6">
          <div class="hero-content" data-aos="fade-up" data-aos-delay="200">
            <div class="search-bar">
              <form class="form-search d-flex align-items-center" method="GET" action="/mdstock/buscar.php">
                <input type="text" placeholder="Busca productos o categorías" name="q">
                <button type="submit" class="btn btn-brand">
                  <i class="bi bi-search"></i>
                </button>
              </form>
            </div>

            <h1 class="mb-4">
              <span class="d-block text-primary">MDSTOCK</span>
              Tu tienda de confianza
            </h1>

            <p class="mb-4 pb-3">
              Descubre una amplia variedad de productos a los mejores precios. Envío rápido, seguro y con garantía de satisfacción.
            </p>

            <div class="hero-buttons">
              <a href="/mdstock/pages/productos.php" class="btn btn-primary me-0 me-sm-2 mx-2">
                Ver Productos
              </a>
              <a href="/mdstock/pages/about.php" class="btn btn-link fx-4 m-0">
                Sobre Nosotros
                <i class="bi bi-arrow-right"></i>
              </a>
            </div>

            <div class="row compare-data text-center mt-5">
              <div class="col-md-4">
                <span class="data-number">+1000</span>
                <span class="label">Productos</span>
              </div>
              <div class="col-md-4">
                <span class="data-number">+5K</span>
                <span class="label">Clientes Satisfechos</span>
              </div>
              <div class="col-md-4">
                <span class="data-number">100%</span>
                <span class="label">Garantía</span>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-6">
            <div class="hero-image" data-aos="zoom-in" data-aos-delay="300">
            <img src="/mdstock/assets/img/hero-img.webp" alt="Hero Image" class="img-fluid">
          </div>
        </div>
      </div>
    </div>

  </section><!-- /Hero Section -->

  <!-- Best Sellers Section -->
  <section id="best-sellers" class="best-sellers section">

    <div class="container section-title" data-aos="fade-up">
      <span>Productos destacados</span>
      <h2>Más Vendidos</h2>
      <p>Los productos más populares de MDSTOCK</p>
    </div>

    <div class="container" data-aos="fade-up" data-aos-delay="100">

      <div class="row g-4">

        <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
          <div class="product-card">
            <div class="product-image">
              <a href="/mdstock/pages/detalle_producto.php?id=1">
                <img src="/mdstock/assets/img/product/product-1.webp" alt="Product 1" loading="lazy">
              </a>
              <span class="badge bg-danger position-absolute" style="top: 10px; right: 10px;">-20%</span>
            </div>
            <div class="product-info">
              <span class="product-category">Categoría</span>
              <h5 class="product-title"><a href="/mdstock/pages/detalle_producto.php?id=1">Producto Ejemplo 1</a></h5>
              <div class="product-rating">
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star"></i>
                <span>(25)</span>
              </div>
              <div class="product-price">
                <span class="current-price">$45.99</span>
                <span class="original-price">$57.99</span>
              </div>
              <a href="/mdstock/pages/carrito.php" class="btn btn-primary w-100">
                <i class="bi bi-cart-plus"></i> Agregar al carrito
              </a>
            </div>
          </div>
        </div>

        <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
          <div class="product-card">
            <div class="product-image">
              <a href="/mdstock/pages/detalle_producto.php?id=2">
                <img src="/mdstock/assets/img/product/product-2.webp" alt="Product 2" loading="lazy">
              </a>
            </div>
            <div class="product-info">
              <span class="product-category">Categoría</span>
              <h5 class="product-title"><a href="/mdstock/pages/detalle_producto.php?id=2">Producto Ejemplo 2</a></h5>
              <div class="product-rating">
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <span>(98)</span>
              </div>
              <div class="product-price">
                <span class="current-price">$89.99</span>
              </div>
              <a href="/mdstock/pages/carrito.php" class="btn btn-primary w-100">
                <i class="bi bi-cart-plus"></i> Agregar al carrito
              </a>
            </div>
          </div>
        </div>

        <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
          <div class="product-card">
            <div class="product-image">
              <a href="/mdstock/pages/detalle_producto.php?id=3">
                <img src="/mdstock/assets/img/product/product-3.webp" alt="Product 3" loading="lazy">
              </a>
              <span class="badge bg-success position-absolute" style="top: 10px; right: 10px;">Nuevo</span>
            </div>
            <div class="product-info">
              <span class="product-category">Categoría</span>
              <h5 class="product-title"><a href="/mdstock/pages/detalle_producto.php?id=3">Producto Ejemplo 3</a></h5>
              <div class="product-rating">
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star"></i>
                <i class="bi bi-star"></i>
                <span>(12)</span>
              </div>
              <div class="product-price">
                <span class="current-price">$34.50</span>
              </div>
              <a href="/mdstock/pages/carrito.php" class="btn btn-primary w-100">
                <i class="bi bi-cart-plus"></i> Agregar al carrito
              </a>
            </div>
          </div>
        </div>

      </div>

    </div>

  </section><!-- /Best Sellers Section -->

</main>

<?php
// Incluir footer (contiene footer HTML, scripts, cierre de body y html)
include 'includes/footer.php';
?>
