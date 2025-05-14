<?php
require 'config.php'; // Aqui, config.php deve criar o objeto $pdo (PDO)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function editarSenhaUsuario($senhaAtual, $novaSenha) {
    global $pdo;

    if (!isset($_SESSION['usuario'])) {
        return "Usuário não autenticado.";
    }

    $usuario_id = $_SESSION['usuario']['id'];

    // Buscar hash da senha atual
    $stmt = $pdo->prepare("SELECT senha FROM usuarios WHERE id = ?");
    $stmt->execute([$usuario_id]);
    $senha_hash = $stmt->fetchColumn();

    if (!$senha_hash || !password_verify($senhaAtual, $senha_hash)) {
        return "Senha atual incorreta.";
    }

    // Atualizar para a nova senha
    $novaSenhaHash = password_hash($novaSenha, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("UPDATE usuarios SET senha = ? WHERE id = ?");
    if ($stmt->execute([$novaSenhaHash, $usuario_id])) {
        return "Senha alterada com sucesso.";
    } else {
        return "Erro ao alterar a senha.";
    }
}
?>