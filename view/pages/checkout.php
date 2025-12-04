<?php require_once '../header.php'; ?>
<?php
$direccion_predeterminada = null;
if (isset($_SESSION['usuario']['id_usuario'])) {
  require_once __DIR__ . '/../../model/conexion.php';
  require_once __DIR__ . '/../../model/dao/DireccionEnvioDAO.php';
  $pdo = Conexion::getConexion();
  $direccionDAO = new DireccionEnvioDAO($pdo);
  $direccion_predeterminada = $direccionDAO->obtenerDireccionPredeterminada($_SESSION['usuario']['id_usuario']);
}
// Unificar cálculo de subtotal y envío para todo el checkout
$subtotal = 0;
if (!empty($_SESSION['carrito_detalles'])) {
  foreach ($_SESSION['carrito_detalles'] as $item) {
    $subtotal += $item->precio * $item->cantidad;
  }
}
$subtotal = intval($subtotal);
$envio = 10000;
if ($subtotal >= 50000) $envio = 0;
?>

  <main class="main">

    <!-- Page Title -->
    <div class="page-title light-background">
      <div class="container d-lg-flex justify-content-between align-items-center">
        <h1 class="mb-2 mb-lg-0">Finalizar Compra</h1>
      </div>
    </div><!-- End Page Title -->

    <!-- Checkout Section -->
    <section id="checkout" class="checkout section">

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <?php if (isset($_SESSION['checkout_error'])): ?>
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <strong>Error:</strong> <?php echo htmlspecialchars($_SESSION['checkout_error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
          <?php unset($_SESSION['checkout_error']); ?>
        <?php endif; ?>

        <div class="row">
          <div class="col-lg-7">
            <!-- Checkout Form -->
            <div class="checkout-container" data-aos="fade-up">
              <form class="checkout-form" method="post" action="../../controller/CheckoutController.php">
                <!-- Información del Cliente -->
                <div class="checkout-section" id="customer-info">
                  <div class="section-header">
                    <div class="section-number">1</div>
                    <h3>Información del Cliente</h3>
                  </div>
                  <div class="section-content">
                    <div class="row">
                      <div class="col-md-6 form-group">
                        <label for="first-name">Nombre</label>
                        <input type="text" name="first-name" class="form-control" id="first-name" placeholder="Tu Nombre" required="" value="<?php echo isset($_SESSION['usuario']['nombres']) ? htmlspecialchars($_SESSION['usuario']['nombres']) : ''; ?>">
                      </div>
                      <div class="col-md-6 form-group">
                        <label for="last-name">Apellido</label>
                        <input type="text" name="last-name" class="form-control" id="last-name" placeholder="Tu Apellido" required="" value="<?php echo isset($_SESSION['usuario']['apellidos']) ? htmlspecialchars($_SESSION['usuario']['apellidos']) : ''; ?>">
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="email">Correo Electrónico</label>
                      <input type="email" class="form-control" name="email" id="email" placeholder="Tu Correo" required="" value="<?php echo isset($_SESSION['usuario']['email']) ? htmlspecialchars($_SESSION['usuario']['email']) : (isset($_SESSION['usuario']['correo']) ? htmlspecialchars($_SESSION['usuario']['correo']) : ''); ?>">
                    </div>
                    <div class="form-group">
                      <label for="phone">Teléfono</label>
                      <input type="tel" class="form-control" name="phone" id="phone" placeholder="Tu Teléfono" required="" value="<?php echo isset($_SESSION['usuario']['telefono']) ? htmlspecialchars($_SESSION['usuario']['telefono']) : ''; ?>">
                    </div>
                  </div>
                </div>

              <!-- Dirección de Envío -->
              <div class="checkout-section" id="shipping-address">
                <div class="section-header">
                  <div class="section-number">2</div>
                  <h3>Dirección de Envío</h3>
                </div>
                <div class="section-content">
                  <div class="form-group">
                    <label for="address">Dirección</label>
                    <input type="text" class="form-control" name="address" id="address" placeholder="Dirección" required="" value="<?php echo $direccion_predeterminada ? htmlspecialchars($direccion_predeterminada['direccion']) : ''; ?>">
                  </div>
                  <div class="form-group">
                    <label for="apartment">Apartamento, Piso, etc. (opcional)</label>
                    <input type="text" class="form-control" name="apartment" id="apartment" placeholder="Apartamento, Piso, Unidad, etc." value="<?php echo $direccion_predeterminada ? htmlspecialchars($direccion_predeterminada['referencia']) : ''; ?>">
                  </div>
                  <div class="row">
                    <div class="col-md-4 form-group">
                      <label for="city">Ciudad</label>
                      <input type="text" name="city" class="form-control" id="city" placeholder="Ciudad" required="" value="<?php echo $direccion_predeterminada ? htmlspecialchars($direccion_predeterminada['ciudad']) : ''; ?>">
                    </div>
                    <div class="col-md-4 form-group">
                      <label for="state">Departamento</label>
                      <input type="text" name="state" class="form-control" id="state" placeholder="Departamento" required="" value="<?php echo $direccion_predeterminada ? htmlspecialchars($direccion_predeterminada['departamento']) : ''; ?>">
                    </div>
                    <div class="col-md-4 form-group">
                      <label for="zip">Código Postal</label>
                      <input type="text" name="zip" class="form-control" id="zip" placeholder="Código Postal" required="" value="<?php echo $direccion_predeterminada ? htmlspecialchars($direccion_predeterminada['codigo_postal']) : ''; ?>">
                    </div>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="save-address" name="save-address">
                    <label class="form-check-label" for="save-address">
                      Guardar esta dirección para futuros pedidos
                    </label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="billing-same" name="billing-same" checked="">
                    <label class="form-check-label" for="billing-same">
                      La dirección de facturación es igual a la de envío
                    </label>
                  </div>
                </div>
              </div>

              <!-- Método de Pago -->
              <div class="checkout-section" id="payment-method">
                <div class="section-header">
                  <div class="section-number">3</div>
                  <h3>Método de Pago</h3>
                </div>
                <div class="section-content">
                  <div class="payment-options">
                    <!-- Pago Contra Entrega -->
                    <div class="payment-option active">
                      <input type="radio" name="payment-method" id="cod" value="cod" checked>
                      <label for="cod">
                        <span class="payment-icon"><i class="bi bi-cash-coin"></i></span>
                        <span class="payment-label">Pago Contra Entrega</span>
                      </label>
                    </div>

                    <!-- Transferencia Bancaria -->
                    <div class="payment-option">
                      <input type="radio" name="payment-method" id="bank-transfer" value="bank_transfer">
                      <label for="bank-transfer">
                        <span class="payment-icon"><i class="bi bi-bank"></i></span>
                        <span class="payment-label">Transferencia Bancaria</span>
                      </label>
                    </div>
                  </div>

                  <!-- Detalles de Pago Contra Entrega -->
                  <div class="payment-details" id="cod-details">
                    <div class="alert alert-info">
                      <i class="bi bi-info-circle"></i>
                      <strong>Pago Contra Entrega:</strong> Pagarás en efectivo cuando recibas tu pedido. Asegúrate de tener el monto exacto.
                    </div>
                  </div>

                  <!-- Detalles de Transferencia Bancaria -->
                  <div class="payment-details d-none" id="bank-transfer-details">
                    <div class="alert alert-warning">
                      <i class="bi bi-exclamation-triangle"></i>
                      <strong>Transferencia Bancaria:</strong> Después de confirmar el pedido, recibirás los datos bancarios para realizar la transferencia. Tu pedido se procesará una vez confirmemos el pago.
                    </div>
                    <div class="bank-info-preview">
                      <p class="text-muted mb-2"><small>Datos bancarios para transferencia:</small></p>
                      <ul class="list-unstyled">
                        <li><strong>Banco:</strong> Banco Ejemplo</li>
                        <li><strong>Cuenta:</strong> 1234567890</li>
                        <li><strong>Titular:</strong> MD Stock</li>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Resumen y Confirmación -->
              <div class="checkout-section" id="order-review">
                <div class="section-header">
                  <div class="section-number">4</div>
                  <h3>Revisar y Confirmar Pedido</h3>
                </div>
                <div class="section-content">
                  <div class="form-check terms-check">
                    <input class="form-check-input" type="checkbox" id="terms" name="terms" required="">
                    <label class="form-check-label" for="terms">
                      Acepto los <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">Términos y Condiciones</a> y la <a href="#" data-bs-toggle="modal" data-bs-target="#privacyModal">Política de Privacidad</a>
                    </label>
                  </div>
                  <div class="success-message d-none">¡Tu pedido ha sido realizado exitosamente! Gracias por tu compra.</div>
                  <div class="place-order-container">
                    <button type="submit" class="btn btn-primary place-order-btn">
                      <span class="btn-text">Confirmar Pedido</span>
                      <span class="btn-price">
                        <?php echo '$' . ($subtotal + $envio); ?>
                      </span>
                    </button>
                  </div>
                </div>
              </div>
              </form>
            </div>
          </div>

          <div class="col-lg-5">
            <!-- Order Summary -->
            <div class="order-summary" data-aos="fade-left" data-aos-delay="200">
              <div class="order-summary-header">
                <h3>Resumen del Pedido</h3>
                <span class="item-count">
                  <?php 
                  $num_items = 0;
                  if (!empty($_SESSION['carrito_detalles'])) {
                    foreach ($_SESSION['carrito_detalles'] as $item) {
                      $num_items += $item->cantidad;
                    }
                  }
                  echo $num_items . ' artículo' . ($num_items == 1 ? '' : 's');
                  ?>
                </span>
              </div>

              <div class="order-summary-content">
                <div class="order-items">
                  <?php
                  // Mostrar los productos del carrito
                  if (!empty($_SESSION['carrito_detalles'])) {
                    foreach ($_SESSION['carrito_detalles'] as $item) {
                      ?>
                      <div class="order-item">
                        <div class="order-item-image">
                          <img src="<?php echo $item->imagen; ?>" alt="Product" class="img-fluid">
                        </div>
                        <div class="order-item-details">
                          <h4><?php echo htmlspecialchars($item->nombre); ?></h4>
                          <div class="order-item-price">
                            <span class="quantity"><?php echo $item->cantidad; ?> ×</span>
                            <span class="price">$<?php echo intval($item->precio); ?></span>
                          </div>
                        </div>
                      </div>
                      <?php
                    }
                  }
                  ?>
                </div>
                <div class="order-totals">
                  <div class="order-subtotal d-flex justify-content-between">
                    <span>Subtotal</span>
                    <span>$<?php echo $subtotal; ?></span>
                  </div>
                  <div class="order-shipping d-flex justify-content-between">
                    <span>Envío</span>
                    <span id="shipping-value">$<?php echo $envio; ?></span>
                  </div>
                  <div class="order-total d-flex justify-content-between">
                    <span>Total</span>
                    <span id="total-value">$<?php echo $subtotal + $envio; ?></span>
                  </div>
                  <input type="hidden" name="shipping" id="shipping-input" value="<?php echo $envio; ?>">
                  <input type="hidden" id="subtotal-checkout" value="<?php echo $subtotal; ?>">
                </div>

                <div class="secure-checkout">
                  <div class="secure-checkout-header">
                    <i class="bi bi-shield-lock"></i>
                    <span>Pago Seguro</span>
                  </div>
                  <div class="payment-icons">
                    <i class="bi bi-cash-coin"></i>
                    <i class="bi bi-bank"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Terms and Privacy Modals -->
        <div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="termsModalLabel">Terms and Conditions</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam in dui mauris. Vivamus hendrerit arcu sed erat molestie vehicula. Sed auctor neque eu tellus rhoncus ut eleifend nibh porttitor. Ut in nulla enim. Phasellus molestie magna non est bibendum non venenatis nisl tempor.</p>
                <p>Suspendisse in orci enim. Vivamus hendrerit arcu sed erat molestie vehicula. Sed auctor neque eu tellus rhoncus ut eleifend nibh porttitor. Ut in nulla enim. Phasellus molestie magna non est bibendum non venenatis nisl tempor.</p>
                <p>Suspendisse in orci enim. Vivamus hendrerit arcu sed erat molestie vehicula. Sed auctor neque eu tellus rhoncus ut eleifend nibh porttitor. Ut in nulla enim. Phasellus molestie magna non est bibendum non venenatis nisl tempor.</p>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">I Understand</button>
              </div>
            </div>
          </div>
        </div>

        <div class="modal fade" id="privacyModal" tabindex="-1" aria-labelledby="privacyModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="privacyModalLabel">Privacy Policy</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam in dui mauris. Vivamus hendrerit arcu sed erat molestie vehicula. Sed auctor neque eu tellus rhoncus ut eleifend nibh porttitor. Ut in nulla enim.</p>
                <p>Suspendisse in orci enim. Vivamus hendrerit arcu sed erat molestie vehicula. Sed auctor neque eu tellus rhoncus ut eleifend nibh porttitor. Ut in nulla enim. Phasellus molestie magna non est bibendum non venenatis nisl tempor.</p>
                <p>Suspendisse in orci enim. Vivamus hendrerit arcu sed erat molestie vehicula. Sed auctor neque eu tellus rhoncus ut eleifend nibh porttitor. Ut in nulla enim. Phasellus molestie magna non est bibendum non venenatis nisl tempor.</p>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">I Understand</button>
              </div>
            </div>
          </div>
        </div>

      </div>

    </section><!-- /Checkout Section -->

  </main>
<?php require_once '../footer.php'; ?>