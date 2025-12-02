<?php
/**
 * Carrito - carrito.php
 */

include 'includes/header.php';
?>

<main class="main">

  <!-- Page Title -->
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
  </div><!-- End Page Title -->

  <!-- Cart Section -->
  <section id="cart" class="cart section">

    <div class="container" data-aos="fade-up" data-aos-delay="100">

      <div class="row g-4">
        <div class="col-lg-8" data-aos="fade-up" data-aos-delay="200">
          <div class="cart-items">
            <div class="cart-header d-none d-lg-block">
              <div class="row align-items-center">
                <div class="col-lg-7"><strong>Producto</strong></div>
                <div class="col-lg-2 text-center"><strong>Precio</strong></div>
                <div class="col-lg-2 text-center"><strong>Cantidad</strong></div>
                <div class="col-lg-1"></div>
              </div>
            </div>

            <!-- Cart Item -->
            <div class="cart-item border-bottom pb-3 mb-3">
              <div class="row align-items-center">
                <div class="col-lg-7">
                  <div class="d-flex align-items-center gap-3">
                    <img src="assets/img/product/product-1.webp" alt="Product" style="width: 80px; height: 80px; object-fit: cover;">
                    <div>
                      <h6><a href="detalle_producto.php?id=1">Producto Ejemplo 1</a></h6>
                      <p class="text-muted small">SKU: #12345</p>
                    </div>
                  </div>
                </div>
                <div class="col-lg-2 text-center">
                  <p>$45.99</p>
                </div>
                <div class="col-lg-2 text-center">
                  <input type="number" class="form-control" value="1" min="1" style="max-width: 80px; margin: 0 auto;">
                </div>
                <div class="col-lg-1 text-center">
                  <button class="btn btn-sm btn-danger">
                    <i class="bi bi-trash"></i>
                  </button>
                </div>
              </div>
            </div>

            <div class="cart-item border-bottom pb-3 mb-3">
              <div class="row align-items-center">
                <div class="col-lg-7">
                  <div class="d-flex align-items-center gap-3">
                    <img src="assets/img/product/product-2.webp" alt="Product" style="width: 80px; height: 80px; object-fit: cover;">
                    <div>
                      <h6><a href="detalle_producto.php?id=2">Producto Ejemplo 2</a></h6>
                      <p class="text-muted small">SKU: #12346</p>
                    </div>
                  </div>
                </div>
                <div class="col-lg-2 text-center">
                  <p>$89.99</p>
                </div>
                <div class="col-lg-2 text-center">
                  <input type="number" class="form-control" value="2" min="1" style="max-width: 80px; margin: 0 auto;">
                </div>
                <div class="col-lg-1 text-center">
                  <button class="btn btn-sm btn-danger">
                    <i class="bi bi-trash"></i>
                  </button>
                </div>
              </div>
            </div>

          </div>
        </div>

        <div class="col-lg-4" data-aos="fade-up" data-aos-delay="300">
          <div class="cart-summary card p-4">
            <h5 class="card-title mb-4">Resumen del Carrito</h5>

            <div class="summary-item d-flex justify-content-between mb-3">
              <span>Subtotal:</span>
              <span>$225.97</span>
            </div>

            <div class="summary-item d-flex justify-content-between mb-3">
              <span>Envío:</span>
              <span>$0.00 <small>(Gratis)</small></span>
            </div>

            <div class="summary-item d-flex justify-content-between mb-3">
              <span>Impuesto:</span>
              <span>$0.00</span>
            </div>

            <hr>

            <div class="summary-total d-flex justify-content-between mb-4 h5">
              <span>Total:</span>
              <span>$225.97</span>
            </div>

            <a href="checkout.php" class="btn btn-primary w-100 mb-2">
              Proceder al Checkout
            </a>

            <a href="productos.php" class="btn btn-outline-secondary w-100">
              Continuar Comprando
            </a>
          </div>
        </div>
      </div>

    </div>

  </section><!-- /Cart Section -->

</main>

<?php include 'includes/footer.php'; ?>
