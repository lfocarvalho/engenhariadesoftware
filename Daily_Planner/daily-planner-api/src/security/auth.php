<?php
// auth.php

function autenticar() {
$config = require __DIR__ . '/../../config/seguranca.php';
    $headers = getallheaders();

    if (!isset($headers['Authorization'])) {
        responderComErro(401, 'Cabeçalho de autorização ausente');
    }

    $token = trim(str_replace('Bearer', '', $headers['Authorization']));

    if ($token !== $config['api_key']) {
        responderComErro(401, 'API Key inválida');
    }
}
