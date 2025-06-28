<?php
session_start();
header('Content-Type: application/json');

if (isset($_SESSION['usuario']) && isset($_SESSION['usuario']['email'])) {
    echo json_encode([
        'nome' => $_SESSION['usuario']['nome'] ?? 'Usuário',
        'email' => $_SESSION['usuario']['email'],
        'apelido' => $_SESSION['usuario']['apelido'] ?? '',
        'data_nascimento' => $_SESSION['usuario']['data_nascimento'] ?? null
    ]);
} else {
    http_response_code(401);
    echo json_encode(['erro' => 'Usuário não autenticado']);
}