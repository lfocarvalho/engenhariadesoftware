<?php
require_once __DIR__ . '/../models/user_model.php';
require_once __DIR__ . '/../../config/config.php'; // Ajuste se o config estiver em outro local relativo a Daily_Planner
session_start();

if (!isset($_SESSION['usuario'])) {
    header('Location: ../views/login.php');
    exit;
}

if (!isset($_SESSION['usuario']['id']) || !is_numeric($_SESSION['usuario']['id'])) {
    header('Location: ../views/login.php');
    exit;
}
$usuario_id = (int)$_SESSION['usuario']['id'];

try {
    $userModel = new UserModel();
    $apagado = $userModel->excluirUsuario($usuario_id);

    if ($apagado) {
        session_destroy();
        // Redireciona para o index público após apagar a conta
        header('Location: ..\..\public\index.php');
        exit;
    } else {
        header('Location: ../views/perfil.php?erro=nao_excluiu');
        exit;
    }
} catch (Exception $e) {
    header('Location: ../views/perfil.php?erro=nao_excluiu');
    exit;
}