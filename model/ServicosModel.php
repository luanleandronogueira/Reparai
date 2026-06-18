<?php

include_once 'ConexaoModel.php';
include_once 'ErrosModel.php';

class ServicosModel {

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
     * Insere um novo tipo de serviço na base de dados
     */
    public function inserirServico($dados)
    {
        $query = "INSERT INTO servicos (tipo) VALUES (:tipo)";

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':tipo', $dados['tipo']);

            return $stmt->execute();
        } catch (Exception $e) {
            $err = [
                'data_erro' => date('Y-m-d H:i:s'),
                'descricao' => 'Erro ao executar a função: ' . $e->getMessage(),
                'funcao' => 'ServicosModel - inserirServico'
            ];
            $this->erros->insereErro($err);
            return false;
        }
    }

    /**
     * Lista todos os serviços cadastrados
     */
    public function listarServicos()
    {
        $query = "SELECT id, tipo FROM servicos ORDER BY tipo ASC";

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            $err = [
                'data_erro' => date('Y-m-d H:i:s'),
                'descricao' => 'Erro ao executar a função: ' . $e->getMessage(),
                'funcao' => 'ServicosModel - listarServicos'
            ];
            $this->erros->insereErro($err);
            return [];
        }
    }

    /**
     * Procura um serviço específico através do ID
     */
    public function buscaServicoPorId($id)
    {
        $query = "SELECT id, tipo FROM servicos WHERE id = :id";

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            $err = [
                'data_erro' => date('Y-m-d H:i:s'),
                'descricao' => 'Erro ao executar a função: ' . $e->getMessage(),
                'funcao' => 'ServicosModel - buscaServicoPorId'
            ];
            $this->erros->insereErro($err);
            return false;
        }
    }

    /**
     * Atualiza os dados de um serviço existente
     */
    public function atualizarServico($dados)
    {
        $query = "UPDATE servicos SET tipo = :tipo WHERE id = :id";

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':id', $dados['id']);
            $stmt->bindValue(':tipo', $dados['tipo']);

            return $stmt->execute();
        } catch (Exception $e) {
            $err = [
                'data_erro' => date('Y-m-d H:i:s'),
                'descricao' => 'Erro ao executar a função: ' . $e->getMessage(),
                'funcao' => 'ServicosModel - atualizarServico'
            ];
            $this->erros->insereErro($err);
            return false;
        }
    }

    /**
     * Elimina permanentemente um serviço através do ID
     */
    public function deletarServico($id)
    {
        $query = "DELETE FROM servicos WHERE id = :id";

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':id', $id);
            return $stmt->execute();
        } catch (Exception $e) {
            $err = [
                'data_erro' => date('Y-m-d H:i:s'),
                'descricao' => 'Erro ao executar a função: ' . $e->getMessage(),
                'funcao' => 'ServicosModel - deletarServico'
            ];
            $this->erros->insereErro($err);
            return false;
        }
    }
}