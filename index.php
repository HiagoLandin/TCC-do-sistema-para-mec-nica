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
<body class="light-mode logged-out">
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
                <li class="nav-item" id="logoutItem" style="display: none;">
                    <a href="#" id="btnLogout">
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
                    <a href="#" class="feature-link" data-feature="veiculos">
                        <div class="feature-icon">
                            <i class="fas fa-car-side"></i>
                        </div>
                        <h3>Controle de Veículos</h3>
                        <p>Gerencie todos os veículos em manutenção</p>
                    </a>
                </div>
                
                <div class="feature-card">
                    <a href="#" class="feature-link" data-feature="clientes">
                        <div class="feature-icon">
                            <i class="fas fa-user-friends"></i>
                        </div>
                        <h3>Gestão de Clientes</h3>
                        <p>Cadastro e histórico de clientes</p>
                    </a>
                </div>
                
                <div class="feature-card">
                    <a href="#" class="feature-link" data-feature="servicos">
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
            <form id="loginForm">
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
            <form id="registerForm">
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
        // Estado de autenticação do usuário
        let isLoggedIn = false;
        let currentUserName = "Convidado";

        // Elementos da interface
        const body = document.body;
        const userNameElement = document.querySelector('.user-name');
        const registerMessage = document.querySelector('.register-message');
        const logoutItem = document.getElementById('btnLogout');
        const loginForm = document.getElementById('loginForm');
        const registerForm = document.getElementById('registerForm');

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
        
        // Processar login
        loginForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const email = document.getElementById('loginEmail').value;
            const password = document.getElementById('loginPassword').value;
            
            // Simulação de login bem-sucedido
            if (email && password) {
                isLoggedIn = true;
                currentUserName = email.split('@')[0]; // Pega a parte antes do @ como nome
                updateUIAfterLogin();
                loginModal.style.display = 'none';
                alert('Login realizado com sucesso!');
            }
        });
        
        // Processar cadastro
        registerForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const name = document.getElementById('registerName').value;
            const email = document.getElementById('registerEmail').value;
            const password = document.getElementById('registerPassword').value;
            const confirmPassword = document.getElementById('registerConfirmPassword').value;
            
            // Validação básica
            if (password !== confirmPassword) {
                alert('As senhas não coincidem!');
                return;
            }
            
            if (name && email && password) {
                isLoggedIn = true;
                currentUserName = name;
                updateUIAfterLogin();
                registerModal.style.display = 'none';
                alert('Cadastro realizado com sucesso! Você está logado.');
            }
        });
        
        // Processar logout
        logoutItem.addEventListener('click', (e) => {
            e.preventDefault();
            isLoggedIn = false;
            currentUserName = "Convidado";
            updateUIAfterLogout();
            alert('Logout realizado com sucesso!');
        });
        
        // Atualizar a UI após o login
        function updateUIAfterLogin() {
            body.classList.remove('logged-out');
            body.classList.add('logged-in');
            userNameElement.textContent = currentUserName;
            registerMessage.style.display = 'none';
            logoutItem.parentElement.style.display = 'block';
            
            // Atualizar botões de autenticação
            document.querySelector('.auth-buttons').innerHTML = `
                <span style="color: #4dabf7; margin-right: 15px;">Olá, ${currentUserName}</span>
                <button class="btn-logout" id="btnLogoutHeader">Sair</button>
            `;
            
            // Adicionar evento de logout ao botão no header
            document.getElementById('btnLogoutHeader').addEventListener('click', (e) => {
                e.preventDefault();
                isLoggedIn = false;
                currentUserName = "Convidado";
                updateUIAfterLogout();
                alert('Logout realizado com sucesso!');
            });
        }
        
        // Atualizar a UI após o logout
        function updateUIAfterLogout() {
            body.classList.remove('logged-in');
            body.classList.add('logged-out');
            userNameElement.textContent = currentUserName;
            registerMessage.style.display = 'block';
            logoutItem.parentElement.style.display = 'none';
            
            // Restaurar botões de autenticação
            document.querySelector('.auth-buttons').innerHTML = `
                <button class="btn-login" id="btnLogin">Login</button>
                <button class="btn-register" id="btnRegister">Cadastro</button>
            `;
            
            // Reatribuir eventos aos botões
            document.getElementById('btnLogin').addEventListener('click', () => {
                loginModal.style.display = 'flex';
            });
            
            document.getElementById('btnRegister').addEventListener('click', () => {
                registerModal.style.display = 'flex';
            });
        }
        
        // Controle do modo escuro
        const themeToggle = document.getElementById('themeToggle');
        
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
        
        // Tornar os cards do conteúdo principal clicáveis
        document.querySelectorAll('.feature-link').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                
                if (!isLoggedIn) {
                    alert('Por favor, faça login para acessar esta funcionalidade.');
                    loginModal.style.display = 'flex';
                    return;
                }
                
                const feature = this.getAttribute('data-feature');
                alert(`Acessando a funcionalidade: ${feature.charAt(0).toUpperCase() + feature.slice(1)}`);
                // Aqui você pode redirecionar para a página específica ou carregar o conteúdo
            });
        });

        // Adicionar eventos de clique para os itens do menu lateral
        document.querySelectorAll('.nav-item a').forEach(item => {
            item.addEventListener('click', function(e) {
                if (this.parentElement.classList.contains('theme-toggle-item')) {
                    return; // Não impedir o toggle do tema
                }
                
                e.preventDefault();
                
                if (!isLoggedIn && !this.parentElement.classList.contains('theme-toggle-item')) {
                    alert('Por favor, faça login para acessar esta funcionalidade.');
                    loginModal.style.display = 'flex';
                    return;
                }
                
                const menuText = this.querySelector('span').textContent;
                alert(`Acessando: ${menuText}`);
                // Aqui você pode redirecionar para a página específica ou carregar o conteúdo
            });
        });
    </script>
<!-- Adicione isso temporariamente em algum lugar do index.php para teste -->
<div style="position: fixed; bottom: 20px; right: 20px; z-index: 1000;">
    <a href="veiculos.php" style="background: #0d6efd; color: white; padding: 10px; border-radius: 5px; text-decoration: none;">
        Link Teste - Veículos
    </a>
</div>
</body>
</html>