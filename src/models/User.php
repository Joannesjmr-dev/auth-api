<?php
namespace Models;

class User {
    private $conn;
    private $table = "users";

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Registra un nuevo usuario
     */
    public function register($username, $password) {
        // Verificar si el usuario ya existe
        $check_query = "SELECT id FROM " . $this->table . " WHERE username = ?";
        $check_stmt = $this->conn->prepare($check_query);
        $check_stmt->execute([$username]);

        if ($check_stmt->rowCount() > 0) {
            return [
                "status" => "error",
                "message" => "El usuario ya existe"
            ];
        }

        // Crear el nuevo usuario
        $query = "INSERT INTO " . $this->table . " (username, password) VALUES (?, ?)";
        
        // Hash de la contraseña
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$username, $hashed_password]);
            
            return [
                "status" => "success",
                "message" => "Usuario registrado exitosamente"
            ];
        } catch(\PDOException $e) {
            return [
                "status" => "error",
                "message" => "Error en el registro: " . $e->getMessage()
            ];
        }
    }

    /**
     * Login de usuario
     */
    public function login($username, $password) {
        $query = "SELECT id, password FROM " . $this->table . " WHERE username = ?";
        
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$username]);
            
            if ($stmt->rowCount() > 0) {
                $row = $stmt->fetch(\PDO::FETCH_ASSOC);
                
                if (password_verify($password, $row['password'])) {
                    return [
                        "status" => "success",
                        "message" => "Autenticación exitosa"
                    ];
                }
            }
            
            return [
                "status" => "error",
                "message" => "Usuario o contraseña incorrectos"
            ];
        } catch(\PDOException $e) {
            return [
                "status" => "error",
                "message" => "Error en la autenticación: " . $e->getMessage()
            ];
        }
    }
}
?>