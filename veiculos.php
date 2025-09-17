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

// Processar formulário de adição de veículo
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['adicionar_veiculo'])) {
        $modelo = $_POST['modelo'];
        $placa = $_POST['placa'];
        $cliente = $_POST['cliente'];
        $data_entrada = $_POST['data_entrada'];
        $status = $_POST['status'];
        $descricao = $_POST['descricao'];
        
        $stmt = $conn->prepare("INSERT INTO veiculos (modelo, placa, cliente, data_entrada, status, descricao) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $modelo, $placa, $cliente, $data_entrada, $status, $descricao);
        
        if ($stmt->execute()) {
            header("Location: veiculos.php?sucesso=1");
            exit;
        } else {
            $mensagem_erro = "Erro ao adicionar veículo: " . $conn->error;
        }
    }
    
    // Processar edição de veículo
    if (isset($_POST['editar_veiculo'])) {
        $id = $_POST['veiculo_id'];
        $modelo = $_POST['modelo'];
        $placa = $_POST['placa'];
        $cliente = $_POST['cliente'];
        $data_entrada = $_POST['data_entrada'];
        $status = $_POST['status'];
        $descricao = $_POST['descricao'];
        
        $stmt = $conn->prepare("UPDATE veiculos SET modelo=?, placa=?, cliente=?, data_entrada=?, status=?, descricao=? WHERE id=?");
        $stmt->bind_param("ssssssi", $modelo, $placa, $cliente, $data_entrada, $status, $descricao, $id);
        
        if ($stmt->execute()) {
            header("Location: veiculos.php?editado=1");
            exit;
        } else {
            $mensagem_erro = "Erro ao editar veículo: " . $conn->error;
        }
    }
    
    // Processar remoção de veículo
    if (isset($_POST['remover_veiculo'])) {
        $id = $_POST['veiculo_id'];
        
        $stmt = $conn->prepare("DELETE FROM veiculos WHERE id = ?");
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            header("Location: veiculos.php?removido=1");
            exit;
        } else {
            $mensagem_erro = "Erro ao remover veículo: " . $conn->error;
        }
    }
}

// Buscar veículos do banco de dados
$veiculos = [];
$result = $conn->query("SELECT * FROM veiculos ORDER BY data_entrada DESC");
if ($result) {
    $veiculos = $result->fetch_all(MYSQLI_ASSOC);
}

// Buscar dados de um veículo específico para edição
$veiculo_editar = null;
if (isset($_GET['editar'])) {
    $id_editar = $_GET['editar'];
    $stmt = $conn->prepare("SELECT * FROM veiculos WHERE id = ?");
    $stmt->bind_param("i", $id_editar);
    $stmt->execute();
    $result = $stmt->get_result();
    $veiculo_editar = $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Controle de Veículos - Mecânica Volmar</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="veiculos.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Estilos específicos para esta página */
        .status-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            margin-right: 8px;
        }
        
        .status-icon i {
            font-size: 0.9rem;
        }
        
        .status-available {
            background-color: rgba(25, 135, 84, 0.15);
            color: #198754;
        }
        
        .status-waiting {
            background-color: rgba(255, 193, 7, 0.15);
            color: #ffc107;
        }
        
        .status-maintenance {
            background-color: rgba(220, 53, 69, 0.15);
            color: #dc3545;
        }
        
        .btn-delete {
            background-color: transparent;
            border: 2px solid #dc3545;
            color: #dc3545;
            padding: 6px 8px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        
        body.dark-mode .btn-delete {
            border-color: #e74c3c;
            color: #e74c3c;
        }
        
        .btn-delete:hover {
            background-color: #dc3545;
            color: white;
        }
        
        body.dark-mode .btn-delete:hover {
            background-color: #e74c3c;
            color: #1a1d21;
        }
        
        .btn-delete-confirm {
            background: linear-gradient(to right, #dc3545, #c82333);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn-delete-confirm:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(220, 53, 69, 0.3);
        }
        
        .btn-details, .btn-edit {
            background-color: transparent;
            border: 2px solid #0d6efd;
            color: #0d6efd;
            padding: 6px 12px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-right: 5px;
        }
        
        .btn-details:hover, .btn-edit:hover {
            background-color: #0d6efd;
            color: white;
        }
        
        .action-buttons {
            display: flex;
            gap: 5px;
        }
        
        textarea {
            width: 100%;
            min-height: 100px;
            padding: 10px;
            border-radius: 6px;
            resize: vertical;
        }
        
        body.light-mode textarea {
            border: 1px solid #ced4da;
            background-color: #f8f9fa;
            color: #212529;
        }
        
        body.dark-mode textarea {
            border: 1px solid #495057;
            background-color: #2b2b2b;
            color: #f8f9fa;
        }
        
        .descricao-container {
            margin-top: 15px;
            padding: 15px;
            border-radius: 8px;
            background-color: rgba(13, 110, 253, 0.05);
        }
        
        body.dark-mode .descricao-container {
            background-color: rgba(13, 110, 253, 0.1);
        }
        
        .descricao-container h3 {
            margin-bottom: 10px;
            color: #0d6efd;
        }
        
        .descricao-texto {
            line-height: 1.6;
        }
    </style>
</head>
<body class="light-mode logged-in">
    <!-- Topbar com botões de voltar e logout -->
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

    <!-- Sidebar com animação (inicialmente fechada) - ATUALIZADA -->
    <aside class="sidebar" id="sidebar">
        <nav class="sidebar-nav">
            <div class="user-info">
                <div class="user-avatar">
                    <i class="fas fa-user-circle"></i>
                </div>
                <span class="user-name"><?php echo htmlspecialchars($nomeUsuario); ?></span>            
            </div>
            
            <div class="register-message" style="display: none;">
                <i class="fas fa-info-circle"></i>
                <p>Você deve realizar seu cadastro antes de poder realizar operações no sistema.</p>
            </div>
            
            <ul class="nav-menu">
                <li class="nav-item">
                    <a href="index.php">
                        <i class="fas fa-home"></i>
                        <span>Início</span>
                    </a>
                </li>
                <li class="nav-item active">
                    <a href="veiculos.php">
                        <i class="fas fa-car"></i>
                        <span>Veículos</span>
                    </a>
                </li>
                <li class="nav-item">
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

                <li class="nav-item logout-item" id="logoutItem" style="display: block;">
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
        <section class="vehicles-section">
            <div class="section-header">
                <h2>Controle de Veículos</h2>
                <button class="btn-add" id="btnAddVehicle">
                    <i class="fas fa-plus"></i> Adicionar Veículo
                </button>
            </div>
            
            <!-- Mensagens de sucesso/erro -->
            <?php if (isset($_GET['sucesso']) && $_GET['sucesso'] == 1): ?>
                <div class="alert alert-success">
                    Veículo adicionado com sucesso!
                </div>
            <?php endif; ?>
            
            <?php if (isset($_GET['editado']) && $_GET['editado'] == 1): ?>
                <div class="alert alert-success">
                    Veículo editado com sucesso!
                </div>
            <?php endif; ?>
            
            <?php if (isset($_GET['removido']) && $_GET['removido'] == 1): ?>
                <div class="alert alert-success">
                    Veículo removido com sucesso!
                </div>
            <?php endif; ?>
            
            <?php if (isset($mensagem_erro)): ?>
                <div class="alert alert-error">
                    <?php echo $mensagem_erro; ?>
                </div>
            <?php endif; ?>
            
            <!-- Estrutura da tabela de veículos -->
            <div class="table-container">
                <table class="vehicles-table">
                    <thead>
                        <tr>
                            <th>Status</th>
                            <th>Modelo</th>
                            <th>Placa</th>
                            <th>Cliente</th>
                            <th>Data de Entrada</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody id="vehiclesTableBody">
                        <?php if (count($veiculos) > 0): ?>
                            <?php foreach ($veiculos as $veiculo): ?>
                                <tr>
                                    <td>
                                        <div class="status-container">
                                            <?php
                                            $status_class = '';
                                            $status_icon = '';
                                            if ($veiculo['status'] == 'Pronto para entrega') {
                                                $status_class = 'status-available';
                                                $status_icon = 'fa-check-circle';
                                            } elseif ($veiculo['status'] == 'Em andamento') {
                                                $status_class = 'status-waiting';
                                                $status_icon = 'fa-tools';
                                            } elseif ($veiculo['status'] == 'Encerrado') {
                                                $status_class = 'status-maintenance';
                                                $status_icon = 'fa-times-circle';
                                            }
                                            ?>
                                            <span class="status-icon <?php echo $status_class; ?>">
                                                <i class="fas <?php echo $status_icon; ?>"></i>
                                            </span>
                                            <span class="status-text"><?php echo $veiculo['status']; ?></span>
                                        </div>
                                    </td>
                                    <td><?php echo htmlspecialchars($veiculo['modelo']); ?></td>
                                    <td><?php echo htmlspecialchars($veiculo['placa']); ?></td>
                                    <td><?php echo htmlspecialchars($veiculo['cliente']); ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($veiculo['data_entrada'])); ?></td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="btn-details" onclick="mostrarDetalhes(<?php echo $veiculo['id']; ?>)">
                                                <i class="fas fa-eye"></i> Detalhes
                                            </button>
                                            <button class="btn-edit" onclick="editarVeiculo(<?php echo $veiculo['id']; ?>)">
                                                <i class="fas fa-edit"></i> Editar
                                            </button>
                                            <button class="btn-delete" onclick="confirmarRemocao(<?php echo $veiculo['id']; ?>, '<?php echo htmlspecialchars(addslashes($veiculo['modelo'])); ?>')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr class="no-vehicles">
                                <td colspan="6">Nenhum veículo cadastrado</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <!-- Modal de Adicionar Veículo -->
    <div class="modal-overlay" id="addVehicleModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Adicionar Novo Veículo</h2>
                <button class="close-modal" id="closeAddModal">&times;</button>
            </div>
            <div class="modal-body">
                <form method="POST" action="veiculos.php" id="addVehicleForm">
                    <div class="form-group">
                        <label for="modelo">Modelo do Veículo</label>
                        <input type="text" id="modelo" name="modelo" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="placa">Placa</label>
                        <input type="text" id="placa" name="placa" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="cliente">Cliente</label>
                        <input type="text" id="cliente" name="cliente" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="data_entrada">Data de Entrada</label>
                        <input type="date" id="data_entrada" name="data_entrada" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select id="status" name="status" required>
                            <option value="Em andamento">Em andamento</option>
                            <option value="Pronto para entrega">Pronto para entrega</option>
                            <option value="Encerrado">Encerrado</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="descricao">Descrição</label>
                        <textarea id="descricao" name="descricao" placeholder="Descreva os problemas do veículo, serviços realizados, etc."></textarea>
                    </div>
                    
                    <div class="form-actions">
                        <button type="button" class="btn-cancel" id="cancelAddVehicle">Cancelar</button>
                        <button type="submit" name="adicionar_veiculo" class="btn-submit">Adicionar Veículo</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal de Editar Veículo -->
    <div class="modal-overlay" id="editVehicleModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Editar Veículo</h2>
                <button class="close-modal" id="closeEditModal">&times;</button>
            </div>
            <div class="modal-body">
                <form method="POST" action="veiculos.php" id="editVehicleForm">
                    <input type="hidden" name="veiculo_id" id="edit_veiculo_id">
                    
                    <div class="form-group">
                        <label for="edit_modelo">Modelo do Veículo</label>
                        <input type="text" id="edit_modelo" name="modelo" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="edit_placa">Placa</label>
                        <input type="text" id="edit_placa" name="placa" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="edit_cliente">Cliente</label>
                        <input type="text" id="edit_cliente" name="cliente" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="edit_data_entrada">Data de Entrada</label>
                        <input type="date" id="edit_data_entrada" name="data_entrada" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="edit_status">Status</label>
                        <select id="edit_status" name="status" required>
                            <option value="Em andamento">Em andamento</option>
                            <option value="Pronto para entrega">Pronto para entrega</option>
                            <option value="Encerrado">Encerrado</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="edit_descricao">Descrição</label>
                        <textarea id="edit_descricao" name="descricao" placeholder="Descreva os problemas do veículo, serviços realizados, etc."></textarea>
                    </div>
                    
                    <div class="form-actions">
                        <button type="button" class="btn-cancel" id="cancelEditVehicle">Cancelar</button>
                        <button type="submit" name="editar_veiculo" class="btn-submit">Salvar Alterações</button>
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
                <p id="deleteMessage">Tem certeza que deseja remover este veículo?</p>
                <form method="POST" action="veiculos.php" id="deleteForm">
                    <input type="hidden" name="veiculo_id" id="veiculoId">
                    <div class="form-actions">
                        <button type="button" class="btn-cancel" id="cancelDelete">Cancelar</button>
                        <button type="submit" name="remover_veiculo" class="btn-delete-confirm">Remover</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal de Detalhes do Veículo -->
    <div class="modal-overlay" id="vehicleDetailsModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Detalhes do Veículo</h2>
                <button class="close-modal" id="closeDetailsModal">&times;</button>
            </div>
            <div class="modal-body" id="vehicleDetails">
                <div class="vehicle-info">
                    <div class="info-row">
                        <span class="info-label">Modelo:</span>
                        <span class="info-value" id="detail-modelo"></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Placa:</span>
                        <span class="info-value" id="detail-placa"></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Cliente:</span>
                        <span class="info-value" id="detail-cliente"></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Data de Entrada:</span>
                        <span class="info-value" id="detail-data-entrada"></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Status:</span>
                        <span class="info-value" id="detail-status"></span>
                    </div>
                    <div class="descricao-container" id="detail-descricao-container">
                        <h3>Descrição</h3>
                        <p class="descricao-texto" id="detail-descricao"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script>
// JavaScript para controlar a sidebar
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');
    
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', () => {
            sidebar.classList.toggle('open');
            
            const icon = sidebarToggle.querySelector('i');
            if (sidebar.classList.contains('open')) {
                icon.classList.remove('fa-bars');
                icon.classList.add('fa-times');
                document.body.style.overflow = 'hidden';
            } else {
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
                document.body.style.overflow = 'auto';
            }
        });
        
        document.addEventListener('click', (e) => {
            if (sidebar.classList.contains('open') && 
                !sidebar.contains(e.target) && 
                !sidebarToggle.contains(e.target)) {
                closeSidebar();
            }
        });
    }
    
    function closeSidebar() {
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebarToggle');
        
        sidebar.classList.remove('open');
        if (sidebarToggle) {
            const icon = sidebarToggle.querySelector('i');
            icon.classList.remove('fa-times');
            icon.classList.add('fa-bars');
        }
        document.body.style.overflow = 'auto';
    }

    // Controle do tema (modo claro/escuro)
    const themeToggle = document.getElementById('themeToggle');
    if (themeToggle) {
        themeToggle.addEventListener('click', (e) => {
            e.preventDefault();
            const body = document.body;
            
            if (body.classList.contains('light-mode')) {
                body.classList.replace('light-mode', 'dark-mode');
                localStorage.setItem('theme', 'dark');
                updateThemeIcon('dark');
            } else {
                body.classList.replace('dark-mode', 'light-mode');
                localStorage.setItem('theme', 'light');
                updateThemeIcon('light');
            }
        });
        
        // Verificar preferência salva
        if (localStorage.getItem('theme') === 'dark') {
            document.body.classList.add('dark-mode');
            document.body.classList.remove('light-mode');
            updateThemeIcon('dark');
        }
    }
    
    function updateThemeIcon(theme) {
        const icon = document.querySelector('#themeToggle i');
        const text = document.querySelector('#themeToggle span');
        
        if (icon && text) {
            if (theme === 'dark') {
                icon.classList.remove('fa-moon');
                icon.classList.add('fa-sun');
                text.textContent = 'Modo Claro';
            } else {
                icon.classList.remove('fa-sun');
                icon.classList.add('fa-moon');
                text.textContent = 'Modo Escuro';
            }
        }
    }

    // Botão Voltar
    const btnBack = document.getElementById('btnBack');
    if (btnBack) {
        btnBack.addEventListener('click', () => {
            window.history.back();
        });
    }

    // Controle dos modais
    const addVehicleBtn = document.getElementById('btnAddVehicle');
    const addVehicleModal = document.getElementById('addVehicleModal');
    const closeAddModal = document.getElementById('closeAddModal');
    const cancelAddBtn = document.getElementById('cancelAddVehicle');
    
    // Abrir modal de adicionar veículo
    if (addVehicleBtn && addVehicleModal) {
        addVehicleBtn.addEventListener('click', function() {
            addVehicleModal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        });
    }
    
    // Fechar modal
    function closeModal() {
        if (addVehicleModal) addVehicleModal.style.display = 'none';
        if (editVehicleModal) editVehicleModal.style.display = 'none';
        if (vehicleDetailsModal) vehicleDetailsModal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }
    
    if (closeAddModal) {
        closeAddModal.addEventListener('click', closeModal);
    }
    
    if (cancelAddBtn) {
        cancelAddBtn.addEventListener('click', closeModal);
    }
    
    // Fechar modal clicando fora dele
    if (addVehicleModal) {
        addVehicleModal.addEventListener('click', function(e) {
            if (e.target === addVehicleModal) {
                closeModal();
            }
        });
    }
    
    // Definir data atual como padrão
    const dataEntrada = document.getElementById('data_entrada');
    if (dataEntrada) {
        const today = new Date();
        const formattedDate = today.toISOString().split('T')[0];
        dataEntrada.value = formattedDate;
    }
    
    // Configurar modal de confirmação de remoção
    const confirmDeleteModal = document.getElementById('confirmDeleteModal');
    const closeDeleteModal = document.getElementById('closeDeleteModal');
    const cancelDelete = document.getElementById('cancelDelete');
    
    function closeDeleteModalFunc() {
        if (confirmDeleteModal) {
            confirmDeleteModal.style.display = 'none';
        }
        document.body.style.overflow = 'auto';
    }
    
    if (closeDeleteModal) {
        closeDeleteModal.addEventListener('click', closeDeleteModalFunc);
    }
    
    if (cancelDelete) {
        cancelDelete.addEventListener('click', closeDeleteModalFunc);
    }
    
    if (confirmDeleteModal) {
        confirmDeleteModal.addEventListener('click', function(e) {
            if (e.target === confirmDeleteModal) {
                closeDeleteModalFunc();
            }
        });
    }
    
    // Configurar modal de edição
    const editVehicleModal = document.getElementById('editVehicleModal');
    const closeEditModal = document.getElementById('closeEditModal');
    const cancelEditBtn = document.getElementById('cancelEditVehicle');
    
    if (closeEditModal) {
        closeEditModal.addEventListener('click', closeModal);
    }
    
    if (cancelEditBtn) {
        cancelEditBtn.addEventListener('click', closeModal);
    }
    
    if (editVehicleModal) {
        editVehicleModal.addEventListener('click', function(e) {
            if (e.target === editVehicleModal) {
                closeModal();
            }
        });
    }
    
    // Configurar modal de detalhes
    const vehicleDetailsModal = document.getElementById('vehicleDetailsModal');
    const closeDetailsModal = document.getElementById('closeDetailsModal');
    
    if (closeDetailsModal) {
        closeDetailsModal.addEventListener('click', closeModal);
    }
    
    if (vehicleDetailsModal) {
        vehicleDetailsModal.addEventListener('click', function(e) {
            if (e.target === vehicleDetailsModal) {
                closeModal();
            }
        });
    }
    
    // Verificar se há parâmetro de edição na URL
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('editar')) {
        editarVeiculo(urlParams.get('editar'));
    }
});

// Função para confirmar remoção de veículo
function confirmarRemocao(id, modelo) {
    const deleteModal = document.getElementById('confirmDeleteModal');
    const deleteMessage = document.getElementById('deleteMessage');
    const veiculoId = document.getElementById('veiculoId');
    
    if (deleteModal && deleteMessage && veiculoId) {
        deleteMessage.textContent = `Tem certeza que deseja remover o veículo "${modelo}"? Esta ação não pode ser desfeita.`;
        veiculoId.value = id;
        
        deleteModal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
}

// Função para editar veículo
function editarVeiculo(id) {
    // Fazer uma requisição AJAX para buscar os dados do veículo
    fetch(`obter_veiculo.php?id=${id}`)
        .then(response => response.json())
        .then(veiculo => {
            // Preencher o formulário de edição com os dados do veículo
            document.getElementById('edit_veiculo_id').value = veiculo.id;
            document.getElementById('edit_modelo').value = veiculo.modelo;
            document.getElementById('edit_placa').value = veiculo.placa;
            document.getElementById('edit_cliente').value = veiculo.cliente;
            document.getElementById('edit_data_entrada').value = veiculo.data_entrada;
            document.getElementById('edit_status').value = veiculo.status;
            document.getElementById('edit_descricao').value = veiculo.descricao || '';
            
            // Exibir o modal de edição
            document.getElementById('editVehicleModal').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        })
        .catch(error => {
            console.error('Erro ao buscar dados do veículo:', error);
            alert('Erro ao carregar dados do veículo. Tente novamente.');
        });
}

// Função para mostrar detalhes do veículo
function mostrarDetalhes(id) {
    // Fazer uma requisição AJAX para buscar os dados do veículo
    fetch(`obter_veiculo.php?id=${id}`)
        .then(response => response.json())
        .then(veiculo => {
            // Preencher os detalhes do veículo
            document.getElementById('detail-modelo').textContent = veiculo.modelo;
            document.getElementById('detail-placa').textContent = veiculo.placa;
            document.getElementById('detail-cliente').textContent = veiculo.cliente;
            document.getElementById('detail-data-entrada').textContent = new Date(veiculo.data_entrada).toLocaleDateString('pt-BR');
            document.getElementById('detail-status').textContent = veiculo.status;
            
            // Preencher a descrição (se existir)
            const descricaoContainer = document.getElementById('detail-descricao-container');
            const descricaoElement = document.getElementById('detail-descricao');
            
            if (veiculo.descricao) {
                descricaoElement.textContent = veiculo.descricao;
                descricaoContainer.style.display = 'block';
            } else {
                descricaoContainer.style.display = 'none';
            }
            
            // Exibir o modal de detalhes
            document.getElementById('vehicleDetailsModal').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        })
        .catch(error => {
            console.error('Erro ao buscar dados do veículo:', error);
            alert('Erro ao carregar dados do veículo. Tente novamente.');
        });
}
</script>

</body>
</html>
