<?php
// engenhariadesoftware/config/database.php

// Configurações do banco de dados
$host = 'localhost';
$dbname = 'daily_planner';
$username = 'root';
$password = ''; // Sua senha do MySQL, se houver

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro na conexão com o banco de dados: " . $e->getMessage());
}

// Inicia a sessão (se ainda não estiver iniciada)
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Função para verificar se o usuário é admin
function isAdmin() {
    return isset($_SESSION['usuario']) && $_SESSION['usuario']['tipo'] === 'admin';
}

// Função para verificar login
function isLoggedIn() {
    return isset($_SESSION['usuario']) && isset($_SESSION['usuario']['id']);
}

// Função para redirecionar
function redirect($url) {
    header("Location: " . $url);
    exit;
}

// Função para obter o ID do usuário logado
function getUserId() {
    return $_SESSION['usuario']['id'] ?? null;
}

// Define o fuso horário padrão
date_default_timezone_set('America/Sao_Paulo');
?>