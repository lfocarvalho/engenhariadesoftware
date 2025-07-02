<?php
session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../models/user_model.php';
require_once __DIR__ . '/../../config/config.php';

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($_SESSION['usuario']['id'])) {
    echo json_encode(['erro' => 'Usuário não autenticado']);
    exit;
}

if (!$data || !isset($data['nome']) || !isset($data['email'])) {
    echo json_encode(['erro' => 'Dados incompletos']);
    exit;
}

$userModel = new UserModel($pdo);
$usuario_id = $_SESSION['usuario']['id'];
$nome = $data['nome'];
$email = $data['email'];
$apelido = isset($data['apelido']) ? $data['apelido'] : '';
$data_nascimento = isset($data['data_nascimento']) ? $data['data_nascimento'] : null;
$atualizado = $userModel->atualizarDados($usuario_id, $nome, $email, $apelido, $data_nascimento);

if ($atualizado) {
    // Atualiza também a sessão
    $_SESSION['usuario']['nome'] = $nome;
    $_SESSION['usuario']['email'] = $email;
    $_SESSION['usuario']['apelido'] = $apelido;
    $_SESSION['usuario']['data_nascimento'] = $data_nascimento;
    echo json_encode(['sucesso' => true]);
} else {
    echo json_encode(['erro' => 'Erro ao atualizar no banco de dados']);
}