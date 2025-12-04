    <?php if (isset($_SESSION['carrito_debug'])): ?>
      <div style="color:blue; font-size:14px; margin-bottom:10px;">
        <?php echo $_SESSION['carrito_debug'];
        unset($_SESSION['carrito_debug']); ?>
      </div>
    <?php endif; ?>
    <?php require_once '../header.php'; ?>

    <main class="main">

      <!-- Page Title -->
      <div class="page-title light-background">
        <div class="container d-lg-flex justify-content-between align-items-center">
          <h1 class="mb-2 mb-lg-0">CARRITO</h1>
        </div>
      </div><!-- End Page Title -->

      <!-- Cart Section -->
      <section id="cart" class="cart section">

        <div class="container">

          <div class="row g-4">
            <div class="col-lg-8">
              <div class="cart-items">
                <!-- La lógica JS de carrito se mueve a assets/js/carrito.js -->
                <div class="cart-header d-none d-lg-block">
                  <div class="row align-items-center gy-4">
                    <div class="col-lg-6">
                      <h5>Producto</h5>
                    </div>
                    <div class="col-lg-2 text-center">
                      <h5>Precio</h5>
                    </div>
                    <div class="col-lg-2 text-center">
                      <h5>Cantidad</h5>
                    </div>
                    <div class="col-lg-2 text-center">
                      <h5>Total</h5>
                    </div>
                  </div>
                </div>

                <?php
                $subtotal = 0;
                if (!empty($_SESSION['carrito_detalles'])) {
                  foreach ($_SESSION['carrito_detalles'] as $item) {
                    $nombre = isset($item->nombre) ? $item->nombre : 'Producto';
                    $imagen = isset($item->imagen) ? $item->imagen : '../../assets/img/product/product-1.webp';
                    $precio = isset($item->precio) ? $item->precio : 0;
                    $cantidad = $item->cantidad;
                    $total = $precio * $cantidad;
                    $subtotal += $total;
                ?>
                    <div class="cart-item">
                      <div class="row align-items-center gy-4">
                        <div class="col-lg-6 col-12 mb-3 mb-lg-0">
                          <div class="product-info d-flex align-items-center">
                            <div class="product-image">
                              <img src="<?php echo $imagen; ?>" alt="Product" class="img-fluid" loading="lazy">
                            </div>
                            <div class="product-details">
                              <h6 class="product-title"><?php echo htmlspecialchars($nombre); ?></h6>
                              <?php if ($nombre === 'Lokiño caramelo masticable 100und'): ?>
                                <p class="product-description" style="font-size:14px; color:#555; margin-bottom:8px;">
                                  Caramelo masticable sabor tradicional. Bolsa por 100 unidades. Ideal para compartir y disfrutar en cualquier ocasión.
                                </p>
                              <?php endif; ?>
                              <form method="post" action="../../controller/CarritoController.php" style="display:inline;">
                                <?php if (isset($_GET['debug'])): ?>
                                  <input type="hidden" name="debug" value="1">
                                <?php endif; ?>
                                <input type="hidden" name="accion" value="eliminar">
                                <input type="hidden" name="id_producto" value="<?php echo $item->id_producto; ?>">
                                <button class="remove-item" type="submit">
                                  <i class="bi bi-trash"></i> Eliminar
                                </button>
                              </form>
                            </div>
                          </div>
                        </div>
                        <div class="col-12 col-lg-2 text-center">
                          <div class="price-tag">
                            <span class="current-price">$<?php echo intval($precio); ?></span>
                          </div>
                        </div>
                        <div class="col-12 col-lg-2 text-center">
                          <div class="quantity-selector">
                            <form method="post" action="../../controller/CarritoController.php" style="display:inline-block;">
                              <?php if (isset($_GET['debug'])): ?>
                                <input type="hidden" name="debug" value="1">
                              <?php endif; ?>
                              <input type="hidden" name="accion" value="actualizar">
                              <input type="hidden" name="id_producto" value="<?php echo $item->id_producto; ?>">
                              <button class="quantity-btn decrease" type="submit" name="cantidad" value="<?php echo ($cantidad > 1) ? $cantidad - 1 : 0; ?>">
                                <i class="bi bi-dash"></i>
                              </button>
                            </form>
                            <form method="post" action="../../controller/CarritoController.php" style="display:inline-block;">
                              <?php if (isset($_GET['debug'])): ?>
                                <input type="hidden" name="debug" value="1">
                              <?php endif; ?>
                              <input type="hidden" name="accion" value="actualizar">
                              <input type="hidden" name="id_producto" value="<?php echo $item->id_producto; ?>">
                              <input type="number" class="quantity-input" name="cantidad" value="<?php echo $cantidad; ?>" min="0" max="10" onchange="this.form.submit()">
                            </form>
                            <form method="post" action="../../controller/CarritoController.php" style="display:inline-block;">
                              <?php if (isset($_GET['debug'])): ?>
                                <input type="hidden" name="debug" value="1">
                              <?php endif; ?>
                              <input type="hidden" name="accion" value="actualizar">
                              <input type="hidden" name="id_producto" value="<?php echo $item->id_producto; ?>">
                              <button class="quantity-btn increase" type="submit" name="cantidad" value="<?php echo $cantidad + 1; ?>">
                                <i class="bi bi-plus"></i>
                              </button>
                            </form>
                          </div>
                        </div>
                        <div class="col-12 col-lg-2 text-center mt-3 mt-lg-0">
                          <div class="item-total">
                            <span>$<?php echo intval($total); ?></span>
                          </div>
                        </div>

                        <div class="col-12 col-lg-2 text-center">
                          <?php if (isset($_GET['debug'])): ?>
                            <div style="color:red; font-size:12px;">Enviado: id_producto=<?php echo $item->id_producto; ?>, cantidad=<?php echo $cantidad; ?></div>
                          <?php endif; ?>
                        </div>
                      </div>
                    </div>
                <?php
                  }
                } else {
                ?>
                  <div class="cart-item">
                    <div class="row">
                      <div class="col-12 text-center">
                        <p>No hay productos en el carrito.</p>
                      </div>
                    </div>
                  </div>
                <?php } ?>
                <div class="cart-actions">
                  <div class="row g-3">
                    <div class="col-lg-6 col-md-6">
                    </div>
                    <div class="col-lg-6 col-md-6 text-md-end">
                    </div>
                  </div>
                </div>
              </div>
            </div>
              <div class="col-lg-4">
                <div class="cart-summary">
                  <h4 class="summary-title">Resumen del Pedido</h4>
                  <div class="summary-item">
                    <span class="summary-label">Subtotal</span>
                    <span class="summary-value" id="subtotal-value">$<?php echo intval($subtotal); ?></span>
                  </div>
                  <div class="summary-item shipping-item">
                    <span class="summary-label">Envío</span>
                    <div class="shipping-options">
                      <div class="form-check">
                        <input class="form-check-input" type="radio" name="shipping" id="standard" checked value="10000">
                        <label class="form-check-label" for="standard">Envío Estándar - $10000</label>
                      </div>
                      <div class="form-check">
                        <input class="form-check-input" type="radio" name="shipping" id="express" value="18000">
                        <label class="form-check-label" for="express">Envío Exprés - $18000</label>
                      </div>
                      <div class="form-check">
                        <input class="form-check-input" type="radio" name="shipping" id="free" value="0">
                        <label class="form-check-label" for="free">Envío Gratis (Pedidos superiores a $50.000)</label>
                      </div>
                    </div>
                  </div>
                  <div class="summary-total">
                    <span class="summary-label">Total</span>
                    <span class="summary-value" id="total-value">$<?php
                      $envio = 10000; // Valor por defecto, puede cambiar según selección
                      if (isset($_POST['shipping'])) {
                        if ($_POST['shipping'] == '10000') $envio = 10000;
                        elseif ($_POST['shipping'] == '18000') $envio = 18000;
                        elseif ($_POST['shipping'] == '0') $envio = 0;
                      }
                      echo intval($subtotal + $envio);
                    ?></span>
                  </div>
                  <div>
                    <div class="checkout-button">
                      <a href="checkout.php" class="btn btn-accent w-100">
                        Proceder al Pago <i class="bi bi-arrow-right"></i>
                      </a>
                    </div>
                    <div class="continue-shopping">
                      <a href="category.php" class="btn btn-link w-100">
                        <i class="bi bi-arrow-left"></i> Continuar Comprando
                      </a>
                    </div>
              </div>
          </div>
        </div>
      </section><!-- /Cart Section -->

    </main>

    <?php require_once '../footer.php'; ?>