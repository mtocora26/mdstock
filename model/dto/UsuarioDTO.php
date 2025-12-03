<?php
class UsuarioDTO {
    public $id_usuario;
    public $nombres;
    public $apellidos;
    public $correo;
    public $password;
    public $estado;
    public $telefono;
    public $fecha_registro;

    public function __construct($nombres = '', $apellidos = '', $correo = '', $password = '', $telefono = '', $estado = 'activo') {
        $this->nombres = $nombres;
        $this->apellidos = $apellidos;
        $this->correo = $correo;
        $this->password = $password;
        $this->telefono = $telefono;
        $this->estado = $estado;
    }
}
