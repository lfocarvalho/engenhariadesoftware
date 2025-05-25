<?php

class TaskModel {
    private PDO $pdo;

    /**
     * Constructor for TaskModel.
     *
     * @param PDO $pdo An instance of the PDO database connection.
     */
    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Creates a new task in the database.
     *
     * @param string $titulo The title of the task.
     * @param string $dataHora The due date and time of the task (format 'YYYY-MM-DD HH:MM:SS').
     * @param int $usuarioId The ID of the user the task belongs to.
     * @param string $descricao An optional description for the task.
     * @return int|false The ID of the newly created task on success, or false on failure.
     */
    public function createTask(string $titulo, string $dataHora, int $usuarioId, string $descricao = ''): int|false {
        // New tasks are created with 'concluida' = false (0) by default as per criar_banco.sql
        $sql = "INSERT INTO tarefas (titulo, descricao, data_vencimento, usuario_id, concluida) VALUES (?, ?, ?, ?, 0)";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$titulo, $descricao, $dataHora, $usuarioId]);
            return (int)$this->pdo->lastInsertId();
        } catch (PDOException $e) {
            // In a real application, log this error
            // error_log("Error creating task: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Updates an existing task in the database.
     *
     * @param int $id The ID of the task to update.
     * @param string $titulo The new title for the task.
     * @param string $dataHora The new due date and time for the task (format 'YYYY-MM-DD HH:MM:SS').
     * @param int $usuarioId The ID of the user who owns the task (for ownership verification).
     * @param string $descricao An optional new description for the task.
     * @return bool True on success, false on failure.
     */
    public function updateTask(int $id, string $titulo, string $dataHora, int $usuarioId, string $descricao = ''): bool {
        // Logic from salvar_tarefa.php and editar_tarefa.php
        $sql = "UPDATE tarefas SET titulo = ?, descricao = ?, data_vencimento = ? WHERE id = ? AND usuario_id = ?";
        try {
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$titulo, $descricao, $dataHora, $id, $usuarioId]);
        } catch (PDOException $e) {
            // error_log("Error updating task: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Deletes a task from the database.
     *
     * @param int $id The ID of the task to delete.
     * @param int $usuarioId The ID of the user who owns the task (for ownership verification).
     * @return int The number of rows affected (1 if successful, 0 otherwise).
     */
    public function deleteTask(int $id, int $usuarioId): int {
        // Logic from excluir_tarefa.php
        $sql = "DELETE FROM tarefas WHERE id = ? AND usuario_id = ?";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$id, $usuarioId]);
            return $stmt->rowCount();
        } catch (PDOException $e) {
            // error_log("Error deleting task: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Toggles the completion status of a task.
     *
     * @param int $id The ID of the task.
     * @param int|null $usuarioId Optional. The ID of the user who owns the task.
     * If provided, the update will also check for task ownership.
     * The original alterar_status.php did not check usuario_id on update.
     * @return bool True on success, false on failure or if the task is not found.
     */
    public function toggleTaskStatus(int $id, ?int $usuarioId = null): bool {
        // First, fetch the current status
        $fetchSql = "SELECT concluida FROM tarefas WHERE id = ?";
        $fetchParams = [$id];

        // If $usuarioId is provided for an ownership check before fetching (though not strictly for toggling)
        // if ($usuarioId !== null) {
        //     $fetchSql .= " AND usuario_id = ?";
        //     $fetchParams[] = $usuarioId;
        // }

        try {
            $stmt = $this->pdo->prepare($fetchSql);
            $stmt->execute($fetchParams);
            $tarefa = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$tarefa) {
                return false; // Task not found
            }

            $novoStatus = !$tarefa['concluida']; // Invert the status

            $updateSql = "UPDATE tarefas SET concluida = ? WHERE id = ?";
            $updateParams = [$novoStatus, $id];

            // If strict ownership is required for updating status
            if ($usuarioId !== null) {
                 $updateSql .= " AND usuario_id = ?"; // Ensure only the owner can change status if rule applies
                 $updateParams[] = $usuarioId;
            }

            $updateStmt = $this->pdo->prepare($updateSql);
            $success = $updateStmt->execute($updateParams);
            return $success && $updateStmt->rowCount() > 0; // Ensure a row was actually updated

        } catch (PDOException $e) {
            // error_log("Error toggling task status: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Fetches a single task by its ID.
     * This is a helper often used before modification or for displaying details.
     *
     * @param int $id The ID of the task.
     * @param int|null $usuarioId Optional. The ID of the user for ownership verification.
     * @return array|false The task data as an associative array, or false if not found.
     */
    public function getTaskById(int $id, ?int $usuarioId = null): array|false {
        $sql = "SELECT * FROM tarefas WHERE id = ?";
        $params = [$id];

        if ($usuarioId !== null) {
            $sql .= " AND usuario_id = ?"; // As seen in editar_tarefa.php
            $params[] = $usuarioId;
        }

        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // error_log("Error fetching task by ID: " . $e->getMessage());
            return false;
        }
    }
}