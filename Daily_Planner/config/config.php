<?php
// Inicia a sessão, se ainda não estiver iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Configurações do banco de dados
$host = 'localhost';
$dbname = 'daily_planner';
$username = 'root';
$password = '';

// Conexão com o banco usando PDO
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro na conexão: " . $e->getMessage());
}

// Função para verificar se o usuário está logado
function isLoggedIn() {
    return isset($_SESSION['usuario']);
}

function isAdmin() {
    return isLoggedIn() && $_SESSION['usuario']['tipo'] === 'admin';
}
?>