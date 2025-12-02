<?php
/**
 * Login - login.php
 */

include 'includes/header.php';
?>

<main class="main">

  <!-- Page Title -->
  <div class="page-title light-background">
    <div class="container d-lg-flex justify-content-between align-items-center">
      <h1 class="mb-2 mb-lg-0">Iniciar Sesión</h1>
      <nav class="breadcrumbs">
        <ol>
          <li><a href="index.php">Inicio</a></li>
          <li class="current">Login</li>
        </ol>
      </nav>
    </div>
  </div><!-- End Page Title -->

  <!-- Login Section -->
  <section id="login" class="login section">

    <div class="container" data-aos="fade-up" data-aos-delay="100">

      <div class="row justify-content-center">

        <div class="col-lg-5 col-md-8">

          <div class="card border-0 shadow-sm">
            <div class="card-body p-5">

              <h3 class="text-center mb-4">Acceder a tu Cuenta</h3>

              <form method="POST" action="">

                <div class="mb-3">
                  <label class="form-label">Email</label>
                  <input type="email" name="email" class="form-control" required>
                </div>

                <div class="mb-3">
                  <label class="form-label">Contraseña</label>
                  <input type="password" name="password" class="form-control" required>
                </div>

                <div class="form-check mb-3">
                  <input class="form-check-input" type="checkbox" id="remember" name="remember">
                  <label class="form-check-label" for="remember">
                    Recuérdame
                  </label>
                </div>

                <button type="submit" class="btn btn-primary w-100 mb-3">
                  Iniciar Sesión
                </button>

              </form>

              <p class="text-center text-muted">
                ¿No tienes cuenta? <a href="registro.php" class="text-primary">Regístrate aquí</a>
              </p>

              <hr>

              <p class="text-center text-muted small">
                <a href="#">¿Olvidaste tu contraseña?</a>
              </p>

            </div>
          </div>

        </div>

      </div>

    </div>

  </section><!-- /Login Section -->

</main>

<?php include 'includes/footer.php'; ?>
