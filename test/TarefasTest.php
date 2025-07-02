<?php
// filepath: c:\xampp\htdocs\engenhariadesoftware\test\TarefasTest.php

// Inclui a classe TarefaModel. Esta será carregada uma única vez.
// Agora testaremos os métodos dela diretamente.
require_once __DIR__ . '/../Daily_Planner/app/models/TarefaModel.php';
// Inclui a classe Database para que a sua definição esteja disponível para o mocking.
// O código global dentro de database.php (como session_start() ou die())
// será efetivamente inibido pelo mocking da instância singleton de Database.
require_once __DIR__ . '/../Daily_Planner/config/database.php';


use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

// A anotação @runInSeparateProcess não é estritamente necessária aqui
// porque não estamos a incluir um script de controlador que termina com exit()/die(),
// mas pode ser mantida para garantir um isolamento máximo, se desejado.
// Removemos, pois agora os testes são mais unitários e não simulam requisições HTTP completas.
// /** @runInSeparateProcess */
class TarefasTest extends TestCase
{
    /**
     * @var MockObject|PDO Mock object para a conexão PDO com o banco de dados.
     */
    private $pdoMock;

    /**
     * @var TarefaModel Instância real de TarefaModel, que receberá o mock PDO.
     */
    private TarefaModel $tarefaModel; // Alterado para ser uma instância real de TarefaModel

    /**
     * Define o ambiente de teste antes de cada método de teste ser executado.
     * Isso garante um estado limpo para cada teste.
     */
    protected function setUp(): void
    {
        // Limpa variáveis de estado globais (não tão crítico sem @runInSeparateProcess, mas boa prática).
        $_SESSION = [];
        $_POST = [];
        $_SERVER = [];
        unset($GLOBALS['tarefaModel']); // Não usaremos mais $GLOBALS['tarefaModel'] para o mock
        unset($GLOBALS['pdo']);

        // Cria um mock para a classe PDO.
        $this->pdoMock = $this->createMock(PDO::class);

        // Cria um mock para a classe Database.
        $databaseMock = $this->createMock(\Database::class);

        // Configura o mock de Database para retornar nosso mock PDO quando getConnection() for chamado.
        $databaseMock->method('getConnection')->willReturn($this->pdoMock);

        // Usa Reflection para substituir a instância singleton do Database.
        // Isso é crucial: garante que QUALQUER chamada futura a Database::getInstance()
        // (ex: de um modelo que o use) receberá o nosso mock.
        $reflection = new ReflectionClass(\Database::class);
        $instanceProperty = $reflection->getProperty('instance');
        $instanceProperty->setAccessible(true);
        $instanceProperty->setValue(null, $databaseMock);

        // Instancia o TarefaModel com o nosso mock PDO.
        $this->tarefaModel = new TarefaModel($this->pdoMock);
    }

    /**
     * Limpa o ambiente após a execução de cada método de teste.
     */
    protected function tearDown(): void
    {
        // Limpa variáveis de estado globais.
        $_SESSION = [];
        unset($_POST);
        unset($_SERVER);
        unset($GLOBALS['tarefaModel']);
        unset($GLOBALS['pdo']);

        // Restaura a instância singleton de Database para null.
        $reflection = new ReflectionClass(\Database::class);
        $instanceProperty = $reflection->getProperty('instance');
        $instanceProperty->setAccessible(true);
        $instanceProperty->setValue(null, null);
    }

    /**
     * Testa a criação de uma tarefa com sucesso diretamente no modelo.
     */
    public function testCriarTarefaComSucesso(): void
    {
        echo "\n--- Início do Teste: testCriarTarefaComSucesso ---\n";

        // Mock do PDOStatement para simular a execução da query INSERT.
        $stmtMock = $this->createMock(PDOStatement::class);
        $stmtMock->expects($this->once())
                 ->method('execute')
                 ->willReturn(true); // Sucesso na execução
        echo "  - Mock PDOStatement::execute configurado para retornar 'true'.\n";

        // Configura o mock do PDO para retornar o PDOStatement mockado quando prepare() for chamado.
        $this->pdoMock->expects($this->once())
                      ->method('prepare')
                      ->with($this->stringContains('INSERT INTO tarefas'))
                      ->willReturn($stmtMock);
        echo "  - Mock PDO::prepare configurado para retornar o PDOStatement mockado para INSERT.\n";

        // Chama o método create diretamente no modelo.
        $resultado = $this->tarefaModel->create('Título Teste', 'Descrição Teste', '2025-06-25 10:00:00', 1);
        echo "  - Tarefa criada com: Título='Título Teste', Descrição='Descrição Teste', Data='2025-06-25 10:00:00', Usuário ID=1.\n";
        echo "  - Resultado da chamada a TarefaModel::create(): " . var_export($resultado, true) . "\n";

        // Verifica o resultado.
        $this->assertTrue($resultado, 'A criação da tarefa deve retornar verdadeiro em caso de sucesso.');
        echo "  - Asserção: Resultado é 'true'.\n";

        echo "--- Fim do Teste: testCriarTarefaComSucesso ---\n";
    }

    /**
     * Testa a criação de uma tarefa sem título.
     * Este teste espera que uma PDOException seja lançada porque a coluna 'titulo' é NOT NULL.
     */
    public function testCriarTarefaSemTitulo(): void
    {
        echo "\n--- Início do Teste: testCriarTarefaSemTitulo ---\n";

        // Mock do PDOStatement.
        $stmtMock = $this->createMock(PDOStatement::class);

        // Configura o mock do execute para lançar uma PDOException.
        // Simulamos a exceção que o PDO lançaria se a restrição NOT NULL fosse violada.
        $stmtMock->expects($this->once())
                 ->method('execute')
                 ->willThrowException(new PDOException('SQLSTATE[23000]: Integrity constraint violation: 1048 Column \'titulo\' cannot be null', 1048));
        echo "  - Mock PDOStatement::execute configurado para lançar PDOException (NOT NULL 'titulo').\n";


        // Configura o mock do PDO para retornar o PDOStatement mockado quando prepare() for chamado.
        $this->pdoMock->expects($this->once())
                      ->method('prepare')
                      ->with($this->stringContains('INSERT INTO tarefas'))
                      ->willReturn($stmtMock);
        echo "  - Mock PDO::prepare configurado para retornar o PDOStatement mockado para INSERT.\n";

        // Espera que uma PDOException seja lançada quando o método create for chamado.
        $this->expectException(PDOException::class);
        $this->expectExceptionCode(1048); // Código de erro comum para NOT NULL
        $this->expectExceptionMessageMatches('/Column \'titulo\' cannot be null/'); // Mensagem de erro
        echo "  - Asserção: Espera-se uma PDOException com código 1048 e mensagem sobre 'titulo' NOT NULL.\n";

        // Chama o método create diretamente no modelo com um título vazio.
        $this->tarefaModel->create('', 'Descrição Teste', '2025-06-25 10:00:00', 1);
        echo "  - Chamada a TarefaModel::create() com título vazio. (Se o teste passar, a exceção foi capturada).\n";

        echo "--- Fim do Teste: testCriarTarefaSemTitulo ---\n";
    }
}
