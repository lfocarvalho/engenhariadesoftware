<?php
function filtrarTarefas($pdo, $tipoFiltro) {
    // Prepara a consulta SQL de acordo com o tipo de filtro
    $sql = "SELECT * FROM tarefas WHERE 1";  // '1' é uma condição sempre verdadeira

    // Adiciona condições dependendo do filtro
    if ($tipoFiltro == 'pendentes') {
        $sql .= " AND concluida = 0";  // Tarefas pendentes (não concluídas)
    } elseif ($tipoFiltro == 'concluidas') {
        $sql .= " AND concluida = 1";  // Tarefas concluídas
    }

    try {
        // Executa a consulta no banco de dados
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        
        // Retorna os resultados como um array associativo
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // Se ocorrer um erro na consulta, exibe a mensagem de erro
        echo "Erro ao filtrar tarefas: " . $e->getMessage();
        return [];
    }
}
?>
