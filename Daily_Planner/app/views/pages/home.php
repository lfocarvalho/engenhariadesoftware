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
  <title>Daily Planner</title>
  <link rel="stylesheet" href="style.css">
  <style>

  </style>
</head>
<body>
  <header class="header-bg">
    <div class="logo">Daily Planner</div>
    <nav class="menu">
      <a href="#inicio">Início</a>
      <a href="#como-funciona">Como funciona</a>
      <a href="#funcionalidades">Funcionalidades</a>
      <a href="#preco">Preço</a>
      <a href="#faq">FAQ</a>
      <a href="#contato">Contato</a>
    </nav>
    <div>
      <a href="login.php" class="login-button">Login</a>
      <a href="cadastrar_usuario.php" class="register-button">Cadastre-se</a>
    </div>
  </header>

  <main class="container">
    <section class="slogan-mascote">
      <div class="slogan-texto">
        <h1>Planeje com calma, Realize com leveza</h1>
        <p>
          Organize seu dia com ajuda da nossa mascote e descubra o poder de pequenos hábitos. Tudo de forma simples e eficiente.
        </p>
        <a href="cadastrar_usuario.php" class="button comecar-agora">Começar agora</a>
      </div>
      <div class="slogan-mascote-img">
        <img src="img/mascote.svg" alt="Mascote Daily Planner" />
      </div>
    </section>
  </main>
</body>
</html>
