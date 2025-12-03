<?php
class Conexion {
    private static $host = 'localhost';
    private static $usuario = 'root'; // Cambia por tu usuario
    private static $clave = '';
    private static $bd = 'db_mdstock';

    public static function getConexion() {
        try {
            $conn = new PDO("mysql:host=" . self::$host . ";dbname=" . self::$bd, self::$usuario, self::$clave);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $conn->exec("set names utf8");
            return $conn;
        } catch (PDOException $e) {
            die('Error de conexión: ' . $e->getMessage());
        }
    }
}
