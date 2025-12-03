<?php
require_once __DIR__ . '/../model/dao/ProductoDAO.php';

class ProductoController {
    public static function productosPorCategoria($categoria_id) {
        return ProductoDAO::obtenerPorCategoria($categoria_id);
    }

    public static function detalleProducto($id_producto) {
        return ProductoDAO::obtenerPorId($id_producto);
    }

    public static function buscarProductos($termino) {
        return ProductoDAO::buscarProductos($termino);
    }
}
