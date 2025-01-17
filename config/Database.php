<?php
namespace Config;

class Database {
    // Credenciales de la base de datos
    private $host = "localhost";
    private $db_name = "auth_system";
    private $username = "root";
    private $password = "";
    private $conn = null;

    /**
     * Obtiene la conexión a la base de datos
     * @return PDO|null
     */
    public function getConnection() {
        try {
            $this->conn = new \PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name,
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            return $this->conn;
        } catch(\PDOException $e) {
            echo "Error de conexión: " . $e->getMessage();
            return null;
        }
    }
}
?>