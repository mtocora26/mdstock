<?php
/**
 * Acerca de nosotros - about.php
 */

include 'includes/header.php';
?>

<main class="main">

  <!-- Page Title -->
  <div class="page-title light-background">
    <div class="container d-lg-flex justify-content-between align-items-center">
      <h1 class="mb-2 mb-lg-0">Sobre Nosotros</h1>
      <nav class="breadcrumbs">
        <ol>
          <li><a href="index.php">Inicio</a></li>
          <li class="current">Sobre Nosotros</li>
        </ol>
      </nav>
    </div>
  </div><!-- End Page Title -->

  <!-- About Section -->
  <section id="about" class="about section">

    <div class="container" data-aos="fade-up" data-aos-delay="100">

      <div class="row">
        <div class="col-lg-6">
          <h2>Quiénes Somos</h2>
          <p>
            MDSTOCK es una tienda online de confianza dedicada a ofrecerte los mejores productos a precios competitivos.
            Con años de experiencia en el mercado, nos comprometemos a brindar un servicio de calidad y atención excepcional.
          </p>
          <p>
            Nuestro equipo está siempre disponible para ayudarte en cualquier duda o consulta que tengas sobre nuestros productos y servicios.
          </p>
        </div>
        <div class="col-lg-6">
          <img src="assets/img/about-img.webp" alt="About Us" class="img-fluid">
        </div>
      </div>

    </div>

  </section><!-- /About Section -->

</main>

<?php include 'includes/footer.php'; ?>
