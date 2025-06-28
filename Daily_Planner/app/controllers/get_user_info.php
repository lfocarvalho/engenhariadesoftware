<?php
session_start();
header('Content-Type: application/json');

if (isset($_SESSION['usuario']) && isset($_SESSION['usuario']['nome']) && isset($_SESSION['usuario']['email'])) {
    echo json_encode([
        'success' => true,
        'nome' => $_SESSION['usuario']['nome'],
        'email' => $_SESSION['usuario']['email']
    ]);
} else {
    // Retorna um erro se não houver usuário logado
    echo json_encode([
        'success' => false,
        'error' => 'Usuário não autenticado.'
    ]);
}
?>