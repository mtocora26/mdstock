<?php
/**
 * Registro - registro.php
 */

include 'includes/header.php';
?>

<main class="main">

  <!-- Page Title -->
  <div class="page-title light-background">
    <div class="container d-lg-flex justify-content-between align-items-center">
      <h1 class="mb-2 mb-lg-0">Crear Cuenta</h1>
      <nav class="breadcrumbs">
        <ol>
          <li><a href="index.php">Inicio</a></li>
          <li class="current">Registro</li>
        </ol>
      </nav>
    </div>
  </div><!-- End Page Title -->

  <!-- Register Section -->
  <section id="register" class="register section">

    <div class="container" data-aos="fade-up" data-aos-delay="100">

      <div class="row justify-content-center">

        <div class="col-lg-6 col-md-8">

          <div class="card border-0 shadow-sm">
            <div class="card-body p-5">

              <h3 class="text-center mb-4">Crear Nueva Cuenta</h3>

              <form method="POST" action="">

                <div class="row mb-3">
                  <div class="col-md-6">
                    <label class="form-label">Nombre</label>
                    <input type="text" name="firstname" class="form-control" required>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label">Apellido</label>
                    <input type="text" name="lastname" class="form-control" required>
                  </div>
                </div>

                <div class="mb-3">
                  <label class="form-label">Email</label>
                  <input type="email" name="email" class="form-control" required>
                </div>

                <div class="mb-3">
                  <label class="form-label">Contraseña</label>
                  <input type="password" name="password" class="form-control" required>
                </div>

                <div class="mb-3">
                  <label class="form-label">Confirmar Contraseña</label>
                  <input type="password" name="password_confirm" class="form-control" required>
                </div>

                <div class="form-check mb-3">
                  <input class="form-check-input" type="checkbox" id="terms" name="terms" required>
                  <label class="form-check-label" for="terms">
                    Acepto los términos y condiciones
                  </label>
                </div>

                <button type="submit" class="btn btn-primary w-100 mb-3">
                  Crear Cuenta
                </button>

              </form>

              <p class="text-center text-muted">
                ¿Ya tienes cuenta? <a href="login.php" class="text-primary">Inicia sesión aquí</a>
              </p>

            </div>
          </div>

        </div>

      </div>

    </div>

  </section><!-- /Register Section -->

</main>

<?php include 'includes/footer.php'; ?>
