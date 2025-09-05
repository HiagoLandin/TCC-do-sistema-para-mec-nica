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
<body class="light-mode">
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
                <span class="user-name">Convidado</span>
            </div>
            
            <div class="register-message">
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
        <section class="welcome-section">
            <h2>Bem-vindo à <span class="logo-highlight">Mecânica Volmar</span></h2>
            <p>Sistema de gestão para oficina mecânica</p>
            
            <div class="features">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-car-side"></i>
                    </div>
                    <h3>Controle de Veículos</h3>
                    <p>Gerencie todos os veículos em manutenção</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-user-friends"></i>
                    </div>
                    <h3>Gestão de Clientes</h3>
                    <p>Cadastro e histórico de clientes</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-tools"></i>
                    </div>
                    <h3>Controle de Serviços</h3>
                    <p>Gerencie todos os serviços oferecidos</p>
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
            <form>
                <div class="form-group">
                    <label for="loginEmail">E-mail</label>
                    <input type="email" id="loginEmail" required>
                </div>
                <div class="form-group">
                    <label for="loginPassword">Senha</label>
                    <input type="password" id="loginPassword" required>
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
            <form>
                <div class="form-group">
                    <label for="registerName">Nome Completo</label>
                    <input type="text" id="registerName" required>
                </div>
                <div class="form-group">
                    <label for="registerEmail">E-mail</label>
                    <input type="email" id="registerEmail" required>
                </div>
                <div class="form-group">
                    <label for="registerPassword">Senha</label>
                    <input type="password" id="registerPassword" required>
                </div>
                <div class="form-group">
                    <label for="registerConfirmPassword">Confirmar Senha</label>
                    <input type="password" id="registerConfirmPassword" required>
                </div>
                <button type="submit" class="form-submit">Criar Conta</button>
            </form>
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
        
        // Controle dos modais
        const loginModal = document.getElementById('loginModal');
        const registerModal = document.getElementById('registerModal');
        const btnLogin = document.getElementById('btnLogin');
        const btnRegister = document.getElementById('btnRegister');
        const closeButtons = document.querySelectorAll('.close-modal');
        
        btnLogin.addEventListener('click', () => {
            loginModal.style.display = 'flex';
        });
        
        btnRegister.addEventListener('click', () => {
            registerModal.style.display = 'flex';
        });
        
        closeButtons.forEach(button => {
            button.addEventListener('click', () => {
                loginModal.style.display = 'none';
                registerModal.style.display = 'none';
            });
        });
        
        // Fechar modal ao clicar fora dele
        window.addEventListener('click', (e) => {
            if (e.target === loginModal) {
                loginModal.style.display = 'none';
            }
            if (e.target === registerModal) {
                registerModal.style.display = 'none';
            }
        });
        
        // Prevenir envio dos formulários (apenas para demonstração)
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', (e) => {
                e.preventDefault();
                alert('Formulário enviado com sucesso!');
                loginModal.style.display = 'none';
                registerModal.style.display = 'none';
            });
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
    </script>
</body>
</html>
