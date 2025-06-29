<?php

require_once __DIR__ . '/../models/user_model.php';


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


        if (is_array($usuario) && isset($usuario['id'])) {
            $_SESSION["usuario"] = [
                "id" => $usuario["id"],
                "nome" => $usuario["nome"],
                "apelido" => $usuario["apelido"] ?? "",
                "email" => $usuario["email"],
                "data_nascimento" => $usuario["data_nascimento"] ?? null,
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


require_once __DIR__ . '/../views/login.php';