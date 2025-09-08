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
                <button class="btn-back" onclick="window.history.back()">
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
                <span class="user-name">Usuário</span>
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
                    <a href="#">
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
        <section class="clients-section">
            <div class="section-header">
                <h2>Gestão de Clientes</h2>
                <button class="btn-add" id="btnAddClient">
                    <i class="fas fa-plus"></i> Novo Cliente
                </button>
            </div>
            
            <!-- Tabela de clientes -->
            <div class="table-container">
                <table class="clients-table">
                    <thead>
                        <tr>
                            <th>Nome Completo</th>
                            <th>Telefone</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Cliente exemplo -->
                        <tr>
                            <td>João Silva</td>
                            <td>(11) 99999-9999</td>
                            <td>joao@email.com</td>
                            <td>
                                <span class="status-badge status-regular">Regular</span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn-edit">
                                        <i class="fas fa-edit"></i> Editar
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <!-- Scripts -->
    <script src="index.js"></script>
</body>
</html>