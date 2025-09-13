<?php
session_start();

// Verificar se o usuário está logado, se não, redirecionar
if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header("Location: index.php");
    exit;
}

$nomeUsuario = isset($_SESSION['usuario_nome']) ? $_SESSION['usuario_nome'] : 'Usuário';
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

    <!-- Sidebar com animação (inicialmente fechada) -->
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
            <!-- Veículo 1: Pronto para entrega -->
            <tr>
                <td>
                    <div class="status-container">
                        <span class="status-icon status-available">
                        </span>
                        <span class="status-text">Pronto para entrega</span>
                    </div>
                </td>
                <td>Fiat Uno</td>
                <td>ABC-1234</td>
                <td>João Silva</td>
                <td>10/05/2023</td>
                <td>
                    <div class="action-buttons">
                        <button class="btn-details">
                            <i class="fas fa-eye"></i> Detalhes
                        </button>
                        <button class="btn-edit" onclick="editarVeiculo(1)">
                            <i class="fas fa-edit"></i> Editar
                        </button>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</div>
    </td>
</tr>
        
        <!-- Linha quando não há veículos -->
        <tr class="no-vehicles">
            <td colspan="6">Nenhum veículo cadastrado</td>
        </tr>
    </tbody>
</table>
            </div>
        </section>
    </main>

    <!-- Modal de Detalhes do Veículo -->
    <div class="modal" id="vehicleDetailsModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Detalhes do Veículo</h2>
                <button class="close-modal">&times;</button>
            </div>
            <div class="modal-body" id="vehicleDetails">
                <!-- Conteúdo será preenchido via JavaScript/PHP -->
            </div>
        </div>
    </div>

<script src="index.js"></script>

</body>
</html>