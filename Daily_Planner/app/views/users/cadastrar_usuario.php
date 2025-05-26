<?php
require __DIR__ . '/../../config/config.php';

// Verifica se uma sessão já foi iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Simulação de usuário (remova isso quando implementar autenticação real)
$_SESSION['usuario']['id'] = 1; // Exemplo: usuário ID 1

$usuario_id = $_SESSION['usuario']['id'] ?? null;
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
