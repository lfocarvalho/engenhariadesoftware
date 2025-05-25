<?php

namespace App\Model;

use DateTime;
use DateTimeInterface;

class Atividade {

    private ?int $id = null;
    private ?string $descricao = null;
    private ?DateTimeInterface $dataCriacao = null;
    private ?DateTimeInterface $dataConclusaoPrevista = null;
    private ?DateTimeInterface $dataConclusaoEfetiva = null;
    private ?string $status = null; // Ex: PENDENTE, EM_ANDAMENTO, CONCLUIDA
    private ?int $responsavelId = null; // ID do usuário responsável

    // Constantes para status (alternativa a Enums em versões mais antigas do PHP)
    public const STATUS_PENDENTE = 'PENDENTE';
    public const STATUS_EM_ANDAMENTO = 'EM_ANDAMENTO';
    public const STATUS_CONCLUIDA = 'CONCLUIDA';

    public function __construct(
        ?string $descricao = null,
        ?DateTimeInterface $dataCriacao = null,
        ?DateTimeInterface $dataConclusaoPrevista = null,
        ?int $responsavelId = null,
        ?string $status = self::STATUS_PENDENTE
    ) {
        $this->descricao = $descricao;
        $this->dataCriacao = $dataCriacao ?? new DateTime();
        $this->dataConclusaoPrevista = $dataConclusaoPrevista;
        $this->responsavelId = $responsavelId;
        $this->status = $status;
    }

    // Getters e Setters

    public function getId(): ?int {
        return $this->id;
    }

    public function setId(int $id): self {
        $this->id = $id;
        return $this;
    }

    public function getDescricao(): ?string {
        return $this->descricao;
    }

    public function setDescricao(string $descricao): self {
        $this->descricao = $descricao;
        return $this;
    }

    public function getDataCriacao(): ?DateTimeInterface {
        return $this->dataCriacao;
    }

    public function setDataCriacao(DateTimeInterface $dataCriacao): self {
        $this->dataCriacao = $dataCriacao;
        return $this;
    }

    public function getDataConclusaoPrevista(): ?DateTimeInterface {
        return $this->dataConclusaoPrevista;
    }

    public function setDataConclusaoPrevista(?DateTimeInterface $dataConclusaoPrevista): self {
        $this->dataConclusaoPrevista = $dataConclusaoPrevista;
        return $this;
    }

    public function getDataConclusaoEfetiva(): ?DateTimeInterface {
        return $this->dataConclusaoEfetiva;
    }

    public function setDataConclusaoEfetiva(?DateTimeInterface $dataConclusaoEfetiva): self {
        $this->dataConclusaoEfetiva = $dataConclusaoEfetiva;
        return $this;
    }

    public function getStatus(): ?string {
        return $this->status;
    }

    public function setStatus(string $status): self {
        // Poderia adicionar validação para garantir que o status é um dos permitidos
        $this->status = $status;
        return $this;
    }

    public function getResponsavelId(): ?int {
        return $this->responsavelId;
    }

    public function setResponsavelId(?int $responsavelId): self {
        $this->responsavelId = $responsavelId;
        return $this;
    }

    /**
     * Marcar a atividade como concluída.
     */
    public function concluir(): self {
        $this->status = self::STATUS_CONCLUIDA;
        $this->dataConclusaoEfetiva = new DateTime();
        return $this;
    }
}

?>