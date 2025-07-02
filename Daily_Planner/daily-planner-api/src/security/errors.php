<?php
// errors.php

function responderComErro($codigo, $mensagem) {
    http_response_code($codigo);
    echo json_encode([
        'erro' => $mensagem,
        'codigo' => $codigo
    ]);
    exit;
}
