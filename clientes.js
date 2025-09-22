// clientes.js - Baseado no funcionamento de veiculos.js
document.addEventListener('DOMContentLoaded', function() {
    console.log('clientes.js carregado');

    // Controle dos modais - CLIENTES
    const addClientBtn = document.getElementById('btnAddClient');
    const addClientModal = document.getElementById('addClientModal');
    const closeAddModal = document.getElementById('closeAddModal');
    const cancelAddBtn = document.getElementById('cancelAddClient');
    
    // Abrir modal de adicionar cliente
    if (addClientBtn && addClientModal) {
        addClientBtn.addEventListener('click', function() {
            console.log('Abrindo modal de adicionar cliente');
            addClientModal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        });
    }
    
    // Fechar modais
    function closeModal() {
        if (addClientModal) addClientModal.style.display = 'none';
        if (editClientModal) editClientModal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }
    
    if (closeAddModal) {
        closeAddModal.addEventListener('click', closeModal);
    }
    
    if (cancelAddBtn) {
        cancelAddBtn.addEventListener('click', closeModal);
    }
    
    // Fechar modal clicando fora dele
    if (addClientModal) {
        addClientModal.addEventListener('click', function(e) {
            if (e.target === addClientModal) {
                closeModal();
            }
        });
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
    const editClientModal = document.getElementById('editClientModal');
    const closeEditModal = document.getElementById('closeEditModal');
    const cancelEditBtn = document.getElementById('cancelEditClient');
    
    if (closeEditModal) {
        closeEditModal.addEventListener('click', closeModal);
    }
    
    if (cancelEditBtn) {
        cancelEditBtn.addEventListener('click', closeModal);
    }
    
    if (editClientModal) {
        editClientModal.addEventListener('click', function(e) {
            if (e.target === editClientModal) {
                closeModal();
            }
        });
    }
});

// Função para confirmar remoção de cliente
function confirmarRemocaoCliente(id, nome) {
    const deleteModal = document.getElementById('confirmDeleteModal');
    const deleteMessage = document.getElementById('deleteMessage');
    const clienteId = document.getElementById('clienteId');
    
    if (deleteModal && deleteMessage && clienteId) {
        deleteMessage.textContent = `Tem certeza que deseja remover o cliente "${nome}"? Esta ação não pode ser desfeita.`;
        clienteId.value = id;
        
        deleteModal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
}

// Função para editar cliente
function editarCliente(id) {
    console.log('Editando cliente ID:', id);
    
    // Fazer uma requisição AJAX para buscar os dados do cliente
    fetch(`get_cliente.php?id=${id}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Erro na requisição');
            }
            return response.json();
        })
        .then(cliente => {
            console.log('Dados do cliente:', cliente);
            
            // Preencher o formulário de edição com os dados do cliente
            document.getElementById('edit_cliente_id').value = cliente.id;
            document.getElementById('edit_nome').value = cliente.nome;
            document.getElementById('edit_telefone').value = cliente.telefone;
            document.getElementById('edit_email').value = cliente.email;
            document.getElementById('edit_status').value = cliente.status;
            
            // Exibir o modal de edição
            document.getElementById('editClientModal').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        })
        .catch(error => {
            console.error('Erro ao buscar dados do cliente:', error);
            alert('Erro ao carregar dados do cliente. Tente novamente.');
        });
}