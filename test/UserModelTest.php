<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

require_once __DIR__ . '/../Daily_Planner/app/models/user_model.php';
require_once __DIR__ . '/../Daily_Planner/config/database.php'; // Inclui Database para simular a conexão PDO

class UserModelTest extends TestCase
{
    private MockObject $pdoMock;
    private UserModel $userModel;

    protected function setUp(): void
    {
        // Mock do PDO para simular o banco de dados
        $this->pdoMock = $this->createMock(PDO::class);
        
        // Mock do Database::getInstance() para retornar nosso PDO mock
        $databaseMock = $this->createMock(Database::class);
        $databaseMock->method('getConnection')->willReturn($this->pdoMock);
        
        // Usa reflection para substituir a instância singleton do Database
        $reflection = new ReflectionClass(Database::class);
        $instanceProperty = $reflection->getProperty('instance');
        $instanceProperty->setAccessible(true);
        $instanceProperty->setValue(null, $databaseMock);

        $this->userModel = new UserModel();
    }

    // Teste para criação de usuário com sucesso
    public function testUsuarioCadastradoComSucesso(): void
    {
        $nome = "Novo Usuário";
        $email = "novo.usuario@example.com";
        $senha = "senhaSegura123";
        $usuarioIdEsperado = 1;

        // Mock para a primeira chamada a prepare (do getUsuarioEmail)
        $stmtSelectMock = $this->createMock(PDOStatement::class);
        $stmtSelectMock->expects($this->once())->method('bindParam')->willReturn(true);
        $stmtSelectMock->expects($this->once())->method('execute')->willReturn(true);
        $stmtSelectMock->expects($this->once())->method('fetch')->willReturn(false); // E-mail não encontrado

        // Mock para a segunda chamada a prepare (do INSERT)
        $stmtInsertMock = $this->createMock(PDOStatement::class);
        $stmtInsertMock->expects($this->exactly(4)) // bindParam é chamado para nome, email, senha, tipo
                       ->method('bindParam')
                       ->willReturn(true);
        $stmtInsertMock->expects($this->once())
                       ->method('execute')
                       ->willReturn(true);

        // Define o comportamento de prepare para chamadas consecutivas
        $this->pdoMock->expects($this->exactly(2))
                      ->method('prepare')
                      ->willReturnOnConsecutiveCalls(
                          $stmtSelectMock, // Primeira chamada para SELECT
                          $stmtInsertMock  // Segunda chamada para INSERT
                      );
        
        // Simula lastInsertId para a criação bem-sucedida, convertendo para string
        $this->pdoMock->expects($this->once())
                      ->method('lastInsertId')
                      ->willReturn((string)$usuarioIdEsperado);

        $resultado = $this->userModel->criarUsuario($nome, $email, $senha);

        $this->assertEquals($usuarioIdEsperado, $resultado);
    }

    // Teste para tentativa de cadastro com e-mail já existente
    public function testCadastroComEmailDuplicado(): void
    {
        $nome = "Usuário Existente";
        $email = "existente@example.com";
        $senha = "senhaQualquer";

        // Mock para a chamada a prepare (do getUsuarioEmail), simulando email existente
        $stmtSelectMock = $this->createMock(PDOStatement::class);
        $stmtSelectMock->expects($this->once())->method('bindParam')->willReturn(true);
        $stmtSelectMock->expects($this->once())->method('execute')->willReturn(true);
        $stmtSelectMock->expects($this->once())->method('fetch')->willReturn(['id' => 1, 'email' => $email, 'nome' => $nome, 'senha' => 'hash_existente', 'tipo' => 'usuario']);

        // Define o comportamento de prepare para a única chamada esperada (para SELECT)
        // **CORRIGIDO AQUI:** A string da query deve ser exata e literal, não pode chamar método inexistente.
        $this->pdoMock->expects($this->once())
                      ->method('prepare')
                      ->with($this->equalTo("SELECT id, nome, email, senha, tipo, data_criacao FROM usuarios WHERE email = :email LIMIT 1"))
                      ->willReturn($stmtSelectMock);
        
        $resultado = $this->userModel->criarUsuario($nome, $email, $senha);

        $this->assertEquals("Email já está em uso", $resultado);
    }

    // Teste para campos ausentes (ex: nome vazio)
    public function testCriarUsuarioCamposVazios(): void
    {
        $nome = ""; // Nome vazio
        $email = "teste@example.com";
        $senha = "senha123";

        // Mock para a primeira chamada a prepare (do getUsuarioEmail)
        $stmtSelectMock = $this->createMock(PDOStatement::class);
        $stmtSelectMock->expects($this->once())->method('bindParam')->willReturn(true);
        $stmtSelectMock->expects($this->once())->method('execute')->willReturn(true);
        $stmtSelectMock->expects($this->once())->method('fetch')->willReturn(false); // E-mail não encontrado

        // Mock para a segunda chamada a prepare (do INSERT), simulando erro do banco
        $stmtInsertMock = $this->createMock(PDOStatement::class);
        $stmtInsertMock->expects($this->exactly(4)) // bindParam é chamado para nome, email, senha, tipo
                       ->method('bindParam')
                       ->willReturn(true);
        $stmtInsertMock->expects($this->once())
                       ->method('execute')
                       ->willThrowException(new PDOException('SQLSTATE[23000]: Integrity constraint violation: 1048 Column \'nome\' cannot be null'));

        // Define o comportamento de prepare para chamadas consecutivas
        $this->pdoMock->expects($this->exactly(2))
                      ->method('prepare')
                      ->willReturnOnConsecutiveCalls(
                          $stmtSelectMock, // Primeira chamada para SELECT
                          $stmtInsertMock  // Segunda chamada para INSERT
                      );

        $resultado = $this->userModel->criarUsuario($nome, $email, $senha);
        $this->assertFalse($resultado); // Esperamos false em caso de erro no PDO
    }

    // Novo teste para cadastro com e-mail inválido (formato)
    public function testCadastroComEmailInvalidoFormato(): void
    {
        $nome = "João";
        $email = "email_invalido"; // Formato de e-mail inválido
        $senha = "senhaSegura";

        // Mock para a primeira chamada a prepare (do getUsuarioEmail)
        $stmtSelectMock = $this->createMock(PDOStatement::class);
        $stmtSelectMock->expects($this->once())->method('bindParam')->willReturn(true);
        $stmtSelectMock->expects($this->once())->method('execute')->willReturn(true);
        $stmtSelectMock->expects($this->once())->method('fetch')->willReturn(false); // E-mail não encontrado

        // Mock para a segunda chamada a prepare (do INSERT), simulando erro de formato do banco
        $stmtInsertMock = $this->createMock(PDOStatement::class);
        $stmtInsertMock->expects($this->exactly(4)) // bindParam é chamado para nome, email, senha, tipo
                       ->method('bindParam')
                       ->willReturn(true);
        $stmtInsertMock->expects($this->once())
                       ->method('execute')
                       ->willThrowException(new PDOException('SQLSTATE[22003]: Numeric value out of range: 1406 Data too long for column \'email\' at row 1 (ou erro de formato se o DB tiver validação mais estrita)'));

        // Define o comportamento de prepare para chamadas consecutivas
        $this->pdoMock->expects($this->exactly(2))
                      ->method('prepare')
                      ->willReturnOnConsecutiveCalls(
                          $stmtSelectMock, // Primeira chamada para SELECT
                          $stmtInsertMock  // Segunda chamada para INSERT
                      );

        $resultado = $this->userModel->criarUsuario($nome, $email, $senha);
        $this->assertFalse($resultado); // Esperamos false em caso de erro no PDO
    }

    // Teste para atualização de usuário
    public function testAtualizarUsuarioSucesso(): void
    {
        $id = 1;
        $nome = "Nome Atualizado";
        $email = "email.atualizado@example.com";
        $tipo = "admin";

        // Mock para a verificação de e-mail existente (getUsuarioEmail)
        $stmtSelectExistingMock = $this->createMock(PDOStatement::class);
        $stmtSelectExistingMock->expects($this->once())->method('bindParam')->willReturn(true);
        $stmtSelectExistingMock->expects($this->once())->method('execute')->willReturn(true);
        $stmtSelectExistingMock->expects($this->once())->method('fetch')->willReturn(false); // Nenhuma duplicidade encontrada

        // Mock para a query de UPDATE
        $stmtUpdateMock = $this->createMock(PDOStatement::class);
        // Bind para :nome, :email, :tipo (se não for null), :id
        $stmtUpdateMock->expects($this->exactly(4)) // Agora espera 4 chamadas para bindParam, incluindo o tipo
                       ->method('bindParam')
                       ->willReturn(true);
        $stmtUpdateMock->expects($this->once())->method('execute')->willReturn(true);

        // Define o comportamento de prepare para chamadas consecutivas
        $this->pdoMock->expects($this->exactly(2))
                      ->method('prepare')
                      ->willReturnOnConsecutiveCalls(
                          $stmtSelectExistingMock, // Primeira chamada para SELECT (verificação de e-mail)
                          $stmtUpdateMock          // Segunda chamada para UPDATE
                      );
        
        $resultado = $this->userModel->atualizarUsuario($id, $nome, $email, $tipo);
        $this->assertTrue($resultado);
    }

    // Teste para exclusão de usuário
    public function testExcluirUsuarioSucesso(): void
    {
        $id = 1;

        $stmtMock = $this->createMock(PDOStatement::class);
        $stmtMock->expects($this->once())->method('bindParam')->willReturn(true);
        $stmtMock->expects($this->once())->method('execute')->willReturn(true);

        $this->pdoMock->expects($this->once())
                      ->method('prepare')
                      ->with($this->stringContains('DELETE FROM usuarios WHERE id = :id'))
                      ->willReturn($stmtMock);

        $resultado = $this->userModel->excluirUsuario($id);
        $this->assertTrue($resultado);
    }

    // Teste para atualizar senha com sucesso
    public function testAtualizarSenhaSucesso(): void
    {
        $id = 1;
        $senhaAtual = "senhaAntiga123";
        $novaSenha = "novaSenhaForte456";
        $senhaHashAntiga = password_hash($senhaAtual, PASSWORD_DEFAULT);

        // Mock para a busca da senha atual
        $stmtFetchMock = $this->createMock(PDOStatement::class);
        $stmtFetchMock->expects($this->once())->method('bindParam')->willReturn(true);
        $stmtFetchMock->expects($this->once())->method('execute')->willReturn(true);
        $stmtFetchMock->expects($this->once())->method('fetch')->willReturn(['senha' => $senhaHashAntiga]);

        // Mock para a atualização da senha
        $stmtUpdateMock = $this->createMock(PDOStatement::class);
        $stmtUpdateMock->expects($this->exactly(2)) // Agora espera 2 chamadas para bindParam (:id e :senha)
                       ->method('bindParam')
                       ->willReturn(true);
        $stmtUpdateMock->expects($this->once())->method('execute')->willReturn(true);

        $this->pdoMock->expects($this->exactly(2))
                      ->method('prepare')
                      ->willReturnOnConsecutiveCalls(
                          $this->returnValue($stmtFetchMock),
                          $this->returnValue($stmtUpdateMock)
                      );

        $resultado = $this->userModel->atualizarSenha($id, $senhaAtual, $novaSenha);
        $this->assertTrue($resultado);
    }

    // Teste para atualizar senha com senha atual incorreta
    public function testAtualizarSenhaIncorreta(): void
    {
        $id = 1;
        $senhaAtual = "senhaErrada";
        $novaSenha = "novaSenhaForte456";
        $senhaHashCorreta = password_hash("senhaCorreta123", PASSWORD_DEFAULT);

        // Mock para a busca da senha atual (retorna uma senha hash diferente)
        $stmtFetchMock = $this->createMock(PDOStatement::class);
        $stmtFetchMock->expects($this->once())->method('bindParam')->willReturn(true);
        $stmtFetchMock->expects($this->once())->method('execute')->willReturn(true);
        $stmtFetchMock->expects($this->once())->method('fetch')->willReturn(['senha' => $senhaHashCorreta]);

        $this->pdoMock->expects($this->once())
                      ->method('prepare')
                      ->with($this->stringContains('SELECT senha FROM'))
                      ->willReturn($stmtFetchMock);

        $resultado = $this->userModel->atualizarSenha($id, $senhaAtual, $novaSenha);
        $this->assertFalse($resultado);
    }
}