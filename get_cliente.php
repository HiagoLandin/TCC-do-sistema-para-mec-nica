<?php
session_start();
require_once 'Database.php';

header('Content-Type: application/json');

if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    echo json_encode(['erro' => 'Não autenticado']);
    exit;
}

if (!isset($_GET['id'])) {
    echo json_encode(['erro' => 'ID não fornecido']);
    exit;
}

$database = new Database();
$conn = $database->getConnection();

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM clientes WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $cliente = $result->fetch_assoc();
    echo json_encode($cliente);
} else {
    echo json_encode(['erro' => 'Cliente não encontrado']);
}

$stmt->close();
$conn->close();
?>