<?php
require_once __DIR__ . '/../models/user_model.php';
require_once __DIR__ . '/../../config/database.php'; // Necessário se UserModel não carregar por si só

class UserController {
    private UserModel $userModel;

    public function __construct() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $this->userModel = new UserModel();
    }

    /**
     * Exibe o formulário de registro ou processa o registro.
     */
    public function register(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nome = $_POST['nome'] ?? null;
            $email = $_POST['email'] ?? null;
            $senha = $_POST['senha'] ?? null;
            $confirmar_senha = $_POST['confirmar_senha'] ?? null;
            // $tipo = 'usuario'; // Padrão, pode ser ajustado se houver tipos diferentes no formulário

            if (!$nome || !$email || !$senha || !$confirmar_senha) {
                echo json_encode(['success' => false, 'message' => 'Todos os campos são obrigatórios.']);
                return;
            }
            if ($senha !== $confirmar_senha) {
                echo json_encode(['success' => false, 'message' => 'As senhas não coincidem.']);
                return;
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo json_encode(['success' => false, 'message' => 'Formato de email inválido.']);
                return;
            }

            $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

            try {
                $userId = $this->userModel->criarUsuario($nome, $email, $senha_hash);
                if ($userId) {
                    echo json_encode(['success' => true, 'message' => 'Usuário registrado com sucesso. Faça o login.']);
                    // Idealmente, redirecionar para a página de login
                    // header('Location: /login.php?registration=success');
                } else {
                    // A mensagem de erro específica (email em uso) já é tratada no model e logada.
                    // Para o usuário, uma mensagem genérica pode ser melhor.
                    echo json_encode(['success' => false, 'message' => 'Erro ao registrar usuário. O email pode já estar em uso.']);
                }
            } catch (Exception $e) {
                // Captura exceções como "Email já está em uso" do model
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
        } else {
            // Em uma aplicação real, aqui você carregaria a view do formulário de registro
            // include __DIR__ . '/../views/users/register.php';
            echo "Método register do UserController: Exibir formulário de registro ou lidar com POST.";
        }
    }

    /**
     * Exibe o formulário de login ou processa a autenticação.
     */
    public function login(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? null;
            $senha = $_POST['senha'] ?? null;

            if (!$email || !$senha) {
                echo json_encode(['success' => false, 'message' => 'Email e senha são obrigatórios.']);
                return;
            }

            $user = $this->userModel->autenticarUsuario($email, $senha);

            if ($user) {
                $_SESSION['usuario_id'] = $user['id'];
                $_SESSION['usuario_nome'] = $user['nome'];
                $_SESSION['usuario_email'] = $user['email'];
                $_SESSION['usuario_tipo'] = $user['tipo']; // 'admin' ou 'usuario'
                echo json_encode(['success' => true, 'message' => 'Login bem-sucedido.', 'user' => $user]);
                // Idealmente, redirecionar para o painel ou página inicial
                // header('Location: /dashboard.php');
            } else {
                echo json_encode(['success' => false, 'message' => 'Email ou senha inválidos.']);
            }
        } else {
            // Em uma aplicação real, aqui você carregaria a view do formulário de login
            // include __DIR__ . '/../views/users/login.php';
            echo "Método login do UserController: Exibir formulário de login ou lidar com POST.";
        }
    }

    public function logout(): void {
        session_unset();
        session_destroy();
        echo json_encode(['success' => true, 'message' => 'Logout realizado com sucesso.']);
        // Idealmente, redirecionar para a página inicial ou de login
        // header('Location: /login.php?logout=success');
        exit;
    }

    /**
     * Exibe ou atualiza o perfil do usuário.
     */
    public function profile(): void {
        if (!isset($_SESSION['usuario_id'])) {
            echo json_encode(['success' => false, 'message' => 'Usuário não autenticado.']);
            // header('Location: /login.php');
            exit;
        }

        $userId = (int)$_SESSION['usuario_id'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Atualizar perfil
            $nome = $_POST['nome'] ?? '';
            $email = $_POST['email'] ?? '';
            // $tipo = $_POST['tipo'] ?? null; // Geralmente, o tipo não é alterado pelo próprio usuário.

            if (empty($nome) || empty($email)) {
                echo json_encode(['success' => false, 'message' => 'Nome e email são obrigatórios.']);
                return;
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo json_encode(['success' => false, 'message' => 'Formato de email inválido.']);
                return;
            }

            // O UserModel->atualizarUsuario já verifica se o email está em uso por outro usuário.
            if ($this->userModel->atualizarUsuario($userId, $nome, $email /*, $tipo - se aplicável */)) {
                $_SESSION['usuario_nome'] = $nome; // Atualiza sessão
                $_SESSION['usuario_email'] = $email;
                echo json_encode(['success' => true, 'message' => 'Perfil atualizado com sucesso.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Erro ao atualizar perfil. O email pode já estar em uso.']);
            }
        } else {
            // Exibir perfil
            $user = $this->userModel->getUsuarioId($userId);
            if ($user) {
                // Em uma aplicação real, passaria $user para uma view
                // include __DIR__ . '/../views/users/profile.php';
                unset($user['senha']); // Nunca exponha a senha hash
                echo json_encode(['success' => true, 'user' => $user]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Usuário não encontrado.']);
            }
        }
    }

    public function updatePassword(): void {
        if (!isset($_SESSION['usuario_id'])) {
            echo json_encode(['success' => false, 'message' => 'Usuário não autenticado.']);
            exit;
        }
        $userId = (int)$_SESSION['usuario_id'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $senhaAtual = $_POST['senha_atual'] ?? '';
            $novaSenha = $_POST['nova_senha'] ?? '';
            $confirmarNovaSenha = $_POST['confirmar_nova_senha'] ?? '';

            if (empty($senhaAtual) || empty($novaSenha) || empty($confirmarNovaSenha)) {
                echo json_encode(['success' => false, 'message' => 'Todos os campos de senha são obrigatórios.']);
                return;
            }
            if ($novaSenha !== $confirmarNovaSenha) {
                echo json_encode(['success' => false, 'message' => 'As novas senhas não coincidem.']);
                return;
            }

            if ($this->userModel->atualizarSenha($userId, $senhaAtual, $novaSenha)) {
                echo json_encode(['success' => true, 'message' => 'Senha atualizada com sucesso.']);
            } else {
                // A mensagem de erro específica (senha atual incorreta) é logada pelo model.
                echo json_encode(['success' => false, 'message' => 'Erro ao atualizar senha. Verifique sua senha atual.']);
            }
        } else {
            // Exibir formulário de alteração de senha
            // include __DIR__ . '/../views/users/change_password.php';
            echo "Método updatePassword do UserController: Exibir formulário ou lidar com POST.";
        }
    }
}
?>