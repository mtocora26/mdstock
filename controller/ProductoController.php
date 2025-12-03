<?php
require_once __DIR__ . '/../model/dao/ProductoDAO.php';

class ProductoController {
    public static function productosPorCategoria($categoria_id) {
        return ProductoDAO::obtenerPorCategoria($categoria_id);
    }
}
