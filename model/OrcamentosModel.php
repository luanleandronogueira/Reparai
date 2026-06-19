<?php

include_once 'ConexaoModel.php';
include_once 'ErrosModel.php';

class OrcamentosModel {

    private Conexao $conexao;
    private $conn;
    private $erros;

    public function __construct()
    {
        $this->conexao = new Conexao;
        $this->conn = $this->conexao->Conexao();
        $this->erros = new ErrosModel;
    }

    /**
     * Insere um novo orçamento no banco de dados.
     */
    public function inserirOrcamento($dados) {
        try {
            $query = "INSERT INTO orcamentos (entidade_id, imovel_id, servicos, observacoes, tipo_urgencia, status_atendimento, solicitante_id, anexo) 
                    VALUES (:entidade_id, :imovel_id, :servicos, :observacoes, :tipo_urgencia, :status_atendimento, :solicitante_id, :anexo)";
            
            $stmt = $this->conn->prepare($query);

            $stmt->bindValue(':entidade_id', $dados['entidade_id']);
            $stmt->bindValue(':imovel_id', $dados['imovel_id']);
            $stmt->bindValue(':servicos', $dados['servicos']);
            $stmt->bindValue(':observacoes', $dados['observacoes']);
            $stmt->bindValue(':tipo_urgencia', $dados['tipo_urgencia']);
            $stmt->bindValue(':status_atendimento', 'PENDENTE');
            $stmt->bindValue(':solicitante_id', $dados['solicitante_id']);
            $stmt->bindValue(':anexo', $dados['anexo']);

            return $stmt->execute();
            
        } catch (PDOException $e) {
            $err = [
                'data_erro' => date('Y-m-d H:i:s'),
                'descricao' => 'Erro ao executar a função: ' . $e->getMessage(),
                'funcao' => 'OrcamentosModel - inserirOrcamento'
            ];
            $this->erros->insereErro($err);
            return false;
        }
    }

}