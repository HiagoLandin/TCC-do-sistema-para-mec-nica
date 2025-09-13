<?php
require_once 'Usuario.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verifica se todos os campos obrigatórios foram preenchidos
    if (empty($_POST['usuario_nome']) || empty($_POST['email']) || empty($_POST['senha']) || empty($_POST['confirmar_senha'])) {
        $_SESSION['mensagem'] = "Todos os campos são obrigatórios."; // LINHA FALTANDO
        $_SESSION['mensagem_tipo'] = "erro";
    } else {
        // Sanitiza os dados do formulário
        $nome_completo = htmlspecialchars(trim($_POST['usuario_nome']));
        $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
        $senha = trim($_POST['senha']);
        $confirmar_senha = trim($_POST['confirmar_senha']);
        
        // Verifica se as senhas coincidem
        if ($senha !== $confirmar_senha) {
            $_SESSION['mensagem'] = "As senhas não coincidem.";
            $_SESSION['mensagem_tipo'] = "erro";
        }
        // Valida o formato do email
        elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['mensagem'] = "Formato de e-mail inválido.";
            $_SESSION['mensagem_tipo'] = "erro";
        } else {
            // Verifica se o email já está cadastrado
            $usuario = new Usuario();
            if ($usuario->buscarPorEmail($email)) {
                $_SESSION['mensagem'] = "Este e-mail já está cadastrado.";
                $_SESSION['mensagem_tipo'] = "erro";
            } else {
                // Se não houver erros, cadastra o usuário (apenas campos essenciais)
                if ($usuario->cadastrar($nome_completo, $email, $senha)) {
                    // Busca o usuário recém-criado para obter o ID
                    $usuario_cadastrado = $usuario->buscarPorEmail($email);
                    
                    if ($usuario_cadastrado) {
                        // Define as variáveis de sessão para logar automaticamente
                        $_SESSION['usuario_id'] = $usuario_cadastrado['id'];
                        $_SESSION['usuario_nome'] = $usuario_cadastrado['nome'];
                        $_SESSION['usuario_email'] = $usuario_cadastrado['email'];
                        $_SESSION['logado'] = true;
                        
                        $_SESSION['mensagem'] = "Cadastro realizado com sucesso! Você está logado.";
                        $_SESSION['mensagem_tipo'] = "sucesso";
                    } else {
                        $_SESSION['mensagem'] = "Erro ao recuperar dados do usuário.";
                        $_SESSION['mensagem_tipo'] = "erro";
                    }
                } else {
                    $_SESSION['mensagem'] = "Erro ao cadastrar o usuário. Tente novamente.";
                    $_SESSION['mensagem_tipo'] = "erro";
                }
            }
        }
    }

    // Redireciona de volta para a página onde o modal está
    header("Location: index.php");
    exit;
}
?>