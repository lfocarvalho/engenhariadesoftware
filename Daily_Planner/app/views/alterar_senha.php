<?php
session_start();
$mensagem = '';
if (isset($_GET['sucesso']) && $_GET['sucesso'] == 1) {
    $mensagem = "Senha alterada com sucesso! Faça login novamente.";
}
if (isset($_GET['erro'])) {
    $mensagem = "Ocorreu um erro ao alterar a senha. Tente novamente.";
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Senha Alterada</title>
    <link rel="stylesheet" href="dashboard.css">
    <style>
        .container {
            max-width: 400px;
            margin: 80px auto;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 8px #bca18c22;
            padding: 2rem;
            text-align: center;
        }
        .mensagem {
            margin-bottom: 1.5rem;
            color: #2e7d32;
            font-weight: bold;
        }
        .btn {
            background: linear-gradient(90deg, #a86f3c 60%, #755F52 100%);
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 12px 24px;
            font-size: 16px;
            cursor: pointer;
            text-decoration: none;
        }
        .btn:hover {
            background: #604A3E;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Alteração de Senha</h2>
        <?php if ($mensagem): ?>
            <div class="mensagem"><?php echo $mensagem; ?></div>
        <?php endif; ?>
        <a href="login.php" class="btn">Ir para o Login</a>
    </div>
</body>
</html>