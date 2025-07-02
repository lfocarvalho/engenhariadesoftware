<?php
// engenhariadesoftware/app/models/TarefaModel.php

class TarefaModel {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function getAllByUserId($usuario_id, $filtro = 'todas') {
        $sql = "SELECT * FROM tarefas WHERE usuario_id = :usuario_id";
        $params = [':usuario_id' => $usuario_id];

        if ($filtro === 'pendentes') {
            $sql .= " AND concluida = 0";
        } elseif ($filtro === 'concluidas') {
            $sql .= " AND concluida = 1";
        }

        $sql .= " ORDER BY data_vencimento DESC, id DESC"; 
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getByIdAndUserId($id, $usuario_id) {
        $stmt = $this->pdo->prepare("SELECT * FROM tarefas WHERE id = :id AND usuario_id = :usuario_id");
        $stmt->execute([':id' => $id, ':usuario_id' => $usuario_id]);
        return $stmt->fetch();
    }

    public function create($titulo, $descricao, $data_vencimento, $usuario_id) {
        $sql = "INSERT INTO tarefas (titulo, descricao, data_vencimento, usuario_id) VALUES (:titulo, :descricao, :data_vencimento, :usuario_id)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':titulo' => $titulo,
            ':descricao' => $descricao,
            ':data_vencimento' => $data_vencimento,
            ':usuario_id' => $usuario_id
        ]);
    }
public function update($id, $titulo, $descricao, $data_vencimento, $usuario_id) {
        $sql = "UPDATE tarefas SET titulo = :titulo, descricao = :descricao, data_vencimento = :data_vencimento WHERE id = :id AND usuario_id = :usuario_id";
        $stmt = $this->pdo->prepare($sql);
        
        $stmt->execute([
            ':titulo' => $titulo,
            ':descricao' => $descricao,
            ':data_vencimento' => $data_vencimento,
            ':id' => $id,
            ':usuario_id' => $usuario_id
        ]);

        // CORREÇÃO: Retorna true somente se uma linha (ou mais) for realmente alterada.
        return $stmt->rowCount() > 0;
    }
    public function delete($id, $usuario_id) {
        $stmt = $this->pdo->prepare("DELETE FROM tarefas WHERE id = :id AND usuario_id = :usuario_id");
        return $stmt->execute([':id' => $id, ':usuario_id' => $usuario_id]);
    }

    public function toggleStatus($id, $usuario_id) {
        $tarefa = $this->getByIdAndUserId($id, $usuario_id);
        if ($tarefa) {
            $novoStatus = !$tarefa['concluida'];
            $stmt = $this->pdo->prepare("UPDATE tarefas SET concluida = :concluida WHERE id = :id AND usuario_id = :usuario_id");
            return $stmt->execute([':concluida' => $novoStatus, ':id' => $id, ':usuario_id' => $usuario_id]);
        }
        return false;
    }

    public function getAllTarefasAdmin() {
        $stmt = $this->pdo->query("SELECT t.*, u.nome as usuario_nome FROM tarefas t JOIN usuarios u ON t.usuario_id = u.id ORDER BY t.data_criacao DESC");
        return $stmt->fetchAll();
    }

    public function getByDateAndUserId($usuario_id, $data) {
        $sql = "SELECT * FROM tarefas WHERE usuario_id = :usuario_id AND DATE(data_vencimento) = :data ORDER BY data_vencimento ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':usuario_id' => $usuario_id,
            ':data' => $data
        ]);
        return $stmt->fetchAll();
    }

    public function findById($id, $usuario_id) {
        $stmt = $this->pdo->prepare("SELECT * FROM tarefas WHERE id = ? AND usuario_id = ?");
        $stmt->execute([$id, $usuario_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>