<?php
session_start();
require_once '../header.php';

// Obtener información del pedido
$pedido_info = $_SESSION['pedido_info'] ?? null;
$id_pedido = $_GET['id_pedido'] ?? null;
$metodo_pago = $_GET['payment'] ?? 'cod';

// Si no hay información del pedido, redirigir
if (!$pedido_info || !$id_pedido) {
    header('Location: category.php');
    exit;
}
?>

<main class="main">
  <!-- Page Title -->
  <div class="page-title light-background">
    <div class="container d-lg-flex justify-content-between align-items-center">
      <h1 class="mb-2 mb-lg-0">Confirmación del Pedido</h1>
    </div>
  </div>

  <!-- Order Confirmation Section -->
  <section id="order-confirmation" class="order-confirmation section">
    <div class="container" data-aos="fade-up" data-aos-delay="100">

      <div class="order-confirmation-1">
        <!-- Success Header -->
        <div class="confirmation-header text-center" data-aos="fade-up">
          <div class="success-icon mb-4">
            <i class="bi bi-check-circle-fill" style="font-size: 80px; color: #28a745;"></i>
          </div>
          <h2>¡Pedido Realizado Exitosamente!</h2>
          <p class="lead">Gracias por tu compra. Hemos recibido tu pedido y lo estamos procesando.</p>
          <div class="order-number mt-3 mb-4">
            <span>Pedido #</span>
            <strong><?php echo str_pad($id_pedido, 8, '0', STR_PAD_LEFT); ?></strong>
            <span class="mx-2">•</span>
            <span><?php echo date('d/m/Y'); ?></span>
          </div>
        </div>

        <!-- Order Details -->
        <div class="order-details p-4 mb-4" data-aos="fade-up" data-aos-delay="150">
          <div class="row">
            <div class="col-md-6 mb-4 mb-md-0">
              <h4>Información de Envío</h4>
              <address class="mt-3">
                <strong><?php echo htmlspecialchars($pedido_info['nombre_completo']); ?></strong><br>
                <?php echo htmlspecialchars($pedido_info['direccion_completa']); ?><br>
                <i class="bi bi-telephone-fill me-1 text-muted small"></i> <?php echo htmlspecialchars($pedido_info['telefono']); ?><br>
                <i class="bi bi-envelope-fill me-1 text-muted small"></i> <?php echo htmlspecialchars($pedido_info['email']); ?>
              </address>
              <div class="mt-3">
                <span class="shipping-method">
                  <i class="bi bi-truck me-2"></i>
                  <?php
                    if ($pedido_info['envio'] == 0) {
                        echo 'Envío Gratis';
                    } elseif ($pedido_info['envio'] == 4.99) {
                        echo 'Envío Estándar (3-5 días hábiles)';
                    } else {
                        echo 'Envío Exprés (1-2 días hábiles)';
                    }
                  ?>
                </span>
              </div>
            </div>
            <div class="col-md-6">
              <h4>Información de Pago</h4>
              <div class="payment-info mt-3">
                <?php if ($metodo_pago === 'cod'): ?>
                  <div class="alert alert-success">
                    <i class="bi bi-cash-coin me-2" style="font-size: 24px;"></i>
                    <div>
                      <strong>Pago Contra Entrega</strong>
                      <p class="mb-0 mt-2">Pagarás en efectivo cuando recibas tu pedido.<br>
                      <small class="text-muted">Monto a pagar: <strong>$<?php echo intval($pedido_info['total']); ?></strong></small></p>
                    </div>
                  </div>
                <?php else: ?>
                  <div class="alert alert-warning">
                    <i class="bi bi-bank me-2" style="font-size: 24px;"></i>
                    <div>
                      <strong>Transferencia Bancaria</strong>
                      <p class="mb-2 mt-2">Realiza la transferencia a los siguientes datos:</p>
                      <div class="bank-details bg-light p-3 rounded">
                        <p class="mb-1"><strong>Banco:</strong> Banco Ejemplo</p>
                        <p class="mb-1"><strong>Cuenta:</strong> 1234567890</p>
                        <p class="mb-1"><strong>Titular:</strong> MD Stock</p>
                        <p class="mb-1"><strong>Monto:</strong> $<?php echo intval($pedido_info['total']); ?></strong></p>
                        <p class="mb-0"><strong>Referencia:</strong> <?php echo str_pad($id_pedido, 8, '0', STR_PAD_LEFT); ?></p>
                      </div>
                      <p class="mt-2 mb-0 text-danger">
                        <small><i class="bi bi-exclamation-triangle me-1"></i>
                        Tu pedido se procesará una vez confirmemos el pago.</small>
                      </p>
                    </div>
                  </div>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>

        <!-- Order Summary -->
        <div class="order-summary mb-4" data-aos="fade-up" data-aos-delay="200">
          <h4>Resumen del Pedido</h4>
          <div class="order-totals mt-4">
            <div class="d-flex justify-content-between py-2">
              <span>Subtotal</span>
              <span>$<?php echo intval($pedido_info['subtotal']); ?></span>
            </div>
            <div class="d-flex justify-content-between py-2">
              <span>Envío</span>
              <span>$<?php echo intval($pedido_info['envio']); ?></span>
            </div>
            <div class="d-flex justify-content-between py-2 total-row border-top pt-2">
              <strong>Total</strong>
              <strong>$<?php echo intval($pedido_info['total']); ?></strong>
            </div>
          </div>
        </div>

        <!-- Next Steps -->
        <div class="next-steps text-center p-4" data-aos="fade-up" data-aos-delay="250">
          <h4>¿Qué Sigue?</h4>
          <p>Recibirás un correo de confirmación en <strong><?php echo htmlspecialchars($pedido_info['email']); ?></strong></p>
          <div class="tracking-info mb-4">
            <i class="bi bi-envelope me-2"></i>Te enviaremos información de seguimiento una vez que tu pedido sea enviado
          </div>
          <div class="action-buttons">
            <a href="category.php" class="btn btn-primary me-3 mb-2 mb-md-0">
              <i class="bi bi-bag me-2"></i>Continuar Comprando
            </a>
            <a href="account.php" class="btn btn-outline-primary">
              <i class="bi bi-person me-2"></i>Ver Historial de Pedidos
            </a>
          </div>
        </div>

        <div class="help-contact text-center mt-5" data-aos="fade-up" data-aos-delay="300">
          <p>¿Necesitas ayuda con tu pedido? <a href="contact.php">Contáctanos</a></p>
        </div>
      </div>

    </div>
  </section>
</main>

<?php
// Limpiar información del pedido de la sesión
unset($_SESSION['pedido_info']);
require_once '../footer.php';
?>
