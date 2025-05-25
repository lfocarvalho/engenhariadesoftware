<?php
// engenhariadesoftware/app/controllers/AdminController.php

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../models/TarefaModel.php'; // Assuming TarefaModel has admin methods or can be used

class AdminController {
    private PDO $pdo;
    private string $base_url;
    private UserModel $userModel;
    private TarefaModel $tarefaModel;

    public function __construct(PDO $pdo, string $base_url) {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $this->pdo = $pdo;
        $this->base_url = $base_url;
        $this->userModel = new UserModel(); // UserModel gets DB via Database::getInstance()
        $this->tarefaModel = new TarefaModel($pdo); // TarefaModel takes PDO
    }

    public function index(): void {
        if (!isAdmin()) {
            $_SESSION['error_message'] = "Acesso não autorizado.";
            redirect($this->base_url . 'index.php?controller=tarefa&action=index');
        }

        $usuarios = $this->userModel->getUsuarios(); // Assumes getUsuarios() exists and is suitable
        $tarefas = $this->tarefaModel->getAllTarefasAdmin(); // Assumes getAllTarefasAdmin() exists
        
        $base_url = $this->base_url; // Pass to view
        require_once __DIR__ . '/../views/admin/index.php';
    }
}
?>