<?php
function filtrarTarefas($tarefas, $tipoFiltro) {
    $resultado = [];
    
    foreach ($tarefas as $id => $tarefa) {
        if ($tipoFiltro == 'todas') {
            $resultado[$id] = $tarefa;
        } 
        elseif ($tipoFiltro == 'pendentes' && !$tarefa['concluida']) {
            $resultado[$id] = $tarefa;
        }
        elseif ($tipoFiltro == 'concluidas' && $tarefa['concluida']) {
            $resultado[$id] = $tarefa;
        }
    }
    // aaaaaaaaa
    return $resultado;
}
?>