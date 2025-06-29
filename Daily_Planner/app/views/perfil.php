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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .logout-btn {
            color: #fff;
            font-weight: 700;
            background: linear-gradient(90deg, #a86f3c 60%, #755F52 100%);
            border-radius: 12px;
            padding: 14px 0;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            margin-top: auto;
            text-decoration: none;
            transition: background 0.2s, color 0.2s;
            gap: 10px;
            box-shadow: 0 2px 8px #bca18c22;
        }
        .logout-btn:hover {
            background: #604A3E;
            color: #fff7f7;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar moderna igual ao dashboard -->
        <div class="sidebar">
            <div class="user-profile">
                <img id="profile-pic" src="../img/foto perfil/perfil 1.svg" alt="Foto do usuário" class="profile-pic">
                <button class="change-pic-btn" onclick="openPicModal()" title="Alterar foto de perfil">
                    <i class="fas fa-camera"></i>
                </button>
                <h3 id="username">Carregando...</h3>
                <p id="user-email">Carregando...</p>
            </div>
            <nav>
                <ul>
                    <li><a href="dashboard.html"><i class="fas fa-home"></i> <span>Início</span></a></li>
                    <li><a href="progresso.html"><i class="fas fa-chart-line"></i> <span>Progresso</span></a></li>
                    <li><a href="perfil.php" class="active"><i class="fas fa-user"></i> <span>Perfil</span></a></li>
                    <li>
                        <a href="#" class="logout-btn" onclick="redirectToIndex()" style="margin-top:0;">
                            <i class="fas fa-sign-out-alt"></i> Sair
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
        <div class="perfil-content-area">
            <div class="perfil-center-container">
                <header class="dashboard-header">
                    <h1>Perfil do Usuário</h1>
                    <p>Gerencie suas informações pessoais</p>
                </header>
                <div class="perfil-card">
                    <h2 style="text-align:center;margin-bottom:2rem;">Trocar Senha</h2>
                    <?php if (!empty($mensagem)): ?>
                        <div class="mensagem<?php echo (strpos($mensagem, 'sucesso') !== false) ? ' sucesso' : ''; ?>">
                            <?php echo $mensagem; ?>
                        </div>
                    <?php endif; ?>
                    <form method="POST">
                        <div class="form-group">
                            <label for="senha_atual">Senha Atual:</label>
                            <input type="password" id="senha_atual" name="senha_atual" required>
                        </div>
                        <div class="form-group">
                            <label for="nova_senha">Nova Senha:</label>
                            <input type="password" id="nova_senha" name="nova_senha" required>
                        </div>
                        <div class="form-group">
                            <label for="repete_senha">Repita a Nova Senha:</label>
                            <input type="password" id="repete_senha" name="repete_senha" required>
                        </div>
                        <button type="submit" class="change-password-button">
                            <i class="fas fa-key"></i> Alterar Senha
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal de foto de perfil -->
    <div id="pic-modal" class="pic-modal hidden">
        <div class="pic-modal-content">
            <span class="close-pic-modal" onclick="closePicModal()">&times;</span>
            <h3>Escolha sua foto de perfil</h3>
            <div class="pic-options">
                <img src="../img/foto perfil/perfil 1.svg" onclick="selectProfilePic('../img/foto perfil/perfil 1.svg')" alt="Perfil 1">
                <img src="../img/foto perfil/perfil 2.svg" onclick="selectProfilePic('../img/foto perfil/perfil 2.svg')" alt="Perfil 2">
                <img src="../img/foto perfil/perfil 3.svg" onclick="selectProfilePic('../img/foto perfil/perfil 3.svg')" alt="Perfil 3">
                <img src="../img/foto perfil/perfil 4.svg" onclick="selectProfilePic('../img/foto perfil/perfil 4.svg')" alt="Perfil 4">
                <img src="../img/foto perfil/perfil 5.svg" onclick="selectProfilePic('../img/foto perfil/perfil 5.svg')" alt="Perfil 5">
                <img src="../img/foto perfil/perfil 6.svg" onclick="selectProfilePic('../img/foto perfil/perfil 6.svg')" alt="Perfil 6">
            </div>
        </div>
    </div>
    <script>
    function openPicModal() {
        document.getElementById('pic-modal').classList.remove('hidden');
    }
    function closePicModal() {
        document.getElementById('pic-modal').classList.add('hidden');
    }
    function selectProfilePic(src) {
        document.getElementById('profile-pic').src = src;
        localStorage.setItem('profilePic', src);
        closePicModal();
    }
    window.addEventListener('DOMContentLoaded', function() {
        const savedPic = localStorage.getItem('profilePic');
        if (savedPic) {
            document.getElementById('profile-pic').src = savedPic;
        }
        // Carregar nome e email do usuário na sidebar
        <?php if (isset($_SESSION['usuario'])): ?>
            document.getElementById('username').textContent = <?php echo json_encode($_SESSION['usuario']['nome']); ?>;
            document.getElementById('user-email').textContent = <?php echo json_encode($_SESSION['usuario']['email']); ?>;
        <?php endif; ?>
    });
    function redirectToIndex() {
        const confirmLogout = confirm("Você deseja mesmo sair da conta?");
        if (confirmLogout) {
            window.location.href = "index.php";
        }
    }
    </script>
</body>
</html>