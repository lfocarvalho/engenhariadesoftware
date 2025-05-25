<?php
// engenhariadesoftware/app/controllers/PageController.php

require_once __DIR__ . '/../../config/database.php'; // Para $base_url e funções de sessão, se necessário

class PageController {
    private $pdo;
    private $base_url;

    public function __construct(PDO $pdo, string $base_url) {
        $this->pdo = $pdo;
        $this->base_url = $base_url;
    }

    public function index() {
        // Passar $base_url para a view poder construir URLs corretamente
        $base_url = $this->base_url;
        // Carrega a view da página inicial
        require_once __DIR__ . '/../views/index.php';
    }
}
?>