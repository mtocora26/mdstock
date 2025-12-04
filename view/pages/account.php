
<?php
// Mostrar todos los errores y warnings en pantalla para depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Verificar si hay usuario logueado ANTES de incluir el header
if (!isset($_SESSION['usuario']['id_usuario'])) {
  echo '<div style="color:red;">No hay sesión de usuario activa</div>';
  header('Location: login-register.php');
  exit;
}

require_once '../header.php';
require_once __DIR__ . '/../../model/conexion.php';
require_once __DIR__ . '/../../model/dao/PedidoDAO.php';

$id_usuario = $_SESSION['usuario']['id_usuario'];

// Obtener pedidos del usuario
$pdo = Conexion::getConexion();
$pedidoDAO = new PedidoDAO($pdo);

$pedidos = $pedidoDAO->obtenerPedidosPorUsuario($id_usuario, 10, 0);
$totalPedidos = $pedidoDAO->contarPedidosPorUsuario($id_usuario);

// Si la variable $pedidos no es array, mostrar error visible
if (!is_array($pedidos)) {
  echo '<div style="background:#ffe0e0; color:#a00; padding:20px; margin:20px 0; text-align:center; font-size:1.2em;">Error al obtener los pedidos. Por favor recarga la página o contacta soporte.</div>';
  $pedidos = [];
}

// Si no hay pedidos, mostrar mensaje visible


// Función helper para formato de estado
function getEstadoClass($estado) {
  $estados = [
    'pendiente' => 'processing',
    'pendiente_pago' => 'processing',
    'enviado' => 'shipped',
    'entregado' => 'delivered',
    'cancelado' => 'cancelled'
  ];
  return $estados[$estado] ?? 'processing';
}

function getEstadoTexto($estado) {
  $textos = [
    'pendiente' => 'Pendiente',
    'pendiente_pago' => 'Pendiente de Pago',
    'enviado' => 'Enviado',
    'entregado' => 'Entregado',
    'cancelado' => 'Cancelado'
  ];
  return $textos[$estado] ?? $estado;
}
?>

<main class="main">


    <!-- Page Title -->
    <div class="page-title light-background">
      <div class="container d-lg-flex justify-content-between align-items-center">
        <h1 class="mb-2 mb-lg-0">Cuenta</h1>
      </div>
    </div><!-- End Page Title -->

    <!-- Account Section -->
    <section id="account" class="account section" style="display:block !important;">

      <div class="container" data-aos="fade-up" data-aos-delay="100" style="display:block !important;">

        <!-- Mobile Menu Toggle -->
        <div class="mobile-menu d-lg-none mb-4">
          <button class="mobile-menu-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#profileMenu">
            <i class="bi bi-grid"></i>
            <span>Menú</span>
          </button>
        </div>

        <div class="row g-4" style="display:flex !important;">
          <!-- Profile Menu -->
          <div class="col-lg-3" style="display:block !important;">
            <div class="profile-menu collapse d-lg-block" id="profileMenu" style="display:block !important;">
              <!-- User Info -->
              <div class="user-info" data-aos="fade-right">
                <h4><?php echo htmlspecialchars((isset($_SESSION['usuario']['nombres']) ? $_SESSION['usuario']['nombres'] : '') . ' ' . (isset($_SESSION['usuario']['apellidos']) ? $_SESSION['usuario']['apellidos'] : '')); ?></h4>
                <div class="user-status">
                  <i class="bi bi-envelope"></i>
                  <span>
                    <?php
                      if (!empty($_SESSION['usuario']['email'])) {
                        echo htmlspecialchars($_SESSION['usuario']['email']);
                      } elseif (!empty($_SESSION['usuario']['correo'])) {
                        echo htmlspecialchars($_SESSION['usuario']['correo']);
                      } else {
                        echo '<span class="text-muted">Sin correo</span>';
                      }
                    ?>
                  </span>
                </div>
              </div>

              <!-- Navigation Menu -->
              <nav class="menu-nav">
                <ul class="nav flex-column" role="tablist">
                  <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" href="#orders">
                      <i class="bi bi-box-seam"></i>
                      <span>Mis Pedidos</span>
                      <?php if ($totalPedidos > 0): ?>
                        <span class="badge"><?php echo $totalPedidos; ?></span>
                      <?php endif; ?>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#addresses">
                      <i class="bi bi-geo-alt"></i>
                      <span>Direcciones</span>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#settings">
                      <i class="bi bi-gear"></i>
                      <span>Configuración de la cuenta</span>
                    </a>
                  </li>
                </ul>

                <div class="menu-footer">
                  <a href="../../controller/LogoutController.php" class="logout-link">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Cerrar Sesión</span>
                  </a>
                </div>
              </nav>
            </div>
          </div>

          <!-- Content Area -->
          <div class="col-lg-9" style="display:block !important;">
            <div class="content-area" style="display:block !important;">
              <div class="tab-content" style="display:block !important;">
                                <!-- Orders Tab -->
                <div class="tab-pane fade show active" id="orders">
                  <div class="section-header" data-aos="fade-up">
                    <h2>Mis Pedidos</h2>
                  </div>

                  <?php if (empty($pedidos)): ?>
                    <div class="empty-state always-visible" data-aos="fade-up" style="display:block !important; opacity:1 !important;">
                      <i class="bi bi-inbox" style="font-size: 64px; color: #ccc;"></i>
                      <h3>No tienes pedidos todavía</h3>
                      <p>Cuando realices tu primera compra, aparecerá aquí.</p>
                      <a href="category.php" class="btn btn-primary">Ir a Comprar</a>
                    </div>
                  <?php else: ?>

                  <div class="orders-grid">
                    <?php foreach ($pedidos as $index => $pedido):
                      $detalles = $pedidoDAO->obtenerPedidoConDetalles($pedido['id_pedido']);
                    ?>
                    <!-- Order Card -->
                    <div class="order-card" data-aos="fade-up" data-aos-delay="<?php echo ($index * 100); ?>">
                      <div class="order-header">
                        <div class="order-id">
                          <span class="label">Pedido #</span>
                          <span class="value"><?php echo str_pad($pedido['id_pedido'], 8, '0', STR_PAD_LEFT); ?></span>
                        </div>
                        <div class="order-date"><?php echo date('d M, Y', strtotime($pedido['fecha'])); ?></div>
                      </div>
                      <div class="order-content">
                        <div class="product-grid">
                          <?php
                          $maxImages = 3;
                          $imageCount = 0;
                          if (!empty($detalles['detalles'])):
                            foreach ($detalles['detalles'] as $item):
                              if ($imageCount >= $maxImages) break;
                              $imagen = $item['imagen'] ?? 'assets/img/product/default.webp';
                          ?>
                            <img src="../../<?php echo htmlspecialchars($imagen); ?>" alt="Product" loading="lazy" onerror="this.src='../../assets/img/product/default.webp'">
                          <?php
                              $imageCount++;
                            endforeach;
                            if (count($detalles['detalles']) > $maxImages):
                          ?>
                            <span class="more-items">+<?php echo (count($detalles['detalles']) - $maxImages); ?></span>
                          <?php endif; endif; ?>
                        </div>
                        <div class="order-info">
                          <div class="info-row">
                            <span>Estado</span>
                            <span class="status <?php echo getEstadoClass($pedido['estado']); ?>">
                              <?php echo getEstadoTexto($pedido['estado']); ?>
                            </span>
                          </div>
                          <div class="info-row">
                            <span>Artículos</span>
                            <span><?php echo $pedido['total_items']; ?> artículo<?php echo $pedido['total_items'] != 1 ? 's' : ''; ?></span>
                          </div>
                          <div class="info-row">
                            <span>Total</span>
                            <span class="price">$<?php echo intval($pedido['total']); ?></span>
                          </div>
                        </div>
                      </div>
                      <div class="order-footer">
                        <button type="button" class="btn-details" data-bs-toggle="collapse" data-bs-target="#details<?php echo $pedido['id_pedido']; ?>" aria-expanded="false">Ver Detalles</button>
                      </div>

                      <!-- Order Details -->
                      <div class="collapse order-details" id="details<?php echo $pedido['id_pedido']; ?>">
                        <div class="details-content">
                          <div class="detail-section">
                            <h5>Información del Pedido</h5>
                            <div class="info-grid">
                              <div class="info-item">
                                <span class="label">Método de Pago</span>
                                <span class="value">
                                  <?php
                                  echo $pedido['metodo_pago'] === 'cod' ? 'Pago Contra Entrega' : 'Transferencia Bancaria';
                                  ?>
                                </span>
                              </div>
                              <div class="info-item">
                                <span class="label">Fecha</span>
                                <span class="value"><?php echo date('d/m/Y H:i', strtotime($pedido['fecha'])); ?></span>
                              </div>
                            </div>
                          </div>

                          <?php if (!empty($detalles['detalles'])): ?>
                          <div class="detail-section">
                            <h5>Artículos (<?php echo count($detalles['detalles']); ?>)</h5>
                            <div class="order-items">
                              <?php foreach ($detalles['detalles'] as $item): ?>
                              <div class="item">
                                <img src="../../<?php echo htmlspecialchars($item['imagen'] ?? 'assets/img/product/default.webp'); ?>" alt="Product" loading="lazy" onerror="this.src='../../assets/img/product/default.webp'">
                                <div class="item-info">
                                  <h6><?php echo htmlspecialchars($item['nombre'] ?? 'Producto'); ?></h6>
                                  <div class="item-meta">
                                    <span class="sku">ID: <?php echo $item['id_producto']; ?></span>
                                    <span class="qty">Cantidad: <?php echo $item['cantidad']; ?></span>
                                  </div>
                                </div>
                                <div class="item-price">$<?php echo intval($item['precio'] * $item['cantidad']); ?></div>
                              </div>
                              <?php endforeach; ?>
                            </div>
                          </div>
                          <?php endif; ?>

                          <div class="detail-section">
                            <h5>Detalles de Precio</h5>
                            <div class="price-breakdown">
                              <div class="price-row total">
                                <span>Total</span>
                                <span>$<?php echo intval($pedido['total']); ?></span>
                              </div>
                            </div>
                          </div>

                          <?php if (!empty($pedido['direccion_envio']) || !empty($pedido['nombre_cliente'])): ?>
                          <div class="detail-section">
                            <h5>Información de Envío</h5>
                            <div class="address-info">
                              <p>
                                <?php if (!empty($pedido['nombre_cliente'])): ?>
                                  <strong><?php echo htmlspecialchars($pedido['nombre_cliente']); ?></strong><br>
                                <?php endif; ?>
                                <?php if (!empty($pedido['direccion_envio'])): ?>
                                  <?php echo nl2br(htmlspecialchars($pedido['direccion_envio'])); ?><br>
                                <?php endif; ?>
                              </p>
                              <?php if (!empty($pedido['telefono_cliente'])): ?>
                                <p class="contact"><?php echo htmlspecialchars($pedido['telefono_cliente']); ?></p>
                              <?php endif; ?>
                            </div>
                          </div>
                          <?php endif; ?>
                        </div>
                      </div>
                    </div>
                    <?php endforeach; ?>
                  </div>

                  <?php endif; ?>
                </div>

                <!-- Addresses Tab -->
                <div class="tab-pane fade" id="addresses">
                  <div class="section-header" data-aos="fade-up">
                    <h2>Mis Direcciones</h2>
                  </div>

                  <div class="empty-state" data-aos="fade-up">
                    <i class="bi bi-geo-alt" style="font-size: 64px; color: #ccc;"></i>
                    <h3>Direcciones de Envío</h3>
                    <p>Las direcciones se ingresan directamente durante el checkout.</p>
                    <p class="text-muted">Puedes ver las direcciones utilizadas en la información de cada pedido.</p>
                    <a href="category.php" class="btn btn-primary">Ir a Comprar</a>
                  </div>
                </div>

                <!-- Settings Tab -->
                <div class="tab-pane fade" id="settings">
                  <div class="section-header" data-aos="fade-up">
                    <h2>Configuración de la Cuenta</h2>
                  </div>

                  <div class="settings-content">
                    <!-- Personal Information -->
                    <div class="settings-section" data-aos="fade-up">
                      <h3>Información Personal</h3>
                      <form class="php-email-form settings-form" method="post" action="../../controller/UsuarioController.php">
                        <div class="row g-3">
                          <div class="col-md-6">
                            <label for="firstName" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="firstName" name="nombres" value="<?php echo htmlspecialchars($_SESSION['usuario']['nombres']); ?>" required>
                          </div>
                          <div class="col-md-6">
                            <label for="lastName" class="form-label">Apellido</label>
                            <input type="text" class="form-control" id="lastName" name="apellidos" value="<?php echo htmlspecialchars($_SESSION['usuario']['apellidos']); ?>" required>
                          </div>
                          <div class="col-md-6">
                            <label for="email" class="form-label">Correo Electrónico</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo isset($_SESSION['usuario']['email']) ? htmlspecialchars($_SESSION['usuario']['email']) : ''; ?>" required>
                          </div>
                          <div class="col-md-6">
                            <label for="phone" class="form-label">Teléfono</label>
                            <input type="tel" class="form-control" id="phone" name="telefono" value="<?php echo isset($_SESSION['usuario']['telefono']) ? htmlspecialchars($_SESSION['usuario']['telefono']) : ''; ?>">
                          </div>
                        </div>

                        <div class="form-buttons">
                          <button type="submit" class="btn-save" name="actualizar_usuario">Guardar Cambios</button>
                        </div>

                        <div class="loading">Cargando</div>
                        <div class="error-message"></div>
                        <div class="sent-message">¡Tus cambios se han guardado con éxito!</div>
                      </form>
                    </div>

                    <!-- Email Preferences -->
                    <div class="settings-section" data-aos="fade-up" data-aos-delay="100">
                      <h3>Preferencias de Correo Electrónico</h3>
                      <div class="preferences-list">
                        <div class="preference-item">
                          <div class="preference-info">
                            <h4>Actualizaciones de Pedidos</h4>
                            <p>Recibe notificaciones sobre el estado de tus pedidos</p>
                          </div>
                          <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="orderUpdates" checked="">
                          </div>
                        </div>

                        <div class="preference-item">
                          <div class="preference-info">
                            <h4>Promociones</h4>
                            <p>Recibe correos sobre nuevas promociones y ofertas</p>
                          </div>
                          <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="promotions">
                          </div>
                        </div>

                        <div class="preference-item">
                          <div class="preference-info">
                            <h4>Boletín</h4>
                            <p>Suscríbete a nuestro boletín semanal</p>
                          </div>
                          <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="newsletter" checked="">
                          </div>
                        </div>
                      </div>
                    </div>

                    <!-- Security Settings -->
                    <div class="settings-section" data-aos="fade-up" data-aos-delay="200">
                      <h3>Seguridad</h3>
                      <form class="php-email-form settings-form" method="post" action="../../controller/UsuarioController.php">
                        <div class="row g-3">
                          <div class="col-md-12">
                            <label for="currentPassword" class="form-label">Contraseña Actual</label>
                            <input type="password" class="form-control" id="currentPassword" name="currentPassword" required>
                          </div>
                          <div class="col-md-6">
                            <label for="newPassword" class="form-label">Nueva Contraseña</label>
                            <input type="password" class="form-control" id="newPassword" name="newPassword" required>
                          </div>
                          <div class="col-md-6">
                            <label for="confirmPassword" class="form-label">Confirmar Contraseña</label>
                            <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required>
                          </div>
                        </div>

                        <input type="hidden" name="actualizar_contrasena" value="1">
                        <div class="form-buttons">
                          <button type="submit" class="btn-save">Actualizar Contraseña</button>
                        </div>

                        <div class="loading">Cargando</div>
                        <div class="error-message"></div>
                        <div class="sent-message">¡Tu contraseña ha sido actualizada con éxito!</div>
                      </form>
                    </div>

                    <!-- Delete Account -->
                    <div class="settings-section danger-zone" data-aos="fade-up" data-aos-delay="300">
                      <h3>Eliminar Cuenta</h3>
                      <div class="danger-zone-content">
                        <p>Una vez que elimines tu cuenta, no hay vuelta atrás. Por favor, asegúrate.</p>
                        <button type="button" class="btn-danger">Eliminar Cuenta</button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>

    </section><!-- /Account Section -->

  </main>

  
  <!-- Scroll Top -->
  <!--  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>-->v

  <!-- Preloader -->
   <!-- <div id="preloader"></div>-->

  <!-- Vendor JS Files -->
  <!--  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>-->
  <!--  <script src="assets/vendor/php-email-form/validate.js"></script>-->
  <!--  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>-->
  <!--  <script src="assets/vendor/aos/aos.js"></script>-->
  <!--  <script src="assets/vendor/imagesloaded/imagesloaded.pkgd.min.js"></script>-->
  <!--  <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>-->
  <!--  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>-->
   <!-- <script src="assets/vendor/drift-zoom/Drift.min.js"></script>-->
  <!--  <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>-->

  <!-- Main JS File -->
  <!--  <script src="assets/js/main.js"></script> -->

<?php require_once '../footer.php'; ?>