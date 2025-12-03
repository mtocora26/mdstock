<?php
require_once '../header.php';
require_once '../../controller/ProductoController.php';
$search = $_POST['search'] ?? $_GET['search'] ?? '';
$resultados = [];
if ($search !== '') {
    $resultados = ProductoController::buscarProductos($search);

}
?>

<main class="main">

    <!-- Search Results Header Section -->
    <section id="search-results-header" class="search-results-header section">

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="search-results-header">
          <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0">
              <div class="results-count" data-aos="fade-right" data-aos-delay="200">
                <h2>Search Results</h2>
                <p>We found <span class="results-number"><?php echo count($resultados); ?></span> results for <span class="search-term">"<?php echo htmlspecialchars($search); ?>"</span></p>
              </div>
            </div>
            <div class="col-lg-6" data-aos="fade-left" data-aos-delay="300">
              <form method="post" action="search-results.php" class="search-form">
                <div class="input-group">
                  <input type="text" class="form-control" placeholder="Search..." name="search" value="<?php echo htmlspecialchars($search); ?>" required="">
                  <button class="btn search-btn" type="submit">
                    <i class="bi bi-search"></i>
                  </button>
                </div>
              </form>
            </div>
          </div>

          <div class="search-filters mt-4" data-aos="fade-up" data-aos-delay="400">
            <div class="row">
              <div class="col-lg-8">
                <div class="filter-tags">
                  <span class="filter-label">Filters:</span>
                  <div class="tags-wrapper">
                    <span class="filter-tag">
                      Category: Blog
                      <i class="bi bi-x-circle"></i>
                    </span>
                    <span class="filter-tag">
                      Date: Last Month
                      <i class="bi bi-x-circle"></i>
                    </span>
                  </div>
                </div>
              </div>
              <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <div class="sort-options">
                  <label for="sort-select" class="me-2">Sort by:</label>
                  <select id="sort-select" class="form-select form-select-sm d-inline-block w-auto">
                    <option value="relevance">Relevance</option>
                    <option value="date-desc">Newest First</option>
                    <option value="date-asc">Oldest First</option>
                    <option value="title-asc">Title A-Z</option>
                    <option value="title-desc">Title Z-A</option>
                  </select>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>

    </section><!-- /Search Results Header Section -->

    <!-- Search Product List Section -->
    <section id="search-product-list" class="search-product-list section">

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row g-4">
          <?php if ($search !== ''): ?>
            <?php foreach ($resultados as $producto): ?>
              <div class="col-6 col-lg-3">
                <div class="product-card" data-aos="zoom-in">
                  <div class="product-image">
                    <img src="<?php echo $producto->imagen ?? 'assets/img/product/product-details-1.webp'; ?>" class="main-image img-fluid" alt="<?php echo htmlspecialchars($producto->nombre); ?>">
                    <div class="product-overlay">
                      <div class="product-actions">
                        <button type="button" class="action-btn" data-bs-toggle="tooltip" title="Quick View">
                          <i class="bi bi-eye"></i>
                        </button>
                        <button type="button" class="action-btn" data-bs-toggle="tooltip" title="Add to Cart">
                          <i class="bi bi-cart-plus"></i>
                        </button>
                      </div>
                    </div>
                  </div>
                  <div class="product-details">
                    <div class="product-category"><?php echo htmlspecialchars($producto->categoria_nombre ?? $producto->id_categoria); ?></div>
                    <h4 class="product-title"><a href="product-details.php?id=<?php echo $producto->id_producto; ?>"><?php echo htmlspecialchars($producto->nombre); ?></a></h4>
                    <div class="product-meta">
                      <div class="product-price">$<?php echo $producto->precio; ?></div>
                      <div class="product-rating">
                        <i class="bi bi-star-fill"></i>
                        4.8 <span>(42)</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
            <?php if (count($resultados) === 0): ?>
              <div class="col-12"><p>No se encontraron productos.</p></div>
            <?php endif; ?>
          <?php endif; ?>
        </div>

      </div>

    </section><!-- /Search Product List Section -->

    <!-- Category Pagination Section -->
    <section id="category-pagination" class="category-pagination section">

      <div class="container">
        <nav class="d-flex justify-content-center" aria-label="Page navigation">
          <ul>
            <li>
              <a href="#" aria-label="Previous page">
                <i class="bi bi-arrow-left"></i>
                <span class="d-none d-sm-inline">Previous</span>
              </a>
            </li>

            <li><a href="#" class="active">1</a></li>
            <li><a href="#">2</a></li>
            <li><a href="#">3</a></li>
            <li class="ellipsis">...</li>
            <li><a href="#">8</a></li>
            <li><a href="#">9</a></li>
            <li><a href="#">10</a></li>

            <li>
              <a href="#" aria-label="Next page">
                <span class="d-none d-sm-inline">Next</span>
                <i class="bi bi-arrow-right"></i>
              </a>
            </li>
          </ul>
        </nav>
      </div>

    </section><!-- /Category Pagination Section -->

  </main>

<?php require_once '../footer.php'; ?>

