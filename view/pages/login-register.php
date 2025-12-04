<?php require_once '../header.php'; ?>

<main class="main">
  <!-- Page Title -->
  <div class="page-title light-background">
    <div class="container d-lg-flex justify-content-between align-items-center">
      <h1 class="mb-2 mb-lg-0">Iniciar sesión</h1>
    </div>
  </div>

  <!-- Login Register Section -->
  <section id="login-register" class="login-register section">
    <div class="container" data-aos="fade-up" data-aos-delay="100">
      <div class="row justify-content-center">
        <div class="col-lg-5">
          <div class="login-register-wraper">
            <!-- Tab Navigation -->
            <ul class="nav nav-tabs nav-tabs-bordered justify-content-center mb-4" role="tablist">
              <li class="nav-item" role="presentation">
                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#login-register-login-form" type="button" role="tab" aria-selected="true">
                  <i class="bi bi-box-arrow-in-right me-1"></i>Iniciar sesión
                </button>
              </li>
              <li class="nav-item" role="presentation">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#login-register-registration-form" type="button" role="tab" aria-selected="false">
                  <i class="bi bi-person-plus me-1"></i>Registrarse
                </button>
              </li>
            </ul>
            <!-- Tab Content -->
            <div class="tab-content">
              <!-- Login Form -->
              <div class="tab-pane fade show active" id="login-register-login-form" role="tabpanel">
                <form action="../../controller/LoginController.php" method="POST">
                  <div class="mb-4">
                    <label for="login-register-login-email" class="form-label">Correo electrónico</label>
                    <input type="email" class="form-control" id="login-register-login-email" name="correo" required="">
                  </div>
                  <div class="mb-4">
                    <label for="login-register-login-password" class="form-label">Contraseña</label>
                    <div class="input-group">
                      <input type="password" class="form-control" id="login-register-login-password" name="password" required="">
                      <span class="input-group-text toggle-password" data-target="login-register-login-password">
                        <i class="fa fa-eye"></i>
                      </span>
                    </div>
                  </div>
                  <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                      <input type="checkbox" class="form-check-input" id="login-register-remember-me">
                      <label class="form-check-label" for="login-register-remember-me">Recuérdame</label>
                    </div>
                    <a href="#" class="forgot-password">¿Olvidaste tu contraseña?</a>
                  </div>
                  <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-lg">Iniciar sesión</button>
                  </div>
                </form>
              </div>
              <!-- Registration Form -->
              <div class="tab-pane fade" id="login-register-registration-form" role="tabpanel">
                <form action="../../controller/UsuarioController.php" method="POST">
                  <div class="row g-3">
                    <div class="col-sm-6">
                      <div class="mb-4">
                        <label for="login-register-reg-firstname" class="form-label">Nombres</label>
                        <input type="text" class="form-control" name="nombres" id="login-register-reg-firstname" required="">
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <div class="mb-4">
                        <label for="login-register-reg-lastname" class="form-label">Apellidos</label>
                        <input type="text" class="form-control" name="apellidos" id="login-register-reg-lastname" required="">
                      </div>
                    </div>
                    <div class="col-12">
                      <div class="mb-4">
                        <label for="login-register-reg-email" class="form-label">Correo electrónico</label>
                        <input type="email" class="form-control" name="correo" id="login-register-reg-email" required="">
                      </div>
                    </div>
                    <div class="col-12">
                      <div class="mb-4">
                        <label for="login-register-reg-phone" class="form-label">Teléfono</label>
                        <input type="text" class="form-control" name="telefono" id="login-register-reg-phone" required="">
                      </div>
                    </div>
                    <div class="col-12">
                      <div class="mb-4">
                        <label for="login-register-reg-password" class="form-label">Contraseña</label>
                        <div class="input-group">
                          <input type="password" class="form-control" name="password" id="login-register-reg-password" required="">
                          <span class="input-group-text toggle-password" data-target="login-register-reg-password">
                            <i class="fa fa-eye"></i>
                          </span>
                        </div>
                      </div>
                    </div>
                    <div class="col-12">
                      <div class="mb-4">
                        <label for="login-register-reg-confirm-password" class="form-label">Confirmar contraseña</label>
                        <div class="input-group">
                          <input type="password" class="form-control" id="login-register-reg-confirm-password" required="">
                          <span class="input-group-text toggle-password" data-target="login-register-reg-confirm-password">
                            <i class="fa fa-eye"></i>
                          </span>
                        </div>
                      </div>
                    </div>
                    <div class="col-12">
                      <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="login-register-terms" required="">
                        <label class="form-check-label" for="login-register-terms">
                          Acepto los <a href="../tos.html">Términos de servicio</a> y la <a href="../privacy.html">Política de privacidad</a>
                        </label>
                      </div>
                    </div>
                    <div class="col-12">
                      <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">Crear cuenta</button>
                      </div>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>

<?php require_once '../footer.php'; ?>
