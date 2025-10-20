<?php
session_start();
require_once 'Database.php';

header('Content-Type: application/json');

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['erro' => 'Não autenticado']);
    exit;
}

if (!isset($_GET['id'])) {
    echo json_encode(['erro' => 'ID não fornecido']);
    exit;
}

try {
    $database = new Database();
    $conn = $database->getConnection();

    $id = $_GET['id'];
    
    // Preparar e executar a consulta
    $stmt = $conn->prepare("SELECT * FROM anotacoes_servicos WHERE id = ? AND usuario_id = ?");
    $stmt->bind_param("ii", $id, $_SESSION['usuario_id']);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $anotacao = $result->fetch_assoc();
        echo json_encode([
            'success' => true,
            'data' => $anotacao
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'erro' => 'Anotação não encontrada'
        ]);
    }

    $stmt->close();
    $conn->close();
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'erro' => 'Erro no servidor: ' . $e->getMessage()
    ]);
}
?>