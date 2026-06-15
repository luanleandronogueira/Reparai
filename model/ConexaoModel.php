<?php 
date_default_timezone_set('America/Sao_Paulo');

class Conexao {
    private $host;
    private $dbname;
    private $userdb;
    private $password;
    
    // Variável estática para guardar a conexão única
    private static $instancia = null;

    public function __construct() {
        self::loadEnv();
        $this->host     = $_ENV['DB_HOST'] ?? 'localhost';
        $this->dbname   = $_ENV['DB_DATABASE'] ?? '';
        $this->userdb   = $_ENV['DB_USERNAME'] ?? '';
        $this->password = $_ENV['DB_PASSWORD'] ?? '';
    }

    private static function loadEnv() {
        $envPath = dirname(__DIR__) . '/.env';
        if (!file_exists($envPath)) {
            throw new Exception('.env file not found');
        }
        $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (empty(trim($line)) || strpos(trim($line), '#') === 0) continue;
            list($name, $value) = explode('=', $line, 2);
            $_ENV[trim($name)] = trim($value);
        }
    }

    public function Conexao() {
        // Se já existe uma conexão, apenas a retorna em vez de criar outra
        if (self::$instancia !== null) {
            return self::$instancia;
        }

        try {
            self::$instancia = new PDO(
                "mysql:host=$this->host;dbname=$this->dbname",
                $this->userdb,
                $this->password,
                [
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    // Reutiliza a conexão se possível
                    PDO::ATTR_PERSISTENT => false 
                ]
            );
            return self::$instancia;
        } catch (PDOException $e) {
            // Em produção, evite dar echo no erro bruto por segurança
            die("Erro de conexão: " . $e->getMessage());
        }
    }
}