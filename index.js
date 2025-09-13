// Controle da sidebar
function initializeSidebar() {
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');
    
    if (!sidebarToggle) return;
    
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
        sidebarToggle.querySelector('i').classList.remove('fa-times');
        sidebarToggle.querySelector('i').classList.add('fa-bars');
    }
    document.body.style.overflow = 'auto';
}

// Controle dos modais
function initializeModals() {
    const loginModal = document.getElementById('loginModal');
    const registerModal = document.getElementById('registerModal');
    const btnLogin = document.getElementById('btnLogin');
    const btnRegister = document.getElementById('btnRegister');
    const closeButtons = document.querySelectorAll('.close-modal');
    
    if (btnLogin && loginModal) {
        btnLogin.addEventListener('click', () => {
            loginModal.style.display = 'flex';
        });
    }
    
    if (btnRegister && registerModal) {
        btnRegister.addEventListener('click', () => {
            registerModal.style.display = 'flex';
        });
    }
    
    closeButtons.forEach(button => {
        button.addEventListener('click', closeAllModals);
    });
    
    window.addEventListener('click', (e) => {
        if (e.target === loginModal || e.target === registerModal) {
            closeAllModals();
        }
    });
}

function closeAllModals() {
    const loginModal = document.getElementById('loginModal');
    const registerModal = document.getElementById('registerModal');
    
    if (loginModal) loginModal.style.display = 'none';
    if (registerModal) registerModal.style.display = 'none';
}

// Controle do modo escuro
function initializeThemeToggle() {
    const themeToggle = document.getElementById('themeToggle');
    const body = document.body;
    
    if (!themeToggle) return;
    
    // Verificar preferência salva
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
}

function updateThemeIcon(theme) {
    const icon = document.querySelector('#themeToggle i');
    const text = document.querySelector('#themeToggle span');
    
    if (!icon || !text) return;
    
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

// Animações para os cards de features
function initializeFeatureCards() {
    const featureCards = document.querySelectorAll('.feature-card');
    
    featureCards.forEach(card => {
        card.addEventListener('mouseenter', () => {
            card.style.transform = 'translateY(-5px)';
            card.style.transition = 'transform 0.3s ease';
        });
        
        card.addEventListener('mouseleave', () => {
            card.style.transform = 'translateY(0)';
        });
    });
}

// Fechar mensagens de alerta
function initializeMessageSystem() {
    // Fechar ao clicar no botão X
    document.querySelectorAll('.fechar-mensagem').forEach(function(btn) {
        btn.addEventListener('click', function() {
            this.parentElement.style.display = 'none';
        });
    });
    
    // Auto-fechar mensagens após 2 segundos
    setTimeout(function() {
        document.querySelectorAll('.mensagem-alerta').forEach(function(msg) {
            msg.style.opacity = '0';
            msg.style.transition = 'opacity 0.5s ease';
            
            setTimeout(() => {
                msg.style.display = 'none';
            }, 500);
        });
    }, 2000);
}

// Atualizar UI quando usuário está logado
function updateUIForLoggedIn() {
    const registerMessage = document.querySelector('.register-message');
    const logoutItem = document.getElementById('logoutItem');
    const authButtons = document.querySelector('.auth-buttons');
    
    if (registerMessage) registerMessage.style.display = 'none';
    if (logoutItem) logoutItem.style.display = 'block';
    if (authButtons) authButtons.style.display = 'none';
    
    // Remover classes disabled dos links
    document.querySelectorAll('.disabled-link, .disabled-feature').forEach(element => {
        element.classList.remove('disabled-link', 'disabled-feature');
    });
}

// Atualizar UI quando usuário não está logado
function updateUIForLoggedOut() {
    const registerMessage = document.querySelector('.register-message');
    const logoutItem = document.getElementById('logoutItem');
    const authButtons = document.querySelector('.auth-buttons');
    
    if (registerMessage) registerMessage.style.display = 'block';
    if (logoutItem) logoutItem.style.display = 'none';
    if (authButtons) authButtons.style.display = 'flex';
}

// Prevenir clique em links desabilitados
function initializeProtectedLinks() {
    document.addEventListener('click', function(e) {
        const disabledLink = e.target.closest('.disabled-link') || e.target.closest('.disabled-feature');
        
        if (disabledLink) {
            e.preventDefault();
            alert('Você precisa estar logado para acessar esta funcionalidade.');
        }
    });
}

// Botão Voltar
function initializeBackButton() {
    const btnBack = document.getElementById('btnBack');
    if (btnBack) {
        btnBack.addEventListener('click', () => {
            window.history.back();
        });
    }
}

function initializeAddButtons() {
    // Botão adicionar veículo
    const btnAddVehicle = document.getElementById('btnAddVehicle');
    if (btnAddVehicle) {
        btnAddVehicle.addEventListener('click', () => {
            alert('Funcionalidade de adicionar veículo será implementada em breve!');
        });
    }
    
    // Botão adicionar cliente
    const btnAddClient = document.getElementById('btnAddClient');
    if (btnAddClient) {
        btnAddClient.addEventListener('click', () => {
            alert('Funcionalidade de adicionar cliente será implementada em breve!');
        });
    }
}

// Inicialização quando o DOM estiver carregado
document.addEventListener('DOMContentLoaded', function() {
    initializeSidebar();
    initializeModals();
    initializeThemeToggle();
    initializeFeatureCards();
    initializeMessageSystem();
    initializeProtectedLinks();
    initializeBackButton();
    initializeAddButtons();
    
    // Verificar estado de login pela classe do body
    const body = document.body;
    if (body.classList.contains('logged-in')) {
        updateUIForLoggedIn();
    } else {
        updateUIForLoggedOut();
    }
});