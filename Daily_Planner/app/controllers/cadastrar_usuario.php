<?php
require_once __DIR__ . '/../models/user_model.php';

$erro = '';
$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $senha = trim($_POST['senha']);

    if (empty($nome) || empty($email) || empty($senha)) {
        $erro = "Por favor, preencha todos os campos.";
    } else {
        $userModel = new UserModel();
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
        $resultado = $userModel->criarUsuario($nome, $email, $senha_hash);

        if ($resultado) {
            $mensagem = "Usuário cadastrado com sucesso! <a href='login.php'>Clique aqui para fazer login</a>.";
        } else {
            $erro = "Erro ao cadastrar usuário. Verifique se o email já está cadastrado.";
        }
    }
}

// Inclui a view (apenas HTML)
require_once __DIR__ . '/../views/cadastrar_usuario.php';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Usuario</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
    <div class="container">
        <h1>Cadastrar Usuario</h1>
        <form method="POST">
            <br>Nome de Usuario:  <input type="text" name="nome" placeholder="Nome" required><br>
            <br>E-mail:  <input type="email" name="email" placeholder="Email" required><br>
            <br>Senha:  <input type="password" name="senha" placeholder="Senha" required><br>
            <br><button type="submit">Cadastrar</button>
        </form>
        <br><a href="index.php">Voltar para página inicial</a>
    </div>

</body>
</html>
