<?php
// TRATAMENTO DE LOGOUT
session_start();

// Verificar se há solicitação de logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit;
}

// VERIFICAÇÃO CORRIGIDA - SEM REDIRECIONAMENTO EM LOOP
$usuarioLogado = isset($_SESSION['logado']) && $_SESSION['logado'] === true;
$nomeUsuario = $usuarioLogado ? $_SESSION['usuario_nome'] : 'Convidado';
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mecânica Volmar - Sistema de Gestão</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="light-mode <?php echo $usuarioLogado ? 'logged-in' : 'logged-out'; ?>">
    
    <!-- Sistema de Mensagens -->
    <?php
    if (isset($_SESSION['mensagem'])) {
        $tipo = isset($_SESSION['mensagem_tipo']) ? $_SESSION['mensagem_tipo'] : 'info';
        echo '<div class="mensagem-alerta mensagem-' . $tipo . '">';
        echo '<span>' . $_SESSION['mensagem'] . '</span>';
        echo '<button class="fechar-mensagem">&times;</button>';
        echo '</div>';
        
        // Limpa a mensagem após exibir
        unset($_SESSION['mensagem']);
        unset($_SESSION['mensagem_tipo']);
    }
    ?>

    <!-- Topbar com botões de login e cadastro -->
    <header class="topbar">
        <div class="topbar-content">
            <div class="left-section">
                <button class="sidebar-toggle" id="sidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>
                <h1 class="logo"><span class="logo-text">Mecânica</span> <span class="logo-highlight">Volmar</span></h1>
            </div>
            <div class="auth-buttons">
                <button class="btn-login" id="btnLogin">Login</button>
                <button class="btn-register" id="btnRegister">Cadastro</button>
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
            
            <div class="register-message" style="<?php echo $usuarioLogado ? 'display: none;' : ''; ?>">
                <i class="fas fa-info-circle"></i>
                <p>Você deve realizar seu cadastro antes de poder realizar operações no sistema.</p>
            </div>
            
            <ul class="nav-menu">
                <li class="nav-item">
                    <a href="#">
                        <i class="fas fa-home"></i>
                        <span>Início</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="veiculos.php" class="<?php echo $usuarioLogado ? '' : 'disabled-link'; ?>">                        <i class="fas fa-car"></i>
                        <span>Veículos</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="servicos.php">
                        <i class="fas fa-tools"></i>
                        <span>Serviços</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="clientes.php" class="<?php echo $usuarioLogado ? '' : 'disabled-link'; ?>">                        <i class="fas fa-users"></i>
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

            <li class="nav-item logout-item" id="logoutItem" style="display: none;">
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
        <section class="welcome-section">
            <h2>Bem-vindo à <span class="logo-highlight">Mecânica Volmar</span></h2>
            <p>Sistema de gestão para oficina mecânica</p>
            
            <div class="features">
                <div class="feature-card">
                    <a href="veiculos.php" class="feature-link <?php echo $usuarioLogado ? '' : 'disabled-feature'; ?>" data-feature="veiculos">                        <div class="feature-icon">
                            <i class="fas fa-car-side"></i>
                        </div>
                        <h3>Controle de Veículos</h3>
                        <p>Gerencie todos os veículos em manutenção</p>
                    </a>
                </div>
                
                <div class="feature-card">
                    <a href="clientes.php" class="feature-link <?php echo $usuarioLogado ? '' : 'disabled-feature'; ?>" data-feature="clientes">                        <div class="feature-icon">
                            <i class="fas fa-user-friends"></i>
                        </div>
                        <h3>Gestão de Clientes</h3>
                        <p>Cadastro e histórico de clientes</p>
                    </a>
                </div>
                
                <div class="feature-card">
                    <a href="servicos.php" class="feature-link" data-feature="servicos">
                        <div class="feature-icon">
                            <i class="fas fa-tools"></i>
                        </div>
                        <h3>Controle de Serviços</h3>
                        <p>Gerencie todos os serviços oferecidos</p>
                    </a>
                </div>
            </div>
        </section>
    </main>

    <!-- Modal de Login -->
    <div class="modal" id="loginModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Login</h2>
                <button class="close-modal">&times;</button>
            </div>
            <form id="loginForm" action="processa_login.php" method="POST">
                <div class="form-group">
                    <label for="loginEmail">E-mail</label>
                    <input type="email" id="loginEmail" name="email" required>
                </div>
                <div class="form-group">
                    <label for="loginPassword">Senha</label>
                    <input type="password" id="loginPassword" name="senha" required>
                </div>
                <button type="submit" class="form-submit">Entrar</button>
            </form>
        </div>
    </div>

    <!-- Modal de Cadastro -->
    <div class="modal" id="registerModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Cadastro</h2>
                <button class="close-modal">&times;</button>
            </div>
            <form id="registerForm" action="processa_cadastro.php" method="POST">
                <div class="form-group">
                    <label for="registerName">Nome de usuário</label>
                    <input type="text" id="registerName" name="usuario_nome" required>
                </div>
                <div class="form-group">
                    <label for="registerEmail">E-mail</label>
                    <input type="email" id="registerEmail" name="email" required>
                </div>
                <div class="form-group">
                    <label for="registerPassword">Senha</label>
                    <input type="password" id="registerPassword" name="senha" required>
                </div>
                <div class="form-group">
                    <label for="registerConfirmPassword">Confirmar Senha</label>
                    <input type="password" id="registerConfirmPassword" name="confirmar_senha" required>
                </div>
                <button type="submit" class="form-submit">Criar Conta</button>
            </form>
        </div> 
    </div>

<script src="index.js"></script>

</div>
</body>
</html>