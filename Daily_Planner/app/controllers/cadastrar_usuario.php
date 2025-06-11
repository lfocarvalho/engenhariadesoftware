<?php
require_once __DIR__ . '/../../config/config.php'; 
require_once '../models/user_model.php';
require_once __DIR__ . '/../email/enviar_boas_vindas.php';

$userModel = new UserModel();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha_plana = $_POST['senha']; 
    $resultado = $userModel->criarUsuario($nome, $email, $senha_plana, 'usuario');

    if (is_numeric($resultado)) { 
        $url = "https://seusite.com.br"; // ou URL do seu sistema
        if (enviarEmailBoasVindas($email, $nome, $url)) {
            // E-mail enviado com sucesso
        } else {
            // Falha ao enviar e-mail
        }

        header('Location: ../views/login.php?cadastro=sucesso');
        exit;
    } elseif (is_string($resultado)) { 
         echo "Erro: " . $resultado;
    } else {
        echo "Erro ao criar usuário.";
    }
}


require_once __DIR__ . '/../views/cadastrar_usuario.php';
?>


