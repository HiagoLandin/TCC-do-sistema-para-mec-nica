<?php
require_once 'Database.php';

class Usuario {
    private $conn;
    private $tabela = "usuarios";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Método para cadastrar um usuário (apenas campos essenciais)
    public function cadastrar($nome, $email, $senha) {
        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
        
        // Query simplificada com apenas os campos essenciais
        $query = "INSERT INTO $this->tabela (nome, email, senha) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($query);

        if (!$stmt) {
            error_log("Erro ao preparar a query: " . $this->conn->error);
            return false;
        }

        $stmt->bind_param("sss", $nome, $email, $senhaHash);

        if ($stmt->execute()) {
            return true;
        } else {
            error_log("Erro ao executar a query: " . $stmt->error);
            return false;
        }
    }

    // Método para buscar um usuário por e-mail
    public function buscarPorEmail($email) {
        $query = "SELECT * FROM $this->tabela WHERE email = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Método para buscar um usuário por ID
    public function buscarPorId($id) {
        $query = "SELECT * FROM $this->tabela WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Método para listar todos os usuários
    public function listarTodos() {
        $query = "SELECT id, nome, email FROM $this->tabela";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
?>