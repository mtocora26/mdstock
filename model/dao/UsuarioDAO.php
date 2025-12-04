<?php
require_once __DIR__ . '/../conexion.php';
require_once __DIR__ . '/../dto/UsuarioDTO.php';

class UsuarioDAO {
        // Actualizar datos del usuario
        public function actualizarUsuario($usuarioDTO) {
            $stmt = $this->pdo->prepare("UPDATE usuario SET Nombres = :nombres, Apellidos = :apellidos, correo = :correo, telefono = :telefono WHERE id_usuario = :id_usuario");
            $stmt->bindParam(':nombres', $usuarioDTO->nombres);
            $stmt->bindParam(':apellidos', $usuarioDTO->apellidos);
            $stmt->bindParam(':correo', $usuarioDTO->correo);
            $stmt->bindParam(':telefono', $usuarioDTO->telefono);
            $stmt->bindParam(':id_usuario', $usuarioDTO->id_usuario);
            $stmt->execute();
            return true;
        }

        // Buscar usuario por ID
        public function buscarPorId($id_usuario) {
            $stmt = $this->pdo->prepare("SELECT * FROM usuario WHERE id_usuario = :id_usuario");
            $stmt->bindParam(':id_usuario', $id_usuario);
            $stmt->execute();
            if ($row = $stmt->fetch()) {
                $usuario = new UsuarioDTO();
                $usuario->id_usuario = $row['id_usuario'];
                $usuario->nombres = $row['Nombres'];
                $usuario->apellidos = $row['Apellidos'];
                $usuario->correo = $row['correo'];
                $usuario->password = $row['password'];
                $usuario->estado = $row['estado'];
                $usuario->telefono = $row['telefono'];
                $usuario->fecha_registro = $row['fecha_registro'];
                return $usuario;
            }
            return null;
        }

        // Actualizar contraseña del usuario
        public function actualizarContrasena($id_usuario, $passwordHash) {
            $stmt = $this->pdo->prepare("UPDATE usuario SET password = :password WHERE id_usuario = :id_usuario");
            $stmt->bindParam(':password', $passwordHash);
            $stmt->bindParam(':id_usuario', $id_usuario);
            $stmt->execute();
            return true;
        }
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Método para crear usuario usando el SP y PDO
    public function crearUsuario($usuarioDTO) {
        $stmt = $this->pdo->prepare("CALL sp_crear_usuario(:nombres, :apellidos, :correo, :password, :telefono, :estado, @id_usuario)");
        $stmt->bindParam(':nombres', $usuarioDTO->nombres);
        $stmt->bindParam(':apellidos', $usuarioDTO->apellidos);
        $stmt->bindParam(':correo', $usuarioDTO->correo);
        $stmt->bindParam(':password', $usuarioDTO->password);
        $stmt->bindParam(':telefono', $usuarioDTO->telefono);
        $stmt->bindParam(':estado', $usuarioDTO->estado);
        $stmt->execute();
        $result = $this->pdo->query("SELECT @id_usuario AS id_usuario");
        $row = $result->fetch();
        return $row['id_usuario'] ?? null;
    }

    // Método para buscar usuario por correo usando PDO
    public function buscarPorCorreo($correo) {
        $stmt = $this->pdo->prepare("SELECT * FROM usuario WHERE correo = :correo");
        $stmt->bindParam(':correo', $correo);
        $stmt->execute();
        if ($row = $stmt->fetch()) {
            $usuario = new UsuarioDTO();
            $usuario->id_usuario = $row['id_usuario'];
            $usuario->nombres = $row['Nombres'];
            $usuario->apellidos = $row['Apellidos'];
            $usuario->correo = $row['correo'];
            $usuario->password = $row['password'];
            $usuario->estado = $row['estado'];
            $usuario->telefono = $row['telefono'];
            $usuario->fecha_registro = $row['fecha_registro'];
            return $usuario;
        }
        return null;
    }
}
