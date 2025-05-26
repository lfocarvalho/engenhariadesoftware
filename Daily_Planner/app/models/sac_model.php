<?php

namespace App\Model;

use DateTime;
use DateTimeInterface;

class SAC {

    private ?int $id = null;
    private ?string $protocolo = null;
    private ?string $assunto = null;
    private ?string $descricao = null;
    private ?DateTimeInterface $dataAbertura = null;
    private ?DateTimeInterface $dataFechamento = null;
    private ?string $status = null; // Ex: ABERTO, EM_ATENDIMENTO, RESOLVIDO, FECHADO
    private ?int $clienteId = null; // ID do cliente
    private ?int $atendenteId = null; // ID do atendente
    private ?string $tipo = null; // Ex: DUVIDA, RECLAMACAO, SUGESTAO

    // Constantes para status
    public const STATUS_ABERTO = 'ABERTO';
    public const STATUS_EM_ATENDIMENTO = 'EM_ATENDIMENTO';
    public const STATUS_RESOLVIDO = 'RESOLVIDO';
    public const STATUS_FECHADO = 'FECHADO';

    // Constantes para tipo
    public const TIPO_DUVIDA = 'DUVIDA';
    public const TIPO_RECLAMACAO = 'RECLAMACAO';
    public const TIPO_SUGESTAO = 'SUGESTAO';
    public const TIPO_SUPORTE_TECNICO = 'SUPORTE_TECNICO';

    public function __construct(
        ?string $protocolo = null,
        ?string $assunto = null,
        ?string $descricao = null,
        ?int $clienteId = null,
        ?string $tipo = null,
        ?string $status = self::STATUS_ABERTO
    ) {
        $this->protocolo = $protocolo ?? uniqid('SAC_'); // Gera um protocolo simples
        $this->assunto = $assunto;
        $this->descricao = $descricao;
        $this->dataAbertura = new DateTime();
        $this->clienteId = $clienteId;
        $this->tipo = $tipo;
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

    public function getProtocolo(): ?string {
        return $this->protocolo;
    }

    public function setProtocolo(string $protocolo): self {
        $this->protocolo = $protocolo;
        return $this;
    }

    public function getAssunto(): ?string {
        return $this->assunto;
    }

    public function setAssunto(string $assunto): self {
        $this->assunto = $assunto;
        return $this;
    }

    public function getDescricao(): ?string {
        return $this->descricao;
    }

    public function setDescricao(string $descricao): self {
        $this->descricao = $descricao;
        return $this;
    }

    public function getDataAbertura(): ?DateTimeInterface {
        return $this->dataAbertura;
    }

    public function setDataAbertura(DateTimeInterface $dataAbertura): self {
        $this->dataAbertura = $dataAbertura;
        return $this;
    }

    public function getDataFechamento(): ?DateTimeInterface {
        return $this->dataFechamento;
    }

    public function setDataFechamento(?DateTimeInterface $dataFechamento): self {
        $this->dataFechamento = $dataFechamento;
        return $this;
    }

    public function getStatus(): ?string {
        return $this->status;
    }

    public function setStatus(string $status): self {
        $this->status = $status;
        return $this;
    }

    public function getClienteId(): ?int {
        return $this->clienteId;
    }

    public function setClienteId(?int $clienteId): self {
        $this->clienteId = $clienteId;
        return $this;
    }

    public function getAtendenteId(): ?int {
        return $this->atendenteId;
    }

    public function setAtendenteId(?int $atendenteId): self {
        $this->atendenteId = $atendenteId;
        return $this;
    }

    public function getTipo(): ?string {
        return $this->tipo;
    }

    public function setTipo(?string $tipo): self {
        $this->tipo = $tipo;
        return $this;
    }

    /**
     * Fecha o chamado de SAC.
     */
    public function fecharChamado(): self {
        $this->status = self::STATUS_FECHADO;
        $this->dataFechamento = new DateTime();
        return $this;
    }
}

?>