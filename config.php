<?php
// Configurações do banco de dados
$host = 'localhost';
$dbname = 'daily_planner';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro na conexão: " . $e->getMessage());
}

// Inicia a sessão (para autenticação)
session_start();

// Função para verificar se o usuário é admin
function isAdmin() {
    return isset($_SESSION['usuario']) && $_SESSION['usuario']['tipo'] === 'admin';
}

// Função para verificar login
function isLoggedIn() {
    return isset($_SESSION['usuario']);
}
?>