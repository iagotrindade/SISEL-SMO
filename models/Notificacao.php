<?php
/**
 * Model para Notificação
 */
class Notificacao
{
    private $id;
    private $tipo;
    private $criticidade;
    private $titulo;
    private $mensagem;
    private $idObrigatorio;
    private $idOm;
    private $dataCriacao;
    private $lida;
    private $dataLeitura;
    private $idUsuarioLeitura;
    private $ativa;

    // Dados relacionados (para views)
    private $obrigatorioNome;
    private $obrigatorioCpf;
    private $omAbreviatura;
    private $omNome;

    // Constantes de tipo
    const TIPO_ADIAMENTO_VENCENDO = 'adiamento_vencendo';
    const TIPO_INCORPORACAO_SEM_REVISAO = 'incorporacao_sem_revisao';

    // Constantes de criticidade
    const CRITICIDADE_ALTA = 'alta';
    const CRITICIDADE_MEDIA = 'media';
    const CRITICIDADE_BAIXA = 'baixa';

    // Getters
    public function getId() { return $this->id; }
    public function getTipo() { return $this->tipo; }
    public function getCriticidade() { return $this->criticidade; }
    public function getTitulo() { return $this->titulo; }
    public function getMensagem() { return $this->mensagem; }
    public function getIdObrigatorio() { return $this->idObrigatorio; }
    public function getIdOm() { return $this->idOm; }
    public function getDataCriacao() { return $this->dataCriacao; }
    public function getLida() { return $this->lida; }
    public function getDataLeitura() { return $this->dataLeitura; }
    public function getIdUsuarioLeitura() { return $this->idUsuarioLeitura; }
    public function getAtiva() { return $this->ativa; }
    public function getObrigatorioNome() { return $this->obrigatorioNome; }
    public function getObrigatorioCpf() { return $this->obrigatorioCpf; }
    public function getOmAbreviatura() { return $this->omAbreviatura; }
    public function getOmNome() { return $this->omNome; }

    // Setters
    public function setId($id) { $this->id = $id; }
    public function setTipo($tipo) { $this->tipo = $tipo; }
    public function setCriticidade($criticidade) { $this->criticidade = $criticidade; }
    public function setTitulo($titulo) { $this->titulo = $titulo; }
    public function setMensagem($mensagem) { $this->mensagem = $mensagem; }
    public function setIdObrigatorio($idObrigatorio) { $this->idObrigatorio = $idObrigatorio; }
    public function setIdOm($idOm) { $this->idOm = $idOm; }
    public function setDataCriacao($dataCriacao) { $this->dataCriacao = $dataCriacao; }
    public function setLida($lida) { $this->lida = $lida; }
    public function setDataLeitura($dataLeitura) { $this->dataLeitura = $dataLeitura; }
    public function setIdUsuarioLeitura($idUsuarioLeitura) { $this->idUsuarioLeitura = $idUsuarioLeitura; }
    public function setAtiva($ativa) { $this->ativa = $ativa; }
    public function setObrigatorioNome($nome) { $this->obrigatorioNome = $nome; }
    public function setObrigatorioCpf($cpf) { $this->obrigatorioCpf = $cpf; }
    public function setOmAbreviatura($abreviatura) { $this->omAbreviatura = $abreviatura; }
    public function setOmNome($nome) { $this->omNome = $nome; }

    /**
     * Retorna o label do tipo
     */
    public function getTipoLabel()
    {
        $labels = [
            self::TIPO_ADIAMENTO_VENCENDO => 'Adiamento Vencendo',
            self::TIPO_INCORPORACAO_SEM_REVISAO => 'Incorporação sem Revisão'
        ];
        return $labels[$this->tipo] ?? $this->tipo;
    }

    /**
     * Retorna o label da criticidade
     */
    public function getCriticidadeLabel()
    {
        $labels = [
            self::CRITICIDADE_ALTA => 'Alta',
            self::CRITICIDADE_MEDIA => 'Média',
            self::CRITICIDADE_BAIXA => 'Baixa'
        ];
        return $labels[$this->criticidade] ?? $this->criticidade;
    }

    /**
     * Retorna a classe CSS para a criticidade
     */
    public function getCriticidadeClass()
    {
        $classes = [
            self::CRITICIDADE_ALTA => 'danger',
            self::CRITICIDADE_MEDIA => 'warning',
            self::CRITICIDADE_BAIXA => 'info'
        ];
        return $classes[$this->criticidade] ?? 'secondary';
    }

    /**
     * Retorna o ícone para o tipo
     */
    public function getTipoIcon()
    {
        $icons = [
            self::TIPO_ADIAMENTO_VENCENDO => 'fa-calendar-times',
            self::TIPO_INCORPORACAO_SEM_REVISAO => 'fa-user-clock'
        ];
        return $icons[$this->tipo] ?? 'fa-bell';
    }

    /**
     * Retorna a data de criação formatada
     */
    public function getDataCriacaoFormatada()
    {
        if (!$this->dataCriacao) return '';
        return date('d/m/Y H:i', strtotime($this->dataCriacao));
    }

    /**
     * Retorna tempo relativo (há X dias)
     */
    public function getTempoRelativo()
    {
        if (!$this->dataCriacao) return '';

        $agora = new DateTime();
        $criacao = new DateTime($this->dataCriacao);
        $diff = $agora->diff($criacao);

        if ($diff->days == 0) {
            if ($diff->h == 0) {
                return 'Agora mesmo';
            }
            return 'Há ' . $diff->h . ' hora' . ($diff->h > 1 ? 's' : '');
        } elseif ($diff->days == 1) {
            return 'Ontem';
        } elseif ($diff->days < 7) {
            return 'Há ' . $diff->days . ' dias';
        } elseif ($diff->days < 30) {
            $semanas = floor($diff->days / 7);
            return 'Há ' . $semanas . ' semana' . ($semanas > 1 ? 's' : '');
        } else {
            return $this->getDataCriacaoFormatada();
        }
    }

    /**
     * Verifica se está lida
     */
    public function isLida()
    {
        return $this->lida == 1;
    }

    /**
     * Verifica se está ativa
     */
    public function isAtiva()
    {
        return $this->ativa == 1;
    }
}
?>
