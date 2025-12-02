<?php
/**
 * includes/header.php
 * Header reutilizable: sesiones, BD, meta, CSS, navbar
 */

// Iniciar sesión si no está activa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Conectar BD
require_once __DIR__ . '/../config/db.php';

?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>MDSTOCK - Tienda Online</title>
  <meta name="description" content="MDSTOCK - Tu tienda de confianza">
  <meta name="keywords" content="tienda online, compras, productos">

  <!-- Favicons -->
  <link href="/mdstock/assets/img/minilogo.png" rel="icon">
  <link href="/mdstock/assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Nunito:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="/mdstock/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="/mdstock/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="/mdstock/assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
  <link href="/mdstock/assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="/mdstock/assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="/mdstock/assets/vendor/drift-zoom/drift-basic.css" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="/mdstock/assets/css/main.css" rel="stylesheet">
</head>

<body>

  <header id="header" class="header position-relative">
    <!-- Top Bar -->
    <div class="top-bar py-2">
      <div class="container-fluid container-xl">
        <div class="row align-items-center">
          <div class="col-lg-4 d-none d-lg-flex">
            <div class="top-bar-item">
              <i class="bi bi-telephone-fill me-2"></i>
              <span>¿Necesitas llamarnos?: </span>
              <a href="tel:+573026781363">+57 302 6781363</a>
            </div>
          </div>

          <div class="col-lg-4 col-md-12 text-center">
            <div class="announcement-slider swiper init-swiper">
              <script type="application/json" class="swiper-config">
                {
                  "loop": true,
                  "speed": 600,
                  "autoplay": {
                    "delay": 5000
                  },
                  "slidesPerView": 1,
                  "direction": "vertical",
                  "effect": "slide"
                }
              </script>
              <div class="swiper-wrapper">
                <div class="swiper-slide">🚚 Envío gratis en compras mayores a $50</div>
                <div class="swiper-slide">💰 Garantía de devolución 30 días</div>
                <div class="swiper-slide">🎁 20% descuento en tu primer pedido</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Main Header -->
    <div class="main-header">
      <div class="container-fluid container-xl">
        <div class="d-flex py-3 align-items-center justify-content-between">

          <!-- Logo -->
          <a href="/mdstock/index.php" class="logo d-flex align-items-center">
            <h1 class="sitename">MDSTOCK</h1>
          </a>

          <!-- Search -->
          <form class="search-form desktop-search-form" action="/mdstock/buscar.php" method="GET">
            <div class="input-group">
              <input type="text" name="q" class="form-control" placeholder="Buscar productos">
              <button class="btn" type="submit">
                <i class="bi bi-search"></i>
              </button>
            </div>
          </form>

          <!-- Actions -->
          <div class="header-actions d-flex align-items-center justify-content-end">

            <!-- Mobile Search Toggle -->
            <button class="header-action-btn mobile-search-toggle d-xl-none" type="button" data-bs-toggle="collapse" data-bs-target="#mobileSearch" aria-expanded="false" aria-controls="mobileSearch">
              <i class="bi bi-search"></i>
            </button>

            <!-- Account -->
            <div class="dropdown account-dropdown">
              <button class="header-action-btn" data-bs-toggle="dropdown">
                <i class="bi bi-person"></i>
              </button>
              <div class="dropdown-menu">
                <div class="dropdown-header">
                  <h6>Bienvenido a <span class="sitename">MDSTOCK</span></h6>
                  <p class="mb-0">Accede a tu cuenta &amp; gestiona tus pedidos</p>
                </div>
                <div class="dropdown-body">
                  <a class="dropdown-item d-flex align-items-center" href="/mdstock/pages/perfil.php">
                    <i class="bi bi-person-circle me-2"></i>
                    <span>Mi perfil</span>
                  </a>
                  <a class="dropdown-item d-flex align-items-center" href="/mdstock/pages/mis-ordenes.php">
                    <i class="bi bi-bag-check me-2"></i>
                    <span>Mis órdenes</span>
                  </a>
                  <a class="dropdown-item d-flex align-items-center" href="/mdstock/pages/carrito.php">
                    <i class="bi bi-heart me-2"></i>
                    <span>Mi carrito</span>
                  </a>
                </div>
                <div class="dropdown-footer">
                  <a href="/mdstock/pages/login.php" class="btn btn-primary w-100 mb-2">Iniciar sesión</a>
                  <a href="/mdstock/pages/registro.php" class="btn btn-outline-primary w-100">Registrarse</a>
                </div>
              </div>
            </div>

            <!-- Wishlist -->
            <a href="/mdstock/pages/perfil.php" class="header-action-btn d-none d-md-block">
              <i class="bi bi-heart"></i>
              <span class="badge">0</span>
            </a>

            <!-- Cart -->
            <a href="/mdstock/pages/carrito.php" class="header-action-btn">
              <i class="bi bi-cart3"></i>
              <span class="badge">3</span>
            </a>

            <!-- Mobile Navigation Toggle -->
            <i class="mobile-nav-toggle d-xl-none bi bi-list me-0"></i>

          </div>
        </div>
      </div>
    </div>

  <?php include 'navbar.php'; ?>

    <!-- Mobile Search Form -->
    <div class="collapse" id="mobileSearch">
      <div class="container">
        <form class="search-form" action="/mdstock/buscar.php" method="GET">
          <div class="input-group">
            <input type="text" name="q" class="form-control" placeholder="Buscar productos">
            <button class="btn" type="submit">
              <i class="bi bi-search"></i>
            </button>
          </div>
        </form>
      </div>
    </div>

  </header>
