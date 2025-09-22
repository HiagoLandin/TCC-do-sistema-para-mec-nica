<?php
session_start();

// Verificar se o usuário está logado, se não, redirecionar
if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header("Location: index.php");
    exit;
}

// Incluir a classe Database
require_once 'Database.php';

// Criar instância do banco de dados
$database = new Database();
$conn = $database->getConnection();

// Processar formulário de adição de cliente
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['adicionar_cliente'])) {
        $nome = $_POST['nome'];
        $telefone = $_POST['telefone'];
        $email = $_POST['email'];
        $status = $_POST['status'];
        $data_cadastro = date('Y-m-d'); // Data atual
        
        $stmt = $conn->prepare("INSERT INTO clientes (nome, telefone, email, status, data_cadastro) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $nome, $telefone, $email, $status, $data_cadastro);
        
        if ($stmt->execute()) {
            header("Location: clientes.php?sucesso=1");
            exit;
        } else {
            $mensagem_erro = "Erro ao adicionar cliente: " . $conn->error;
            // Você pode redirecionar para uma página de erro ou mostrar a mensagem
            header("Location: clientes.php?erro=" . urlencode($mensagem_erro));
            exit;
        }
    }
    
    // Processar edição de cliente
    if (isset($_POST['editar_cliente'])) {
        $id = $_POST['cliente_id'];
        $nome = $_POST['nome'];
        $telefone = $_POST['telefone'];
        $email = $_POST['email'];
        $status = $_POST['status'];
        
        $stmt = $conn->prepare("UPDATE clientes SET nome=?, telefone=?, email=?, status=? WHERE id=?");
        $stmt->bind_param("ssssi", $nome, $telefone, $email, $status, $id);
        
        if ($stmt->execute()) {
            header("Location: clientes.php?editado=1");
            exit;
        } else {
            $mensagem_erro = "Erro ao editar cliente: " . $conn->error;
            header("Location: clientes.php?erro=" . urlencode($mensagem_erro));
            exit;
        }
    }
    
    // Processar remoção de cliente
    if (isset($_POST['remover_cliente'])) {
        $id = $_POST['cliente_id'];
        
        $stmt = $conn->prepare("DELETE FROM clientes WHERE id = ?");
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            header("Location: clientes.php?removido=1");
            exit;
        } else {
            $mensagem_erro = "Erro ao remover cliente: " . $conn->error;
            header("Location: clientes.php?erro=" . urlencode($mensagem_erro));
            exit;
        }
    }
}

// Se alguém acessar este arquivo diretamente sem POST, redirecionar
header("Location: clientes.php");
exit;
?>