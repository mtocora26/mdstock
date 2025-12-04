<?php
session_start();
require_once __DIR__ . '/../../model/conexion.php';
require_once __DIR__ . '/../../model/dao/ProductoDAO.php';
require_once __DIR__ . '/../../model/helpers/RoleHelper.php';

// Verificar autenticación y permisos
if (!isset($_SESSION['usuario']['id_usuario'])) {
    header('Location: ../pages/login-registro.php');
    exit;
}

$pdo = Conexion::getConexion();
$id_usuario = $_SESSION['usuario']['id_usuario'];

if (!RoleHelper::esAdmin($id_usuario, $pdo)) {
    $_SESSION['error'] = 'No tienes permisos de administrador';
    header('Location: ../pages/category.php');
    exit;
}

// Obtener productos
$productos = ProductoDAO::obtenerTodos(100, 0);
$totalProductos = ProductoDAO::contarProductos();

// Obtener categorías para el formulario
$stmt = $pdo->prepare("SELECT id_categoria, nombre FROM categorias ORDER BY nombre");
$stmt->execute();
$categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administración de Productos - MD Stock</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        body {
            background-color: #f8f9fa;
        }
        .admin-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .stats-card {
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            border: none;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .stats-card.primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .product-img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
        }
        .table-actions {
            white-space: nowrap;
        }
        .btn-action {
            padding: 0.25rem 0.75rem;
            font-size: 0.875rem;
            margin: 0 0.25rem;
        }
        .modal-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .badge-stock {
            padding: 0.5rem 1rem;
            border-radius: 20px;
        }
        .badge-stock.high {
            background-color: #d4edda;
            color: #155724;
        }
        .badge-stock.medium {
            background-color: #fff3cd;
            color: #856404;
        }
        .badge-stock.low {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="admin-header">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="mb-0"><i class="bi bi-speedometer2"></i> Panel de Administración</h1>
                    <p class="mb-0 mt-2">Gestión de Productos</p>
                </div>
                <div>
                    <a href="../pages/category.php" class="btn btn-light">
                        <i class="bi bi-house"></i> Volver a la tienda
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <!-- Mensajes de éxito/error -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle"></i> <?php echo htmlspecialchars($_SESSION['success']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle"></i> <?php echo htmlspecialchars($_SESSION['error']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <!-- Estadísticas -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card stats-card primary">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-0"><?php echo $totalProductos; ?></h3>
                            <p class="mb-0">Total Productos</p>
                        </div>
                        <i class="bi bi-box-seam" style="font-size: 3rem; opacity: 0.5;"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Botón Agregar -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-list-ul"></i> Lista de Productos</h5>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAgregar">
                        <i class="bi bi-plus-lg"></i> Agregar Producto
                    </button>
                </div>
            </div>
        </div>

        <!-- Tabla de Productos -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Imagen</th>
                                <th>Nombre</th>
                                <th>Precio</th>
                                <th>Stock</th>
                                <th>Marca</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($productos)): ?>
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                                        <p class="text-muted mt-2">No hay productos registrados</p>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($productos as $producto): ?>
                                    <tr>
                                        <td><?php echo $producto->id_producto; ?></td>
                                        <td>
                                            <?php if ($producto->imagen): ?>
                                                <img src="../../<?php echo htmlspecialchars($producto->imagen); ?>"
                                                     alt="Producto"
                                                     class="product-img"
                                                     onerror="this.src='../../assets/img/product/default.webp'">
                                            <?php else: ?>
                                                <div class="product-img bg-secondary d-flex align-items-center justify-content-center">
                                                    <i class="bi bi-image text-white"></i>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <strong><?php echo htmlspecialchars($producto->nombre); ?></strong><br>
                                            <small class="text-muted"><?php echo htmlspecialchars(substr($producto->descripcion, 0, 50)); ?>...</small>
                                        </td>
                                        <td><strong>$<?php echo intval($producto->precio); ?></strong></td>
                                        <td>
                                            <?php
                                            $stock = $producto->stock ?? 0;
                                            $badgeClass = $stock > 20 ? 'high' : ($stock > 5 ? 'medium' : 'low');
                                            ?>
                                            <span class="badge-stock <?php echo $badgeClass; ?>">
                                                <?php echo $stock; ?> unidades
                                            </span>
                                        </td>
                                        <td><?php echo htmlspecialchars($producto->marca ?? 'N/A'); ?></td>
                                        <td class="table-actions text-center">
                                            <button class="btn btn-sm btn-warning btn-action"
                                                    onclick='editarProducto(<?php echo json_encode($producto); ?>)'>
                                                <i class="bi bi-pencil"></i> Editar
                                            </button>
                                            <a href="../../controller/AdminProductoController.php?accion=eliminar&id=<?php echo $producto->id_producto; ?>"
                                               class="btn btn-sm btn-danger btn-action"
                                               onclick="return confirm('¿Estás seguro de eliminar este producto?')">
                                                <i class="bi bi-trash"></i> Eliminar
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Agregar Producto -->
    <div class="modal fade" id="modalAgregar" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-plus-circle"></i> Agregar Producto</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="../../controller/AdminProductoController.php" method="POST">
                    <input type="hidden" name="accion" value="crear">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nombre *</label>
                            <input type="text" class="form-control" name="nombre" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Descripción</label>
                            <textarea class="form-control" name="descripcion" rows="3"></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Precio *</label>
                                <input type="number" class="form-control" name="precio" step="0.01" min="0" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Stock Inicial</label>
                                <input type="number" class="form-control" name="stock" value="0" min="0">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Categoría</label>
                                <select class="form-select" name="id_categoria">
                                    <?php foreach ($categorias as $cat): ?>
                                        <option value="<?php echo $cat['id_categoria']; ?>">
                                            <?php echo htmlspecialchars($cat['nombre']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Marca</label>
                                <input type="text" class="form-control" name="marca">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Editar Producto -->
    <div class="modal fade" id="modalEditar" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-pencil-square"></i> Editar Producto</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="../../controller/AdminProductoController.php" method="POST">
                    <input type="hidden" name="accion" value="actualizar">
                    <input type="hidden" name="id_producto" id="edit_id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nombre *</label>
                            <input type="text" class="form-control" name="nombre" id="edit_nombre" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Descripción</label>
                            <textarea class="form-control" name="descripcion" id="edit_descripcion" rows="3"></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Precio *</label>
                                <input type="number" class="form-control" name="precio" id="edit_precio" step="0.01" min="0" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Stock</label>
                                <input type="number" class="form-control" name="stock" id="edit_stock" min="0">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Categoría</label>
                                <select class="form-select" name="id_categoria" id="edit_categoria">
                                    <?php foreach ($categorias as $cat): ?>
                                        <option value="<?php echo $cat['id_categoria']; ?>">
                                            <?php echo htmlspecialchars($cat['nombre']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Marca</label>
                                <input type="text" class="form-control" name="marca" id="edit_marca">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> Actualizar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function editarProducto(producto) {
            document.getElementById('edit_id').value = producto.id_producto;
            document.getElementById('edit_nombre').value = producto.nombre;
            document.getElementById('edit_descripcion').value = producto.descripcion || '';
            document.getElementById('edit_precio').value = producto.precio;
            document.getElementById('edit_stock').value = producto.stock || 0;
            document.getElementById('edit_categoria').value = producto.id_categoria || 1;
            document.getElementById('edit_marca').value = producto.marca || '';

            const modal = new bootstrap.Modal(document.getElementById('modalEditar'));
            modal.show();
        }
    </script>
</body>
</html>
