<?php

include_once 'ConexaoModel.php';
include_once 'ErrosModel.php';

class UsuariosModel {

    private Conexao $conexao;
    private $conn;
    private $erros;

    public function __construct()
    {
        $this->conexao = new Conexao;
        $this->conn = $this->conexao->Conexao();
        $this->erros = new ErrosModel;
    }

    public function inserirUsuario($dados)
    {
        $query = "INSERT INTO usuarios (nome, cpf, email, senha, nivel_acesso, ativo) 
                  VALUES (:nome, :cpf, :email, :senha, :nivel_acesso, :ativo)";

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':nome', $dados['nome']);
            $stmt->bindValue(':cpf', $dados['cpf']);
            $stmt->bindValue(':email', $dados['email']);
            $stmt->bindValue(':senha', $dados['senha']);
            $stmt->bindValue(':nivel_acesso', $dados['nivel_acesso']);
            $stmt->bindValue(':ativo', $dados['ativo']);

            return $stmt->execute();
        } catch (Exception $e) {
            $err = [
                'data_erro' => date('Y-m-d H:i:s'),
                'descricao' => 'Erro ao executar a função: ' . $e->getMessage(),
                'funcao' => 'UsuariosModel - inserirUsuario'
            ];
            $this->erros->insereErro($err);
            return false;
        }
    }

    public function buscaUsuarioPorId($id)
    {
        $query = "SELECT id, nome, cpf, email, nivel_acesso, ativo FROM usuarios WHERE id = :id";

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            $err = [
                'data_erro' => date('Y-m-d H:i:s'),
                'descricao' => 'Erro ao executar a função: ' . $e->getMessage(),
                'funcao' => 'UsuariosModel - buscaUsuarioPorId'
            ];
            $this->erros->insereErro($err);
            return false;
        }
    }

    public function buscaUsuarioPorEmail($email)
    {
        $query = "SELECT email, senha, nivel_acesso, ativo FROM usuarios WHERE email = :email";

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':email', $email);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            $err = [
                'data_erro' => date('Y-m-d H:i:s'),
                'descricao' => 'Erro ao executar a função: ' . $e->getMessage(),
                'funcao' => 'UsuariosModel - buscaUsuarioPorEmail'
            ];
            $this->erros->insereErro($err);
            return false;
        }
    }

    public function listarUsuarios()
    {
        $query = "SELECT id, nome, cpf, email, nivel_acesso, ativo FROM usuarios ORDER BY nome ASC";

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            $err = [
                'data_erro' => date('Y-m-d H:i:s'),
                'descricao' => 'Erro ao executar a função: ' . $e->getMessage(),
                'funcao' => 'UsuariosModel - listarUsuarios'
            ];
            $this->erros->insereErro($err);
            return false;
        }
    }

    public function atualizarUsuario($dados)
    {
        $query = "UPDATE usuarios SET nome = :nome, cpf = :cpf, email = :email, senha = :senha, 
                  nivel_acesso = :nivel_acesso, ativo = :ativo WHERE id = :id";

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':id', $dados['id']);
            $stmt->bindValue(':nome', $dados['nome']);
            $stmt->bindValue(':cpf', $dados['cpf']);
            $stmt->bindValue(':email', $dados['email']);
            $stmt->bindValue(':senha', $dados['senha']);
            $stmt->bindValue(':nivel_acesso', $dados['nivel_acesso']);
            $stmt->bindValue(':ativo', $dados['ativo']);

            return $stmt->execute();
        } catch (Exception $e) {
            $err = [
                'data_erro' => date('Y-m-d H:i:s'),
                'descricao' => 'Erro ao executar a função: ' . $e->getMessage(),
                'funcao' => 'UsuariosModel - atualizarUsuario'
            ];
            $this->erros->insereErro($err);
            return false;
        }
    }

    public function deletarUsuario($id)
    {
        $query = "DELETE FROM usuarios WHERE id = :id";

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':id', $id);
            return $stmt->execute();
        } catch (Exception $e) {
            $err = [
                'data_erro' => date('Y-m-d H:i:s'),
                'descricao' => 'Erro ao executar a função: ' . $e->getMessage(),
                'funcao' => 'UsuariosModel - deletarUsuario'
            ];
            $this->erros->insereErro($err);
            return false;
        }
    }
}