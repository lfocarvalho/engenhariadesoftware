<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
    
    try {
        $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)");
        $stmt->execute([$nome, $email, $senha]);
        
        header("Location: login.php?success=1");
        exit();
    } catch (PDOException $e) {
        $erro = "Erro ao cadastrar: " . (strpos($e->getMessage(), 'Duplicate') ? 'Email já existe' : 'Erro no sistema');
    }
}
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
