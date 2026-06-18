<?php

include_once 'ConexaoModel.php';
include_once 'ErrosModel.php';

class PrestadoresServicoModel {

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
     * Insere um novo prestador de serviço na base de dados
     */
    public function inserirPrestador($dados)
    {
        $query = "INSERT INTO prestadores_servico (nome, contato, cidade, servicos_prestados) 
                  VALUES (:nome, :contato, :cidade, :servicos_prestados)";

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':nome', $dados['nome']);
            $stmt->bindValue(':contato', $dados['contato']);
            $stmt->bindValue(':cidade', $dados['cidade']);
            $stmt->bindValue(':servicos_prestados', $dados['servicos_prestados']);

            return $stmt->execute();
        } catch (Exception $e) {
            $err = [
                'data_erro' => date('Y-m-d H:i:s'),
                'descricao' => 'Erro ao executar a função: ' . $e->getMessage(),
                'funcao' => 'PrestadoresServicoModel - inserirPrestador'
            ];
            $this->erros->insereErro($err);
            return false;
        }
    }

    /**
     * Lista todos os prestadores cadastrados, ordenados por nome
     */
    public function listarPrestadores()
    {
        $query = "SELECT id, nome, contato, cidade, servicos_prestados 
                  FROM prestadores_servico 
                  ORDER BY nome ASC";

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            $err = [
                'data_erro' => date('Y-m-d H:i:s'),
                'descricao' => 'Erro ao executar a função: ' . $e->getMessage(),
                'funcao' => 'PrestadoresServicoModel - listarPrestadores'
            ];
            $this->erros->insereErro($err);
            return [];
        }
    }

    /**
     * Procura um prestador de serviço específico através do ID
     */
    public function buscaPrestadorPorId($id)
    {
        $query = "SELECT id, nome, contato, cidade, servicos_prestados 
                  FROM prestadores_servico 
                  WHERE id = :id";

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            $err = [
                'data_erro' => date('Y-m-d H:i:s'),
                'descricao' => 'Erro ao executar a função: ' . $e->getMessage(),
                'funcao' => 'PrestadoresServicoModel - buscaPrestadorPorId'
            ];
            $this->erros->insereErro($err);
            return false;
        }
    }

    /**
     * Atualiza os dados de um prestador existente
     */
    public function atualizarPrestador($dados)
    {
        $query = "UPDATE prestadores_servico 
                  SET nome = :nome, contato = :contato, cidade = :cidade, servicos_prestados = :servicos_prestados 
                  WHERE id = :id";

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':id', $dados['id']);
            $stmt->bindValue(':nome', $dados['nome']);
            $stmt->bindValue(':contato', $dados['contato']);
            $stmt->bindValue(':cidade', $dados['cidade']);
            $stmt->bindValue(':servicos_prestados', $dados['servicos_prestados']);

            return $stmt->execute();
        } catch (Exception $e) {
            $err = [
                'data_erro' => date('Y-m-d H:i:s'),
                'descricao' => 'Erro ao executar a função: ' . $e->getMessage(),
                'funcao' => 'PrestadoresServicoModel - atualizarPrestador'
            ];
            $this->erros->insereErro($err);
            return false;
        }
    }

    /**
     * Elimina permanentemente um prestador de serviço
     */
    public function deletarPrestador($id)
    {
        $query = "DELETE FROM prestadores_servico WHERE id = :id";

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':id', $id);
            return $stmt->execute();
        } catch (Exception $e) {
            $err = [
                'data_erro' => date('Y-m-d H:i:s'),
                'descricao' => 'Erro ao executar a função: ' . $e->getMessage(),
                'funcao' => 'PrestadoresServicoModel - deletarPrestador'
            ];
            $this->erros->insereErro($err);
            return false;
        }
    }
}