document.addEventListener('DOMContentLoaded', function() {
    // Sidebar
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.querySelector('.sidebar-toggle');
    
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
        const sidebarToggle = document.querySelector('.sidebar-toggle');
        
        sidebar.classList.remove('open');
        if (sidebarToggle) {
            const icon = sidebarToggle.querySelector('i');
            icon.classList.remove('fa-times');
            icon.classList.add('fa-bars');
        }
        document.body.style.overflow = 'auto';
    }

    // Controle do tema (modo claro/escuro)
    const themeToggle = document.querySelector('.theme-toggle-item a');
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
        const icon = document.querySelector('.theme-toggle-item i');
        const text = document.querySelector('.theme-toggle-item span');
        
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

    // Controle dos modais de serviços
    const modalServico = document.getElementById('modalServico');
    const modalAdicionarAnotacao = document.getElementById('modalAdicionarAnotacao');
    
    // Fechar modais
    function closeModals() {
        if (modalServico) modalServico.style.display = 'none';
        if (modalAdicionarAnotacao) modalAdicionarAnotacao.style.display = 'none';
        document.body.style.overflow = 'auto';
    }
    
    // Fechar modais ao clicar no X
    document.querySelectorAll('.close-modal').forEach(btn => {
        btn.addEventListener('click', closeModals);
    });
    
    // Fechar modais clicando fora
    if (modalServico) {
        modalServico.addEventListener('click', function(e) {
            if (e.target === modalServico) {
                closeModals();
            }
        });
    }
    
    if (modalAdicionarAnotacao) {
        modalAdicionarAnotacao.addEventListener('click', function(e) {
            if (e.target === modalAdicionarAnotacao) {
                closeModals();
            }
        });
    }
    
    // Fechar modais com ESC
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeModals();
        }
    });

    // Inicializar serviços
    let semanaAtual = 0;
    let servicoEditando = null;
    let anotacoes = JSON.parse(localStorage.getItem('anotacoesServicos')) || [];
    let servicos = JSON.parse(localStorage.getItem('servicosAgendados')) || {};

    atualizarDisplaySemana();
    carregarAnotacoes();
    carregarServicosSemana(semanaAtual);

    // Navegação entre semanas
    window.mudarSemana = function(direcao) {
        semanaAtual += direcao;
        atualizarDisplaySemana();
        carregarServicosSemana(semanaAtual);
    }

    function atualizarDisplaySemana() {
        const data = new Date();
        data.setDate(data.getDate() + semanaAtual * 7);
        
        const inicioSemana = new Date(data);
        inicioSemana.setDate(data.getDate() - data.getDay() + 1);
        
        const fimSemana = new Date(inicioSemana);
        fimSemana.setDate(inicioSemana.getDate() + 6);
        
        const options = { day: 'numeric', month: 'long' };
        const displayText = `Semana de ${inicioSemana.toLocaleDateString('pt-BR', options)} a ${fimSemana.toLocaleDateString('pt-BR', options)}`;
        
        document.getElementById('semanaAtual').textContent = displayText;
        
        // Atualizar datas dos dias
        const dias = ['Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado', 'Domingo'];
        dias.forEach((dia, index) => {
            const dataDia = new Date(inicioSemana);
            dataDia.setDate(inicioSemana.getDate() + index);
            const elementoData = document.querySelector(`.day-date[data-dia="${dia}"]`);
            if (elementoData) {
                elementoData.textContent = dataDia.toLocaleDateString('pt-BR', { day: 'numeric', month: 'short' });
                elementoData.setAttribute('data-data', dataDia.toISOString().split('T')[0]);
            }
        });
    }

    function carregarServicosSemana() {
        const dias = ['Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado', 'Domingo'];
        
        dias.forEach(dia => {
            const container = document.getElementById(`servicos-${dia}`);
            const dataElement = document.querySelector(`.day-date[data-dia="${dia}"]`);
            const data = dataElement ? dataElement.getAttribute('data-data') : null;
            
            if (container) {
                if (data && servicos[data] && servicos[data].length > 0) {
                    const servicosDoDia = servicos[data];
                    container.innerHTML = servicosDoDia.map(servico => `
                        <div class="service-item">
                            <div class="service-descricao">${servico.descricao}</div>
                            ${servico.observacoes ? `<div class="service-observacoes">${servico.observacoes}</div>` : ''}
                            <div class="service-actions">
                                <button class="btn-edit-service" onclick="editarServico('${data}', ${servico.id})" title="Editar Serviço">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn-delete-service" onclick="excluirServico('${data}', ${servico.id})" title="Excluir Serviço">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    `).join('');
                } else {
                    container.innerHTML = '<div class="no-services text-muted text-center py-3">Nenhum serviço agendado</div>';
                }
            }
        });
    }

    // Modal Serviço
    window.abrirModalAdicionarServico = function(dia) {
        if (!verificarLogin()) return;
        
        servicoEditando = null;
        document.getElementById('tituloModalServico').textContent = `Adicionar Serviço - ${dia}`;
        document.getElementById('servicoDia').value = dia;
        document.getElementById('servicoId').value = '';
        document.getElementById('descricaoServico').value = '';
        document.getElementById('observacoesServico').value = '';
        
        document.getElementById('modalServico').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    window.editarServico = function(data, servicoId) {
        if (!verificarLogin()) return;
        
        const servicosDoDia = servicos[data] || [];
        const servico = servicosDoDia.find(s => s.id === servicoId);
        
        if (servico) {
            servicoEditando = { data, id: servicoId };
            document.getElementById('tituloModalServico').textContent = 'Editar Serviço';
            document.getElementById('servicoDia').value = data;
            document.getElementById('servicoId').value = servicoId;
            document.getElementById('descricaoServico').value = servico.descricao;
            document.getElementById('observacoesServico').value = servico.observacoes || '';
            
            document.getElementById('modalServico').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }
    }

    window.fecharModalServico = function() {
        document.getElementById('modalServico').style.display = 'none';
        document.body.style.overflow = 'auto';
        servicoEditando = null;
        document.getElementById('formServico').reset();
    }

    window.salvarServico = function() {
        if (!verificarLogin()) return;
        
        const descricao = document.getElementById('descricaoServico').value.trim();
        const observacoes = document.getElementById('observacoesServico').value.trim();
        const dia = document.getElementById('servicoDia').value;
        const dataElement = document.querySelector(`.day-date[data-dia="${dia}"]`);
        const data = dataElement ? dataElement.getAttribute('data-data') : null;
        
        if (!descricao) {
            alert('Por favor, preencha a descrição do serviço.');
            return;
        }
        
        const servico = {
            id: servicoEditando ? servicoEditando.id : Date.now(),
            descricao: descricao,
            observacoes: observacoes,
            data: data,
            dia: dia
        };
        
        if (!servicos[data]) servicos[data] = [];
        
        if (servicoEditando) {
            const index = servicos[data].findIndex(s => s.id === servicoEditando.id);
            if (index !== -1) servicos[data][index] = servico;
        } else {
            servicos[data].push(servico);
        }
        
        localStorage.setItem('servicosAgendados', JSON.stringify(servicos));
        carregarServicosSemana(semanaAtual);
        alert(servicoEditando ? 'Serviço atualizado com sucesso!' : 'Serviço adicionado com sucesso!');
        fecharModalServico();
    }

    window.excluirServico = function(data, servicoId) {
        if (!verificarLogin()) return;
        
        if (confirm('Tem certeza que deseja excluir este serviço?')) {
            if (servicos[data]) {
                servicos[data] = servicos[data].filter(s => s.id !== servicoId);
                if (servicos[data].length === 0) delete servicos[data];
                localStorage.setItem('servicosAgendados', JSON.stringify(servicos));
                carregarServicosSemana(semanaAtual);
                alert('Serviço excluído com sucesso!');
            }
        }
    }

    // Modal Anotações
    window.abrirModalAdicionarAnotacao = function() {
        if (!verificarLogin()) return;
        
        document.getElementById('dataAnotacao').value = new Date().toISOString().split('T')[0];
        document.getElementById('modalAdicionarAnotacao').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    window.fecharModalAdicionarAnotacao = function() {
        document.getElementById('modalAdicionarAnotacao').style.display = 'none';
        document.body.style.overflow = 'auto';
        document.getElementById('formAdicionarAnotacao').reset();
    }

    window.adicionarAnotacao = function() {
        if (!verificarLogin()) return;
        
        const dataAnotacao = document.getElementById('dataAnotacao').value;
        const observacoes = document.getElementById('observacoesAnotacao').value.trim();
        
        if (!dataAnotacao || !observacoes) {
            alert('Por favor, preencha todos os campos obrigatórios.');
            return;
        }
        
        const novaAnotacao = {
            id: Date.now(),
            data: dataAnotacao,
            observacoes: observacoes,
            timestamp: new Date().toISOString()
        };
        
        anotacoes.unshift(novaAnotacao);
        localStorage.setItem('anotacoesServicos', JSON.stringify(anotacoes));
        alert('Anotação adicionada com sucesso!');
        fecharModalAdicionarAnotacao();
        carregarAnotacoes();
    }

    function carregarAnotacoes() {
        const container = document.getElementById('historicoAnotacoes');
        
        if (anotacoes.length === 0) {
            container.innerHTML = '<div class="text-muted text-center py-3">Nenhuma anotação no histórico</div>';
            return;
        }
        
        container.innerHTML = anotacoes.map(anotacao => `
            <div class="history-item">
                <div class="history-header">
                    <strong>Anotação #${anotacao.id.toString().slice(-4)}</strong>
                    <span class="history-date">${new Date(anotacao.data + 'T00:00:00').toLocaleDateString('pt-BR')}</span>
                </div>
                <div class="history-content">${anotacao.observacoes}</div>
            </div>
        `).join('');
    }

    // Função auxiliar
    function verificarLogin() {
        if (!document.body.classList.contains('logged-in')) {
            alert('Você precisa estar logado para acessar esta funcionalidade.');
            return false;
        }
        return true;
    }
});