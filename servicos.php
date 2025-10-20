<?php
// servicos.php
session_start();

// Verificar se usuário está logado
$usuarioLogado = isset($_SESSION['usuario_id']);
$nomeUsuario = $usuarioLogado ? $_SESSION['usuario_nome'] : 'Visitante';
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mecânica Volmar - Sistema de Gestão</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="servicos.css">
</head>
<body class="light-mode <?php echo $usuarioLogado ? 'logged-in' : 'logged-out'; ?>">
    
    <!-- Topbar -->
    <div class="topbar">
        <div class="topbar-content">
            <div class="left-section">
                <button class="sidebar-toggle" onclick="toggleSidebar()">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="logo">
                    <span class="logo-text">Mecânica<span class="logo-highlight">Volmar</span></span>
                </div>
            </div>
            
            <div class="auth-buttons">
                <?php if (!$usuarioLogado): ?>
                    <button class="btn-login" onclick="abrirModalLogin()">Login</button>
                    <button class="btn-register" onclick="abrirModalRegistro()">Registrar</button>
                <?php else: ?>
                    <span class="user-welcome">Olá, <?php echo $nomeUsuario; ?></span>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-nav">
            <div class="user-info">
                <div class="user-avatar">
                    <i class="fas fa-user-circle"></i>
                </div>
                <div class="user-name"><?php echo $nomeUsuario; ?></div>
            </div>

            <?php if (!$usuarioLogado): ?>
            <div class="register-message">
                <i class="fas fa-info-circle"></i>
                <p>Faça login para acessar todas as funcionalidades</p>
            </div>
            <?php endif; ?>

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
                <li class="nav-item">
                    <a href="clientes.php" class="<?php echo !$usuarioLogado ? 'disabled-link' : ''; ?>">
                        <i class="fas fa-users"></i>
                        <span>Clientes</span>
                    </a>
                </li>
                
                <li class="nav-item theme-toggle-item">
                    <a href="#" onclick="toggleTheme()">
                        <i class="fas fa-moon"></i>
                        <span>Modo Escuro</span>
                    </a>
                </li>
                
                <?php if ($usuarioLogado): ?>
                <li class="nav-item logout-item">
                    <a href="logout.php">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Sair</span>
                    </a>
                </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>

    <!-- Conteúdo Principal -->
    <div class="main-content" id="mainContent">
        <div class="container-fluid">
            <!-- Cabeçalho -->
            <div class="row mb-4">
                <div class="col-12">
                    <h1 class="text-center my-4">Agenda de Serviços</h1>
                    
                    <!-- Navegação da Semana -->
                    <div class="week-navigation d-flex justify-content-between align-items-center mb-4">
                        <button class="btn-week-nav" onclick="mudarSemana(-1)">
                            <i class="fas fa-chevron-left"></i> Semana Anterior
                        </button>
                        
                        <div class="current-week text-center">
                            <h3 class="mb-0" id="semanaAtual">Carregando...</h3>
                        </div>
                        
                        <button class="btn-week-nav" onclick="mudarSemana(1)">
                            Próxima Semana <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Grade da Semana -->
            <div class="row">
                <?php foreach (['Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado', 'Domingo'] as $dia): ?>
                <div class="col-lg col-md-4 col-sm-6 mb-3">
                    <div class="calendar-day <?= in_array($dia, ['Sábado', 'Domingo']) ? 'weekend' : '' ?>">
                        <div class="day-header">
                            <h5><?= $dia ?></h5>
                            <small class="text-white day-date" data-dia="<?= $dia ?>">--/--</small>
                        </div>
                        
                        <div class="services-list" id="servicos-<?= $dia ?>">
                            <div class="no-services text-white text-center py-3">
                                Nenhum serviço agendado
                            </div>
                        </div>
                        
                        <!-- Botão Adicionar Serviço -->
                        <div class="add-service-btn">
                            <button class="btn-add-service" onclick="abrirModalAdicionarServico('<?= $dia ?>')">
                                <i class="fas fa-plus"></i> Adicionar Serviço
                            </button>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Botão Adicionar Anotação -->
            <div class="row mt-4">
                <div class="col-12 text-center">
                    <button class="btn-add" onclick="abrirModalAdicionarAnotacao()">
                        <i class="fas fa-plus"></i> Adicionar Nova Anotação
                    </button>
                </div>
            </div>

            <!-- Histórico de Anotações -->
            <div class="row mt-5">
                <div class="col-12">
                    <div class="history-section">
                        <h3 class="mb-4">Histórico de Anotações</h3>
                        
                        <div class="history-container" id="historicoAnotacoes">
                            <div class="text-muted text-center py-3">
                                Nenhuma anotação no histórico
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Adicionar/Editar Serviço -->
    <div id="modalServico" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="tituloModalServico">Adicionar Serviço</h2>
                <button class="close-modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="formServico">
                    <input type="hidden" id="servicoId">
                    <input type="hidden" id="servicoDia">
                    
                    <div class="form-group">
                        <label for="descricaoServico">Descrição do Serviço *</label>
                        <textarea class="form-control" id="descricaoServico" rows="4" placeholder="Descreva o serviço a ser realizado..." required></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="observacoesServico">Observações (Opcional)</label>
                        <textarea class="form-control" id="observacoesServico" rows="3" placeholder="Adicione observações relevantes..."></textarea>
                    </div>
                    
                    <div class="form-actions">
                        <button type="button" class="btn-cancel" onclick="fecharModalServico()">Cancelar</button>
                        <button type="button" class="btn-submit" onclick="salvarServico()">Salvar Serviço</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Adicionar Anotação -->
    <div id="modalAdicionarAnotacao" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Adicionar Anotação</h2>
                <button class="close-modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="formAdicionarAnotacao">
                    <div class="form-group">
                        <label for="dataAnotacao">Data da Anotação</label>
                        <input type="date" class="form-control" id="dataAnotacao">
                    </div>
                    
                    <div class="form-group">
                        <label for="observacoesAnotacao">Observações *</label>
                        <textarea class="form-control" id="observacoesAnotacao" rows="5" placeholder="Digite suas observações..." required></textarea>
                    </div>
                    
                    <div class="form-actions">
                        <button type="button" class="btn-cancel" onclick="fecharModalAdicionarAnotacao()">Cancelar</button>
                        <button type="button" class="btn-submit" onclick="adicionarAnotacao()">Adicionar Anotação</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modais de Login/Registro -->
    <div id="modalLogin" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Login</h2>
                <button class="close-modal" onclick="fecharModalLogin()">&times;</button>
            </div>
            <div class="modal-body">
                <form id="formLogin">
                    <div class="form-group">
                        <label for="loginEmail">Email</label>
                        <input type="email" id="loginEmail" required>
                    </div>
                    <div class="form-group">
                        <label for="loginSenha">Senha</label>
                        <input type="password" id="loginSenha" required>
                    </div>
                    <button type="submit" class="form-submit">Entrar</button>
                </form>
            </div>
        </div>
    </div>

    <div id="modalRegistro" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Registrar</h2>
                <button class="close-modal" onclick="fecharModalRegistro()">&times;</button>
            </div>
            <div class="modal-body">
                <form id="formRegistro">
                    <div class="form-group">
                        <label for="regNome">Nome Completo</label>
                        <input type="text" id="regNome" required>
                    </div>
                    <div class="form-group">
                        <label for="regEmail">Email</label>
                        <input type="email" id="regEmail" required>
                    </div>
                    <div class="form-group">
                        <label for="regSenha">Senha</label>
                        <input type="password" id="regSenha" required>
                    </div>
                    <div class="form-group">
                        <label for="regConfirmarSenha">Confirmar Senha</label>
                        <input type="password" id="regConfirmarSenha" required>
                    </div>
                    <button type="submit" class="form-submit">Registrar</button>
                </form>
            </div>
        </div>
    </div>

    <script src="index.js"></script>
    <script src="servicos.js"></script>
</body>
</html>