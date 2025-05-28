<?php
require_once __DIR__ . '/../../config/config.php'; // Ajuste se o config estiver em outro local relativo a Daily_Planner
require_once '../models/user_model.php';
$userModel = new UserModel();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha_plana = $_POST['senha']; // Senha vinda diretamente do formulário

    $resultado = $userModel->criarUsuario($nome, $email, $senha_plana, 'usuario');

    if (is_numeric($resultado)) { // Se retornou o ID do usuário
        echo "Usuário criado com sucesso! ID: " . $resultado;
        // Redirecionar para login ou dashboard
    } elseif (is_string($resultado)) { // Se retornou uma mensagem de erro específica
         echo "Erro: " . $resultado;
    } else {
        echo "Erro ao criar usuário.";
    }
}

// Inclui a view (apenas HTML)
require_once __DIR__ . '/../views/cadastrar_usuario.php';
?>


