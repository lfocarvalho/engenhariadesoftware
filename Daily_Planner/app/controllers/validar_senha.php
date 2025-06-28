<?php
session_start();
header('Content-Type: application/json');
require_once __DIR__ . '/../models/user_model.php';
require_once __DIR__ . '/../../config/config.php';

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($_SESSION['usuario']['id']) || !isset($data['senha'])) {
    echo json_encode(['sucesso' => false]);
    exit;
}

$userModel = new UserModel($pdo);
$usuario = $userModel->getUsuarioId($_SESSION['usuario']['id']);

if ($usuario && password_verify($data['senha'], $usuario['senha'])) {
    echo json_encode(['sucesso' => true, 'senha' => $usuario['senha']]);
} else {
    echo json_encode(['sucesso' => false]);
}