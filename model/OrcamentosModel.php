<?php

include_once 'ConexaoModel.php';
include_once 'ErrosModel.php';

class OrcamentosModel
{

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
    public function inserirOrcamento($dados)
    {
        try {
            $query = "INSERT INTO orcamentos (entidade_id, imovel_id, servicos, observacoes, tipo_urgencia, status_atendimento, solicitante_id, aprovado, anexo) 
                    VALUES (:entidade_id, :imovel_id, :servicos, :observacoes, :tipo_urgencia, :status_atendimento, :solicitante_id, :aprovado, :anexo)";

            $stmt = $this->conn->prepare($query);

            $stmt->bindValue(':entidade_id', $dados['entidade_id']);
            $stmt->bindValue(':imovel_id', $dados['imovel_id']);
            $stmt->bindValue(':servicos', $dados['servicos']);
            $stmt->bindValue(':observacoes', $dados['observacoes']);
            $stmt->bindValue(':tipo_urgencia', $dados['tipo_urgencia']);
            $stmt->bindValue(':status_atendimento', 'PENDENTE');
            $stmt->bindValue(':solicitante_id', $dados['solicitante_id']);
            $stmt->bindValue(':aprovado', $dados['aprovado'] ?? 'A');
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

    public function listaOrcamentos($id_entidade)
    {
        try {
            $query = "SELECT o.id, i.nome_locacao, 
                            o.servicos, o.tipo_urgencia, 
                            o.status_atendimento, o.aprovado 
                      FROM orcamentos o 
                      JOIN imoveis i ON o.imovel_id = i.id 
                      WHERE o.entidade_id = :id_entidade";

            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':id_entidade', $id_entidade);
            $stmt->execute();

            $retorno = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $retorno;
        } catch (PDOException $e) {
            $err = [
                'data_erro' => date('Y-m-d H:i:s'),
                'descricao' => 'Erro ao executar a função: ' . $e->getMessage(),
                'funcao' => 'OrcamentosModel - listaOrcamentos'
            ];
            $this->erros->insereErro($err);
            return false;
        }
    }

    public function listaTodosOrcamentos()
    {
        try {
            $query = "SELECT o.id, i.nome_locacao, o.servicos, o.tipo_urgencia, o.status_atendimento FROM orcamentos o JOIN imoveis i ON o.imovel_id = i.id";

            $stmt = $this->conn->prepare($query);
            // $stmt->bindValue(':id_entidade', $id_entidade);
            $stmt->execute();

            $retorno = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $retorno;
        } catch (PDOException $e) {
            $err = [
                'data_erro' => date('Y-m-d H:i:s'),
                'descricao' => 'Erro ao executar a função: ' . $e->getMessage(),
                'funcao' => 'OrcamentosModel - listaTodosOrcamentos'
            ];
            $this->erros->insereErro($err);
            return false;
        }
    }

    public function buscaOrcamentoPorId($id)
    {
        try {
            $query = "SELECT o.*, 
                             i.nome_locacao, 
                             i.endereco, 
                             e.entidade_nome,
                             u.nome as nome_solicitante,
                             u.email as email_solicitante
                      FROM orcamentos o
                      LEFT JOIN imoveis i ON o.imovel_id = i.id
                      LEFT JOIN entidade e ON o.entidade_id = e.id
                      LEFT JOIN usuarios u ON o.solicitante_id = u.id
                      WHERE o.id = :id";

            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $err = [
                'data_erro' => date('Y-m-d H:i:s'),
                'descricao' => 'Erro ao buscar orçamento: ' . $e->getMessage(),
                'funcao' => 'OrcamentosModel - buscaOrcamentoPorId'
            ];
            $this->erros->insereErro($err);
            return false;
        }
    }

    public function atualizarAvaliacao($dados)
    {
        try {
            $query = "UPDATE orcamentos SET 
                        custo = :custo, 
                        prazo = :prazo, 
                        observacao_orcamento = :observacao_orcamento, 
                        status_atendimento = :status_atendimento,
                        responsavel_id = :responsavel_id,
                        anexo = :anexo
                      WHERE id = :id";

            $stmt = $this->conn->prepare($query);

            $stmt->bindValue(':custo', $dados['custo']);
            $stmt->bindValue(':prazo', $dados['prazo']);
            $stmt->bindValue(':observacao_orcamento', $dados['observacao_orcamento']);
            $stmt->bindValue(':status_atendimento', $dados['status_atendimento']);
            $stmt->bindValue(':responsavel_id', $dados['responsavel_id'], PDO::PARAM_INT);
            $stmt->bindValue(':anexo', $dados['anexo']);
            $stmt->bindValue(':id', $dados['id'], PDO::PARAM_INT);

            return $stmt->execute();
        } catch (PDOException $e) {
            $err = [
                'data_erro' => date('Y-m-d H:i:s'),
                'descricao' => 'Erro ao atualizar avaliação de orçamento: ' . $e->getMessage(),
                'funcao' => 'OrcamentosModel - atualizarAvaliacao'
            ];
            $this->erros->insereErro($err);
            return false;
        }
    }

    public function listaTodosOrcamentosCompleto()
    {
        try {
            $query = "SELECT o.id, 
                             o.tipo_urgencia, 
                             o.status_atendimento, 
                             o.custo, 
                             o.prazo,
                             o.servicos,
                             o.aprovado,
                             i.nome_locacao, 
                             e.entidade_nome,
                             u.nome as nome_solicitante
                      FROM orcamentos o
                      LEFT JOIN imoveis i ON o.imovel_id = i.id
                      LEFT JOIN entidade e ON o.entidade_id = e.id
                      LEFT JOIN usuarios u ON o.solicitante_id = u.id
                      ORDER BY o.id DESC";

            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $err = [
                'data_erro' => date('Y-m-d H:i:s'),
                'descricao' => 'Erro ao listar orçamentos completos: ' . $e->getMessage(),
                'funcao' => 'OrcamentosModel - listaTodosOrcamentosCompleto'
            ];
            $this->erros->insereErro($err);
            return [];
        }
    }

    public function atualizarApenasAprovacao($id, $aprovacao)
    {
        try {
            $query = "UPDATE orcamentos SET aprovado = :aprovacao WHERE id = :id";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':aprovacao', $aprovacao);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            $err = [
                'data_erro' => date('Y-m-d H:i:s'),
                'descricao' => 'Erro ao atualizar apenas o campo aprovacao: ' . $e->getMessage(),
                'funcao' => 'OrcamentosModel - atualizarApenasAprovacao'
            ];
            $this->erros->insereErro($err);
            return false;
        }
    }
}
