<?php
session_start();

// Verificar se o usuário está logado, se não, redirecionar
if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header("Location: index.php");
    exit;
}

$nomeUsuario = isset($_SESSION['usuario_nome']) ? $_SESSION['usuario_nome'] : 'Usuário';

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
        }
    }
}

// Buscar clientes do banco de dados
$clientes = [];
$result = $conn->query("SELECT * FROM clientes ORDER BY data_cadastro DESC");
if ($result) {
    $clientes = $result->fetch_all(MYSQLI_ASSOC);
}

// Buscar dados de um cliente específico para edição
$cliente_editar = null;
if (isset($_GET['editar'])) {
    $id_editar = $_GET['editar'];
    $stmt = $conn->prepare("SELECT * FROM clientes WHERE id = ?");
    $stmt->bind_param("i", $id_editar);
    $stmt->execute();
    $result = $stmt->get_result();
    $cliente_editar = $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestão de Clientes - Mecânica Volmar</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="clientes.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="light-mode logged-in">
    <!-- Topbar com botões de voltar -->
    <header class="topbar">
        <div class="topbar-content">
            <div class="left-section">
                <button class="sidebar-toggle" id="sidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>
                <h1 class="logo"><span class="logo-text">Mecânica</span> <span class="logo-highlight">Volmar</span></h1>
            </div>
            <div class="auth-buttons">
                <button class="btn-back" id="btnBack">
                    <i class="fas fa-arrow-left"></i> Voltar
                </button>
            </div>
        </div>
    </header>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <nav class="sidebar-nav">
            <div class="user-info">
                <div class="user-avatar">
                    <i class="fas fa-user-circle"></i>
                </div>
                <span class="user-name"><?php echo htmlspecialchars($nomeUsuario); ?></span>
            </div>
            
            <ul class="nav-menu">
                <li class="nav-item">
                    <a href="index.php">
                        <i class="fas fa-home"></i>
                        <span>Início</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="veiculos.php">
                        <i class="fas fa-car"></i>
                        <span>Veículos</span>
                    </a>
                </li>
                <li class="nav-item active">
                    <a href="servicos.php">
                        <i class="fas fa-tools"></i>
                        <span>Serviços</span>
                    </a>
                </li>
                <li class="nav-item active">
                    <a href="clientes.php">
                        <i class="fas fa-users"></i>
                        <span>Clientes</span>
                    </a>
                </li>
                <li class="nav-item theme-toggle-item">
                    <a href="#" id="themeToggle">
                        <i class="fas fa-moon"></i>
                        <span>Modo Escuro</span>
                    </a>
                </li>

                <li class="nav-divider"></li>
                <li class="nav-item logout-item">
                    <a href="index.php?logout=true" id="btnLogout">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Sair</span>
                    </a>
                </li>
            </ul>
        </nav>
    </aside>

    <!-- Conteúdo principal -->
    <main class="main-content" id="mainContent">
        <section class="clients-section">
            <div class="section-header">
                <h2>Gestão de Clientes</h2>
                <button class="btn-add" id="btnAddClient">
                    <i class="fas fa-plus"></i> Novo Cliente
                </button>
            </div>
            
            <!-- Mensagens de sucesso/erro -->
            <?php if (isset($_GET['sucesso']) && $_GET['sucesso'] == 1): ?>
                <div class="alert alert-success">
                    Cliente adicionado com sucesso!
                </div>
            <?php endif; ?>
            
            <?php if (isset($_GET['editado']) && $_GET['editado'] == 1): ?>
                <div class="alert alert-success">
                    Cliente editado com sucesso!
                </div>
            <?php endif; ?>
            
            <?php if (isset($_GET['removido']) && $_GET['removido'] == 1): ?>
                <div class="alert alert-success">
                    Cliente removido com sucesso!
                </div>
            <?php endif; ?>
            
            <!-- Tabela de clientes -->
            <div class="table-container">
                <table class="clients-table">
                    <thead>
                        <tr>
                            <th>Nome Completo</th>
                            <th>Telefone</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Data Cadastro</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($clientes) > 0): ?>
                            <?php foreach ($clientes as $cliente): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($cliente['nome']); ?></td>
                                    <td><?php echo htmlspecialchars($cliente['telefone']); ?></td>
                                    <td><?php echo htmlspecialchars($cliente['email']); ?></td>
                                    <td>
                                        <span class="status-badge status-<?php echo strtolower($cliente['status']); ?>">
                                            <?php echo $cliente['status']; ?>
                                        </span>
                                    </td>
                                    <td><?php echo date('d/m/Y', strtotime($cliente['data_cadastro'])); ?></td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="btn-edit" onclick="editarCliente(<?php echo $cliente['id']; ?>)">
                                                <i class="fas fa-edit"></i> Editar
                                            </button>
                                            <button class="btn-delete" onclick="confirmarRemocaoCliente(<?php echo $cliente['id']; ?>, '<?php echo htmlspecialchars(addslashes($cliente['nome'])); ?>')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6">Nenhum cliente cadastrado</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <!-- Modal de Adicionar Cliente -->
    <div class="modal-overlay" id="addClientModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Adicionar Novo Cliente</h2>
                <button class="close-modal" id="closeAddModal">&times;</button>
            </div>
            <div class="modal-body">
                <form method="POST" action="clientes.php" id="addClientForm">
                    <div class="form-group">
                        <label for="nome">Nome Completo</label>
                        <input type="text" id="nome" name="nome" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="telefone">Telefone</label>
                        <input type="tel" id="telefone" name="telefone" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select id="status" name="status" required>
                            <option value="Regular">Regular</option>
                            <option value="VIP">VIP</option>
                            <option value="Inativo">Inativo</option>
                        </select>
                    </div>
                    
                    <div class="form-actions">
                        <button type="button" class="btn-cancel" id="cancelAddClient">Cancelar</button>
                        <button type="submit" name="adicionar_cliente" class="btn-submit">Adicionar Cliente</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal de Editar Cliente -->
    <div class="modal-overlay" id="editClientModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Editar Cliente</h2>
                <button class="close-modal" id="closeEditModal">&times;</button>
            </div>
            <div class="modal-body">
                <form method="POST" action="clientes.php" id="editClientForm">
                    <input type="hidden" name="cliente_id" id="edit_cliente_id">
                    
                    <div class="form-group">
                        <label for="edit_nome">Nome Completo</label>
                        <input type="text" id="edit_nome" name="nome" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="edit_telefone">Telefone</label>
                        <input type="tel" id="edit_telefone" name="telefone" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="edit_email">Email</label>
                        <input type="email" id="edit_email" name="email" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="edit_status">Status</label>
                        <select id="edit_status" name="status" required>
                            <option value="Regular">Regular</option>
                            <option value="VIP">VIP</option>
                            <option value="Inativo">Inativo</option>
                        </select>
                    </div>
                    
                    <div class="form-actions">
                        <button type="button" class="btn-cancel" id="cancelEditClient">Cancelar</button>
                        <button type="submit" name="editar_cliente" class="btn-submit">Salvar Alterações</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal de Confirmação de Remoção -->
    <div class="modal-overlay" id="confirmDeleteModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Confirmar Remoção</h2>
                <button class="close-modal" id="closeDeleteModal">&times;</button>
            </div>
            <div class="modal-body">
                <p id="deleteMessage">Tem certeza que deseja remover este cliente?</p>
                <form method="POST" action="clientes.php" id="deleteForm">
                    <input type="hidden" name="cliente_id" id="clienteId">
                    <div class="form-actions">
                        <button type="button" class="btn-cancel" id="cancelDelete">Cancelar</button>
                        <button type="submit" name="remover_cliente" class="btn-delete-confirm">Remover</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="index.js"></script>
    <script src="clientes.js"></script>
</body>
</html>