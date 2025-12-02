<?php
/**
 * Contacto - contacto.php
 */

include 'includes/header.php';
?>

<main class="main">

  <!-- Page Title -->
  <div class="page-title light-background">
    <div class="container d-lg-flex justify-content-between align-items-center">
      <h1 class="mb-2 mb-lg-0">Contacto</h1>
      <nav class="breadcrumbs">
        <ol>
          <li><a href="index.php">Inicio</a></li>
          <li class="current">Contacto</li>
        </ol>
      </nav>
    </div>
  </div><!-- End Page Title -->

  <!-- Contact Section -->
  <section id="contact" class="contact section">

    <div class="container" data-aos="fade-up" data-aos-delay="100">

      <div class="row gy-4">

        <div class="col-lg-6">
          <h3>Ponte en Contacto</h3>
          <p>¿Tienes preguntas? Nos encantaría escucharte. Envíanos un mensaje y nos pondremos en contacto pronto.</p>

          <div class="contact-info mt-5">
            <div class="info-item d-flex mb-4">
              <i class="bi bi-geo-alt me-3"></i>
              <div>
                <h5>Ubicación</h5>
                <p>Colombia</p>
              </div>
            </div>

            <div class="info-item d-flex mb-4">
              <i class="bi bi-telephone me-3"></i>
              <div>
                <h5>Teléfono</h5>
                <p>+57 302 6781363</p>
              </div>
            </div>

            <div class="info-item d-flex">
              <i class="bi bi-envelope me-3"></i>
              <div>
                <h5>Email</h5>
                <p>contacto@mdstock.com</p>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-6">
          <form method="POST" action="" class="php-email-form">
            <div class="row mb-3">
              <div class="col-md-6">
                <label class="form-label">Nombre</label>
                <input type="text" name="name" class="form-control" required>
              </div>
              <div class="col-md-6">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required>
              </div>
            </div>

            <div class="mb-3">
              <label class="form-label">Asunto</label>
              <input type="text" name="subject" class="form-control" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Mensaje</label>
              <textarea class="form-control" name="message" rows="5" required></textarea>
            </div>

            <button type="submit" class="btn btn-primary w-100">
              Enviar Mensaje
            </button>
          </form>
        </div>

      </div>

    </div>

  </section><!-- /Contact Section -->

</main>

<?php include 'includes/footer.php'; ?>
