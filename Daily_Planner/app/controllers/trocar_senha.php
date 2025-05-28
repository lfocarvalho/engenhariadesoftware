<?php
require 'config.php'; 
session_start();

function editarSenhaUsuario($senhaAtual, $novaSenha) {
    global $pdo;

    if (!isset($_SESSION['usuario'])) {
        return "Usuário não autenticado.";
    }

    $usuario_id = $_SESSION['usuario']['id'];

    $stmt = $pdo->prepare("SELECT senha FROM usuarios WHERE id = ?");
    $stmt->execute([$usuario_id]);
    $senha_hash = $stmt->fetchColumn();

    if (!$senha_hash || !password_verify($senhaAtual, $senha_hash)) {
        return "Senha atual incorreta.";
    }

    $novaSenhaHash = password_hash($novaSenha, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("UPDATE usuarios SET senha = ? WHERE id = ?");
    if ($stmt->execute([$novaSenhaHash, $usuario_id])) {
        return "Senha alterada com sucesso.";
    } else {
        return "Erro ao alterar a senha.";
    }
}
?>