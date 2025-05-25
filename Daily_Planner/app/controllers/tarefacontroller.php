<?php
// engenhariadesoftware/app/controllers/TarefaController.php
require_once '../app/models/TarefaModel.php';

class TarefaController {
    private $tarefaModel;
    private $pdo;
    private $base_url;

    public function __construct(PDO $pdo, $base_url) {
        $this->pdo = $pdo; // Guardar o pdo se precisar para outras coisas que não o model
        $this->tarefaModel = new TarefaModel($pdo);
        $this->base_url = $base_url;
    }

    private function checkLogin() {
        if (!isLoggedIn()) {
            $_SESSION['erro_login'] = "Você precisa estar logado para acessar esta página.";
            redirect($this->base_url . 'index.php?controller=auth&action=login');
        }
        return getUserId();
    }

    public function index() {
        $usuario_id = $this->checkLogin();
        $filtro = $_GET['filtro'] ?? 'todas';
        $tarefas = $this->tarefaModel->getAllByUserId($usuario_id, $filtro);
        
        // Passar mensagens de erro/sucesso para a view
        $erro = $_SESSION['erro_tarefa'] ?? null;
        $sucesso = $_SESSION['sucesso_tarefa'] ?? null;
        unset($_SESSION['erro_tarefa'], $_SESSION['sucesso_tarefa']);

        $base_url = $this->base_url; // Para usar na view
        require '../app/views/tarefas/index.php';
    }

    public function store() {
        $usuario_id = $this->checkLogin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (empty($_POST['titulo']) || empty($_POST['data_vencimento'])) {
                $_SESSION['erro_tarefa'] = "Título e data de vencimento são obrigatórios.";
                redirect($this->base_url . 'index.php?controller=tarefa&action=index');
            }

            $titulo = $_POST['titulo'];
            $descricao = $_POST['descricao'] ?? '';
            // Formato esperado pelo datetime-local: Y-m-d\TH:i
            // Convertendo para Y-m-d H:i:s para o banco
            $data_vencimento = date('Y-m-d H:i:s', strtotime($_POST['data_vencimento']));

            if ($this->tarefaModel->create($titulo, $descricao, $data_vencimento, $usuario_id)) {
                $_SESSION['sucesso_tarefa'] = "Tarefa adicionada com sucesso!";
            } else {
                $_SESSION['erro_tarefa'] = "Erro ao adicionar tarefa.";
            }
            redirect($this->base_url . 'index.php?controller=tarefa&action=index');
        }
    }

    public function edit() {
        $usuario_id = $this->checkLogin();
        $id = $_GET['id'] ?? null;

        if (!$id) {
            $_SESSION['erro_tarefa'] = "ID da tarefa não fornecido.";
            redirect($this->base_url . 'index.php?controller=tarefa&action=index');
        }

        $tarefa = $this->tarefaModel->getByIdAndUserId($id, $usuario_id);

        if (!$tarefa) {
            $_SESSION['erro_tarefa'] = "Tarefa não encontrada ou não pertence a você.";
            redirect($this->base_url . 'index.php?controller=tarefa&action=index');
        }
        
        $base_url = $this->base_url;
        require '../app/views/tarefas/editar.php';
    }

    public function update() {
        $usuario_id = $this->checkLogin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null; // Pegar ID do POST (campo oculto no form)
            if (empty($_POST['titulo']) || empty($_POST['data_vencimento']) || !$id) {
                $_SESSION['erro_tarefa'] = "Todos os campos são obrigatórios.";
                 // Se o ID não estiver no POST, tente pegar do GET (fallback, mas ideal é POST)
                $redirect_id = $id ?: ($_GET['id'] ?? null);
                if ($redirect_id) {
                    redirect($this->base_url . 'index.php?controller=tarefa&action=edit&id=' . $redirect_id);
                } else {
                    redirect($this->base_url . 'index.php?controller=tarefa&action=index');
                }
            }

            $titulo = $_POST['titulo'];
            $descricao = $_POST['descricao'] ?? '';
            $data_vencimento = date('Y-m-d H:i:s', strtotime($_POST['data_vencimento']));

            if ($this->tarefaModel->update($id, $titulo, $descricao, $data_vencimento, $usuario_id)) {
                $_SESSION['sucesso_tarefa'] = "Tarefa atualizada com sucesso!";
            } else {
                $_SESSION['erro_tarefa'] = "Erro ao atualizar tarefa ou nenhuma alteração feita.";
            }
            redirect($this->base_url . 'index.php?controller=tarefa&action=index');
        }
    }

    public function destroy() {
        $usuario_id = $this->checkLogin();
        $id = $_GET['id'] ?? null;

        if ($id && $this->tarefaModel->delete($id, $usuario_id)) {
            $_SESSION['sucesso_tarefa'] = "Tarefa excluída com sucesso!";
        } else {
            $_SESSION['erro_tarefa'] = "Erro ao excluir tarefa ou tarefa não encontrada.";
        }
        redirect($this->base_url . 'index.php?controller=tarefa&action=index');
    }

    public function toggleStatus() {
        $usuario_id = $this->checkLogin();
        $id = $_GET['id'] ?? null;

        if ($id && $this->tarefaModel->toggleStatus($id, $usuario_id)) {
            // Mensagem opcional, pode poluir a interface
            // $_SESSION['sucesso_tarefa'] = "Status da tarefa alterado.";
        } else {
            $_SESSION['erro_tarefa'] = "Erro ao alterar status da tarefa.";
        }
        redirect($this->base_url . 'index.php?controller=tarefa&action=index');
    }
}
?>