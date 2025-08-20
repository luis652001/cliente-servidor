<?php
/**
 * Modelo User
 * Sistema Cliente-Servidor - Arquitecturas 2025
 */

require_once __DIR__ . '/../config/database.php';

class User {
    private $conn;
    private $table_name = "usuarios";

    public $id;
    public $nombre;
    public $correo;
    public $password_hash;
    public $creado_en;
    public $actualizado_en;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Crear usuario
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                  (nombre, correo, password_hash) 
                  VALUES (:nombre, :correo, :password_hash)";

        $stmt = $this->conn->prepare($query);

        // Sanitizar datos
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->correo = htmlspecialchars(strip_tags($this->correo));

        // Bind de parámetros
        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":correo", $this->correo);
        $stmt->bindParam(":password_hash", $this->password_hash);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Verificar si el correo existe
    public function emailExists() {
        $query = "SELECT id, nombre, correo, password_hash 
                  FROM " . $this->table_name . " 
                  WHERE correo = ? 
                  LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->correo);
        $stmt->execute();

        $num = $stmt->rowCount();

        if($num > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->id = $row['id'];
            $this->nombre = $row['nombre'];
            $this->correo = $row['correo'];
            $this->password_hash = $row['password_hash'];
            return true;
        }
        return false;
    }

    // Obtener usuario por ID
    public function readOne() {
        $query = "SELECT id, nombre, correo, creado_en 
                  FROM " . $this->table_name . " 
                  WHERE id = ? 
                  LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row) {
            $this->nombre = $row['nombre'];
            $this->correo = $row['correo'];
            $this->creado_en = $row['creado_en'];
            return true;
        }
        return false;
    }

    // Actualizar usuario
    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                  SET nombre = :nombre, correo = :correo 
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        // Sanitizar datos
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->correo = htmlspecialchars(strip_tags($this->correo));
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Bind de parámetros
        $stmt->bindParam(':nombre', $this->nombre);
        $stmt->bindParam(':correo', $this->correo);
        $stmt->bindParam(':id', $this->id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>
