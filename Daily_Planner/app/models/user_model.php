<?php
require_once __DIR__ . '/../../config/database.php';

class UserModel {
    private $db;
    private $table_name = "usuarios";

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getUsuarioEmail($email) {
        try {
            $query = "SELECT id, nome, email, senha, tipo, data_criacao FROM " . $this->table_name . " WHERE email = :email LIMIT 1";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        } catch (PDOException $e) {
            error_log("Erro ao buscar usuário por email: " . $e->getMessage());
            return null;
        }
    }

    public function getUsuarioId($id) {
        $query = "SELECT id, nome, email, tipo, data_criacao FROM " . $this->table_name . " WHERE id = :id LIMIT 0,1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC); // Alterado para FETCH_ASSOC para consistência
    }

    /**
     * Cria um novo usuário.
     * A senha fornecida ($senha_plana) será hasheada antes de ser armazenada.
     */
    public function criarUsuario($nome, $email, $senha_plana, $tipo = 'usuario') {
        try {
            if ($this->getUsuarioEmail($email)) {
                // Considerar lançar uma exceção mais específica ou retornar uma mensagem de erro.
                error_log("Tentativa de criar usuário com email já existente: " . $email);
                return "Email já está em uso"; // Ou false, ou lançar exceção
            }

            $query = "INSERT INTO " . $this->table_name . " (nome, email, senha, tipo) VALUES (:nome, :email, :senha, :tipo)";
            $stmt = $this->db->prepare($query);

            $nome_seguro = htmlspecialchars(strip_tags($nome));
            $email_seguro = htmlspecialchars(strip_tags($email));
            
            // Gera o hash da senha aqui
            $senha_hash = password_hash($senha_plana, PASSWORD_DEFAULT);

            $stmt->bindParam(':nome', $nome_seguro);
            $stmt->bindParam(':email', $email_seguro);
            $stmt->bindParam(':senha', $senha_hash);
            $stmt->bindParam(':tipo', $tipo);

            if ($stmt->execute()) {
                return $this->db->lastInsertId();
            }
            error_log("Erro ao executar a query de criação de usuário para email: " . $email_seguro);
            return false;
        } catch (PDOException $e) {
            error_log("Erro PDO ao criar usuário: " . $e->getMessage());
            return false;
        }
    }

    public function atualizarUsuario(int $id, string $nome, string $email, ?string $tipo = null): bool {
        try {
            $existingUser = $this->getUsuarioEmail($email);
            if ($existingUser && $existingUser['id'] != $id) {
                 error_log("Tentativa de atualizar para email já em uso por outro usuário. ID: $id, Email: $email");
                return false; // Ou lançar exceção "Email já está em uso por outro usuário"
            }

            $query = "UPDATE " . $this->table_name . " SET nome = :nome, email = :email";
            $params = [
                ':id' => $id,
                ':nome' => htmlspecialchars(strip_tags($nome)),
                ':email' => htmlspecialchars(strip_tags($email))
            ];

            if ($tipo !== null) {
                $query .= ", tipo = :tipo";
                $params[':tipo'] = $tipo;
            }

            $query .= " WHERE id = :id";
            $stmt = $this->db->prepare($query);
            
            // Bind dos parâmetros
            $stmt->bindParam(':id', $params[':id'], PDO::PARAM_INT);
            $stmt->bindParam(':nome', $params[':nome']);
            $stmt->bindParam(':email', $params[':email']);
            if ($tipo !== null) {
                $stmt->bindParam(':tipo', $params[':tipo']);
            }


            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erro PDO ao atualizar usuário: " . $e->getMessage());
            return false;
        }
    }

    public function atualizarSenha(int $id, string $senhaAtual, string $novaSenha): bool {
        try {
            // Não precisa de transação explícita aqui se for apenas uma query,
            // mas é bom para operações mais complexas.
            // $this->db->beginTransaction(); 

            $query = "SELECT senha FROM " . $this->table_name . " WHERE id = :id LIMIT 1";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            $stored_hash = $user ? $user['senha'] : null;

            if (!$stored_hash || !password_verify($senhaAtual, $stored_hash)) {
                error_log("Tentativa de atualização de senha falhou para o usuário ID: $id. Senha atual incorreta.");
                return false; // "Senha atual incorreta"
            }

            $novaSenhaHash = password_hash($novaSenha, PASSWORD_DEFAULT);
            $queryUpdate = "UPDATE " . $this->table_name . " SET senha = :senha WHERE id = :id";
            $stmtUpdate = $this->db->prepare($queryUpdate);
            $stmtUpdate->bindParam(':id', $id, PDO::PARAM_INT);
            $stmtUpdate->bindParam(':senha', $novaSenhaHash);
            
            $result = $stmtUpdate->execute();
            // if ($result) $this->db->commit(); else $this->db->rollBack();
            
            return $result;
        } catch (PDOException $e) {
            // $this->db->rollBack();
            error_log("Erro PDO ao atualizar senha: " . $e->getMessage());
            return false;
        }
    }


    public function excluirUsuario($id) {
        if (!$id || !is_numeric($id)) {
            error_log("Tentativa de exclusão com ID inválido: " . var_export($id, true));
            return false;
        }
        try {
            $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erro PDO ao excluir usuário: " . $e->getMessage());
            return false;
        }
    }

    public function getUsuarios() {
        try {
            $query = "SELECT id, nome, email, tipo, created_at FROM " . $this->table_name . " ORDER BY created_at DESC";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro PDO ao buscar todos os usuários: " . $e->getMessage());
            return []; // Retorna array vazio em caso de erro
        }
    }

    public function autenticarUsuario($email, $password) {
        $user = $this->getUsuarioEmail($email); // getUsuarioEmail já retorna null ou o array do usuário
        
        if ($user && isset($user['senha']) && password_verify($password, $user['senha'])) {
            unset($user['senha']); // Remove o hash da senha do array retornado
            return $user;
        }
        // Log para debug, pode ser removido em produção
        if (!$user) {
            error_log("Tentativa de login falhou: Email não encontrado - " . $email);
        } else if (!isset($user['senha'])) {
            error_log("Tentativa de login falhou: Campo senha não encontrado para o email - " . $email);
        }
        else if (!password_verify($password, $user['senha'])) {
            error_log("Tentativa de login falhou: Senha incorreta para o email - " . $email);
        }
        return false;
    }
}
