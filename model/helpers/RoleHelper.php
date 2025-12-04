<?php
/**
 * Helper para manejo de roles y permisos
 */
class RoleHelper {

    /**
     * Verifica si un usuario es administrador
     */
    public static function esAdmin($id_usuario, $pdo) {
        try {
            $stmt = $pdo->prepare("
                SELECT r.nombre
                FROM usuario_rol ur
                JOIN rol r ON ur.id_rol = r.id_rol
                WHERE ur.id_usuario = :id_usuario
            ");
            $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $stmt->execute();
            $rol = $stmt->fetch(PDO::FETCH_ASSOC);

            return $rol && $rol['nombre'] === 'administrador';
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Obtiene el rol de un usuario
     */
    public static function obtenerRol($id_usuario, $pdo) {
        try {
            $stmt = $pdo->prepare("
                SELECT r.nombre
                FROM usuario_rol ur
                JOIN rol r ON ur.id_rol = r.id_rol
                WHERE ur.id_usuario = :id_usuario
            ");
            $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $stmt->execute();
            $rol = $stmt->fetch(PDO::FETCH_ASSOC);

            return $rol ? $rol['nombre'] : 'cliente';
        } catch (Exception $e) {
            return 'cliente';
        }
    }

    /**
     * Redirige si el usuario no es administrador
     */
    public static function requerirAdmin($id_usuario, $pdo, $redirect = '../pages/category.php') {
        if (!self::esAdmin($id_usuario, $pdo)) {
            header("Location: $redirect");
            exit;
        }
    }
}
