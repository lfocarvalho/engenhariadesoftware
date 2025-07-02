<?php
require_once __DIR__ . '/../../config/config.php'; 
require_once '../models/user_model.php';
require_once __DIR__ . '/../../DailyPlannerApi/src/services/enviar_boas_vindas.php';

$userModel = new UserModel();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha_plana = $_POST['senha']; 
    $resultado = $userModel->criarUsuario($nome, $email, $senha_plana, 'usuario');

    if (is_numeric($resultado)) { 
        $url = "http://localhost/engenhariadesoftware/engenhariadesoftware/Daily_Planner/public/index.php"; // ou URL do seu sistema
        echo $nome; // Teste: deve mostrar o nome do usuário
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

$para_nome = $nome; // ou a variável que contém o nome do usuário cadastrado

require_once __DIR__ . '/../views/cadastrar_usuario.php';
?>


