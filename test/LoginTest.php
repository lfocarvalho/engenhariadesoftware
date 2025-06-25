<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

require_once __DIR__ . '/../Daily_Planner/app/models/user_model.php';
require_once __DIR__ . '/../Daily_Planner/config/database.php';

class LoginTest extends TestCase
{
    private MockObject $pdoMock;
    private UserModel $userModel;

    protected function setUp(): void
    {
        // Mock do PDO
        $this->pdoMock = $this->createMock(PDO::class);
        
        // Mock do Database para retornar nosso PDO mock
        $databaseMock = $this->createMock(Database::class);
        $databaseMock->method('getConnection')->willReturn($this->pdoMock);
        
        // Substitui a instância singleton do Database
        $reflection = new ReflectionClass(Database::class);
        $instanceProperty = $reflection->getProperty('instance');
        $instanceProperty->setAccessible(true);
        $instanceProperty->setValue(null, $databaseMock);

        $this->userModel = new UserModel();
    }

    // Teste para login válido
    public function testLoginValido(): void
    {
        $email = "usuario@example.com";
        $senha = "senhaCorreta123";
        $senhaHash = password_hash($senha, PASSWORD_BCRYPT);

        // Mock da consulta ao banco
        $stmtMock = $this->createMock(PDOStatement::class);
        $stmtMock->expects($this->once())
                 ->method('bindParam')
                 ->with(':email', $email);
        $stmtMock->expects($this->once())
                 ->method('execute')
                 ->willReturn(true);
        $stmtMock->expects($this->once())
                 ->method('fetch')
                 ->willReturn([
                     'id' => 1,
                     'nome' => 'Usuário Teste',
                     'email' => $email,
                     'senha' => $senhaHash,
                     'tipo' => 'usuario'
                 ]);

        $this->pdoMock->expects($this->once())
                      ->method('prepare')
                      ->with($this->equalTo('SELECT id, nome, email, senha, tipo, data_criacao FROM usuarios WHERE email = :email LIMIT 1'))
                      ->willReturn($stmtMock);

        $resultado = $this->userModel->login($email, $senha);

        $this->assertIsArray($resultado);
        $this->assertEquals(1, $resultado['id']);
        $this->assertEquals($email, $resultado['email']);
    }

    // Teste para login com senha errada
    public function testLoginComSenhaErrada(): void
    {
        $email = "usuario@example.com";
        $senhaCorreta = "senhaCorreta123";
        $senhaErrada = "senhaErrada456";
        $senhaHash = password_hash($senhaCorreta, PASSWORD_BCRYPT);

        $stmtMock = $this->createMock(PDOStatement::class);
        $stmtMock->method('bindParam')->willReturn(true);
        $stmtMock->method('execute')->willReturn(true);
        $stmtMock->method('fetch')->willReturn([
            'id' => 1,
            'nome' => 'Usuário Teste',
            'email' => $email,
            'senha' => $senhaHash,
            'tipo' => 'usuario'
        ]);

        $this->pdoMock->method('prepare')->willReturn($stmtMock);

        $resultado = $this->userModel->login($email, $senhaErrada);

        $this->assertFalse($resultado);
    }

    // Teste para login com usuário inexistente
    public function testLoginComUsuarioInexistente(): void
    {
        $email = "naoexiste@example.com";
        $senha = "qualquerSenha";

        $stmtMock = $this->createMock(PDOStatement::class);
        $stmtMock->method('bindParam')->willReturn(true);
        $stmtMock->method('execute')->willReturn(true);
        $stmtMock->method('fetch')->willReturn(false);

        $this->pdoMock->method('prepare')->willReturn($stmtMock);

        $resultado = $this->userModel->login($email, $senha);

        $this->assertFalse($resultado);
    }

    // Teste para verificação de token (simulado)
    public function testTokenGeradoComSucesso(): void
    {
        // Supondo que o UserModel tenha um método para gerar token
        $id = 1;
        $email = "usuario@example.com";
        
        // Mock da sessão
        $_SESSION = [];

        $resultado = $this->userModel->gerarTokenSessao($id, $email);

        $this->assertIsString($resultado);
        $this->assertNotEmpty($resultado);
        $this->assertArrayHasKey('usuario', $_SESSION);
        $this->assertEquals($id, $_SESSION['usuario']['id']);
    }

    // Teste para logout
    public function testLogout(): void
    {
        // Configura sessão mock
        $_SESSION = [
            'usuario' => [
                'id' => 1,
                'email' => 'usuario@example.com'
            ]
        ];

        $this->userModel->logout();

        $this->assertArrayNotHasKey('usuario', $_SESSION);
    }
}