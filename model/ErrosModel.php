<?php

include_once 'ConexaoModel.php';

class ErrosModel {

    private Conexao $conexao;
    private $conn;
    private $erros;

    public function __construct()
    {
        $this->conexao = new Conexao;
        $this->conn = $this->conexao->Conexao();
    }

    public function insereErro($dados)
    {
        $query = "INSERT INTO erros (data_erro, descricao, funcao, status_erro) VALUES (:data_erro, :descricao, :funcao, :status_erro)";

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':data_erro', $dados['data_erro']);
            $stmt->bindValue(':descricao', $dados['descricao']);
            $stmt->bindValue(':funcao', $dados['funcao']);
            $stmt->bindValue(':status_erro', $dados['status_erro'] ?? 'A');

            $stmt->execute();

            return true;

        } catch (Exception $e){
            return false;
        }
    }

    public function chamaErros()
    {
        $query = "SELECT id, data_erro, funcao, status_erro, descricao FROM erros ORDER BY data_erro DESC";

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (Exception $e){
            return false;
        }
    }

    public function atualizaErro($id)
    {
        $query = "UPDATE erros SET status_erro = 'F' WHERE id = :id";

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':id', $id);

            $stmt->execute();

            return true;

        } catch (Exception $e){
            return false;
        }
    }

}