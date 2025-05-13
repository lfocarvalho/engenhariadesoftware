<?php
// Inclui o arquivo de conexão PDO
require 'config.php';  // Substitua com o seu arquivo de conexão PDO

// Verifica se o ID foi passado via URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    try {
        // Consulta a tarefa pelo ID
        $stmt = $pdo->prepare("SELECT concluida FROM tarefas WHERE id = ?");
        $stmt->execute([$id]);
        $tarefa = $stmt->fetch();

        // Verifica se a tarefa foi encontrada
        if ($tarefa) {
            // Inverte o status da tarefa (se concluída, torna-se pendente, e vice-versa)
            $novoStatus = !$tarefa['concluida'];
            
            // Atualiza o status da tarefa no banco de dados
            $updateStmt = $pdo->prepare("UPDATE tarefas SET concluida = ? WHERE id = ?");
            $updateStmt->execute([$novoStatus, $id]);

            // Redireciona para a página principal após a atualização
            header('Location: index.php');
            exit;
        } else {
            echo "Tarefa não encontrada!";
        }
    } catch (PDOException $e) {
        echo "Erro ao atualizar status: " . $e->getMessage();
    }
} else {
    echo "ID não fornecido!";
}
?>
