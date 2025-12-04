<?php
class DireccionEnvioDAO {
    private $pdo;
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Guarda o actualiza la dirección predeterminada del usuario
    public function guardarDireccionPredeterminada($id_usuario, $direccion, $ciudad, $departamento, $codigo_postal, $referencia) {
        // Primero, poner todas las direcciones como no predeterminadas
        $stmt = $this->pdo->prepare("UPDATE direcciones_envio SET predeterminada = 0 WHERE id_usuario = :id_usuario");
        $stmt->bindParam(':id_usuario', $id_usuario);
        $stmt->execute();
        // Insertar nueva dirección como predeterminada
        $stmt = $this->pdo->prepare("INSERT INTO direcciones_envio (id_usuario, direccion, ciudad, departamento, codigo_postal, referencia, predeterminada) VALUES (:id_usuario, :direccion, :ciudad, :departamento, :codigo_postal, :referencia, 1)");
        $stmt->bindParam(':id_usuario', $id_usuario);
        $stmt->bindParam(':direccion', $direccion);
        $stmt->bindParam(':ciudad', $ciudad);
        $stmt->bindParam(':departamento', $departamento);
        $stmt->bindParam(':codigo_postal', $codigo_postal);
        $stmt->bindParam(':referencia', $referencia);
        $stmt->execute();
        return true;
    }

    // Obtiene la dirección predeterminada del usuario
    public function obtenerDireccionPredeterminada($id_usuario) {
        $stmt = $this->pdo->prepare("SELECT * FROM direcciones_envio WHERE id_usuario = :id_usuario AND predeterminada = 1 LIMIT 1");
        $stmt->bindParam(':id_usuario', $id_usuario);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
