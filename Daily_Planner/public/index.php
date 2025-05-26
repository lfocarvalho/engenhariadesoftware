<?php
// engenhariadesoftware/public/index.php
require_once '../config/database.php';

// Define um base_url para facilitar os links
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$host = $_SERVER['HTTP_HOST'];
$script_name = dirname($_SERVER['SCRIPT_NAME']); // Pega o diretório do script atual
// Se o script estiver na raiz do servidor web, $script_name será '/', caso contrário, será o caminho do subdiretório
$base_url = rtrim($protocol . $host . $script_name, '/') . '/';


// Roteamento simples
$controllerName = $_GET['controller'] ?? 'tarefa'; // Controller padrão
$actionName = $_GET['action'] ?? 'index';         // Ação padrão

// Segurança básica para nomes de controller e action
if (!preg_match('/^[a-zA-Z0-9_]+$/', $controllerName) || !preg_match('/^[a-zA-Z0-9_]+$/', $actionName)) {
    die('Controller ou ação inválida.');
}

$controllerFile = '../app/controllers/' . ucfirst($controllerName) . 'Controller.php';

if (file_exists($controllerFile)) {
    require_once $controllerFile;
    $className = ucfirst($controllerName) . 'Controller';
    if (class_exists($className)) {
        $controllerInstance = new $className($pdo, $base_url); // Passa $pdo e $base_url
        if (method_exists($controllerInstance, $actionName)) {
            try {
                $controllerInstance->$actionName();
            } catch (Exception $e) {
                // Em um ambiente de produção, logue o erro e mostre uma página de erro amigável.
                error_log("Erro no controller: " . $e->getMessage());
                die('Ocorreu um erro na aplicação. Por favor, tente novamente mais tarde.');
            }
        } else {
            die("Ação '{$actionName}' não encontrada no controller '{$className}'.");
        }
    } else {
        die("Controller class '{$className}' não encontrada.");
    }
} else {
    die("Controller file '{$controllerFile}' não encontrado.");
}
?>