<?php
/**
 * Perfil de usuario - perfil.php
 */

include 'includes/header.php';
?>

<main class="main">

  <!-- Page Title -->
  <div class="page-title light-background">
    <div class="container d-lg-flex justify-content-between align-items-center">
      <h1 class="mb-2 mb-lg-0">Mi Perfil</h1>
      <nav class="breadcrumbs">
        <ol>
          <li><a href="/mdstock/index.php">Inicio</a></li>
          <li class="current">Perfil</li>
        </ol>
      </nav>
    </div>
  </div><!-- End Page Title -->

  <!-- Account Section -->
  <section id="account" class="account section">

    <div class="container" data-aos="fade-up" data-aos-delay="100">

      <div class="row g-4">

        <!-- Sidebar -->
        <div class="col-lg-3">
          <div class="account-sidebar card">
            <div class="card-body">
              <div class="user-info text-center mb-4">
                <img src="/mdstock/assets/img/default-avatar.png" alt="Avatar" class="rounded-circle mb-3" style="width: 80px; height: 80px;">
                <h5>Juan Pérez</h5>
                <p class="text-muted">juan@example.com</p>
              </div>

              <nav class="nav flex-column">
                <a class="nav-link active" href="/mdstock/pages/perfil.php">Mi Perfil</a>
                <a class="nav-link" href="/mdstock/pages/mis-ordenes.php">Mis Órdenes</a>
                <a class="nav-link" href="/mdstock/pages/direcciones.php">Direcciones</a>
                <a class="nav-link" href="/mdstock/pages/carrito.php">Mi Carrito</a>
                <a class="nav-link" href="/mdstock/pages/logout.php">Cerrar Sesión</a>
              </nav>
            </div>
          </div>
        </div>

        <!-- Main Content -->
        <div class="col-lg-9">

          <!-- Profile Information -->
          <div class="card mb-4" data-aos="fade-up" data-aos-delay="200">
            <div class="card-header">
              <h5>Información Personal</h5>
            </div>
            <div class="card-body">
              <form method="POST" action="">
                <div class="row mb-3">
                  <div class="col-md-6">
                    <label class="form-label">Nombre</label>
                    <input type="text" name="firstname" class="form-control" value="Juan" required>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label">Apellido</label>
                    <input type="text" name="lastname" class="form-control" value="Pérez" required>
                  </div>
                </div>

                <div class="mb-3">
                  <label class="form-label">Email</label>
                  <input type="email" name="email" class="form-control" value="juan@example.com" required>
                </div>

                <div class="mb-3">
                  <label class="form-label">Teléfono</label>
                  <input type="tel" name="phone" class="form-control" value="+57 301 234 5678">
                </div>

                <button type="submit" class="btn btn-primary">
                  Guardar Cambios
                </button>
              </form>
            </div>
          </div>

          <!-- Change Password -->
          <div class="card" data-aos="fade-up" data-aos-delay="300">
            <div class="card-header">
              <h5>Cambiar Contraseña</h5>
            </div>
            <div class="card-body">
              <form method="POST" action="">
                <div class="mb-3">
                  <label class="form-label">Contraseña Actual</label>
                  <input type="password" name="current_password" class="form-control" required>
                </div>

                <div class="mb-3">
                  <label class="form-label">Nueva Contraseña</label>
                  <input type="password" name="new_password" class="form-control" required>
                </div>

                <div class="mb-3">
                  <label class="form-label">Confirmar Nueva Contraseña</label>
                  <input type="password" name="confirm_password" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary">
                  Cambiar Contraseña
                </button>
              </form>
            </div>
          </div>

        </div>

      </div>

    </div>

  </section><!-- /Account Section -->

</main>

<?php include 'includes/footer.php'; ?>
