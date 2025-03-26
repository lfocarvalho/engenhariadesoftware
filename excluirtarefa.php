<?php
if (!empty($_GET['id'])) {
    $id = $_GET['id'];
    $arquivo = 'excluirtarefa.json';

//verifica se exite o arquivo
    if (file_exists($arquivo)) {
        $dados = json_decode(file_get_contents($arquivo), true);

        // Remove o usuário com o ID informado
        $novosDados = [];
        foreach ($dados as $usuario) {
            if ($usuario['id'] != $id) {
                $novosDados[] = $usuario;
            }
        }

        // Salva o novo JSON
        file_put_contents($arquivo, json_encode($novosDados, JSON_PRETTY_PRINT));
    }
}
 // Redireciona o usuário para a página do sistema
header('index.php');
exit();
?>
