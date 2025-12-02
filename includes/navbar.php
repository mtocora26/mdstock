<?php
/**
 * includes/navbar.php
 * Navbar dinámico: categorías y subcategorías desde BD
 */

try {
    // Cargar categorías
    $categorias = $pdo->query("SELECT id_categoria, nombre FROM categorias ORDER BY nombre")->fetchAll();
    
    // Cargar subcategorías
    $subs = $pdo->query("SELECT id_subcategoria, id_categoria, nombre FROM subcategorias ORDER BY nombre")->fetchAll();
    
    // Agrupar subcategorías por categoría
    $subsPorCat = [];
    foreach ($subs as $s) {
        $subsPorCat[$s['id_categoria']][] = $s;
    }
} catch (PDOException $e) {
    $categorias = [];
    $subsPorCat = [];
}

?>

<!-- Navigation -->
<div class="header-nav">
  <div class="container-fluid container-xl">
    <div class="position-relative">
      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="/mdstock/index.php" class="active">Inicio</a></li>
          
          <?php foreach ($categorias as $cat): ?>
            <li class="dropdown">
              <a href="/mdstock/pages/productos.php?cat=<?= (int)$cat['id_categoria'] ?>">
                <?= htmlspecialchars($cat['nombre']) ?>
                <?php if (!empty($subsPorCat[$cat['id_categoria']])): ?>
                  <i class="bi bi-chevron-down toggle-dropdown"></i>
                <?php endif; ?>
              </a>
              
              <?php if (!empty($subsPorCat[$cat['id_categoria']])): ?>
                <ul class="dropdown-menu">
                  <?php foreach ($subsPorCat[$cat['id_categoria']] as $sub): ?>
                    <li>
                      <a href="/mdstock/pages/productos.php?cat=<?= (int)$cat['id_categoria'] ?>&sub=<?= (int)$sub['id_subcategoria'] ?>">
                        <?= htmlspecialchars($sub['nombre']) ?>
                      </a>
                    </li>
                  <?php endforeach; ?>
                </ul>
              <?php endif; ?>
            </li>
          <?php endforeach; ?>
          
          <li><a href="/mdstock/pages/carrito.php">Carrito</a></li>

          <?php if (!empty($_SESSION['usuario_id'])): ?>
            <li class="dropdown">
              <a href="/mdstock/pages/perfil.php">
                Mi cuenta <i class="bi bi-chevron-down toggle-dropdown"></i>
              </a>
              <ul class="dropdown-menu">
                <li><a href="/mdstock/pages/perfil.php">Mi Perfil</a></li>
                <li><a href="/mdstock/pages/mis-ordenes.php">Mis Órdenes</a></li>
                <li><hr></li>
                <li><a href="/mdstock/pages/logout.php">Salir</a></li>
              </ul>
            </li>
          <?php else: ?>
            <li><a href="/mdstock/pages/login.php">Ingresar</a></li>
          <?php endif; ?>
          
          <li><a href="/mdstock/pages/contacto.php">Contacto</a></li>
        </ul>
      </nav>
    </div>
  </div>
</div>

