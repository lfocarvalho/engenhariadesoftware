<?php
// Caminho: app/controllers/trocar_senha.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// O seu user_model.php já inclui o config/database.php, então só precisamos dele.
require_once __DIR__ . '/../models/user_model.php';

// --- Validações Iniciais ---
if (!isset($_SESSION['usuario']['id'])) {
    header('Location: ../views/login.php');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header('Location: ../views/alterar_senha.html');
    exit();
}

$senhaAtual = $_POST["senha_atual"] ?? '';
$novaSenha = $_POST["nova_senha"] ?? '';
$repeteSenha = $_POST["repete_senha"] ?? '';
$usuario_id = $_SESSION['usuario']['id'];

if (empty($senhaAtual) || empty($novaSenha) || empty($repeteSenha)) {
    header('Location: ../views/alterar_senha.html?status=erro&msg=campos_vazios');
    exit();
}

if ($novaSenha !== $repeteSenha) {
    header('Location: ../views/alterar_senha.html?status=erro&msg=senhas_nao_coincidem');
    exit();
}

if (strlen($novaSenha) < 6) {
    header('Location: ../views/alterar_senha.html?status=erro&msg=senha_curta');
    exit();
}

// --- Lógica de Atualização CORRIGIDA ---
try {
    $userModel = new UserModel();

    // A mágica acontece aqui: chamamos APENAS UMA função do seu modelo.
    // Ela retorna 'true' se a senha atual estava correta E a nova foi salva.
    // Ela retorna 'false' se a senha atual estava incorreta.
    $sucesso = $userModel->atualizarSenha($usuario_id, $senhaAtual, $novaSenha);

    if ($sucesso) {
        // Sucesso! Força o logout por segurança.
        session_destroy();
        header("Location: ../views/login.php?status=sucesso&msg=senha_alterada");
        exit();
    } else {
        // Se retornou false, a senha atual não bateu.
        header('Location: ../views/alterar_senha.html?status=erro&msg=senha_incorreta');
        exit();
    }

} catch (Exception $e) {
    // Captura qualquer outro erro (ex: falha de banco de dados)
    error_log("Erro ao trocar senha para o usuário ID {$usuario_id}: " . $e->getMessage());
    header('Location: ../views/alterar_senha.html?status=erro&msg=inesperado');
    exit();
}
?>