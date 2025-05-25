<?php
// engenhariadesoftware/app/controllers/SacController.php

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/SacModel.php';

class SacController {
    private SacModel $sacModel;
    private PDO $pdo;
    private string $base_url;

    public function __construct(PDO $pdo, string $base_url) {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $this->pdo = $pdo;
        $this->sacModel = new SacModel($pdo);
        $this->base_url = $base_url;
    }

    private function checkLogin(): int {
        if (!isLoggedIn()) {
            $_SESSION['error_message'] = "Você precisa estar logado para abrir um chamado.";
            redirect($this->base_url . 'index.php?controller=user&action=login');
        }
        return getUserId();
    }

    // Corresponds to the old salvar_sac.php
    public function store(): void {
        $usuario_id = $this->checkLogin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (empty($_POST['titulo']) || empty($_POST['descricao'])) {
                $_SESSION['error_message_sac'] = "Título e descrição são obrigatórios para o SAC.";
                redirect($this->base_url . 'index.php?controller=tarefa&action=index&erro_sac=1'); // Or redirect to a SAC form page
            }

            $titulo = $_POST['titulo'];
            $descricao = $_POST['descricao'];

            if ($this->sacModel->create($titulo, $descricao, $usuario_id)) {
                $_SESSION['success_message_sac'] = "Chamado SAC aberto com sucesso!";
            } else {
                $_SESSION['error_message_sac'] = "Erro ao abrir chamado SAC.";
            }
            redirect($this->base_url . 'index.php?controller=tarefa&action=index&sucesso_sac=1'); // Or redirect to a SAC confirmation/list page
        } else {
            // Optionally, show a form to create a SAC ticket if accessed via GET
            // For now, redirect if not POST
            redirect($this->base_url . 'index.php?controller=tarefa&action=index');
        }
    }

    // Add other methods like index (to list SACs), show, update, delete as needed.
}
?>