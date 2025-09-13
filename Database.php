<?php
error_reporting(E_ALL);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

class Database {
    private $host = "localhost";
    private $db_name = "sistema_mecanica";
    private $username = "root";
    private $password = "";
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new mysqli($this->host, $this->username, $this->password, $this->db_name);
            
            // Verifica se a conexão foi bem sucedida
            if ($this->conn->connect_error) {
                throw new Exception("Falha na conexão: " . $this->conn->connect_error);
            }
            
        } catch (Exception $e) {
            die("Erro de conexão: " . $e->getMessage());
        }
        return $this->conn;
    }
}
?>