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
                <span class="user-name">Usuário</span>
            </div>
            
            <ul class="nav-menu">
                <li class="nav-item">
                    <a href="index.php">
                        <i class="fas fa-home"></i>
                        <span>Início</span>
                    </a>
                </li>
                <li class="nav-item active">
                    <a href="#">
                        <i class="fas fa-car"></i>
                        <span>Veículos</span>
                    </a>
                </li>
                <li class="nav-item">
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

            <!-- Veículo 2: Em manutenção -->
            <tr>
                <td>
                    <div class="status-container">
                        <span class="status-icon status-maintenance">
                        </span>
                        <span class="status-text">Em manutenção</span>
                    </div>
                </td>
                <td>Volkswagen Gol</td>
                <td>DEF-5678</td>
                <td>Maria Santos</td>
                <td>12/05/2023</td>
                <td>
                    <div class="action-buttons">
                        <button class="btn-details">
                            <i class="fas fa-eye"></i> Detalhes
                        </button>
                        <button class="btn-edit" onclick="editarVeiculo(2)">
                            <i class="fas fa-edit"></i> Editar
                        </button>
                    </div>
                </td>
            </tr>

            <!-- Veículo 3: Aguardando peças -->
            <tr>
                <td>
                    <div class="status-container">
                        <span class="status-icon status-waiting">
                        </span>
                        <span class="status-text">Aguardando peças</span>
                    </div>
                </td>
                <td>Chevrolet Onix</td>
                <td>GHI-9012</td>
                <td>Pedro Oliveira</td>
                <td>15/05/2023</td>
                <td>
                    <div class="action-buttons">
                        <button class="btn-details">
                            <i class="fas fa-eye"></i> Detalhes
                        </button>
                        <button class="btn-edit" onclick="editarVeiculo(3)">
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

    <script>
        // Controle da sidebar
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebarToggle');
        
        sidebarToggle.addEventListener('click', () => {
            sidebar.classList.toggle('open');
            
            // Alterar ícone do botão
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
        
        // Fechar sidebar ao clicar fuera dela
        document.addEventListener('click', (e) => {
            if (sidebar.classList.contains('open') && 
                !sidebar.contains(e.target) && 
                !sidebarToggle.contains(e.target)) {
                sidebar.classList.remove('open');
                sidebarToggle.querySelector('i').classList.remove('fa-times');
                sidebarToggle.querySelector('i').classList.add('fa-bars');
                document.body.style.overflow = 'auto';
            }
        });
        
        // Botão Voltar
        document.getElementById('btnBack').addEventListener('click', () => {
            window.history.back();
        });
        
        // Botão Adicionar Veículo
        document.getElementById('btnAddVehicle').addEventListener('click', () => {
            alert('Funcionalidade de adicionar veículo será implementada em breve!');
        });
        
        // Controle do modo escuro
        const themeToggle = document.getElementById('themeToggle');
        const body = document.body;
        
        // Verificar se há preferência salva
        if (localStorage.getItem('theme') === 'dark') {
            body.classList.add('dark-mode');
            body.classList.remove('light-mode');
            updateThemeIcon('dark');
        }
        
        themeToggle.addEventListener('click', (e) => {
            e.preventDefault();
            
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
        
        function updateThemeIcon(theme) {
            const icon = themeToggle.querySelector('i');
            const text = themeToggle.querySelector('span');
            
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
        
        // Modal de detalhes do veículo
        const vehicleModal = document.getElementById('vehicleDetailsModal');
        const closeButtons = document.querySelectorAll('.close-modal');
        
        // Fechar modal
        closeButtons.forEach(button => {
            button.addEventListener('click', () => {
                vehicleModal.style.display = 'none';
            });
        });
        
        // Fechar modal ao clicar fora dele
        window.addEventListener('click', (e) => {
            if (e.target === vehicleModal) {
                vehicleModal.style.display = 'none';
            }
        });
    </script>
</body>
</html>