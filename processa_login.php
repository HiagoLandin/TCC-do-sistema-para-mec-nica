<?php
require_once 'Usuario.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verifica se todos os campos obrigatórios foram preenchidos
    if (empty($_POST['email']) || empty($_POST['senha'])) {
        $_SESSION['mensagem'] = "Todos os campos são obrigatórios.";
        $_SESSION['mensagem_tipo'] = "erro";
    } else {
        // Sanitiza os dados do formulário
        $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
        $senha = trim($_POST['senha']);
        
        // Valida o formato do email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['mensagem'] = "Formato de e-mail inválido.";
            $_SESSION['mensagem_tipo'] = "erro";
        } else {
            // Verifica se o usuário existe usando o método EXISTENTE
            $usuario = new Usuario();
            $usuario_data = $usuario->buscarPorEmail($email);
            
            if ($usuario_data) {
                // Verifica se a senha está correta
                if (password_verify($senha, $usuario_data['senha'])) {
                    // Login bem-sucedido
                    $_SESSION['usuario_id'] = $usuario_data['id'];
                    $_SESSION['usuario_nome'] = $usuario_data['nome'];
                    $_SESSION['usuario_email'] = $usuario_data['email'];
                    $_SESSION['logado'] = true;
                    
                    $_SESSION['mensagem'] = "Login realizado com sucesso!";
                    $_SESSION['mensagem_tipo'] = "sucesso";
                } else {
                    $_SESSION['mensagem'] = "Senha incorreta.";
                    $_SESSION['mensagem_tipo'] = "erro";
                }
            } else {
                $_SESSION['mensagem'] = "Usuário não encontrado.";
                $_SESSION['mensagem_tipo'] = "erro";
            }
        }
    }

    // Redireciona de volta para a página onde o modal está
    header("Location: index.php");
    exit;
}
?>