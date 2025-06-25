<?php
// tests/EdicaoConclusaoTarefaTest.php

use PHPUnit\Framework\TestCase;

class EdicaoConclusaoTarefaTest extends TestCase
{
    private $pdo;
    private $tarefaModel;
    private $user_id = 1;

    /**
     * Prepara o banco de dados antes de cada teste.
     */
    protected function setUp(): void
    {
        $this->pdo = new PDO(
            "mysql:host=localhost;dbname=daily_planner_test", 'root', '',
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );

        $this->pdo->exec("DROP TABLE IF EXISTS tarefas, usuarios;");
        $sql = file_get_contents(__DIR__ . '/../Daily_Planner/app/criar_banco.sql');
        $sql = preg_replace('/CREATE DATABASE IF NOT EXISTS `?daily_planner`?;\s*/i', '', $sql);
        $sql = preg_replace('/USE `?daily_planner`?;\s*/i', '', $sql);
        $this->pdo->exec($sql);
        
        $this->pdo->exec("INSERT INTO usuarios (id, nome, email, senha) VALUES (2, 'Outro Usuario', 'outro@exemplo.com', 'senha_hash')");

        require_once __DIR__ . '/../Daily_Planner/app/models/tarefamodel.php';
        $this->tarefaModel = new TarefaModel($this->pdo);
    }

    /**
     * TESTE DE SUCESSO 1: Verifica se a edição de uma tarefa funciona.
     */
    public function testEdicaoDeTarefaComSucesso()
    {
        $this->tarefaModel->create("Tarefa para editar", "Descrição", "2025-01-01 12:00:00", $this->user_id);
        $tarefaId = $this->pdo->lastInsertId();
        
        $novoTitulo = "Tarefa editada com sucesso";
        $resultado = $this->tarefaModel->update($tarefaId, $novoTitulo, "Desc nova", "2026-01-01 12:00:00", $this->user_id);
        
        $this->assertTrue($resultado);
        $this->assertEquals($novoTitulo, $this->tarefaModel->findById($tarefaId, $this->user_id)['titulo']);
    }

    /**
     * TESTE DE SUCESSO 2: Verifica se a alteração de status (concluir/desfazer) funciona.
     */
    public function testMarcarTarefaComoConcluidaComSucesso()
    {
        $this->tarefaModel->create("Tarefa para concluir", "Descrição", "2025-01-01 12:00:00", $this->user_id);
        $tarefaId = $this->pdo->lastInsertId();
        
        $this->assertEquals(0, $this->tarefaModel->findById($tarefaId, $this->user_id)['concluida']);
        
        $this->tarefaModel->toggleStatus($tarefaId, $this->user_id);
        $this->assertEquals(1, $this->tarefaModel->findById($tarefaId, $this->user_id)['concluida']);
    }

    /**
     * TESTE DE ERRO 1: Verifica se um usuário NÃO consegue editar a tarefa de outro.
     */
    public function testFalhaAoEditarTarefaDeOutroUsuario()
    {
        $this->tarefaModel->create("Tarefa do Usuário 1", "Conteúdo", "2025-01-01 12:00:00", $this->user_id);
        $tarefaId = $this->pdo->lastInsertId();

        $idUsuarioMalicioso = 2;

        $resultado = $this->tarefaModel->update($tarefaId, "Título Malicioso", "...", "2025-01-01 12:00:00", $idUsuarioMalicioso);
        
        $this->assertFalse($resultado);
    }

    /**
     * TESTE DE ERRO 2: Verifica se um usuário NÃO consegue concluir a tarefa de outro.
     */
    public function testFalhaAoConcluirTarefaDeOutroUsuario()
    {
        $this->tarefaModel->create("Tarefa do Usuário 1", "Conteúdo", "2025-01-01 12:00:00", $this->user_id);
        $tarefaId = $this->pdo->lastInsertId();

        $idUsuarioMalicioso = 2;

        $resultado = $this->tarefaModel->toggleStatus($tarefaId, $idUsuarioMalicioso);

        $this->assertFalse($resultado);
    }

    protected function tearDown(): void
    {
        $this->pdo = null;
    }
}