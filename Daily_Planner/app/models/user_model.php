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
            $query = "SELECT id, nome, email, senha, tipo, created_at FROM " . $this->table_name . " WHERE email = :email LIMIT 1";
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
        $query = "SELECT id, nome, email, tipo, created_at FROM " . $this->table_name . " WHERE id = :id LIMIT 0,1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function criarUsuario($nome, $email, $senha_hash, $tipo = 'usuario') {
        try {
            if ($this->getUsuarioEmail($email)) {
                throw new Exception("Email já está em uso");
            }

            $query = "INSERT INTO " . $this->table_name . " (nome, email, senha, tipo) VALUES (:nome, :email, :senha, :tipo)";
            $stmt = $this->db->prepare($query);

            $nome = htmlspecialchars(strip_tags($nome));
            $email = htmlspecialchars(strip_tags($email));

            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':senha', $senha_hash);
            $stmt->bindParam(':tipo', $tipo);

            if ($stmt->execute()) {
                return $this->db->lastInsertId();
            }
            return false;
        } catch (PDOException $e) {
            error_log("Erro ao criar usuário: " . $e->getMessage());
            return false;
        } catch (Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function atualizarUsuario(int $id, string $nome, string $email, ?string $tipo = null): bool {
        try {
            $existingUser = $this->getUsuarioEmail($email);
            if ($existingUser && $existingUser['id'] != $id) {
                throw new Exception("Email já está em uso por outro usuário");
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
            
            foreach ($params as $key => &$val) {
                $stmt->bindParam($key, $val);
            }

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erro ao atualizar usuário: " . $e->getMessage());
            return false;
        } catch (Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function atualizarSenha(int $id, string $senhaAtual, string $novaSenha): bool {
        try {
            $this->db->beginTransaction();

            $query = "SELECT senha FROM " . $this->table_name . " WHERE id = :id LIMIT 1";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $stored_hash = $stmt->fetchColumn();

            if (!$stored_hash || !password_verify($senhaAtual, $stored_hash)) {
                throw new Exception("Senha atual incorreta");
            }

            $novaSenhaHash = password_hash($novaSenha, PASSWORD_DEFAULT);
            $query = "UPDATE " . $this->table_name . " SET senha = :senha WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':senha', $novaSenhaHash);
            
            $result = $stmt->execute();
            $this->db->commit();
            
            return $result;
        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log("Erro ao atualizar senha: " . $e->getMessage());
            return false;
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log($e->getMessage());
            return false;
        }
    }


    public function excluirUsuario($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function getUsuarios() {
        $query = "SELECT id, nome, email, tipo, created_at FROM " . $this->table_name . " ORDER BY created_at DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function autenticarUsuario($email, $password) {
        $user = $this->getUsuarioEmail($email);
        if ($user && password_verify($password, $user['senha'])) {
            unset($user['senha']);
            return $user;
        }
        return true;
    }
}