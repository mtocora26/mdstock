
<?php
require_once '../header.php';
require_once '../../controller/ProductoController.php';
$id_producto = isset($_GET['id']) ? $_GET['id'] : null;
$producto = null;
if ($id_producto !== null && $id_producto !== '') {
    $producto = ProductoController::detalleProducto($id_producto);
}
?>

<main class="main">

    <!-- Page Title -->
    <div class="page-title light-background">
      <div class="container d-lg-flex justify-content-between align-items-center">
        <h1 class="mb-2 mb-lg-0">Detalles del Producto</h1>
      </div>
    </div><!-- End Page Title -->

      <!-- Product Details Section -->
    <section id="product-details" class="product-details section">

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row">
          <!-- Product Images -->
          <div class="col-lg-6 mb-5 mb-lg-0" data-aos="fade-right" data-aos-delay="200">
            <div class="product-images">
              <div class="main-image-container mb-3">
                <div class="image-zoom-container">
                  <img src="<?php echo $producto->imagen ?? 'assets/img/product/product-details-1.webp'; ?>" alt="Product Image" class="img-fluid main-image drift-zoom" id="main-product-image" data-zoom="<?php echo $producto->imagen ?? 'assets/img/product/product-details-1.webp'; ?>">
                </div>
              </div>
              <div class="product-thumbnails">
                <div class="swiper product-thumbnails-slider init-swiper">
                  <script type="application/json" class="swiper-config">
                    {
                      "loop": false,
                      "speed": 400,
                      "slidesPerView": 4,
                      "spaceBetween": 10,
                      "navigation": {
                        "nextEl": ".swiper-button-next",
                        "prevEl": ".swiper-button-prev"
                      },
                      "breakpoints": {
                        "320": {
                          "slidesPerView": 3
                        },
                        "576": {
                          "slidesPerView": 4
                        }
                      }
                    }
                  </script>
                </div>
              </div>
            </div>
          </div>
          <!-- Product Info -->
          <div class="col-lg-6" data-aos="fade-left" data-aos-delay="200">
            <div class="product-info">
              <div class="product-meta mb-2">
                <span class="product-category">
                  <?php echo $producto->categoria_nombre ?? ''; ?>
                </span>
              </div>
              <h1 class="product-title"><?php echo $producto->nombre ?? 'Producto'; ?></h1>
              <div class="product-price-container mb-4">
                <span class="current-price">$<?php echo $producto->precio ?? '0.00'; ?></span>
              </div>
              <div class="product-short-description mb-4">
                <p><?php echo $producto->descripcion ?? ''; ?></p>
              </div>
              <div class="product-availability mb-4">
                <?php if (($producto->stock ?? 0) > 0) { ?>
                  <i class="bi bi-check-circle-fill text-success"></i>
                  <span>En stock</span>
                  <span class="stock-count">(<?php echo $producto->stock; ?> disponibles)</span>
                <?php } else { ?>
                  <i class="bi bi-x-circle-fill text-danger"></i>
                  <span>Sin stock</span>
                <?php } ?>
              </div>
              <!-- Quantity Selector -->
              <div class="product-quantity mb-4">
                <h6 class="option-title">Cantidad:</h6>
                <div class="quantity-selector">
                  <button class="quantity-btn decrease">
                    <i class="bi bi-dash"></i>
                  </button>
                  <input type="number" class="quantity-input" value="1" min="1" max="<?php echo $producto->stock ?? 1; ?>">
                  <button class="quantity-btn increase">
                    <i class="bi bi-plus"></i>
                  </button>
                </div>
              </div>
              <!-- Action Buttons -->
              <div class="product-actions">
                <button class="btn btn-primary add-to-cart-btn">
                  <i class="bi bi-cart-plus"></i> Agregar al carrito
                </button>
                <button class="btn btn-outline-primary buy-now-btn">
                  <i class="bi bi-lightning-fill"></i> Comprar ahora
                </button>
                <button class="btn btn-outline-secondary wishlist-btn">
                  <i class="bi bi-heart"></i> 
                </button>
              </div>
            </div>
          </div>
        </div>

      <!-- Fin del bloque principal limpio -->
    </section><!-- /Product Details Section -->

  </main>

<?php require_once '../footer.php'; ?>

