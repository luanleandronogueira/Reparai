<?php

include_once 'ConexaoModel.php';
include_once 'ErrosModel.php';

class EntidadeModel {

    private Conexao $conexao;
    private $conn;
    private $erros;

    public function __construct()
    {
        $this->conexao = new Conexao;
        $this->conn = $this->conexao->Conexao();
        $this->erros = new ErrosModel;
    }

    public function inserirEntidade($dados)
    {
        $query = "INSERT INTO entidade (entidade_nome, cnpj, responsavel, ativo, email, telefone) 
                  VALUES (:entidade_nome, :cnpj, :responsavel, :ativo, :email, :telefone)";

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':entidade_nome', $dados['entidade_nome']);
            $stmt->bindValue(':cnpj', $dados['cnpj']);
            $stmt->bindValue(':responsavel', $dados['responsavel']);
            $stmt->bindValue(':ativo', $dados['ativo']);
            $stmt->bindValue(':email', $dados['email']);
            $stmt->bindValue(':telefone', $dados['telefone']);

            return $stmt->execute();
        } catch (Exception $e) {
            $err = [
                'data_erro' => date('Y-m-d H:i:s'),
                'descricao' => 'Erro ao executar a função: ' . $e->getMessage(),
                'funcao' => 'EntidadeModel - inserirEntidade'
            ];
            $this->erros->insereErro($err);
            return false;
        }
    }

    public function buscaEntidadePorId($id)
    {
        $query = "SELECT id, entidade_nome, cnpj, responsavel, ativo, email, telefone FROM entidade WHERE id = :id";

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            $err = [
                'data_erro' => date('Y-m-d H:i:s'),
                'descricao' => 'Erro ao executar a função: ' . $e->getMessage(),
                'funcao' => 'EntidadeModel - buscaEntidadePorId'
            ];
            $this->erros->insereErro($err);
            return false;
        }
    }

    public function buscaNomeEntidadePorId($id)
    {
        $query = "SELECT id, entidade_nome FROM entidade WHERE id = :id";

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            $err = [
                'data_erro' => date('Y-m-d H:i:s'),
                'descricao' => 'Erro ao executar a função: ' . $e->getMessage(),
                'funcao' => 'EntidadeModel - buscaNomeEntidadePorId'
            ];
            $this->erros->insereErro($err);
            return false;
        }
    }

    public function listarEntidades()
    {
        $query = "SELECT id, entidade_nome, cnpj, responsavel, ativo, email, telefone FROM entidade ORDER BY entidade_nome ASC";

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            $err = [
                'data_erro' => date('Y-m-d H:i:s'),
                'descricao' => 'Erro ao executar a função: ' . $e->getMessage(),
                'funcao' => 'EntidadeModel - listarEntidades'
            ];
            $this->erros->insereErro($err);
            return false;
        }
    }

    public function listaTodasEntidades()
    {
        $query = "SELECT id, entidade_nome, cnpj, responsavel, email, telefone, ativo 
                  FROM entidade 
                  ORDER BY entidade_nome ASC";

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (Exception $e) {
            $err = [
                'data_erro' => date('Y-m-d H:i:s'),
                'descricao' => 'Erro ao listar entidades: ' . $e->getMessage(),
                'funcao' => 'EntidadeModel - listaTodasEntidades'
            ];
            $this->erros->insereErro($err);
            return [];
        }
    }

    public function atualizarEntidade($dados)
    {
        $query = "UPDATE entidade SET entidade_nome = :entidade_nome, cnpj = :cnpj, responsavel = :responsavel, 
                  ativo = :ativo, email = :email, telefone = :telefone WHERE id = :id";

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':id', $dados['id']);
            $stmt->bindValue(':entidade_nome', $dados['entidade_nome']);
            $stmt->bindValue(':cnpj', $dados['cnpj']);
            $stmt->bindValue(':responsavel', $dados['responsavel']);
            $stmt->bindValue(':ativo', $dados['ativo']);
            $stmt->bindValue(':email', $dados['email']);
            $stmt->bindValue(':telefone', $dados['telefone']);

            return $stmt->execute();
        } catch (Exception $e) {
            $err = [
                'data_erro' => date('Y-m-d H:i:s'),
                'descricao' => 'Erro ao executar a função: ' . $e->getMessage(),
                'funcao' => 'EntidadeModel - atualizarEntidade'
            ];
            $this->erros->insereErro($err);
            return false;
        }
    }

    public function deletarEntidade($id)
    {
        $query = "DELETE FROM entidade WHERE id = :id";

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':id', $id);
            return $stmt->execute();
        } catch (Exception $e) {
            $err = [
                'data_erro' => date('Y-m-d H:i:s'),
                'descricao' => 'Erro ao executar a função: ' . $e->getMessage(),
                'funcao' => 'EntidadeModel - deletarEntidade'
            ];
            $this->erros->insereErro($err);
            return false;
        }
    }
}