<?php
// engenhariadesoftware/config/database.php

class Database {
    private static $instance = null;
    private $conn;

    private $host = 'localhost';
    private $db = 'daily_planner';
    private $user = 'root';
    private $pass = '';

    private function __construct() {
        $this->conn = new PDO(
            "mysql:host={$this->host};dbname={$this->db};charset=utf8",
            $this->user,
            $this->pass
        );
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    }

    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->conn;
    }
}

// Inicia a sessão (se ainda não estiver iniciada)
if (session_status() == PHP_SESSION_NONE) {
    session_start();
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