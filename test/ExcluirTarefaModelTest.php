<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

// Ajuste os caminhos conforme a estrutura do seu projeto
require_once __DIR__ . '/../Daily_Planner/app/models/tarefamodel.php'; //
require_once __DIR__ . '/../Daily_Planner/config/database.php'; //

class ExcluirTarefaModelTest extends TestCase
{
    private MockObject $pdoMock; //
    private TarefaModel $tarefaModel; //

    protected function setUp(): void
    {
        // Mock do PDO para simular o banco de dados
        $this->pdoMock = $this->createMock(PDO::class); //
        
        // Mock do Database::getInstance() para retornar nosso PDO mock
        $databaseMock = $this->createMock(Database::class); //
        $databaseMock->method('getConnection')->willReturn($this->pdoMock); //
        
        // Usa reflection para substituir a instância singleton do Database
        $reflection = new ReflectionClass(Database::class); //
        $instanceProperty = $reflection->getProperty('instance'); //
        $instanceProperty->setAccessible(true); //
        $instanceProperty->setValue(null, $databaseMock); //

        $this->tarefaModel = new TarefaModel($this->pdoMock); //
    }

    /**
     * Testa a exclusão bem-sucedida de uma tarefa.
     */
    public function testExclusaoDeTarefa()
    {
        fwrite(STDERR, "\n[DEBUG] Iniciando teste: " . __METHOD__ . "\n");
        $tarefaId = 15; //
        $usuarioId = 1; //

        // Mock para a query de DELETE
        $stmtMock = $this->createMock(PDOStatement::class); //
        $stmtMock->expects($this->once())
                 ->method('execute')
                 ->with([':id' => $tarefaId, ':usuario_id' => $usuarioId])
                 ->willReturn(true); //

        $this->pdoMock->expects($this->once())
                      ->method('prepare')
                      ->with("DELETE FROM tarefas WHERE id = :id AND usuario_id = :usuario_id")
                      ->willReturn($stmtMock); //

        fwrite(STDERR, "[DEBUG] Chamando tarefaModel->delete() com tarefaId: $tarefaId e usuarioId: $usuarioId\n");
        $resultado = $this->tarefaModel->delete($tarefaId, $usuarioId); //

        fwrite(STDERR, "[DEBUG] Resultado obtido: " . ($resultado ? 'true' : 'false') . ". Esperado: true\n");
        $this->assertTrue($resultado); //
    }
        public function testFalhaNaExclusaoPorErroPDO()
    {
        fwrite(STDERR, "\n[DEBUG] Iniciando teste: " . __METHOD__ . "\n");
        $tarefaId = 999; //
        $usuarioId = 1; //

        $exceptionMessage = "SQLSTATE[23000]: Integrity constraint violation: 1451 Cannot delete or update a parent row..."; //
        $exception = new PDOException($exceptionMessage); //

        // Mock do PDOStatement para simular a falha
        $stmtMock = $this->createMock(PDOStatement::class); //
        $stmtMock->expects($this->once())
                 ->method('execute')
                 ->with([':id' => $tarefaId, ':usuario_id' => $usuarioId])
                 ->will($this->throwException($exception)); //

        $this->pdoMock->expects($this->once())
                      ->method('prepare')
                      ->with("DELETE FROM tarefas WHERE id = :id AND usuario_id = :usuario_id")
                      ->willReturn($stmtMock); //

        // Informa ao PHPUnit que este teste DEVE lançar uma exceção
        $this->expectException(PDOException::class); //
        $this->expectExceptionMessage($exceptionMessage); //

        fwrite(STDERR, "[DEBUG] Chamando tarefaModel->delete(). Uma PDOException é esperada.\n");
        // Executa o método que causará o erro
        $this->tarefaModel->delete($tarefaId, $usuarioId); //
    }
}