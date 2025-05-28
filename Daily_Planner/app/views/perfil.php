<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit();
}
require_once 'trocar_senha.php';

$mensagem = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $senhaAtual = $_POST["senha_atual"] ?? '';
    $novaSenha = $_POST["nova_senha"] ?? '';
    $repeteSenha = $_POST["repete_senha"] ?? '';

    if (empty($senhaAtual) || empty($novaSenha) || empty($repeteSenha)) {
        $mensagem = "Preencha todos os campos.";
    } elseif ($novaSenha !== $repeteSenha) {
        $mensagem = "As novas senhas não coincidem.";
    } else {

        $_SESSION['usuario_id'] = $_SESSION['usuario']['id'];
        $mensagem = editarSenhaUsuario($senhaAtual, $novaSenha);
        if ($mensagem === "Senha alterada com sucesso.") {
            session_destroy();
            header("Location: login.php?senha_alterada=1");
            exit();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Perfil - Trocar Senha</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f8f8f8; }
        .container { max-width: 400px; margin: 60px auto; background: #fff; padding: 30px; border-radius: 10px; box-shadow: 0 2px 8px #ccc; }
        h2 { color: #d32f2f; }
        label { display: block; margin-top: 15px; }
        input[type="password"] { width: 100%; padding: 8px; margin-top: 5px; border-radius: 5px; border: 1px solid #ccc; }
        button { margin-top: 20px; padding: 10px 20px; background: #d32f2f; color: #fff; border: none; border-radius: 5px; cursor: pointer; }
        .mensagem { margin-top: 15px; color: #d32f2f; }
        .mensagem.sucesso { color: green; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Trocar Senha</h2>
        <?php if (!empty($mensagem)): ?>
            <div class="mensagem<?php echo (strpos($mensagem, 'sucesso') !== false) ? ' sucesso' : ''; ?>">
                <?php echo $mensagem; ?>
            </div>
        <?php endif; ?>
        <form method="POST">
            <label for="senha_atual">Senha Atual:</label>
            <input type="password" id="senha_atual" name="senha_atual" required>

            <label for="nova_senha">Nova Senha:</label>
            <input type="password" id="nova_senha" name="nova_senha" required>

            <label for="repete_senha">Repita a Nova Senha:</label>
            <input type="password" id="repete_senha" name="repete_senha" required>

            <button type="submit">Alterar Senha</button>
        </form>
    </div>
</body>
</html>