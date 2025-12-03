<?php
class ProductoDTO {
    public $id_producto;
    public $nombre;
    public $descripcion;
    public $precio;
    public $id_categoria;
    public $id_subcategoria;
    public $marca;
    public $fecha_vencimiento;
    public $imagen;
    public $stock;

    public function __construct($id_producto, $nombre, $descripcion, $precio, $id_categoria, $id_subcategoria, $marca, $fecha_vencimiento, $imagen, $stock) {
        $this->id_producto = $id_producto;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->precio = $precio;
        $this->id_categoria = $id_categoria;
        $this->id_subcategoria = $id_subcategoria;
        $this->marca = $marca;
        $this->fecha_vencimiento = $fecha_vencimiento;
        $this->imagen = $imagen;
        $this->stock = $stock;
    }
}
