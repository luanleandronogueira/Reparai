<?php

include_once 'ConexaoModel.php';
include_once 'ErrosModel.php';

class PrestacaoServicoModel {

    private Conexao $conexao;
    private $conn;
    private $erros;

    public function __construct()
    {
        $this->conexao = new Conexao;
        $this->conn = $this->conexao->Conexao();
        $this->erros = new ErrosModel;
    }

    public function cadastrarPrestacaoServico($dados)
    {
        try {
            $query = "INSERT INTO prestacao_servico (
                        orcamento_id, 
                        prestador_id, 
                        valor_contratado, 
                        valor_adicional, 
                        prazo_acordado,
                        data_inicio_real, 
                        status_execucao, 
                        status_pagamento, 
                        forma_pagamento, 
                        observacoes_finais
                    ) VALUES (
                        :orcamento_id, 
                        :prestador_id, 
                        :valor_contratado, 
                        :valor_adicional, 
                        :prazo_acordado,
                        :data_inicio_real, 
                        :status_execucao, 
                        :status_pagamento, 
                        :forma_pagamento, 
                        :observacoes_finais
                    )";

            $stmt = $this->conn->prepare($query);
            
            // Vinculação segura dos parâmetros (PDO)
            $stmt->bindValue(':orcamento_id', (int)$dados['orcamento_id'], PDO::PARAM_INT);
            $stmt->bindValue(':prestador_id', (int)$dados['prestador_id'], PDO::PARAM_INT);
            $stmt->bindValue(':valor_contratado', (float)$dados['valor_contratado']);
            $stmt->bindValue(':valor_adicional', 0.00); 
            $stmt->bindValue(':prazo_acordado', $dados['prazo_acordado']);
            
            // Tratamento de campos opcionais ou nulos de forma limpa
            $dataInicio = !empty($dados['data_inicio_real']) ? $dados['data_inicio_real'] : null;
            $stmt->bindValue(':data_inicio_real', $dataInicio);
            
            $stmt->bindValue(':status_execucao', $dados['status_execucao']);
            $stmt->bindValue(':status_pagamento', $dados['status_pagamento']);
            
            $formaPgto = !empty($dados['forma_pagamento']) ? $dados['forma_pagamento'] : null;
            $stmt->bindValue(':forma_pagamento', $formaPgto);
            
            $obs = !empty($dados['observacoes_finais']) ? $dados['observacoes_finais'] : null;
            $stmt->bindValue(':observacoes_finais', $obs);

            return $stmt->execute();

        } catch (PDOException $e) {
            $err = [
                'data_erro' => date('Y-m-d H:i:s'),
                'descricao' => 'Erro ao executar a função: ' . $e->getMessage(),
                'funcao' => 'PrestacaoServicoModel - cadastrarPrestacaoServico'
            ];
            $this->erros->insereErro($err);
            return false;
        }
    }
}