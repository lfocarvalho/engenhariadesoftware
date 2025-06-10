<?php
// engenhariadesoftware/Daily_Planner/DailyPlannerApi/public/index.php

// Define o diretório raiz da sua API (DailyPlannerApi/)
define('API_ROOT', dirname(__DIR__));

// Carrega o autoloader do Composer.
require API_ROOT . '/vendor/autoload.php';

// Carrega a função loadEnv
require API_ROOT . '/config/env_loader.php';

try {
    // Chama a função para carregar as variáveis de ambiente
    loadEnv(API_ROOT . '/.env');

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erro interno do servidor ao iniciar a API: ' . $e->getMessage()]);
    exit();
}

// INCLUI O ARQUIVO DE ROTAS PRINCIPAL.
require API_ROOT . '/src/routes/api.php';