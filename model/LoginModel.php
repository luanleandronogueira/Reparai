<?php

include_once 'ConexaoModel.php';
include_once 'ErrosModel.php';

class LoginModel {

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
     * Autentica o usuário verificando e-mail, status ativo e senha hash.
     * Baseado estritamente nos campos da tabela `usuarios`.
     */
    public function autenticarUsuario($email, $senha)
    {
        $query = "SELECT id, nome, cpf, email, senha, nivel_acesso, ativo FROM usuarios WHERE email = :email";

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':email', $email);
            $stmt->execute();
            
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($usuario) {
                // Verifica se o usuário está ativo ('1' ou 'S' dependendo do seu padrão de preenchimento)
                if ($usuario['ativo'] !== '1' && $usuario['ativo'] !== 'S') {
                    return $usuario['ativo'] = 'S';
                }

                // Verifica a senha utilizando password_verify (ideal para o VARCHAR(600) definido no seu SQL)
                if (password_verify($senha, $usuario['senha'])) {
                    return $usuario;
                }
            }

            return false;
        } catch (Exception $e) {
            $err = [
                'data_erro' => date('Y-m-d H:i:s'),
                'descricao' => 'Erro ao executar a função: ' . $e->getMessage(),
                'funcao' => 'LoginModel - autenticarUsuario'
            ];
            $this->erros->insereErro($err);
            return false;
        }
    }

    /**
     * Cria a sessão do usuário de forma segura injetando o nível de acesso
     */
    public function criaSessaoUsuario($usuario) 
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Regenera o ID da sessão para prevenir Session Fixation
        session_regenerate_id(true);

        $_SESSION['usuario_id']    = $usuario['id'];
        $_SESSION['usuario_nome']  = $usuario['nome'];
        $_SESSION['usuario_cpf']   = $usuario['cpf'];
        $_SESSION['usuario_nivel'] = $usuario['nivel_acesso']; // Armazena o nível de acesso (ex: '1', '2')
        $_SESSION['email']         = $usuario['email'];
        $_SESSION['logado']        = true;
        
        // Grava o IP e o Navegador para validar a sessão depois (Proteção contra Session Hijacking)
        $_SESSION['user_ip']      = $_SERVER['REMOTE_ADDR'];
        $_SESSION['user_agent']   = $_SERVER['HTTP_USER_AGENT'];
        $_SESSION['ultimo_acesso'] = time();

        return true;
    }

    /**
     * Valida se a sessão atual é legítima e não foi expirada ou sequestrada
     */
    public function validaSessao() 
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Verifica se os dados básicos de login existem
        if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
            return false;
        }

        // Valida se o IP ou Navegador mudaram durante a sessão
        if ($_SESSION['user_ip'] !== $_SERVER['REMOTE_ADDR'] || 
            $_SESSION['user_agent'] !== $_SERVER['HTTP_USER_AGENT']) {
            $this->finalizaSessao();
            return false;
        }

        // Verifica timeout por inatividade (Ex: 60 minutos = 3600 segundos)
        $tempo_limite = 3600; 
        if (time() - $_SESSION['ultimo_acesso'] > $tempo_limite) {
            $this->finalizaSessao();
            return false;
        }

        $_SESSION['ultimo_acesso'] = time(); // Atualiza o rastro de atividade
        return true;
    }

    /**
     * Método auxiliar para proteger rotas e páginas com base em níveis de acesso específicos
     * Exemplo de uso no controlador: $loginModel->verificarPermissao(['1', '2'])
     */
    public function verificarPermissao($niveisPermitidos = [])
    {
        // Se a sessão não for válida, corta o acesso imediatamente
        if (!$this->validaSessao()) {
            return false;
        }

        // Verifica se o nível do usuário logado está mapeado no array de permissões da página
        if (in_array($_SESSION['usuario_nivel'], $niveisPermitidos)) {
            return true;
        }

        return false;
    }

    /**
     * Limpa e destrói completamente a sessão ativa
     */
    public function finalizaSessao() 
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION = array();
        session_destroy();
    }
}