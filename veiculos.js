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