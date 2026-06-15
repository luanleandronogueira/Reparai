<?php

include_once 'ConexaoModel.php';
include_once 'ErrosModel.php';

class ImoveisModel {

    private Conexao $conexao;
    private $conn;
    private $erros;

    public function __construct()
    {
        $this->conexao = new Conexao;
        $this->conn = $this->conexao->Conexao();
        $this->erros = new ErrosModel;
    }

    public function inserirImovel($dados)
    {
        $query = "INSERT INTO imoveis (nome_locacao, endereco, entidade) VALUES (:nome_locacao, :endereco, :entidade)";

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':nome_locacao', $dados['nome_locacao']);
            $stmt->bindValue(':endereco', $dados['endereco']);
            $stmt->bindValue(':entidade', $dados['entidade']);

            return $stmt->execute();
        } catch (Exception $e) {
            $err = [
                'data_erro' => date('Y-m-d H:i:s'),
                'descricao' => 'Erro ao executar a função: ' . $e->getMessage(),
                'funcao' => 'ImoveisModel - inserirImovel'
            ];
            $this->erros->insereErro($err);
            return false;
        }
    }

    public function buscaImovelPorId($id)
    {
        $query = "SELECT id, nome_locacao, endereco, entidade FROM imoveis WHERE id = :id";

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            $err = [
                'data_erro' => date('Y-m-d H:i:s'),
                'descricao' => 'Erro ao executar a função: ' . $e->getMessage(),
                'funcao' => 'ImoveisModel - buscaImovelPorId'
            ];
            $this->erros->insereErro($err);
            return false;
        }
    }

    public function listarImoveis()
    {
        $query = "SELECT i.id, i.nome_locacao, i.endereco, i.entidade, e.entidade_nome 
                  FROM imoveis i 
                  LEFT JOIN entidade e ON i.entidade = e.id 
                  ORDER BY i.nome_locacao ASC";

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            $err = [
                'data_erro' => date('Y-m-d H:i:s'),
                'descricao' => 'Erro ao executar a função: ' . $e->getMessage(),
                'funcao' => 'ImoveisModel - listarImoveis'
            ];
            $this->erros->insereErro($err);
            return false;
        }
    }

    public function listarImoveisPorEntidade($entidade_id)
    {
        $query = "SELECT id, nome_locacao, endereco, entidade FROM imoveis WHERE entidade = :entidade_id ORDER BY nome_locacao ASC";

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':entidade_id', $entidade_id);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            $err = [
                'data_erro' => date('Y-m-d H:i:s'),
                'descricao' => 'Erro ao executar a função: ' . $e->getMessage(),
                'funcao' => 'ImoveisModel - listarImoveisPorEntidade'
            ];
            $this->erros->insereErro($err);
            return false;
        }
    }

    public function atualizarImovel($dados)
    {
        $query = "UPDATE imoveis SET nome_locacao = :nome_locacao, endereco = :endereco, entidade = :entidade WHERE id = :id";

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':id', $dados['id']);
            $stmt->bindValue(':nome_locacao', $dados['nome_locacao']);
            $stmt->bindValue(':endereco', $dados['endereco']);
            $stmt->bindValue(':entidade', $dados['entidade']);

            return $stmt->execute();
        } catch (Exception $e) {
            $err = [
                'data_erro' => date('Y-m-d H:i:s'),
                'descricao' => 'Erro ao executar a função: ' . $e->getMessage(),
                'funcao' => 'ImoveisModel - atualizarImovel'
            ];
            $this->erros->insereErro($err);
            return false;
        }
    }

    public function deletarImovel($id)
    {
        $query = "DELETE FROM imoveis WHERE id = :id";

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':id', $id);
            return $stmt->execute();
        } catch (Exception $e) {
            $err = [
                'data_erro' => date('Y-m-d H:i:s'),
                'descricao' => 'Erro ao executar a função: ' . $e->getMessage(),
                'funcao' => 'ImoveisModel - deletarImovel'
            ];
            $this->erros->insereErro($err);
            return false;
        }
    }
}