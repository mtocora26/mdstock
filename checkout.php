<?php
/**
 * Checkout - checkout.php
 */

include 'includes/header.php';
?>

<main class="main">

  <!-- Page Title -->
  <div class="page-title light-background">
    <div class="container d-lg-flex justify-content-between align-items-center">
      <h1 class="mb-2 mb-lg-0">Checkout</h1>
      <nav class="breadcrumbs">
        <ol>
          <li><a href="/mdstock/index.php">Inicio</a></li>
          <li><a href="/mdstock/pages/carrito.php">Carrito</a></li>
          <li class="current">Checkout</li>
        </ol>
      </nav>
    </div>
  </div><!-- End Page Title -->

  <!-- Checkout Section -->
  <section id="checkout" class="checkout section">

    <div class="container" data-aos="fade-up" data-aos-delay="100">

      <div class="row g-4">

        <!-- Checkout Form -->
        <div class="col-lg-8">

          <!-- Shipping Information -->
          <div class="card mb-4" data-aos="fade-up" data-aos-delay="200">
            <div class="card-body">
              <h5 class="card-title">Dirección de Envío</h5>
              <form>
                <div class="row mb-3">
                  <div class="col-md-6">
                    <label class="form-label">Nombre</label>
                    <input type="text" class="form-control" required>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label">Apellido</label>
                    <input type="text" class="form-control" required>
                  </div>
                </div>
                <div class="mb-3">
                  <label class="form-label">Email</label>
                  <input type="email" class="form-control" required>
                </div>
                <div class="mb-3">
                  <label class="form-label">Dirección</label>
                  <input type="text" class="form-control" required>
                </div>
                <div class="row mb-3">
                  <div class="col-md-6">
                    <label class="form-label">Ciudad</label>
                    <input type="text" class="form-control" required>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label">Código Postal</label>
                    <input type="text" class="form-control" required>
                  </div>
                </div>
              </form>
            </div>
          </div>

          <!-- Payment Information -->
          <div class="card mb-4" data-aos="fade-up" data-aos-delay="300">
            <div class="card-body">
              <h5 class="card-title">Método de Pago</h5>
              <form>
                <div class="mb-3">
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="payment" id="payment1" value="credit" checked>
                    <label class="form-check-label" for="payment1">
                      Tarjeta de Crédito
                    </label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="payment" id="payment2" value="cash">
                    <label class="form-check-label" for="payment2">
                      Efectivo Contra Entrega
                    </label>
                  </div>
                </div>
              </form>
            </div>
          </div>

        </div>

        <!-- Order Summary -->
        <div class="col-lg-4">

          <div class="card mb-4" data-aos="fade-up" data-aos-delay="400">
            <div class="card-body">
              <h5 class="card-title">Resumen del Pedido</h5>

              <div class="summary-item d-flex justify-content-between mb-3 pb-3 border-bottom">
                <span>Producto 1 x 1</span>
                <span>$45.99</span>
              </div>

              <div class="summary-item d-flex justify-content-between mb-3 pb-3 border-bottom">
                <span>Producto 2 x 2</span>
                <span>$179.98</span>
              </div>

              <div class="summary-item d-flex justify-content-between mb-3 pb-3 border-bottom">
                <span>Subtotal:</span>
                <span>$225.97</span>
              </div>

              <div class="summary-item d-flex justify-content-between mb-3 pb-3 border-bottom">
                <span>Envío:</span>
                <span>$0.00</span>
              </div>

              <div class="summary-total d-flex justify-content-between h5 mb-4">
                <span>Total:</span>
                <span>$225.97</span>
              </div>

              <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" id="terms" required>
                <label class="form-check-label" for="terms">
                  Acepto los términos y condiciones
                </label>
              </div>

              <button class="btn btn-primary w-100 mb-2">
                Completar Pedido
              </button>

              <a href="/mdstock/pages/carrito.php" class="btn btn-outline-secondary w-100">
                Volver al Carrito
              </a>
            </div>
          </div>

        </div>

      </div>

    </div>

  </section><!-- /Checkout Section -->

</main>

<?php include 'includes/footer.php'; ?>
