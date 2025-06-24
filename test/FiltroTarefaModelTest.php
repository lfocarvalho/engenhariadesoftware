<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

// Ajuste os caminhos conforme a estrutura do seu projeto
require_once __DIR__ . '/../Daily_Planner/app/models/tarefamodel.php'; //
require_once __DIR__ . '/../Daily_Planner/config/database.php'; //

class FiltroTarefaModelTest extends TestCase
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
     * Testa o filtro de tarefas para retornar apenas as tarefas concluídas.
     */
    public function testFiltroTarefasConcluidas()
    {
        fwrite(STDERR, "\n[DEBUG] Iniciando teste: " . __METHOD__ . "\n");
        $usuarioId = 1; //
        $filtro = 'concluidas'; //
        $tarefasConcluidasEsperadas = [
            ['id' => 1, 'titulo' => 'Tarefa Concluída 1', 'concluida' => 1, 'usuario_id' => $usuarioId],
            ['id' => 3, 'titulo' => 'Tarefa Concluída 2', 'concluida' => 1, 'usuario_id' => $usuarioId],
        ]; //

        // Mock para a query de SELECT com filtro
        $stmtMock = $this->createMock(PDOStatement::class); //
        $stmtMock->expects($this->once())
                 ->method('execute')
                 ->with([':usuario_id' => $usuarioId])
                 ->willReturn(true); //
        
        $stmtMock->expects($this->once())
                 ->method('fetchAll')
                 ->willReturn($tarefasConcluidasEsperadas); //

        $this->pdoMock->expects($this->once())
                      ->method('prepare')
                      ->with("SELECT * FROM tarefas WHERE usuario_id = :usuario_id AND concluida = 1 ORDER BY data_vencimento DESC, id DESC")
                      ->willReturn($stmtMock); //

        fwrite(STDERR, "[DEBUG] Chamando tarefaModel->getAllByUserId() com filtro: '$filtro'\n");
        $resultado = $this->tarefaModel->getAllByUserId($usuarioId, $filtro); //

        // --- CORREÇÃO DO LOG ---
        // 1. Prepara a string do array em uma variável separada.
        $resultadoEmString = print_r($resultado, true);
        
        // 2. Imprime o log de forma mais organizada e clara.
        fwrite(STDERR, "[DEBUG] --- INÍCIO DO RESULTADO DO MODELO ---\n");
        fwrite(STDERR, $resultadoEmString);
        fwrite(STDERR, "[DEBUG] --- FIM DO RESULTADO DO MODELO ---\n");
        
        $this->assertEquals(
            $tarefasConcluidasEsperadas, 
            $resultado,
            "A filtragem de tarefas concluídas não retornou o resultado esperado."
        ); //
    
        foreach ($resultado as $tarefa) {
            $this->assertEquals(
                1, 
                $tarefa['concluida'],
                "A tarefa com ID {$tarefa['id']} deveria estar marcada como concluída, mas não está."
            ); //
        }
        fwrite(STDERR, "[DEBUG] Teste '" . __METHOD__ . "' concluído com sucesso.\n");
    }
}