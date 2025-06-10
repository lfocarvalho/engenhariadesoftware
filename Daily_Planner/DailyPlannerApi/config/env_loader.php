<?php
// engenhariadesoftware/Daily_Planner/DailyPlannerApi/config/env_loader.php

function loadEnv(string $filePath): void
{
    // Verifica se o arquivo .env existe. Se não, lança uma exceção.
    if (!file_exists($filePath)) {
        throw new Exception("O arquivo .env não foi encontrado em: " . $filePath);
    }

    // Lê o arquivo linha por linha, ignorando linhas vazias e removendo quebras de linha.
    $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);

        // Ignora linhas vazias ou linhas que começam com '#' (comentários).
        if (empty($line) || str_starts_with($line, '#')) {
            continue;
        }

        // Garante que a linha contém um sinal de '=' para ser uma variável.
        if (strpos($line, '=') === false) {
            continue;
        }

        // Divide a linha em chave e valor no primeiro '=' encontrado.
        list($key, $value) = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value);

        // Remove aspas simples ou duplas do valor, se existirem.
        if (str_starts_with($value, '"') && str_ends_with($value, '"')) {
            $value = substr($value, 1, -1);
        } elseif (str_starts_with($value, "'") && str_ends_with($value, "'")) {
            $value = substr($value, 1, -1);
        }

        // Define a variável de ambiente.
        // É importante definir em $_ENV e $_SERVER para garantir que sejam acessíveis
        // em diferentes contextos do PHP. putenv() também é uma boa prática.
        if (!isset($_ENV[$key])) {
            $_ENV[$key] = $value;
        }
        if (!isset($_SERVER[$key])) {
            $_SERVER[$key] = $value;
        }
        // putenv() é útil para algumas extensões ou chamadas de sistema que dependem do ambiente.
        putenv(sprintf('%s=%s', $key, $value));
    }
}
