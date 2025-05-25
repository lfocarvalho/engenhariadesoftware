<?php
session_start();
require_once __DIR__ . '/../../config/database.php'; // Ajuste o caminho conforme necessário
require_once __DIR__ . '/../models/user_model.php';   // Ajuste o caminho conforme necessário

if (!isLoggedIn()) {
    header('Location: ../../index.html'); // Redireciona para a página inicial ou de login
    exit;
}

if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];

    $db = Database::getInstance()->getConnection();
    $userModel = new UserModel($db);

    // Assumindo que seu UserModel tem um método deleteUser($id) ou similar
    if ($userModel->deleteUser($userId)) {
        // Usuário deletado com sucesso
        session_unset();
        session_destroy();
        // Redireciona para uma página de confirmação ou para a página inicial
        // Você pode adicionar uma mensagem de sucesso via query string se desejar
        header('Location: ../../index.html?message=account_deleted_successfully');
        exit;
    } else {
        // Falha ao deletar a conta
        // Redireciona de volta para o perfil com uma mensagem de erro
        header('Location: ../perfil.php?error=account_delete_failed');
        exit;
    }
} else {
    // user_id não encontrado na sessão, algo está errado ou o usuário não está logado
    header('Location: ../../index.html'); // Redireciona para a página inicial ou de login
    exit;
}
?>