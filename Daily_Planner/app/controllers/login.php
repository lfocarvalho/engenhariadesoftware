<?php

require_once __DIR__ . '/../models/user_model.php';

// Verifica se uma sessão já foi iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$erro = '';
$mensagem = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $senha = trim($_POST["senha"]);

    if (empty($email) || empty($senha)) {
        $erro = "Por favor, preencha todos os campos.";
    } else {
        $userModel = new UserModel();
        $usuario = $userModel->autenticarUsuario($email, $senha);

        // só faz login se $usuario for array (usuário válido)
        if (is_array($usuario) && isset($usuario['id'])) {
            $_SESSION["usuario"] = [
                "id" => $usuario["id"],
                "nome" => $usuario["nome"],
                "email" => $usuario["email"],
                "tipo" => $usuario["tipo"] ?? "usuario"
            ];
            header("Location: ../views/dashboard.html");
            exit();
        } else {
            header("Location: ../views/login.php?erro=1");
            exit();
        }
    }
}

// Inclui a view (apenas HTML)
require_once __DIR__ . '/../views/login.php';